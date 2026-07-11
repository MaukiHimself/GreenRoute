<?php

namespace App\Events;

use App\Models\Truck;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TruckLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $truck;
    public $latitude;
    public $longitude;
    public $dailyDistance;
    public $isOnline;
    public $stopStatuses;
    public $etaList;

    /**
     * Create a new event instance.
     */
    public function __construct(Truck $truck, $latitude, $longitude, $dailyDistance, $isOnline, $stopStatuses = [], $etaList = [])
    {
        $this->truck = $truck;
        $this->latitude = (double) $latitude;
        $this->longitude = (double) $longitude;
        $this->dailyDistance = (double) $dailyDistance;
        $this->isOnline = (bool) $isOnline;
        $this->stopStatuses = $stopStatuses;
        $this->etaList = $etaList;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('truck-tracking.' . $this->truck->id),
            new Channel('truck-tracking-global'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'truck_id' => $this->truck->id,
            'plate_number' => $this->truck->plate_number,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'daily_distance' => $this->dailyDistance,
            'is_online' => $this->isOnline,
            'stop_statuses' => $this->stopStatuses,
            'eta_list' => $this->etaList,
            'last_updated' => now()->toIso8601String()
        ];
    }
}
