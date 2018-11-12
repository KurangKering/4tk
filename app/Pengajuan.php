<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
	protected $table = "pengajuan";
	protected $fillable = ['is_paraf', 'subbidang_id', 'pengajuan_user_id', 'paraf_user_id'];
	public function perawatan()
	{
		return $this->hasOne('App\Perawatan');
	}

	public function det_pengajuan()
	{
		return $this->hasMany('App\DetPengajuan');
	}
	public function subbidang()
	{
		return $this->belongsTo('App\SubBidang');
	}
	
	public function user()
	{
		return $this->belongsTo('App\User', 'pengajuan_user_id');
	}
}
