<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TahapDistribusiAtk;
use App\DistribusiAtk;
use App\DetDistribusiAtk;
use App\DetPembelianAtk;
use App\PembelianAtk;
use App\MstAtk;
use PDF;
use App\Exports\LaporanKebutuhanExport;
use App\Imports\LaporanKebutuhanImport;
use Excel;
use Storage;
class PembelianAtkController extends Controller
{

    /**
     * url('pembelian_atk/{pembelian_id}/cetak')
     * @return [type] [description]
     */
    public function cetakPembelianAtk(Request $request, $id)
    {
        $pembelian = PembelianAtk::findOrFail($id);
        $pdf = PDF::loadView('pembelian_atk.cetak_pembelian_atk', compact('pembelian'));

        return $pdf->stream();
    }


    public function importLaporanKebutuhan(Request $request)
    {



        $isiFile = Excel::toArray(new LaporanKebutuhanImport(), request()->file('file'));
        $isiFile = is_array($isiFile) ? $isiFile[0] : NULL;

        if ($isiFile == NULL) 
            return response()->json(['success' => false]);


        $imported = collect();
        foreach ($isiFile as $key => $value) {
            if ($key == 0) {
                // if ($value[0] != 'Barang ID' && $value[1] != 'Nama' && $value[2] != 'satuan' && $value[3] != 'Kebutuhan' && $value[4] != 'Jumlah Beli' && $value[5] != 'Hargas')
                //     break;
                // else 
                continue;
            }
            $barang = MstAtk::where('id',$value[0])->get()->first();
            if (!$barang)
                continue;
            
            $arr = [
                'id' => $value[0],
                'nama' => $barang->nama,
                'jumlah' => $value[4],
                'satuan' => $barang->satuan,
                'harga' => $value[5],
            ];

            $imported->push($arr);

        }
        return response()->json($imported);
    }
    public function cetakLaporanKebutuhan()
    {
        $daftarTidakComplete = DistribusiAtk::whereIn('status', ['never', 'incomplete'])->get();
        $stockBarang = MstAtk::get();
        $daftarBarangBeli = [];
        foreach ($daftarTidakComplete as $k => $daftar) {
            $telahDistribusi = [];
            foreach ($daftar->tahap_distribusi_atk as $k => $v) {
                foreach ($v->det_distribusi_atk as $key => $value) {
                    $mst_atk_id = $value->mst_atk_id;
                    $jumlah = $value->jumlah;
                    $index = array_search($mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));
                    if ($index === FALSE) {
                        $telahDistribusi[] =
                        [
                            'mst_atk_id' => $mst_atk_id,
                            'jumlah' => $jumlah
                        ];
                    } else 
                    {
                        $telahDistribusi[$index]['jumlah'] += $jumlah;
                    }
                }
            }

            foreach ($daftar->permintaan_atk->det_permintaan_atk as $key => $det_permintaan) {

                $mst_atk_id = $det_permintaan->mst_atk_id;
                $stock = $stockBarang->where('id', $mst_atk_id)->first()->stock;
                $terdistribusi = 0;
                $kebutuhan = $det_permintaan->jumlah;

                if (!empty($telahDistribusi)) {
                    $indexTelah = array_search($det_permintaan->mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));
                    if ($indexTelah !== FALSE) {
                        $terdistribusi = $telahDistribusi[$indexTelah]['jumlah']  ;
                    } 
                }

                $pembelian = $stock - ($kebutuhan - $terdistribusi);

                $indexBarangBeli = array_search($det_permintaan->mst_atk_id, array_column($daftarBarangBeli, 'mst_atk_id'));
                if ($indexBarangBeli === FALSE) {
                    if ($pembelian >= 0)
                        continue;
                    $daftarBarangBeli[] =
                    [
                        'mst_atk_id' =>$det_permintaan->mst_atk_id,
                        'pembelian' => abs($pembelian), 
                    ];
                } else
                {
                    $daftarBarangBeli[$indexBarangBeli]['pembelian'] += abs($pembelian);
                }
            }
            

        }
        $MstAtkIDs = array_map(function($tmp) {return $tmp['mst_atk_id'];}, $daftarBarangBeli);

        $stockBarang = MstAtk::whereIn('id', $MstAtkIDs)->get();


        $daftarPembelian = PembelianAtk::get();
        $data['daftarBarangBeli'] = $daftarBarangBeli;
        $data['stockBarang'] = $stockBarang;

        $now = indonesian_date(now());
        
        if (empty($data['daftarBarangBeli'])) {
            echo "<script>window.close();</script>";
            return;
        }        
        return Excel::download(new LaporanKebutuhanExport($data), "Kebutuhan - $now.xlsx");

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $daftarTidakComplete = DistribusiAtk::whereIn('status', ['never', 'incomplete'])->get();
        $stockBarang = MstAtk::get();
        $daftarBarangBeli = [];
        foreach ($daftarTidakComplete as $k => $daftar) {
           $telahDistribusi = [];
           foreach ($daftar->tahap_distribusi_atk as $k => $v) {
            foreach ($v->det_distribusi_atk as $key => $value) {
                $mst_atk_id = $value->mst_atk_id;
                $jumlah = $value->jumlah;
                $index = array_search($mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));
                if ($index === FALSE) {
                    $telahDistribusi[] =
                    [
                        'mst_atk_id' => $mst_atk_id,
                        'jumlah' => $jumlah
                    ];
                } else 
                {
                    $telahDistribusi[$index]['jumlah'] += $jumlah;
                }
            }
        }
        foreach ($daftar->permintaan_atk->det_permintaan_atk as $key => $det_permintaan) {

            $mst_atk_id = $det_permintaan->mst_atk_id;
            $stock = $stockBarang->where('id', $mst_atk_id)->first()->stock;
            $terdistribusi = 0;
            $kebutuhan = $det_permintaan->jumlah;

            if (!empty($telahDistribusi)) {
                $indexTelah = array_search($det_permintaan->mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));
                if ($indexTelah !== FALSE) {
                    $terdistribusi = $telahDistribusi[$indexTelah]['jumlah']  ;
                } 
            }

            $pembelian = $stock - ($kebutuhan - $terdistribusi);

            $indexBarangBeli = array_search($det_permintaan->mst_atk_id, array_column($daftarBarangBeli, 'mst_atk_id'));
            if ($indexBarangBeli === FALSE) {
                $daftarBarangBeli[] =
                [
                    'mst_atk_id' =>$det_permintaan->mst_atk_id,
                    'pembelian' => $pembelian, 
                ];
            } else
            {
                $daftarBarangBeli[$indexBarangBeli]['pembelian'] += $pembelian;
            }
        }


    }
    $MstAtkIDs = array_map(function($tmp) {return $tmp['mst_atk_id'];}, $daftarBarangBeli);

    $stockBarang = MstAtk::whereIn('id', $MstAtkIDs)->get();


    $daftarPembelian = PembelianAtk::get();
    return view('pembelian_atk.index', compact('daftarBarangBeli', 'stockBarang', 'daftarPembelian'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $barangs = MstAtk::latest()->get();
        return view('pembelian_atk.create', compact('barangs'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = new \App\Libraries\Response();

        if ($request->get('tanggal_pembelian') == NULL)
            $response->addError('Tanggal Pembelian Tidak Boleh Kosong', 'tanggal_pembelian');
        if ($request->get('val_id') == NULL) {
            $response->addError('Tidak Ada ATK yang di pilih', 'val_id');
        }

        foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
            if (!preg_match('/^\d+$/', $jumlah))
                $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
        }
        foreach (($request->get('val_harga') ?? []) as $k => $harga) {
            if (!preg_match('/^\d+$/', $harga))
                $response->addError('Value Hanya Berisi Angka !', "val_harga[$k]");
        }

        if ($response->isSuccess()) {
            $postData['tanggal_pembelian'] = $request->get('tanggal_pembelian_submit');
            $arrBarang = [];
            $arrBarangID = $request->get('val_id');
            $arrBarangJumlah = $request->get('val_jumlah');
            $arrBarangHarga = $request->get('val_harga');
            $newPembelianAtk = PembelianAtk::create($postData);

            foreach ($arrBarangID as $k => $bar) {
                $arrBarang[$k]['mst_atk_id'] = $arrBarangID[$k];
                $arrBarang[$k]['jumlah'] = $arrBarangJumlah[$k];
                $arrBarang[$k]['harga'] = $arrBarangHarga[$k];
                $arrBarang[$k]['pembelian_atk_id'] = $newPembelianAtk->id;
                $arrBarang[$k]['created_at'] = date('Y-m-d H:i:s');
                $arrBarang[$k]['updated_at'] = date('Y-m-d H:i:s');

            }

            $massNewDetPembelianAtk = DetPembelianAtk::insert($arrBarang);
            foreach ($arrBarang as $k => $v) {
                $stock = MstAtk::where('id', $v['mst_atk_id'])->get()->first();
                $stock->stock += $v['jumlah'];
                $stock->save();
            }
            $response->setMessage('Berhasil');
        }
        return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->wantsJson()) {
            $pembelian = PembelianAtk::with('det_pembelian_atk.mst_atk')->findOrFail($id);
            $pembelian->det_pembelian_atk->each(function($item) {
                $item->harga_rupiah = rupiah($item->harga);
            });
            $pembelian->total_harga = rupiah($pembelian->det_pembelian_atk->sum('harga'));
            $pembelian->tanggal_manusia = indonesian_date($pembelian->tanggal_pembelian);
            return $pembelian; 
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barangs = MstAtk::latest()->get();
        $pembelian_atk = PembelianAtk::findOrFail($id);
        $requested = [];
        foreach ($pembelian_atk->det_pembelian_atk as $key => $detPembelian) {
            $requested[$key]['det_pembelian_id'] = $detPembelian->id;
            $requested[$key]['id'] = $detPembelian->mst_atk_id;
            $requested[$key]['nama'] = $detPembelian->mst_atk->nama;
            $requested[$key]['satuan'] = $detPembelian->mst_atk->satuan;
            $requested[$key]['jumlah'] = $detPembelian->jumlah;
            $requested[$key]['harga'] = (int) $detPembelian->harga;
        }
        return view('pembelian_atk.edit', compact('requested', 'pembelian_atk', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {



        $pembelian = PembelianAtk::findOrFail($id);

        $hasPem = PembelianAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $hasDist = DistribusiAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $hasTahap = TahapDistribusiAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $isFailed =  $hasPem ||  $hasDist || $hasTahap;

        if ($isFailed)
            return response()->json(['success' => false,  'msg' => 'Gagal Menghapus']);


        $response = new \App\Libraries\Response();

        if ($request->get('tanggal_pembelian') == NULL)
            $response->addError('Tanggal Pembelian Tidak Boleh Kosong', 'tanggal_pembelian');
        if ($request->get('val_id') == NULL) {
            $response->addError('Tidak Ada ATK yang di pilih', 'val_id');
        }

        foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
            if (!preg_match('/^\d+$/', $jumlah))
                $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
        }
        foreach (($request->get('val_harga') ?? []) as $k => $harga) {
            if (!preg_match('/^\d+$/', $harga))
                $response->addError('Value Hanya Berisi Angka !', "val_harga[$k]");
        }


        if ($response->isSuccess()) {
         $MasterDetIDs = $pembelian->det_pembelian_atk->pluck('id');
         $postDetID = $request->get('val_det_id');
         $postJumlah = $request->get('val_jumlah');
         $postHarga = $request->get('val_harga');
         $postBarangID = $request->get('val_id');
        /**
         * aksi hapus det_permintaan_atk jika barang di hapus
         */
        foreach ($MasterDetIDs as $key => $value) {
            $index = array_search($value, $postDetID);
            if ($index === FALSE) {

                $det = DetPembelianAtk::findOrFail($value);
                $det->mst_atk->stock -= $det->jumlah;
                $det->mst_atk->save();
                $det->delete();
            } 
        }

        foreach ($postDetID as $key => $value) {

            if ($value == 'undefined') {

                $newDetPembelian = new DetPembelianAtk();
                $newDetPembelian->pembelian_atk_id = $id;
                $newDetPembelian->mst_atk_id = $postBarangID[$key];
                $newDetPembelian->jumlah = $postJumlah[$key];
                $newDetPembelian->harga = $postHarga[$key];
                $newDetPembelian->mst_atk->stock += $newDetPembelian->jumlah;
                $newDetPembelian->mst_atk->save();
                $newDetPembelian->save();
            } else 
            {
                $detPembelian = DetPembelianAtk::where('id', $value)->get()->first();

                if ($detPembelian) {
                    $jumlah = $postJumlah[$key] - $detPembelian->jumlah;

                    $detPembelian->mst_atk->stock += $jumlah;
                    $detPembelian->mst_atk->save(); 
                    $detPembelian->jumlah = $postJumlah[$key];
                    $detPembelian->harga = $postHarga[$key];
                    $detPembelian->save();
                } 
            }
        }
    }
    return response()->json($response);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   

        $pembelian = PembelianAtk::findOrFail($id);

        $hasPem = PembelianAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $hasDist = DistribusiAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $hasTahap = TahapDistribusiAtk::where('created_at', '>', $pembelian->created_at)->get()->toArray();
        $isFailed =  $hasPem ||  $hasDist || $hasTahap;
        
        if ($isFailed)
            return response()->json(['success' => false]);


        $pembelian->det_pembelian_atk->each(function($item) {
            $barang = MstAtk::findOrFail($item->mst_atk_id);
            $barang->stock = $barang->stock - $item->jumlah;
            $barang->save();
            $item->delete();
        });
        $pembelian->delete();
        return response()->json(['success' => true]);


    }
}
