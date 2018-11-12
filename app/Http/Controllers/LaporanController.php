<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\DistribusiAtk;
use App\TahapDistribusiAtk;
use App\DetDistribusiAtk;
use App\PembelianAtk;
use App\DetPembelianAtk;
use App\MstAtk;
use App\Perawatan;
use App\DetPerawatan;
use PDF;
class LaporanController extends Controller
{

	/**
	 * url('laporan/front_opname')
	 */
	public function frontOpname(Request $request)
	{
		
		$pembelian = PembelianAtk::select('created_at');
		$distribusi = DistribusiAtk::select('created_at');
		$tahapDistribusi = TahapDistribusiAtk::select('created_at');
		$years = $pembelian->union($distribusi)
		->union($tahapDistribusi)
		->get()
		->pluck('created_at');
		$allYear = $years->map(function($y) {
			return $y->year;
		});
		$uniqueYear = $allYear->unique()->sort()->all();

        $year = $request->query('tahun');
        $month = $request->query('bulan');
        $paramWaktu = Carbon::create($year, $month, 1);
        $paramWaktu = $paramWaktu < now() ? $paramWaktu : Carbon::parse(now())->day(1); 

        $waktuSekarang = Carbon::parse(now());


        $dataDistribusiBulanItu = DistribusiAtk::whereHas('tahap_distribusi_atk', function($q) use($paramWaktu) {
            $q->whereMonth('tanggal_distribusi', '=', $paramWaktu->format('m'));
            $q->whereYear('tanggal_distribusi', '=', $paramWaktu->format('Y'));
        })
        ->with(['tahap_distribusi_atk' => function($q) use($paramWaktu) {
            $q->whereMonth('tanggal_distribusi', '=', $paramWaktu->format('m'));
            $q->whereYear('tanggal_distribusi', '=', $paramWaktu->format('Y'));

        }])
        ->get();

        /**
         * [$arrDistribusiBarang description]
         * Adalah tempat menyimpan data distribusi barang selama bulan 
         * yang telah ditetapkan.
         */
        $arrDistribusiBarang = [];
        foreach ($dataDistribusiBulanItu as $k_d => $sDistribusi) {
            foreach ($sDistribusi->tahap_distribusi_atk as $k_s_t => $sTahap) {
                foreach ($sTahap->det_distribusi_atk as $k_d_a => $sDet) {
                    $key_exist = array_search($sDet->mst_atk_id, array_column($arrDistribusiBarang, 'mst_atk_id'));

                    if ($key_exist === FALSE) {
                        $arr = [
                            'mst_atk_id' => $sDet->mst_atk_id,
                            'jumlah' => $sDet->jumlah,
                        ];
                        $arrDistribusiBarang[] = $arr;
                    } else
                    {
                        $arrDistribusiBarang[$key_exist]['jumlah'] += $sDet->jumlah;
                    }
                }
            }
        }
        
        /**
         * @var array $[dataSeluruhDistribusi] [<data distribusi bulan 8 sampai sekarang>]
         */
        $dataSeluruhDistribusi = DistribusiAtk::whereHas('tahap_distribusi_atk', function($q) use($paramWaktu) {
            $q->whereDate('tanggal_distribusi','>=', $paramWaktu->format('Y-m-d'));
        })
        ->with(['tahap_distribusi_atk' => function($q) use($paramWaktu) {
            $q->whereDate('tanggal_distribusi', '>=', $paramWaktu->format('Y-m-d'));

        }])
        ->get();

        /**
         * @var array 
         * data seluruh  barang dan jumlah pada  bulan 8 sampai sekarang;
         */
        $arrSeluruhDistribusi = [];
        foreach ($dataSeluruhDistribusi as $k_d => $sDistribusi) {
            foreach ($sDistribusi->tahap_distribusi_atk as $k_s_t => $sTahap) {
                foreach ($sTahap->det_distribusi_atk as $k_d_a => $sDet) {
                    $key_exist = array_search($sDet->mst_atk_id, array_column($arrSeluruhDistribusi, 'mst_atk_id'));

                    if ($key_exist === FALSE) {
                        $arr = [
                            'mst_atk_id' => $sDet->mst_atk_id,
                            'jumlah' => $sDet->jumlah,
                        ];
                        $arrSeluruhDistribusi[] = $arr;
                    } else
                    {
                        $arrSeluruhDistribusi[$key_exist]['jumlah'] += $sDet->jumlah;
                    }
                }
            }
        }

        $dataPembelianBulanItu = PembelianAtk::whereMonth('tanggal_pembelian', '=', $paramWaktu->format('m'))
        ->whereYear('tanggal_pembelian', '=', $paramWaktu->format('Y'))
        ->get();
        $arrPembelianBulanItu = [];
        foreach ($dataPembelianBulanItu as $k_d => $sPembelian) {
            foreach ($sPembelian->det_pembelian_atk as $kdpa => $sDetail) {
                $key_exist = array_search($sDetail->mst_atk_id, array_column($arrPembelianBulanItu, 'mst_atk_id'));

                if ($key_exist === FALSE) {
                    $arr = [
                        'mst_atk_id' => $sDetail->mst_atk_id,
                        'jumlah' => $sDetail->jumlah,
                    ];
                    $arrPembelianBulanItu[] = $arr;
                } else
                {
                    $arrPembelianBulanItu[$key_exist]['jumlah'] += $sDetail->jumlah;
                }
            }
        }

        $dataPembelian = PembelianAtk::whereDate('tanggal_pembelian', '>=', $paramWaktu->format('Y-m-d'))->get();
        $arrSeluruhPembelian = [];
        foreach ($dataPembelian as $k_d => $sPembelian) {
            foreach ($sPembelian->det_pembelian_atk as $kdpa => $sDetail) {
                $key_exist = array_search($sDetail->mst_atk_id, array_column($arrSeluruhPembelian, 'mst_atk_id'));

                if ($key_exist === FALSE) {
                    $arr = [
                        'mst_atk_id' => $sDetail->mst_atk_id,
                        'jumlah' => $sDetail->jumlah,
                    ];
                    $arrSeluruhPembelian[] = $arr;
                } else
                {
                    $arrSeluruhPembelian[$key_exist]['jumlah'] += $sDetail->jumlah;
                }
            }
        }

        $stockBarangBulanItu = MstAtk::get()->toArray();

        foreach ($stockBarangBulanItu as $k_stock => $stock) {
            $i_dis = array_search($stock['id'], array_column($arrSeluruhDistribusi, 'mst_atk_id'));
            $i_pem = array_search($stock['id'], array_column($arrSeluruhPembelian, 'mst_atk_id'));

            if ($i_dis !== FALSE) {
                $stockBarangBulanItu[$k_stock]['stock'] += $arrSeluruhDistribusi[$i_dis]['jumlah'];
            }
            if ($i_pem !== FALSE) {
                $stockBarangBulanItu[$k_stock]['stock'] -= $arrSeluruhPembelian[$i_pem]['jumlah'];

            }
        }

        $tanggalStock = Carbon::parse($paramWaktu)->subDay(1);
        $bulanLaporan = Carbon::parse($paramWaktu);
        $akhirBulanLaporan = Carbon::parse($paramWaktu)->endOfMonth();

       
        return view('laporan.front_opname', compact('uniqueYear','stockBarangBulanItu', 'tanggalStock', 'arrSeluruhDistribusi', 'arrDistribusiBarang', 'arrPembelianBulanItu', 'bulanLaporan', 'akhirBulanLaporan'));
    }

	/**
	 * url('laporan/front_perawatan')
	 */
	public function frontPerawatan(Request $request)
	{
		

        $perawatans = Perawatan::where('status', '2')->get();
        $yearPerawatan = $perawatans->pluck('tanggal_perawatan');
        $allYear = $yearPerawatan->map(function($y) {
            return $y->year;
        });
        
        $uniqueYear = $allYear->unique()->sort()->all();
        return view('laporan.front_perawatan', compact('uniqueYear'));
    }


	/**
	 * url('laporan/cetak_opname')
	 */
	public function cetakOpname(Request $request)
	{
		$year = $request->query('tahun');
		$month = $request->query('bulan');
		$paramWaktu = Carbon::create($year, $month, 1);
		$paramWaktu = $paramWaktu < now() ? $paramWaktu : Carbon::parse(now())->day(1); 

		$waktuSekarang = Carbon::parse(now());


		$dataDistribusiBulanItu = DistribusiAtk::whereHas('tahap_distribusi_atk', function($q) use($paramWaktu) {
			$q->whereMonth('tanggal_distribusi', '=', $paramWaktu->format('m'));
			$q->whereYear('tanggal_distribusi', '=', $paramWaktu->format('Y'));
		})
		->with(['tahap_distribusi_atk' => function($q) use($paramWaktu) {
			$q->whereMonth('tanggal_distribusi', '=', $paramWaktu->format('m'));
			$q->whereYear('tanggal_distribusi', '=', $paramWaktu->format('Y'));

		}])
		->get();

        /**
         * [$arrDistribusiBarang description]
         * Adalah tempat menyimpan data distribusi barang selama bulan 
         * yang telah ditetapkan.
         */
        $arrDistribusiBarang = [];
        foreach ($dataDistribusiBulanItu as $k_d => $sDistribusi) {
        	foreach ($sDistribusi->tahap_distribusi_atk as $k_s_t => $sTahap) {
        		foreach ($sTahap->det_distribusi_atk as $k_d_a => $sDet) {
        			$key_exist = array_search($sDet->mst_atk_id, array_column($arrDistribusiBarang, 'mst_atk_id'));

        			if ($key_exist === FALSE) {
        				$arr = [
        					'mst_atk_id' => $sDet->mst_atk_id,
        					'jumlah' => $sDet->jumlah,
        				];
        				$arrDistribusiBarang[] = $arr;
        			} else
        			{
        				$arrDistribusiBarang[$key_exist]['jumlah'] += $sDet->jumlah;
        			}
        		}
        	}
        }
        
        /**
         * @var array $[dataSeluruhDistribusi] [<data distribusi bulan 8 sampai sekarang>]
         */
        $dataSeluruhDistribusi = DistribusiAtk::whereHas('tahap_distribusi_atk', function($q) use($paramWaktu) {
        	$q->whereDate('tanggal_distribusi','>=', $paramWaktu->format('Y-m-d'));
        })
        ->with(['tahap_distribusi_atk' => function($q) use($paramWaktu) {
        	$q->whereDate('tanggal_distribusi', '>=', $paramWaktu->format('Y-m-d'));

        }])
        ->get();

        /**
         * @var array 
         * data seluruh  barang dan jumlah pada  bulan 8 sampai sekarang;
         */
        $arrSeluruhDistribusi = [];
        foreach ($dataSeluruhDistribusi as $k_d => $sDistribusi) {
        	foreach ($sDistribusi->tahap_distribusi_atk as $k_s_t => $sTahap) {
        		foreach ($sTahap->det_distribusi_atk as $k_d_a => $sDet) {
        			$key_exist = array_search($sDet->mst_atk_id, array_column($arrSeluruhDistribusi, 'mst_atk_id'));

        			if ($key_exist === FALSE) {
        				$arr = [
        					'mst_atk_id' => $sDet->mst_atk_id,
        					'jumlah' => $sDet->jumlah,
        				];
        				$arrSeluruhDistribusi[] = $arr;
        			} else
        			{
        				$arrSeluruhDistribusi[$key_exist]['jumlah'] += $sDet->jumlah;
        			}
        		}
        	}
        }

        $dataPembelianBulanItu = PembelianAtk::whereMonth('tanggal_pembelian', '=', $paramWaktu->format('m'))
        ->whereYear('tanggal_pembelian', '=', $paramWaktu->format('Y'))
        ->get();
        $arrPembelianBulanItu = [];
        foreach ($dataPembelianBulanItu as $k_d => $sPembelian) {
        	foreach ($sPembelian->det_pembelian_atk as $kdpa => $sDetail) {
        		$key_exist = array_search($sDetail->mst_atk_id, array_column($arrPembelianBulanItu, 'mst_atk_id'));

        		if ($key_exist === FALSE) {
        			$arr = [
        				'mst_atk_id' => $sDetail->mst_atk_id,
        				'jumlah' => $sDetail->jumlah,
        			];
        			$arrPembelianBulanItu[] = $arr;
        		} else
        		{
        			$arrPembelianBulanItu[$key_exist]['jumlah'] += $sDetail->jumlah;
        		}
        	}
        }

        $dataPembelian = PembelianAtk::whereDate('tanggal_pembelian', '>=', $paramWaktu->format('Y-m-d'))->get();
        $arrSeluruhPembelian = [];
        foreach ($dataPembelian as $k_d => $sPembelian) {
        	foreach ($sPembelian->det_pembelian_atk as $kdpa => $sDetail) {
        		$key_exist = array_search($sDetail->mst_atk_id, array_column($arrSeluruhPembelian, 'mst_atk_id'));

        		if ($key_exist === FALSE) {
        			$arr = [
        				'mst_atk_id' => $sDetail->mst_atk_id,
        				'jumlah' => $sDetail->jumlah,
        			];
        			$arrSeluruhPembelian[] = $arr;
        		} else
        		{
        			$arrSeluruhPembelian[$key_exist]['jumlah'] += $sDetail->jumlah;
        		}
        	}
        }

        $stockBarangBulanItu = MstAtk::get()->toArray();

        foreach ($stockBarangBulanItu as $k_stock => $stock) {
        	$i_dis = array_search($stock['id'], array_column($arrSeluruhDistribusi, 'mst_atk_id'));
        	$i_pem = array_search($stock['id'], array_column($arrSeluruhPembelian, 'mst_atk_id'));

        	if ($i_dis !== FALSE) {
        		$stockBarangBulanItu[$k_stock]['stock'] += $arrSeluruhDistribusi[$i_dis]['jumlah'];
        	}
        	if ($i_pem !== FALSE) {
        		$stockBarangBulanItu[$k_stock]['stock'] -= $arrSeluruhPembelian[$i_pem]['jumlah'];

        	}
        }

        $tanggalStock = Carbon::parse($paramWaktu)->subDay(1);
        $bulanLaporan = Carbon::parse($paramWaktu);
        $akhirBulanLaporan = Carbon::parse($paramWaktu)->endOfMonth();

        $pdf = PDF::setPaper('A4','landscape')->loadView('laporan.laporan_bulanan_opname', compact('stockBarangBulanItu', 'tanggalStock', 'arrSeluruhDistribusi', 'arrDistribusiBarang', 'arrPembelianBulanItu', 'bulanLaporan', 'akhirBulanLaporan'));
        return $pdf->stream();
    }
	/**
	 * url('laporan/cetak_perawatan')
	 */
	public function cetakPerawatan(Request $request)
	{
        $year = $request->query('tahun');
        $month = $request->query('bulan');
        $paramWaktu = Carbon::create($year, $month, 1);
        $paramWaktu = $paramWaktu < now() ? $paramWaktu : Carbon::parse(now())->day(1); 

        $waktuSekarang = Carbon::parse(now());

        $perawatans = Perawatan::where('status', '2')
        ->whereMonth('tanggal_perawatan', '=', $paramWaktu->format('m'))
        ->whereYear('tanggal_perawatan', '=', $paramWaktu->format('Y'))
        ->get();
        $dataLaporan = [];
        $total = 0;
        foreach ($perawatans as $k => $v) {
            foreach ($v->det_perawatan as $key => $value) {
                $mst_barang_id = $value->mst_barang_id;
                $nama_barang = $value->mst_barang->nama;
                $jumlah = $value->jumlah;
                $satuan = $value->mst_barang->satuan;
                $biaya = $value->biaya;
                $total += $biaya;
                $index = array_search($mst_barang_id, array_column($dataLaporan, 'mst_barang_id'));
                if ($index === FALSE) {
                    $dataLaporan[] =
                    [
                        'mst_barang_id' => $mst_barang_id,
                        'jumlah' => $jumlah,
                        'biaya' => $biaya,
                        'nama_barang' => $nama_barang,
                        'satuan' => $satuan,
                    ];
                } else 
                {
                    $dataLaporan[$index]['jumlah'] += $jumlah;
                    $dataLaporan[$index]['biaya'] += $biaya;
                }
            }
        }

        $pdf = PDF::loadView('laporan.laporan_bulanan_perawatan', compact('paramWaktu', 'dataLaporan', 'total'));
        return $pdf->stream();

    }
}
