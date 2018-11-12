<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MstAtk extends Model
{
	protected $table = "mst_atk";
	protected $fillable = ['nama', 'satuan', 'kode', 'stock'];

	public function det_distribusi_atk()
	{
		return $this->hasMany('App\DetDistribusiAtk');
	}
	public function det_permintaan_atk()
	{
		return $this->hasMany('App\DetPermintaanAtk');
	}
	public function det_pembelian_atk()
	{
		return $this->hasMany('App\DetPembelianAtk');
	}

}
