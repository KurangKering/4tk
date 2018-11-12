<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembelianAtk extends Model
{
	protected $table = 'pembelian_atk';
	protected $fillable = ['tanggal_pembelian'];
	protected $dates = ['tanggal_pembelian'];
	public function det_pembelian_atk()
	{
		return $this->hasMany('App\DetPembelianAtk');
	}
}
