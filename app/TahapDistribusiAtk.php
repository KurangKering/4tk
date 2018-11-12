<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahapDistribusiAtk extends Model
{
	protected $table = 'tahap_distribusi_atk';
	protected $fillable = ['distribusi_atk_id', 'tanggal_distribusi'];
	protected $dates = ['tanggal_distribusi'];
	public function distribusi_atk()
	{
		return $this->belongsTo('App\DistribusiAtk');
	}

	public function det_distribusi_atk()
	{
		return $this->hasMany('App\DetDistribusiAtk');
	}
}