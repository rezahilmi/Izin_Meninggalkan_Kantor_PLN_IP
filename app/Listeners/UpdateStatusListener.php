<?php

namespace App\Listeners;

use App\Events\UpdateStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateStatusEvent $event): void
    {
        Izin::where('nip', auth()->user()->nip)->update(['status' => 2]);
    }
}
