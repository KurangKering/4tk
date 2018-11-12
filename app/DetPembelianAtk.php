<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPembelianAtk extends Model
{
	protected $table = 'det_pembelian_atk';
	protected $fillable = ['pembelian_atk_id', 'mst_atk_id', 'jumlah'];

	public function pembelian_atk()
	{
		return $this->belongsTo('App\PembelianAtk');
	}
	public function mst_atk()
	{
		return $this->belongsTo('App\MstAtk');
	}
}
