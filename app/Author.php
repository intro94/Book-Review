<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Все модели лучше держать в отдельной директории а не в корне app

/**
 * Class Author
 * @package App
 */
class Author extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'first_name', 'birth_date',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
