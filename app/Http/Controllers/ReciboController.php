<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    public function show($id)
    {
        $recibo = Recibo::with(['cobro.miembro.persona', 'cobro.detallesCobros.servicio'])->findOrFail($id);
        
        return view('Recibo.show', compact('recibo'));
    }
}