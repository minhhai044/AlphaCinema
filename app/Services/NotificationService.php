<?php

namespace App\Services;

use App\Events\RealTimeNotificationEvent;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function indexService()
    {
        $query = Notification::with('ticket.cinema.branch')->latest('id');

        if (Auth::user()->branch_id) {
            $query->whereHas('ticket.cinema', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });
        }
        if (Auth::user()->cinema_id) {
            $query->whereHas('ticket', function ($q) {
                $q->where('cinema_id', Auth::user()->cinema_id);
            });
        }

        return $query->get();
    }
    public function storeService(array $data)
    {

        $ticket = Ticket::with('cinema.branch')->find($data['ticket_id']);


        $notification = [];
        $notification = Notification::create($data);

        if ($ticket && $ticket->cinema) {
            $notification['cinema_id'] = $ticket->cinema_id;
            $notification['branch_id'] = $ticket->cinema->branch_id ?? null;
        }
        Log::info($notification);
        
        broadcast(new RealTimeNotificationEvent($notification))->toOthers();

        return $notification;
    }
}
