<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\TipoOrganizacion;
use App\Models\Pais;
use App\Models\Municipio;
use App\Models\Departamento;

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

        $orgId   = session('tenant_organization_id');

        if (!$orgId) {
            return response()->json(['success' => false, 'message' => 'Organización no identificada.'], 403);
        }

        $storedPath = null;

        try {
            $result = DB::connection('mysql')->transaction(function () use ($request, $orgId, &$storedPath) {

                $organization = Organization::on('mysql')->findOrFail($orgId);

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
            // Registrar el error para diagnóstico
            \Log::error('Error al subir logo de organización', [
                'org_id'      => $orgId,
                'stored_path' => $storedPath,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            // Si algo falló, eliminar el archivo subido (si llegó a guardarse)
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // mensaje real para depuración
            ], 500);
        }
    }
    public function edit(Request $request)
    {
        // Si se pasa un ID por query, lo usamos (solo para root)
        $orgId = $request->query('org') ?? session('tenant_organization_id');

        // Seguridad: Si el ID solicitado no es el de su sesión y NO es root, bloqueamos
        if ($orgId != session('tenant_organization_id') && !auth()->user()->hasRole('root')) {
            abort(403, 'No tienes permisos para gestionar otras organizaciones.');
        }

        $org = Organization::on('mysql')->find($orgId);

        if (!$org) {
            abort(404, 'Organización no encontrada.');
        }

        return view('livewire.configuracion.edit-organization', [
            'org' => $org,
            'tipos' => TipoOrganizacion::all(),
            'paises' => Pais::all(),
            'departamentos' => Departamento::all(),
            'municipios' => Municipio::all(),
            'pais_id' => optional($org->departamento)->pais_id
        ]);
    }
    public function update(Request $request)
    {
        $orgId = $request->input('org_id') ?? session('tenant_organization_id');

        // Seguridad: Si intenta actualizar otra y no es root, prohibido
        if ($orgId != session('tenant_organization_id') && !auth()->user()->hasRole('root')) {
            abort(403, 'No tienes permisos para actualizar esta organización.');
        }

        $org = Organization::on('mysql')->find($orgId);

        if (!$org) {
            abort(404, 'No se encontró la organización.');
        }

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'rtn' => ['nullable', 'string', 'max:20'],
            'fecha_creacion' => ['nullable', 'date'],
            'meses_mora' => ['nullable', 'integer', 'min:0', 'max:12'],
            'dias_pago' => ['nullable', 'integer', 'min:1', 'max:31'],
            'logo' => ['nullable', 'image', 'max:2048'],
            // Validaciones solo para Root
            'plan_name' => ['nullable', 'string', 'in:Desarrollo,Comunitario,Residencial,Macro'],
            'subscription_status' => ['nullable', 'string', 'in:active,expired,suspended'],
            'subscription_expires_at' => ['nullable', 'date'],
            'max_households' => ['nullable', 'integer', 'min:0'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email ?: null,
            'phone' => $request->phone ?: null,
            'rtn' => $request->rtn ?: null,
            'fecha_creacion' => $request->fecha_creacion ?: null,
            'meses_mora' => $request->has('meses_mora') ? $request->meses_mora : $org->meses_mora,
            'dias_pago' => $request->has('dias_pago') ? $request->dias_pago : $org->dias_pago,
        ];

        // ── ACTUALIZACIÓN DE SUSCRIPCIÓN (SOLO ROOT) ──
        if (auth()->user()->hasRole('root')) {
            if ($request->has('plan_name')) $data['plan_name'] = $request->plan_name;
            if ($request->has('subscription_status')) $data['subscription_status'] = $request->subscription_status;
            if ($request->has('subscription_expires_at')) $data['subscription_expires_at'] = $request->subscription_expires_at;
            if ($request->has('max_households')) $data['max_households'] = $request->max_households;
        }

        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            if ($org->logo && Storage::disk('public')->exists($org->logo)) {
                Storage::disk('public')->delete($org->logo);
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($org->logo && Storage::disk('public')->exists($org->logo)) {
                Storage::disk('public')->delete($org->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $org->update($data);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Información actualizada correctamente.');
    }

    /**
     * Actualiza los meses de gracia para considerar mora.
     * Se usa desde el wizard de configuración inicial.
     */
    public function updateMesesMora(Request $request)
    {
        $request->validate([
            'meses_mora' => 'required|integer|min:0|max:12',
            'dias_pago'  => 'required|integer|min:1|max:31',
        ]);

        $orgId = session('tenant_organization_id');
        
        \Log::info("Wizard: Intentando guardar meses_mora={$request->meses_mora}, dias_pago={$request->dias_pago} para OrgID={$orgId}");

        if (!$orgId) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la organización en la sesión.'
            ], 400);
        }

        try {
            $org = Organization::on('mysql')->findOrFail($orgId);
            $org->update([
                'meses_mora' => $request->meses_mora,
                'dias_pago'  => $request->dias_pago
            ]);

            \Log::info("Wizard: Guardado exitoso meses_mora={$org->meses_mora} para {$org->name}");

            return response()->json([
                'success' => true,
                'message' => 'Configuración de mora actualizada.',
                'meses_mora' => $org->meses_mora
            ]);
        } catch (\Throwable $e) {
            \Log::error('Error al actualizar meses_mora', [
                'org_id' => $orgId,
                'error'  => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
