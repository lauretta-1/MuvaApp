<?php

namespace App\Traits;

use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Static_;

trait BasemodelTrait
{
    // protected  $uuid;

    /**
     * @return bool
     */

    //abstract protected function set_uuid_key();

    protected $has_uuid = false;

    protected function has_uuid(): bool
    {
        return $this->has_uuid ?? false;
    }

    protected static function localBooted(){}

    protected static function booted()
    {
        static::localBooted();

        static::creating(function ($data){
            if($data->has_uuid() || $data->has_uuid) {
                $data->attributes['uuid'] = Str::uuid();
            }
        });
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function findByUuid($uuid): mixed
    {
        return $this->where('uuid', $uuid)->first();
    }

}
