<?php

namespace App\Models;

use App\Traits\BasemodelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Basemodel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Basemodel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basemodel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basemodel query()
 * @mixin \Eloquent
 */
class Basemodel extends Model
{
    use BasemodelTrait, HasFactory;

    protected $guarded = ['id'];
}
