<?php

namespace App\Providers;

use App\Models\RoomChat;
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
        // View::composer('partials.sidebar', function ($view) {
        //     $dataSidebar = YourModel::getSidebarData(); // hoặc logic bạn cần lấy data
        //     $view->with('dataSidebar', $dataSidebar);
        // });
        View::composer('admin.layouts.partials.sidebar', function ($view) {
            $RoomChats = RoomChat::all(); 

            $view->with('RoomChats', $RoomChats);
        });
    }
}
