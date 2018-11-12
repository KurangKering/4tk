<?php

use Illuminate\Database\Seeder;
use App\MstBulan;
class MstBulanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$mst_bulan = [
    		[
    			"nama_bulan" => "Januari",
    			"no_bulan" => 1
    		],
    		[
    			"nama_bulan" => "Maret",
    			"no_bulan" => 3
    		],
    		[
    			"nama_bulan" => "Mei",
    			"no_bulan" => 5
    		],
    		[
    			"nama_bulan" => "Juli",
    			"no_bulan" => 7
    		],
    		[
    			"nama_bulan" => "September",
    			"no_bulan" => 9
    		],
    		[
    			"nama_bulan" => "November",
    			"no_bulan" => 11
    		],

    	];
    	MstBulan::truncate();
    	foreach ($mst_bulan as $k => $b) {
            MstBulan::create($b);
        }
    }
}
