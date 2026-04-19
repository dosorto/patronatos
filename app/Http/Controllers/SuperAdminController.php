<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Muestra el panel maestro con todas las organizaciones y sus usuarios.
     * Solo accesible para el rol 'root' y desde el dominio central.
     */
    public function index(Request $request)
    {
        // Seguridad: Solo permitir acceso desde el dominio central
        $currentHost = $request->getHost();
        $baseHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
        
        // Incluir 127.0.0.1 expresamente para desarrollo local
        $isCentral = ($currentHost === $baseHost || 
                      $currentHost === 'localhost' || 
                      $currentHost === '127.0.0.1' || 
                      !str_contains($currentHost, '.'));

        if (!$isCentral) {
            abort(404);
        }

        // Obtenemos todas las organizaciones de la base de datos central
        // Cargamos la relación de usuarios para cada una
        $organizations = Organization::on('mysql')
            ->with('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.organizations.index', compact('organizations'));
    }
}
