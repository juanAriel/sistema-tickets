<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ticket {{ strtoupper($ticket->type) }}-{{ $ticket->number_ticket }}</title>
  <style>
    /* Estilo simple tipo ticket (impresora térmica) */
    * { box-sizing: border-box; }
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    .ticket { width: 260px; margin: 0 auto; padding: 12px; }
    .center { text-align: center; }
    .big { font-size: 40px; font-weight: 800; line-height: 1; margin: 8px 0; }
    .small { font-size: 12px; color: #444; margin: 4px 0; }
    .hr { border: 0; border-top: 1px dashed #aaa; margin: 8px 0; }
    .btn { display:inline-block; padding:8px 12px; background:#111; color:#fff; text-decoration:none; border-radius:6px; }
    @media print {
      .no-print { display: none !important; }
      body { margin: 0; }
    }
  </style>
</head>
    <body onload="window.print()">
    <div class="ticket">
        <p class="center small">{{ config('app.name', 'Turnero') }}</p>
        <p class="center small">Tipo: <strong>{{ strtoupper($ticket->type) }}</strong></p>
        <p class="center big">{{ $ticket->number_ticket }}</p>
        <hr class="hr" />
        <p class="center small">Generado: {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
        <p class="center small">Conserve su ticket</p>
    </div>

    <div class="no-print" style="text-align:center;margin-top:12px;">
        <a class="btn" href="{{ route('tickets.form') }}">Volver</a>
        <a class="btn" href="#" onclick="window.print();return false;">Reimprimir</a>
    </div>
    </body>
</html>
