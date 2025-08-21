<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TicketController extends Controller
{
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

        $actual = Ticket::type($type)->attending()->orderByDesc('updated_at')->first();
        $cola = Ticket::type($type)->wait()->orderBy('id')->limit(5)->get();

        return view('panel.index', compact('type', 'actual', 'cola'));
    }

    // Avanzar al siguiente: cierra el actual (si hay) y toma el siguiente en espera
    public function nextTicket(string $type)
    {
        abort_unless(in_array($type, ['cajero', 'cliente']), 404);


        DB::transaction(function () use ($type) {
            $actual = Ticket::type($type)->attending()->orderByDesc('updated_at')->first();
            if ($actual) {
                $actual->update(['status' => 'attending', 'attending_in' => now()]);
            }

            $next = Ticket::type($type)->wait()->orderBy('id')->lockForUpdate()->first();
            if ($next) {
                $next->update(['status' => 'attending']);
            }
        });


        return back();
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
        $cola = Ticket::type($type)->wait()->orderBy('id')->limit(5)->get(['number_ticket']);


        return response()->json([
            'actual' => $actual ? $actual->number_ticket : null,
            'cola' => $cola->pluck('number_ticket'),
        ]);
    }
}
