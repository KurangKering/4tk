<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubBidang extends Model
{
    protected $table = 'subbidang';
    protected $fillable = ['nama', 'keterangan'];

    public function users()
    {
    	return $this->hasMany('App\Users');
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
