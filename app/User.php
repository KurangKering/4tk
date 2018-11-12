<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
 use Notifiable;
 use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'subbidang_id', 'users_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function subbidang()
    {
        return $this->belongsTo('App\Subbidang');
    }

    public function permintaan_atk()
    {
        return $this->hasMany('App\PermintaanAtk');
    }

    public function perawatan()
    {
        return $this->hasMany('App\Perawatan');
    }
}
