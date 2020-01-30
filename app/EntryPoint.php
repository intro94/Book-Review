<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class EntryPoint
 * @package App
 */
class EntryPoint extends Authenticatable
{
    /**
     * @var int
     */
    public const NATIVE_REG = 1;

    /**
     * @var int
     */
    public const GITHUB_REG = 2;

    /**
     * @var int
     */
    public const GOOGLE_REG = 3;

    /**
     * @var array
     */
    public static $oauth_names = [
        self::GITHUB_REG => 'github',
        self::GOOGLE_REG => 'google',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'password', 'login_name',
    ];

    /**
     * @var bool
     */
    protected $rememberTokenName = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->user->isAdmin();
    }
}
