<?php

use Illuminate\Database\Seeder;
use App\SubBidang;
class SubBidangTableSeeder extends Seeder
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
    			'nama' => 'Subbidang A',
    			'keterangan' => 'Subbidang A',
    		],
    		[
    			'nama' => 'Subbidang B',
    			'keterangan' => 'Subbidang B',
    		],
    		[
    			'nama' => 'Subbidang C',
    			'keterangan' => 'Subbidang C',
    		],
    		[
    			'nama' => 'Subbidang D',
    			'keterangan' => 'Subbidang D',
    		],
    	];

    	SubBidang::truncate();
    	foreach ($arr as $k => $b) {
    		SubBidang::create($b);
    	}
    }
}
