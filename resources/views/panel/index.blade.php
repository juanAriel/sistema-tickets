<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-center mb-8">🎛️ Panel de Control</h1>

        {{-- Cajero --}}
        @if ($type == 'cajero')
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 text-blue-700">Cajero</h2>

                @if ($actualCajero)
                    <p class="text-lg mb-2">
                        Atendiendo: <span class="font-bold text-2xl">{{ $actualCajero->number_ticket }}</span>
                    </p>
                @else
                    <p class="text-gray-500 mb-2">No hay clientes en atención</p>
                @endif

                <form method="POST" action="{{ route('panel.next', ['type' => 'cajero']) }}">
                    @csrf
                    <button
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg {{ $statusBtn ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $statusBtn ? 'disabled' : '' }}>Llamar siguiente</button>
                </form>
                {{-- Tabla de tickets --}}
                <h3 class="mt-4 font-semibold">Tickets Cajero</h3>
                <table class="table-auto w-full mt-2 border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-2 py-1 border">Ticket</th>
                            <th class="px-2 py-1 border">Estado</th>
                            <th class="px-2 py-1 border">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketsCajero as $ticket)
                            <tr
                                class="{{ $ticket->status === 'attending' ? 'bg-blue-200' : ($ticket->status === 'wait' ? 'bg-green-200' : 'bg-red-200') }}">
                                <td class="px-2 py-1 border">{{ $ticket->number_ticket }}</td>
                                <td class="px-2 py-1 border">{{ ucfirst($ticket->status) }}</td>
                                <td class="px-2 py-1 border">
                                    @if ($ticket->status === 'attending')
                                        <form method="POST"
                                            action="{{ route('panel.next', ['type' => $ticket->type, 'ticket' => $ticket->id]) }}">
                                            @csrf
                                            <button class="bg-red-600 text-white px-2 py-1 rounded">Finalizar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Atención al Cliente --}}
        @if ($type == 'cliente')
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-green-700">Atención al Cliente</h2>
                @if ($actualCliente)
                    <p class="text-lg mb-2">
                        Atendiendo: <span class="font-bold text-2xl">{{ $actualCliente->number_ticket }}</span>
                    </p>
                @else
                    <p class="text-gray-500 mb-2">No hay clientes en atención</p>
                @endif
                <form method="POST" action="{{ route('panel.next', ['type' => 'cliente']) }}">
                    @csrf
                    <button
                        class="buttonNext bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 {{ $statusBtn ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $statusBtn ? 'disabled' : '' }}>
                        Llamar siguiente
                    </button>
                </form>

                <h3 class="mt-4 font-semibold">Tickets Cliente</h3>
                <table class="table-auto w-full mt-2 border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-2 py-1 border">Ticket</th>
                            <th class="px-2 py-1 border">Estado</th>
                            <th class="px-2 py-1 border">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketsCliente as $ticket)
                            <tr
                                class="{{ $ticket->status === 'attending' ? 'bg-blue-200' : ($ticket->status === 'wait' ? 'bg-green-200' : 'bg-red-200') }}">
                                <td class="px-2 py-1 border">{{ $ticket->number_ticket }}</td>
                                <td class="px-2 py-1 border">{{ ucfirst($ticket->status) }}</td>
                                <td class="px-2 py-1 border">
                                    @if ($ticket->status === 'attending')
                                        <form
                                            action="{{ route('panel.next', ['type' => $ticket->type, 'ticket' => $ticket->id]) }}"
                                            method="POST">
                                            @csrf
                                            <button
                                                class="buttonFinish bg-red-600 text-white px-2 py-1 rounded">Finalizar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>

</html>
