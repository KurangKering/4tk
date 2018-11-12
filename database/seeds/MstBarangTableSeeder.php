<?php

use Illuminate\Database\Seeder;
use App\MstBarang;
class MstBarangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$arr = [

    		[
    			'nama' => 'Printer',
    			'satuan' => 'Unit',
    		],
    		[
    			'nama' => 'Komputer',
    			'satuan' => 'Unit',
    		],
    		[
    			'nama' => 'Scanner',
    			'satuan' => 'Unit',
    		],
  
    	];

    	MstBarang::truncate();
    	foreach ($arr as $k => $b) {
    		MstBarang::create($b);
    	}
    }
}
