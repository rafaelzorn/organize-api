<?php

namespace App\Organize\UserMovement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'movement_category_id',
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
        'created_at',
        'updated_at',
        'user_id',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id'              => 'integer',
        'movement_category_id' => 'integer',
        'description'          => 'string',
        'value'                => 'decimal:2',
        'movement_date'        => 'date:Y-m-d',
        'movement_type'        => 'string',
        'created_at'           => 'datetime:Y-m-d H:i:s',
        'updated_at'           => 'datetime:Y-m-d H:i:s',
        'deleted_at'           => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @param Builder $query
     * @param int $movementCategoryId
     *
     * @return Builder
     */
    public function scopeWhereByMovementCategoryId(Builder $query, int $movementCategoryId): Builder
    {
        return $query->where('movement_category_id', $movementCategoryId);
    }

    /**
     * @param Builder $query
     * @param int $userId
     *
     * @return Builder
     */
    public function scopeWhereByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param Builder $query
     * @param string $movementDateStartDate
     * @param string $movementDateFinalDate
     *
     * @return Builder
     */
    public function scopeWhereBetweenMovementDatePeriod(Builder $query, string $movementDateStartDate, string $movementDateFinalDate): Builder
    {
        return $query->whereBetween('movement_date', [$movementDateStartDate, $movementDateFinalDate]);
    }
}
