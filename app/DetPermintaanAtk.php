<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPermintaanAtk extends Model
{
	protected $table = 'det_permintaan_atk';
	protected $fillable = ['permintaan_atk_id', 'mst_atk_id', 'jumlah'];

	public function permintaan_atk()
	{
		return $this->belongsTo('App\PermintaanAtk');
	}
	public function mst_atk()
	{
		return $this->belongsTo('App\MstAtk');
	}
}
