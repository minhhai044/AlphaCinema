<?php

namespace App\Providers;

use App\Models\RoomChat;
use App\Services\NotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewRoomChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer('admin.layouts.partials.sidebar', function ($view) {
            $RoomChats = RoomChat::all(); 

            $view->with('RoomChats', $RoomChats);
        });

        View::composer('admin.layouts.partials.header', function ($view) {
            $notificationService = app(NotificationService::class);
            $notifications = $notificationService->indexService();
           
            $view->with('notifications',$notifications);
        });
    }
}
