<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanKebutuhanExport implements FromView,ShouldAutoSize
{	
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		return view('pembelian_atk.laporan_kebutuhan', [
			'data' => $this->data,
		]);
	}
}
