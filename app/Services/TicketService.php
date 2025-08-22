<?php

namespace App\Services;

use Illuminate\Support\Collection;

class TicketService
{
    /**
     * status btn next ticket
     */
    public function statusBtn(Collection|array $tickets): bool
    {
        $count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->status == 'attending') {
                return true;
            }
            if ($ticket->status == 'served') {
                $count++;
            }
        }
        if (count($tickets) == $count) {
            return true;
        }

        return false;
    }
}
