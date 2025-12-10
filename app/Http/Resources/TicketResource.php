<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status->label(),
            'created_at' => $this->created_at->toDateTimeString(),
            'customer' => [
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
            ],
            'files' => $this->getMedia('tickets')->map(function ($media) {
                return [
                    'name' => $media->file_name,
                    'url' => $media->getUrl(),
                ];
            }),
        ];
    }
}
