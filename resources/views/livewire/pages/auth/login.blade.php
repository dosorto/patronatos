<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Organization;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
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

<div class="min-h-screen w-full relative flex flex-col justify-center items-center py-10 px-4">
    {{-- Fondo --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-900/90 via-sky-800/95 to-slate-900 z-10 backdrop-blur-sm"></div>
        <img alt="Fondo agua" class="w-full h-full object-cover opacity-30 scale-105" src="https://images.unsplash.com/photo-1549467657-30c8ff0e199d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" />
    </div>
    
    <div class="relative z-20 w-full max-w-[480px] bg-white/10 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] p-10 transform transition-all hover:shadow-[0_8px_40px_0_rgba(56,189,248,0.2)]">
        
        {{-- Header --}}
        <div class="flex flex-col items-center mb-10 text-center">
            <div class="w-20 h-20 bg-gradient-to-tr from-sky-400 to-blue-600 rounded-3xl flex items-center justify-center shadow-[0_0_20px_rgba(56,189,248,0.4)] mb-6 transform hover:scale-105 hover:rotate-3 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2">SISGAP</h1>
            <p class="text-sky-300 text-sm font-semibold tracking-wider uppercase">Por GIC SOLUTIONS</p>
        </div>

        <form wire:submit="login" class="space-y-6">
            
            <div class="space-y-5">
                


                {{-- Email --}}
                <div class="relative group mt-4">
                    <label class="block text-xs font-bold text-sky-200 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-sky-400" for="email">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-sky-300/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <input wire:model="form.email" id="email" class="block w-full border-white/20 rounded-2xl text-sm focus:ring-2 focus:ring-sky-400/50 focus:border-sky-400 bg-white/5 text-white placeholder-sky-200/30 h-14 pl-12 pr-4 backdrop-blur-md transition-all shadow-inner" type="email" name="email" required autofocus autocomplete="username" placeholder="usuario@correo.com"/>
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-300 font-medium" />
                </div>

                {{-- Password --}}
                <div class="relative group mt-4">
                    <label class="block text-xs font-bold text-sky-200 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-sky-400" for="password">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-sky-300/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input wire:model="form.password" id="password" class="block w-full border-white/20 rounded-2xl text-sm focus:ring-2 focus:ring-sky-400/50 focus:border-sky-400 bg-white/5 text-white placeholder-sky-200/30 h-14 pl-12 pr-4 backdrop-blur-md transition-all shadow-inner" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-300 font-medium" />
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center gap-3 pt-2">
                    <div class="relative flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="w-5 h-5 rounded border-white/30 text-sky-500 focus:ring-sky-500 bg-white/5 transition-colors cursor-pointer">
                    </div>
                    <label for="remember" class="text-sm font-medium text-sky-100 cursor-pointer select-none hover:text-white transition-colors">Recordarme en este dispositivo</label>
                </div>
            </div>

            {{-- Acciones Finales --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-4 mt-8 border-t border-white/10">
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-sky-300 hover:text-white hover:underline underline-offset-4 transition-all">
                    ¿Olvidaste tu contraseña?
                </a>

                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-white font-extrabold py-3.5 px-8 rounded-2xl flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(56,189,248,0.3)] hover:shadow-[0_4px_25px_rgba(56,189,248,0.5)] transition-all active:scale-95 group">
                    <span>Acceder</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
        
    </div>
</div>