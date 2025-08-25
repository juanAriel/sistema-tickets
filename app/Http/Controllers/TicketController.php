<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected TicketService $ticketService;
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    public function formTicket()
    {
        return view('tickets.create');
    }

    public function generateTicket(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:cajero,cliente',
        ]);

        $ticket = DB::transaction(function () use ($data) {
            $counter = Counter::where('type', $data['type'])
                ->lockForUpdate()
                ->firstOrFail();


            $next = $counter->last_number + 1;
            $counter->last_number = $next;
            $counter->save();


            return Ticket::create([
                'type' => $data['type'],
                'number_ticket' => $next,
                'status' => 'wait',
            ]);
        });

        // dd('test');
        return view('tickets.ticket')->with(
            ['ticket' => $ticket]
        );
    }

    /**funtion to see ticket actual and next */
    public function panel(string $type)
    {
        abort_unless(in_array($type, ['cajero', 'cliente']), 404);

        $actualCajero = Ticket::type('cajero')->attending()->first();
        $actualCliente = Ticket::type('cliente')->attending()->first();

        $ticketsCajero = Ticket::type('cajero')->orderBy('id')->get();
        $ticketsCliente = Ticket::type('cliente')->orderBy('id')->get();

        if ($type == 'cliente') {
            $statusBtn = $this->ticketService->statusBtn($ticketsCliente);
        } else {
            $statusBtn = $this->ticketService->statusBtn($ticketsCajero);
        }

        return view('panel.index', compact(
            'actualCajero',
            'actualCliente',
            'ticketsCajero',
            'ticketsCliente',
            'type',
            'statusBtn'
        ));
    }

    // Avanzar al siguiente: cierra el actual (si hay) y toma el siguiente en espera
    public function nextTicket(string $type, $ticketId = null)
    {
        // ✅ Caso 1: finalizar ticket existente
        if ($ticketId) {
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->status = 'served';
            $ticket->attending_in = null;
            $ticket->save();

            return back()->with('success', "El ticket {$ticket->number_ticket} fue finalizado.");
        }

        // ✅ Caso 2: llamar al siguiente ticket en espera
        $next = Ticket::type($type)->inWait()->orderBy('id')->first();
        if ($next) {
            $next->status = 'attending';
            $next->attending_in = now();
            $next->save();

            return back()->with('success', "Ahora atendiendo el ticket {$next->number_ticket}.");
        }

        return back()->with('warning', "No hay tickets en espera para {$type}.");
    }



    // Monitor (pantalla pública)
    public function monitor(string $type)
    {
        abort_unless(in_array($type, ['cajero', 'cliente']), 404);
        return view('monitor.show', compact('type'));
    }


    // Endpoint para polling (JSON)
    public function statusTicket(string $type)
    {
        abort_unless(in_array($type, ['cajero', 'cliente']), 404);

        $actual = Ticket::type($type)->attending()->orderByDesc('updated_at')->first();

        // dd(empty($actual));

        $ultimos = Ticket::type($type)
            ->served()
            ->orderBy('updated_at', 'desc')
            ->limit(empty($actual) ? 6 : 5)
            ->get(['number_ticket']);

            // dd($ultimos);

        return response()->json([
            'actual' => $actual ? $actual->number_ticket : null,
            'ultimos' => $ultimos->pluck('number_ticket')
                ->when($actual, fn($c) => $c->prepend($actual->number_ticket)), // agregamos el actual al final
        ]);
    }
}
