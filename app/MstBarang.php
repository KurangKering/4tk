<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MstBarang extends Model
{
    protected $table = "mst_barang";
    protected $fillable = ['nama', 'satuan'];

	public function det_perawatan()
	{
		return $this->hasMany('App\DetPerawatan');
	}

	
}
