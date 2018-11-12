<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPerawatan extends Model
{
	protected $table = 'det_perawatan';
	protected $fillable = ['perawatan_id', 'mst_barang_id', 'jumlah', 'biaya'];

	
	public function perawatan()
	{
		return $this->belongsTo('App\Perawatan');
	}
	public function mst_barang()
	{
		return $this->belongsTo('App\MstBarang');
	}
}
