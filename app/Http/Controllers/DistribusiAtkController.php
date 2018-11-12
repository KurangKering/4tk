<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TahapDistribusiAtk;
use App\DistribusiAtk;
use App\PembelianAtk;
use App\PermintaanAtk;
use App\DetDistribusiAtk;
use App\MstAtk;
use PDF;
use DB;
class DistribusiAtkController extends Controller
{


    /**
     * url('distribusi_atk/riwayat/{tahap_id}/cetak')
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function cetakTahapDistribusi($id)
    {
        $tahapDistribusi = TahapDistribusiAtk::with('distribusi_atk','det_distribusi_atk.mst_atk')->findOrFail($id);
       

        $pdf = PDF::loadView('distribusi_atk.cetak_tahap_distribusi_atk', compact('tahapDistribusi'));
        return $pdf->stream();
    }
    /**
     * url('distribusi_atk/delete_tahap')
     */
    public function deleteTahapDistribusi(Request $request)
    {


        if ($request->wantsJson()) {
            $id = $request->get('id');
            $tahap = TahapDistribusiAtk::with('det_distribusi_atk.mst_atk')->findOrFail($id);

            $hasPem = PembelianAtk::where('created_at', '>', $tahap->created_at)->get()->toArray();
            $hasDist = DistribusiAtk::where('created_at', '>', $tahap->created_at)->get()->toArray();
            $hasTahap = TahapDistribusiAtk::where('created_at', '>', $tahap->created_at)->get()->toArray();
            $isFailed =  $hasPem ||  $hasDist || $hasTahap;

            if ($isFailed)
                return response()->json(['success' => false, 'msg' => 'Gagal Menghapus']);


            $tahap->det_distribusi_atk->each(function($ii) {
                $ii->mst_atk->stock += $ii->jumlah ;
                $ii->mst_atk->save();
                $ii->delete();
            });
            $tahap->delete();
            $dataDistribusi = DistribusiAtk::findOrFail($tahap->distribusi_atk_id);
            $detPermintaanAtk = $dataDistribusi->permintaan_atk->det_permintaan_atk;
            $telahDistribusi = [];
            foreach ($dataDistribusi->tahap_distribusi_atk as $k => $v) {
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
            $totalBarang = sizeof($detPermintaanAtk);
            $jumlahSama = 0;
            foreach ($detPermintaanAtk as $key => $value) {
                $indexTelah = array_search($value->mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));

                if ($indexTelah !== FALSE) {
                    if ($value->jumlah == $telahDistribusi[$indexTelah]['jumlah']) {
                        $jumlahSama++;
                    }
                }
            }
            if ($totalBarang == $jumlahSama) {
                $dataDistribusi->status = 'complete';
            } else if (!empty($telahDistribusi)  && $jumlahSama < $totalBarang)
            {
                $dataDistribusi->status = 'incomplete';
            } else if(empty($telahDistribusi))
            {
                $dataDistribusi->status = 'never';
            }
            $dataDistribusi->save();
            return response()->json(['success' => true, 'msg' => 'Berhasil Menghapus Data']);
        }
        abort(404);

    }
    /**
     * url('distribusi_atk/edit_distribusi/{tahap_id}')
     */
    public function editTahapDistribusi($id)
    {
        $tahapDistribusi = TahapDistribusiAtk::with('distribusi_atk.permintaan_atk.subbidang', 'det_distribusi_atk.mst_atk')->findOrFail($id);
        $detPermintaanAtk = $tahapDistribusi->distribusi_atk->permintaan_atk->det_permintaan_atk;

        $MstAtkIDs = array_map(function($tmp) {return $tmp['mst_atk_id'];}, $detPermintaanAtk->toArray());
        $stockBarang = MstAtk::whereIn('id', $MstAtkIDs)->get();
        $telahDistribusi = [];
        foreach ($tahap_distribusi_atk as $k => $v) {
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
        return view('distribusi_atk.distribusi', compact('dataDistribusi', 'stockBarang', 'detPermintaanAtk', 'telahDistribusi'));
    }
    /**
     * url('distribusi_atk/detail_riwayat/{tahap_id}')
     */
    public function detailRiwayat($id)
    {
        $tahapDistribusi = TahapDistribusiAtk::with('distribusi_atk.permintaan_atk.subbidang', 'det_distribusi_atk.mst_atk')->orderBy('tanggal_distribusi', 'DESC')->findOrFail($id);
        $tahapDistribusi->tanggal_manusia = indonesian_date($tahapDistribusi->tanggal_distribusi, 'l, j F Y, H:i','WIB');
        return $tahapDistribusi;
    }

    /**
     * url('distribusi_atk/selesai')
     */
    public function selesaiDistribusi()
    {
        return view('distribusi_atk.selesai');  
    }
    /**
     * url('distribusi_atk/riwayat')
     */
    public function tahapDistribusi()
    {
        return view('distribusi_atk.riwayat');  
        
    }
    /**
     * url('distribusi_atk/{distribusi_id}/cetak)')
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function cetakDistribusiAtk($id)
    {
        $distribusi = DistribusiAtk::findOrFail($id);
        $tDisID = $distribusi->tahap_distribusi_atk->pluck('id')->all();
        $detDis = DetDistribusiAtk::with('mst_atk')->select('mst_atk_id', 'jumlah')->whereIn('tahap_distribusi_atk_id', $tDisID)->get();
        $arrAtk = [];
        foreach ($detDis as $k => $det) {
            $mst_atk_id = $det->mst_atk_id;
            $jumlah = $det->jumlah;
            $nama = $det->mst_atk->nama;
            $satuan = $det->mst_atk->satuan;
            $index = array_search($mst_atk_id, array_column($arrAtk, 'mst_atk_id'));
            if ($index === FALSE) {
                $arrAtk[] =
                [
                    'mst_atk_id' => $mst_atk_id,
                    'jumlah' => $jumlah,
                    'nama' => $nama,
                    'satuan' => $satuan,
                ];
            } 
            else  
                $arrAtk[$index]['jumlah'] += $jumlah;
        }

        $pdf = PDF::loadView('distribusi_atk.cetak_distribusi_atk', compact('distribusi', 'arrAtk'));
        return $pdf->stream();
    }
    /**
     * url('distribusi_atk/{distribusi_atk_id}/distribusi')
     */
    public function doDistribusiAtk($id)
    {
        $dataDistribusi = DistribusiAtk::findOrFail($id);
        if ($dataDistribusi->status == 'complete') {
            return redirect(route('distribusi_atk.index')); 
        }
        $detPermintaanAtk = $dataDistribusi->permintaan_atk->det_permintaan_atk;
        $MstAtkIDs = array_map(function($tmp) {return $tmp['mst_atk_id'];}, $detPermintaanAtk->toArray());
        $stockBarang = MstAtk::whereIn('id', $MstAtkIDs)->get();
        $telahDistribusi = [];
        foreach ($dataDistribusi->tahap_distribusi_atk as $k => $v) {
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
        return view('distribusi_atk.distribusi', compact('dataDistribusi', 'stockBarang', 'detPermintaanAtk', 'telahDistribusi'));
    }

    /**
     * url('distribusi_atk/post/distribusi')
     * 
     */
    public function postDistribusiAtk(Request $request)
    {

        $response = new \App\Libraries\Response();

        $dataDistribusi = DistribusiAtk::findOrFail($request->get('distribusi_atk_id'));
        $detPermintaanAtk = $dataDistribusi->permintaan_atk->det_permintaan_atk;

        $barangDistribusi = [];
        $createDetDistribusi = null;

        foreach ($request->get('mst_atk_id') as $k => $v) {
            if ($request->get('jumlah')[$k] == 0)
                continue;
            $barangDistribusi[] = 
            [ 
                'mst_atk_id' => $v,
                'jumlah' => $request->get('jumlah')[$k],
                'max_input' => $request->get('max_input')[$k],
            ];
        }


        if (empty($barangDistribusi)) {
            $response->success = false;
            $response->setMessage('Periksa Kembali Inputan Anda', '');
        }

        foreach ($barangDistribusi as $k => $v) {
            if (!preg_match('/^\d+$/', $v['jumlah']))
                $response->addError('Inputan Hanya Berisi Angka !', "mst_atk_id[$k]");

            if ($v['jumlah'] > $v['max_input']) 
                $response->addError('Inputan Lebih Besar dari Maximal Input', "mst_atk_id[$k]");
        }
        
        if ($response->isSuccess()) {



            $newTahapDistribusi = new TahapDistribusiAtk();
            $newTahapDistribusi->tanggal_distribusi = date('Y-m-d H:i:s');
            $newTahapDistribusi->distribusi_atk_id = $dataDistribusi->id;
            $newTahapDistribusi->save();

            foreach ($barangDistribusi as $k => $v) {
                $v['tahap_distribusi_atk_id'] = $newTahapDistribusi->id;
                $createDetDistribusi[] = DetDistribusiAtk::create($v);   
            }


            $stockBarang = MstAtk::whereIn('id', $request->get('mst_atk_id'))->get();

            $index = [];
            foreach ($barangDistribusi as $k => $v) {
                $stock = $stockBarang->where('id', $v['mst_atk_id'])->first(); 
                $stock->stock -= $v['jumlah'];
                $stock->save();


            }


            $telahDistribusi = [];
            foreach ($dataDistribusi->tahap_distribusi_atk as $k => $v) {
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

            $totalBarang = sizeof($detPermintaanAtk);
            $jumlahSama = 0;
            foreach ($detPermintaanAtk as $key => $value) {
                $indexTelah = array_search($value->mst_atk_id, array_column($telahDistribusi, 'mst_atk_id'));

                if ($indexTelah !== FALSE) {
                    if ($value->jumlah == $telahDistribusi[$indexTelah]['jumlah']) {
                        $jumlahSama++;
                    }
                }
            }

            if ($totalBarang == $jumlahSama) {
                $dataDistribusi->status = 'complete';
            } else if (!empty($telahDistribusi)  && $jumlahSama < $totalBarang)
            {
                $dataDistribusi->status = 'incomplete';
            } else if(empty($telahDistribusi))
            {
                $dataDistribusi->status = 'never';
            }
            $dataDistribusi->save();


        }

        
        return response()->json($response);
    }


    /**
     * url('distribusi_atk/{permintaan_id}/detail')
     * @return [type] [description]
     */
    public function detailDistribusi(Request $request, $id)
    {
        // if ($request->wantsJson()) {
        $permintaan = PermintaanAtk::with('det_permintaan_atk.mst_atk', 'subbidang', 'user', 'distribusi_atk.tahap_distribusi_atk.det_distribusi_atk')->findOrFail($id);
        $permintaan->tanggal_manusia = indonesian_date($permintaan->tanggal_permintaan);
        $status = '';

        if ($permintaan->is_paraf == 'Y') {
            $distribusi = DistribusiAtk::where('permintaan_atk_id', $permintaan->id)->get()->first();
            if ($distribusi && $distribusi->status == 'complete')
                $status = 'Success';
            else if ($distribusi && $distribusi->status == 'incomplete')
                $status = 'OnGoing';
            else if ($distribusi && $distribusi->status == 'never')
                $status = 'Waiting For Distribution';
        } else if ($permintaan->is_paraf == 'N')
        {
            $status = 'Rejected';
        } else 
        {
            $status = 'Waiting';
        }
        $permintaan->status = $status;

        $dDistribusi = $permintaan->distribusi_atk;
        $telahDistribusi = [];
        if ($dDistribusi) {
            foreach ($dDistribusi->tahap_distribusi_atk as $k => $v) {
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
        }   
        return response()->json(['permintaan' => $permintaan, 'telah_distribusi' => $telahDistribusi]);
        // }
        // abort(404);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('distribusi_atk.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
