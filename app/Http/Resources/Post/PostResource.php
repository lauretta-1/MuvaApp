<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
                    'title'=>$this->title,
                    'body'=>$this->body,
                    'category'=>$this->category,
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
