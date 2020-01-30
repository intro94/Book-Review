<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 */
class User extends Model
{
    use Notifiable;

    /**
     * @var int
     */
    public const USER_TYPE  = 1;

    /**
     * @var int
     */
    public const ADMIN_TYPE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'birth_date',
    ];

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entryPoints()
    {
        return $this->hasMany(EntryPoint::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
        //нет такого слова!
    {
        return $this->hasMany(Comment::class);
    }
}
