<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetDistribusiAtk extends Model
{
	protected $table = 'det_distribusi_atk';
	protected $fillable = ['tahap_distribusi_atk_id', 'mst_atk_id', 'jumlah'];

	
	public function tahap_distribusi_atk()
	{
		return $this->belongsTo('App\TahapDistribusiAtk');
	}
	public function mst_atk()
	{
		return $this->belongsTo('App\MstAtk');
	}
}
