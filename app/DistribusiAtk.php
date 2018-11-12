<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistribusiAtk extends Model
{
	protected $table = 'distribusi_atk';
	protected $fillable = [ 'permintaan_atk_id', 'status'];


	public function tahap_distribusi_atk()
	{
		return $this->hasMany('App\TahapDistribusiAtk');
	}

	public function permintaan_atk()
	{
		return $this->belongsTo('App\PermintaanAtk');
	}
}
