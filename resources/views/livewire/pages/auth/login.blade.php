<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Organization;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-[#DDEAF7] flex flex-col items-center pt-12">
    
    
    {{-- Tarjeta Blanca Principal --}}
    <div class="w-full max-w-[440px] bg-white rounded-[32px] shadow-2xl overflow-hidden p-10">
        
        {{-- Header: Icono Azul + Títulos --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 bg-[#4B8BF5] rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h1 class="text-[28px] font-black text-[#1E3A5F] tracking-tight">Bienvenido</h1>
            <p class="text-gray-400 text-sm mt-1">Ingresa con tu cuenta para continuar</p>
        </div>

        <form wire:submit="login" class="space-y-6">
            
            {{-- Bloque Interno Crema --}}
            <div class="bg-[#FFF9F2] border border-[#FDE9C7] rounded-[20px] p-6 space-y-5">
                
                {{-- Label Pill --}}
                <div class="inline-flex items-center gap-2 bg-[#F59E42] text-[#7C3D00] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                    </svg>
                    Acceso al sistema
                </div>

                {{-- Organización --}}
                <div>
                    <label class="block text-[12px] font-bold text-[#7C4A00] mb-1">Organización</label>
                    <select wire:model="form.organization_id" class="w-full border-[#E2CFA8] rounded-xl text-sm focus:ring-[#F59E42] focus:border-[#F59E42] bg-white h-11">
                        <option value="">— Root / Sin organización —</option>
                        @foreach (Organization::all() as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-[12px] font-bold text-[#7C4A00] mb-1">Correo electrónico</label>
                    <input wire:model="form.email" type="email" placeholder="tu@correo.com" 
                        class="w-full border-[#E2CFA8] rounded-xl text-sm focus:ring-[#F59E42] focus:border-[#F59E42] bg-white h-11">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-[12px] font-bold text-[#7C4A00] mb-1">Contraseña</label>
                    <input wire:model="form.password" type="password" placeholder="••••••••" 
                        class="w-full border-[#E2CFA8] rounded-xl text-sm focus:ring-[#F59E42] focus:border-[#F59E42] bg-white h-11">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center gap-2 pt-1">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#E2CFA8] text-[#2563EB] focus:ring-blue-500">
                    <label for="remember" class="text-[13px] text-[#6B5230]">Recordarme en este dispositivo</label>
                </div>
            </div>

            {{-- Acciones Finales --}}
            <div class="flex items-center justify-between items-end">
                <a href="{{ route('password.request') }}" wire:navigate class="text-[12px] text-gray-400 underline underline-offset-4 hover:text-blue-600 transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>

                <button type="submit" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 px-8 rounded-xl flex items-center gap-2 shadow-lg shadow-blue-100 transition-all active:scale-95">
                    Ingresar
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>