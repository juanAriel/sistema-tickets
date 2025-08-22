@extends('layouts.app')

@section('title', 'Generar ficha')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
        <form method="POST" action="{{ route('tickets.generate') }}" class="bg-white p-4 rounded-xl shadow justify-items-center">
            @csrf
            <input type="hidden" name="type" value="cajero">
            <h2 class="text-lg font-semibold mb-2">Ficha para Cajero</h2>
            <button class="w-full py-3 rounded-lg bg-blue-600 text-white font-medium">Generar ficha</button>
        </form>


        <form method="POST" action="{{ route('tickets.generate') }}" class="bg-white p-4 rounded-xl shadow justify-items-center">
            @csrf
            <input type="hidden" name="type" value="cliente">
            <h2 class="text-lg font-semibold mb-2">Ficha para Atención</h2>
            <button class="w-full py-3 rounded-lg bg-green-600 text-white font-medium">Generar ficha</button>
        </form>
    </div>


    @if ($errors->any())
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">{{ $errors->first() }}</div>
    @endif
@endsection
