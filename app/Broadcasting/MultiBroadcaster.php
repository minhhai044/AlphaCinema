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
                'pusher' => app(\Illuminate\Contracts\Broadcasting\Factory::class)->driver('pusher'),
                'reverb' => app(\Illuminate\Contracts\Broadcasting\Factory::class)->driver('reverb'),
            ];
        }
    }

    public function auth($request)
    {
        $this->loadBroadcasters();
        return $this->broadcasters['pusher']->auth($request);
    }

    public function validAuthenticationResponse($request, $result)
    {
        $this->loadBroadcasters();
        return $this->broadcasters['pusher']->validAuthenticationResponse($request, $result);
    }

    // public function broadcast(array $channels, $event, array $payload = [])
    // {
    //     $this->loadBroadcasters();

    //     $eventClass = is_object($event) ? get_class($event) : $event;
    //     Log::info( $eventClass);
    //     switch ($eventClass) {
    //         case \App\Events\RealTimeSeatEvent::class:
    //             Log::info('🎯 Gửi tới REVERB');
    //             $this->broadcasters['reverb']->broadcast($channels, $event, $payload);
    //             break;

    //         case \App\Events\RealTimeChatEvent::class:
    //             Log::info('💬 Gửi tới PUSHER');
    //             $this->broadcasters['pusher']->broadcast($channels, $event, $payload);
    //             break;

    //         default:
    //             foreach ($this->broadcasters as $name => $broadcaster) {
    //                 $broadcaster->broadcast($channels, $event, $payload);
    //             }
    //     }
    // }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->loadBroadcasters();


        $channelNames = array_map(fn($ch) => (string) $ch, $channels);

       
        $eventClass = is_object($event) ? get_class($event) : $event;

        switch ($eventClass) {
            case \App\Events\RealTimeSeatEvent::class:
                Log::info('🎯 Gửi tới REVERB');
                $this->broadcasters['reverb']->broadcast($channelNames, $event, $payload);
                break;

            case \App\Events\RealTimeChatEvent::class:
                Log::info('💬 Gửi tới PUSHER');
                $this->broadcasters['pusher']->broadcast($channelNames, $event, $payload);
                break;

            default:
                foreach ($this->broadcasters as $name => $broadcaster) {
                    $broadcaster->broadcast($channelNames, $event, $payload);
                }
        }
    }

    public function channel(...$parameters)
    {
        $this->loadBroadcasters();
        return $this->broadcasters['pusher']->channel(...$parameters);
    }
}
