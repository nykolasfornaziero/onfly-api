<?php


namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelDestinationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'destination' => $this->destination,
            'departure_date' => Carbon::parse($this->departure_date)->format('d/m/Y'),
            'return_date' => $this->return_date ? Carbon::parse($this->return_date)->format('d/m/Y') : null,
            'status' => $this->status,
            'is_trip_active' => $this->departure_date <= now() && $this->return_date >= now(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
