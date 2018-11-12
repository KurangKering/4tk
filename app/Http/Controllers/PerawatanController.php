<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perawatan;
use App\DetPerawatan;
use App\MstBarang;
use PDF;
use DB;



class PerawatanController extends Controller
{


    /**
     * url('perawatan/pengajuan')
     * role : staff
     */
    public function pengajuan()
    {
        $barangs = MstBarang::latest()->get();
        return view('perawatan.pengajuan', compact('barangs'));
    }


    /**
     * url('perawatan/store_pengajuan')
     * role : staff
     */
    public function storePengajuan(Request $request)
    {
      $response = new \App\Libraries\Response();
      if ($request->get('val_id') == NULL) {
          $response->addError('Tidak Ada Barang yang di pilih', 'val_id');
      }
      foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
        if ($jumlah == 0)
            $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
    }
    if ($response->isSuccess()) {
        $postData['subbidang_id'] =  \Auth::user()->subbidang_id;
        $postData['perawatan_user_id'] = \Auth::user()->id;
        $postData['status'] = '0';
        $postData['tanggal_pengajuan'] = now();
        $arrBarang = [];
        $arrBarangID = $request->get('val_id');
        $arrBarangJumlah = $request->get('val_jumlah');
        $newPengajuan = Perawatan::create($postData);
        foreach ($arrBarangID as $k => $bar) 
        {
          $arrBarang[$k]['mst_barang_id'] = $arrBarangID[$k];
          $arrBarang[$k]['jumlah'] = $arrBarangJumlah[$k];
          $arrBarang[$k]['perawatan_id'] = $newPengajuan->id;
          $arrBarang[$k]['created_at'] = date('Y-m-d H:i:s');
          $arrBarang[$k]['updated_at'] = date('Y-m-d H:i:s'); }
          $massNewDetPerawatan = DetPerawatan::insert($arrBarang); }
          return response()->json($response);
      }

    /**
     * url('perawatan/{id}/edit_pengajuan')
    * role : staff
    */
    public function editPengajuan($id)
    {
        $perawatan = Perawatan::with('user')->findOrFail($id);
        if (in_array($perawatan->status, ["-1", "1", '2']))
            abort(404);
        $requestedBarang = [];

        foreach ($perawatan->det_perawatan as $key => $detPerawatan) {
            $requestedBarang[$key]['det_perawatan_id'] = $detPerawatan->id;
            $requestedBarang[$key]['id'] = $detPerawatan->mst_barang_id;
            $requestedBarang[$key]['nama'] = $detPerawatan->mst_barang->nama;
            $requestedBarang[$key]['satuan'] = $detPerawatan->mst_barang->satuan;
            $requestedBarang[$key]['jumlah'] = $detPerawatan->jumlah; }

            $barangs = MstBarang::get();
            return view('perawatan.edit_pengajuan', compact('perawatan', 'barangs', 'requestedBarang'));
        }

    /**
     * url('perawatan/{id}/update_pengajuan')
     * role : staff
     */
    public function updatePengajuan(Request $request, $id)
    {
        $perawatan = Perawatan::findOrFail($id);
        $response = new \App\Libraries\Response();

        if (in_array($perawatan->status, ["-1", "1", "2"]))
            $response->addError('Tidak Dapat Mengubah Data Pengajuan', 'data');

        if ($request->get('val_id') == NULL) {
            $response->addError('Tidak Ada Barang yang di pilih', 'val_id');
        }
        foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
            if ($jumlah == 0)
              $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
      }
      if ($response->isSuccess()) {
        $MasterDetIDs = $perawatan->det_perawatan->pluck('id');
        $postDetID = $request->get('val_det_id');
        $postJumlah = $request->get('val_jumlah');
        $postBarangID = $request->get('val_id');
        
        foreach ($MasterDetIDs as $key => $value) {
          $index = array_search($value, $postDetID);
          if ($index === FALSE) {
            DetPerawatan::findOrFail($value)->delete();
        } 
    }
    foreach ($postDetID as $key => $value) {
      if ($value == 'undefined') {
        $newDetPerawatan = new DetPerawatan();
        $newDetPerawatan->perawatan_id = $id;
        $newDetPerawatan->mst_barang_id = $postBarangID[$key];
        $newDetPerawatan->jumlah = $postJumlah[$key];
        $newDetPerawatan->save();
    } else 
    {
        $detPerawatan = DetPerawatan::where('id', $value)->get()->first();
        if ($detPerawatan) {
          $detPerawatan->mst_barang_id = $postBarangID[$key];
          $detPerawatan->jumlah = $postJumlah[$key];
          $detPerawatan->save();
      }  } }}
      return response()->json($response);
  }

    /**
    * url('perawatan/delete_pengajuan')
     * role : staff
     */
    public function destroyPengajuan()
    { 
      if (request()->wantsJson()) {
          $id = request()->get('id');
          $perawatan = Perawatan::findOrFail($id);
          if (in_array($perawatan->status, ["-1", "1", "2"]))
              return response()->json(['success' => false]);
          $perawatan->det_perawatan->each(function($det) {
              $det->delete();
          });
          $perawatan->delete();
          return response()->json(['success' => true]);
      } else
      {
          return abort('404');
      }
  }
    /**
     * url('perawatan/{id}/show_pengajuan')
     * role : staff | humas
     */
    public function showPengajuan(Request $request, $id)
    {


      $perawatan = Perawatan::with('det_perawatan.mst_barang', 'subbidang', 'user')->findOrFail($id);

      $perawatan->tanggal_manusia = indonesian_date($perawatan->tanggal_pengajuan);
      $status_perawatan = \Config::get('enums.status_perawatan');      
      $perawatan->status = $status_perawatan[$perawatan->status];
      return $perawatan; 
  }

    /**
     * url('perawatan/index_anggota')
     *  semua data yang ada pada subbidang user
     */
    public function indexAnggota()
    {


        return view('perawatan.index_anggota');
    }

    /**
     * url('perawatan/index_anggota')
     * semua data 
     * order by belum di paraf dan tanggal_pengajuan
     */
    public function indexKepala()
    {
        return view('perawatan.index_kepala');

    }

    /**
     * url('perawatan/paraf_pengajuan')
     * role : kepala
     * terima atau tolak
     */
    public function parafPengajuan(Request $request)
    {
        $id = $request->get('perawatan_id');
        $status = $request->get('status');
        $user_id = \Auth::user()->id;
        $updatePengajuan = Perawatan::where([
         'id' => $id
     ])->update([
         'status' => $status,
     ]);
     return response()->json(['success' => true, 'msg' => 'Berhasil']);
 }

    /**
     * url('perawatan/{id}/show_perawatan')
     * role : kepala | humas | staff
     */
    public function showPerawatan(Request $request, $id)
    {
        $perawatan = Perawatan::where('status', '2')->with('det_perawatan.mst_barang', 'subbidang', 'user')->findOrFail($id);

        $perawatan->tanggal_manusia_pengajuan = indonesian_date($perawatan->tanggal_pengajuan);
        $perawatan->tanggal_manusia_perawatan = indonesian_date($perawatan->tanggal_perawatan);
        $status_perawatan = \Config::get('enums.status_perawatan');      
        $perawatan->status = $status_perawatan[$perawatan->status];

        $perawatan->det_perawatan->each(function($ii) {
            $ii->biaya_manusia = rupiah($ii->biaya);
        });
        $perawatan->total = rupiah($perawatan->det_perawatan->sum('biaya'));
        return $perawatan; 

    }
    /**
     * url('perawatan/index_humas_belum')
     * role: humas
     * semua data perawatan status 1,2, 
     * order by status 1 dan tanggal_pengajuan
     */
    public function indexHumasBelum()
    {
        return view('perawatan.index_humas_belum');
    }   


    public function indexHumasSelesai()
    {
        return view('perawatan.index_humas_selesai');

    }
    /**
     * url('perawatan/input_perawatan')
     * role : humas
     * yang diinputkan hanya biaya setiap barang.
     * bisa edit hapus
     */
    public function inputPerawatan($id)
    {   

        $dataPerawatan = Perawatan::findOrFail($id);
        if ($dataPerawatan->status != '1') {
            return redirect(url('perawatan/index_humas_belum')); 
        }
        $detPerawatan = $dataPerawatan->det_perawatan;
        $MstBarangIDs = $detPerawatan->pluck('mst_barang_id')->all();
        $stockBarang = MstBarang::whereIn('id', $MstBarangIDs)->get();

        return view('perawatan.input_perawatan', compact('dataPerawatan', 'stockBarang', 'detPerawatan'));
    }

    /**
     * url('perawatan/store_perawatan')
     * role : humas
     */
    public function storePerawatan(Request $request)
    {
        $response = new \App\Libraries\Response();
        $id = $request->get('perawatan_id');
        foreach ($request->get('det_perawatan_id') as $key => $det) {
            if ($request->get('biaya')[$key] == 0) {
                $response->addError('Periksa Kembali Data Input');
            }
        }

        $dataPerawatan = Perawatan::findOrFail($request->get('perawatan_id'));
        if ($response->isSuccess())
        {
            $biaya = $request->get('biaya');
            $detPerawatanId = $request->get('det_perawatan_id');
            foreach ($detPerawatanId as $key => $det) {
                $detPerawatan = DetPerawatan::findOrFail($det);
                $detPerawatan->biaya = $biaya[$key];
                $detPerawatan->save();
            }
            $dataPerawatan->status = '2';
            $dataPerawatan->tanggal_perawatan = now();
            $dataPerawatan->save();
        }
        return response()->json($response);
    }

    /**
     * url('perawatan/{id}/edit_perawatan')
     * role : humas
     */
    public function editPerawatan(Request $request, $id)
    {
        $dataPerawatan = Perawatan::findOrFail($id);
        $detPerawatan = $dataPerawatan->det_perawatan;
        return view('perawatan.edit_perawatan', compact('dataPerawatan', 'detPerawatan'));
    }

    /**
     * url('perawatan/{id}/update_perawatan')
     */
    public function updatePerawatan(Request $request, $id)
    {
        $response = new \App\Libraries\Response();
        $id = $request->get('perawatan_id');
        foreach ($request->get('det_perawatan_id') as $key => $det) {
            if ($request->get('biaya')[$key] == 0) {
                $response->addError('Periksa Kembali Data Input');
            }
        }


        $dataPerawatan = Perawatan::findOrFail($id);
        if ($response->isSuccess())
        {
            $biaya = $request->get('biaya');
            $detPerawatanId = $request->get('det_perawatan_id');
            foreach ($detPerawatanId as $key => $det) {
                $detPerawatan = DetPerawatan::findOrFail($det);
                $detPerawatan->biaya = $biaya[$key];
                $detPerawatan->save();
            }
            $dataPerawatan->status = '2';
            $dataPerawatan->save();
        }
        return response()->json($response);
    }

    /**
     * url('perawatan/delete_perawatan')
     */
    public function destroyPerawatan(Request $request)
    {
        $id = $request->get('id');
        $perawatan = Perawatan::findOrFail($id);
        $perawatan->status = '1';
        $perawatan->det_perawatan->each(function($ii) {
            $ii->biaya = '0';
            $ii->save();
        });
        $perawatan->save();

        return response()->json(['success' => true]);
    }

    /**
     * url('perawatan/{id}/cetak_perawatan')
     * role : humas
     */
    public function cetakPerawatan(Request $request, $id)
    {   
        $perawatan = Perawatan::findOrFail($id);
        $perawatan->det_perawatan->each(function($ii) {
            $ii->biaya_manusia = rupiah($ii->biaya);
        });

        $perawatan->total = rupiah($perawatan->det_perawatan->sum('biaya'));
        $det_perawatan = $perawatan->det_perawatan;
        $pdf = PDF::loadView('perawatan.cetak_perawatan', compact('perawatan', 'det_perawatan'));
        return $pdf->stream();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('perawatan.index');
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
