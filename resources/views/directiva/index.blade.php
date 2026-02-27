@extends('layouts.app')

@section('title', 'Gestión de Directiva')

@section('content')
    <div class="container-fluid">
        @livewire('directiva.directivas-index')
    </div>
@endsection
