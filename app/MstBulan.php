<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MstBulan extends Model
{
	protected $table = 'mst_bulan';
	protected $fillable = ['no_bulan', 'nama_bulan'];
}
