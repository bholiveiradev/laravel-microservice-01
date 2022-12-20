<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid'      => $this->uuid,
            'name'      => $this->name,
            'slug'      => $this->url,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'whatsapp'  => $this->whatsapp,
            'facebook'  => $this->facebook,
            'instagram' => $this->instagram,
            'youtube'   => $this->youtube,
            'category'  => new CategoryResource($this->category),
        ];
    }
}
