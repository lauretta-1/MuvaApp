<?php

namespace App\Models;

use App\Traits\BasemodelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Basemodel extends Model
{
    use BasemodelTrait, HasFactory;

    protected $guarded = ['id'];
}
