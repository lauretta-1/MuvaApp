<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
                'posts'=>$this->posts,
                'user'=>$this->user,
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
