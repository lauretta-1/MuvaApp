<?php

namespace App\Http\Resources\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'uuid'=>$this->uuid,
                'attributes'=>[
                    'name'=>$this->name,
                    'eamil'=>$this->email,
                    'categories'=>$this->category,
                    'posts'=>$this->posts,
                    'created_at'=>$this->created_at
                ]
        ];
    }

    public function with($request)
    {
        return [
            'Status' => 'OK'
        ];
    }
}
