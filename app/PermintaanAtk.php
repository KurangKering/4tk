<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermintaanAtk extends Model
{
	protected $table = 'permintaan_atk';
	protected $fillable = ['subbidang_id', 'permintaan_user_id', 'tanggal_permintaan', 'is_paraf', 'paraf_user_id'];
	protected $dates = ['tanggal_permintaan'];

	protected $casts = ['is_paraf'];
	public function det_permintaan_atk()
	{
		return $this->hasMany('App\DetPermintaanAtk');
	}
	public function distribusi_atk()
	{
		return $this->hasOne('App\DistribusiAtk');
	}

	public function subbidang()
	{
		return $this->belongsTo('App\SubBidang');
	}
	
	public function user()
	{
		return $this->belongsTo('App\User', 'permintaan_user_id');
	}
}
