<?php

namespace App\Organize\UserMovement\Models;

use Illuminate\Database\Eloquent\Model;

class UserMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'description',
        'value',
        'movement_date',
        'movement_type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id'                   => 'integer',
        'description'               => 'string',
        'value'                     => 'decimal:2',
        'movement_date'             => 'date:Y-m-d',
        'movement_type'             => 'string',
        'created_at'                => 'datetime:Y-m-d H:i:s',
        'updated_at'                => 'datetime:Y-m-d H:i:s',
        'deleted_at'                => 'datetime:Y-m-d H:i:s',
    ];
}
