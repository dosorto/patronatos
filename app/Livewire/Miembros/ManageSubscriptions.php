<?php

namespace App\Livewire\Miembros;

use Livewire\Component;
use App\Models\Miembros;
use App\Models\Servicio;
use App\Models\Suscripcion;
use App\Models\Medidores;

class ManageSubscriptions extends Component
{
    public $miembroId;
    public $miembro;
    
    // Formulario para nueva suscripción
    public $mostrarFormulario = false;
    public $servicio_id;
    public $identificador;
    public $medidor_id;
    public $nuevo_medidor_numero;
    
    // Listas
    public $serviciosDisponibles = [];
    public $medidoresLibres = [];

    public function mount($miembroId)
    {
        $this->miembroId = $miembroId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->miembro = Miembros::with('suscripciones.servicio', 'suscripciones.medidor')->findOrFail($this->miembroId);
        
        // Mostrar todos los servicios activos para permitir múltiples suscripciones (ej: varias casas)
        $this->serviciosDisponibles = Servicio::where('estado', 'activo')->get();
            
        // Medidores libres (no asignados a nadie)
        $this->medidoresLibres = Medidores::whereNull('miembro_id')
            ->get()
            ->groupBy('servicio_id')
            ->toArray();
    }

    public function toggleEstado($suscripcionId)
    {
        $suscripcion = Suscripcion::findOrFail($suscripcionId);
        $suscripcion->estado = !$suscripcion->estado;
        $suscripcion->save();
        
        $this->loadData();
        session()->flash('success', 'Estado de suscripción actualizado.');
    }

    public function eliminarSuscripcion($suscripcionId)
    {
        $suscripcion = Suscripcion::findOrFail($suscripcionId);
        
        // Si tiene medidor, liberarlo
        if ($suscripcion->medidor_id) {
            $medidor = Medidores::find($suscripcion->medidor_id);
            if ($medidor) {
                $medidor->miembro_id = null;
                $medidor->save();
            }
        }
        
        $suscripcion->delete();
        
        $this->loadData();
        session()->flash('success', 'Suscripción eliminada correctamente.');
    }

    public function mostrarNuevo()
    {
        $this->mostrarFormulario = !$this->mostrarFormulario;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->servicio_id = null;
        $this->identificador = null;
        $this->medidor_id = null;
        $this->nuevo_medidor_numero = null;
    }

    public function guardarNuevaSuscripcion()
    {
        $this->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'identificador' => 'nullable|string|max:255',
        ]);

        $servicio = Servicio::find($this->servicio_id);
        $finalMedidorId = null;

        if ($servicio->tiene_medidor) {
            if ($this->medidor_id === 'nuevo') {
                $this->validate(['nuevo_medidor_numero' => 'required|string|max:100']);
                
                $medidor = Medidores::create([
                    'numero_medidor' => $this->nuevo_medidor_numero,
                    'miembro_id' => $this->miembroId,
                    'servicio_id' => $this->servicio_id,
                    'estado' => 'activo',
                    'unidad_medida' => $servicio->unidad_medida,
                    'precio_unidad_medida' => $servicio->precio_por_unidad_de_medida ?: 0,
                    'fecha_instalacion' => now(),
                ]);
                $finalMedidorId = $medidor->id;
            } elseif ($this->medidor_id) {
                $medidor = Medidores::findOrFail($this->medidor_id);
                $medidor->miembro_id = $this->miembroId;
                $medidor->save();
                $finalMedidorId = $medidor->id;
            }
        }

        Suscripcion::create([
            'miembro_id' => $this->miembroId,
            'servicio_id' => $this->servicio_id,
            'medidor_id' => $finalMedidorId,
            'identificador' => $this->identificador,
            'fecha_inicio' => now(),
            'ultimo_mes_pagado' => now()->subMonth()->startOfMonth(),
            'estado' => 1,
        ]);

        $this->mostrarFormulario = false;
        $this->resetForm();
        $this->loadData();
        
        session()->flash('success', 'Nueva suscripción agregada.');
    }

    public function render()
    {
        return view('livewire.miembro.manage-subscriptions');
    }
}
