<?php

namespace App\Broadcasting;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Support\Facades\Log;

class MultiBroadcaster implements Broadcaster
{
    protected $broadcasters = [];

    protected function loadBroadcasters()
    {
        if (empty($this->broadcasters)) {
            $this->broadcasters = [
                app(\Illuminate\Contracts\Broadcasting\Factory::class)->driver('pusher'),
                app(\Illuminate\Contracts\Broadcasting\Factory::class)->driver('reverb'),
            ];
        }
    }

    public function auth($request)
    {
        $this->loadBroadcasters();
        return $this->broadcasters[0]->auth($request);
    }

    public function validAuthenticationResponse($request, $result)
    {
        $this->loadBroadcasters();
        return $this->broadcasters[0]->validAuthenticationResponse($request, $result);
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->loadBroadcasters();

        foreach ($this->broadcasters as $broadcaster) {
            Log::info('📡 Đang phát tới: ' . get_class($broadcaster));
            $broadcaster->broadcast($channels, $event, $payload);
        }
    }
    public function channel(...$parameters)
    {
        $this->loadBroadcasters();
        return $this->broadcasters[0]->channel(...$parameters);
    }
}
