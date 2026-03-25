<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Sube y guarda el logo de la organización en disco.
     * El logo se almacena en storage/app/public/logos/
     * y la ruta relativa se guarda en la columna 'logo' de la tabla organizations.
     * Toda la operación está envuelta en una transacción para garantizar consistencia.
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        $orgId   = auth()->user()->organization_id;
        $storedPath = null;

        try {
            $result = DB::transaction(function () use ($request, $orgId, &$storedPath) {

                $organization = Organization::findOrFail($orgId);

                // Eliminar logo anterior si existe
                $logoAnterior = $organization->logo;

                // Guardar el nuevo logo con nombre único por organización
                $path = $request->file('logo')->storeAs(
                    'logos',
                    'org_' . $orgId . '_' . time() . '.' . $request->file('logo')->getClientOriginalExtension(),
                    'public'
                );

                $storedPath = $path;

                // Actualizar la BD (si falla, el transaction hace rollback)
                $organization->update(['logo' => $path]);

                // Borrar el logo anterior del disco solo si la BD se actualizó correctamente
                if ($logoAnterior && Storage::disk('public')->exists($logoAnterior)) {
                    Storage::disk('public')->delete($logoAnterior);
                }

                return $path;
            });

            return response()->json([
                'success'  => true,
                'logo_url' => Storage::url($result),
            ]);

        } catch (\Throwable $e) {
            // Si algo falló, eliminar el archivo subido (si llegó a guardarse)
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el logo. Por favor intenta de nuevo.',
            ], 500);
        }
    }
}
