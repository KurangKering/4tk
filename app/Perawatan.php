<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
	protected $table = 'perawatan';
	protected $fillable = [ 'subbidang_id', 'tanggal_pengajuan', 'tanggal_perawatan', 'perawatan_user_id','status'];

	protected $dates = ['tanggal_perawatan'];

	public function det_perawatan()
	{
		return $this->hasMany('App\DetPerawatan');
	}

	public function subbidang()
	{
		return $this->belongsTo('App\Subbidang');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'perawatan_user_id');
	}
}
