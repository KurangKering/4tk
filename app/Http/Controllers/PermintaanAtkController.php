<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\MstAtk;
use App\PermintaanAtk;
use App\DetPermintaanAtk;
use App\DistribusiAtk;
use PDF;    
class PermintaanAtkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    public function indexAnggota()
    {
        return view('permintaan_atk.index_anggota');
    }
    public function indexKepala()
    {
        return view('permintaan_atk.index_kepala');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barangs = MstAtk::latest()->get();
        return view('permintaan_atk.create', compact('barangs'));
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
        if ($request->get('val_id') == NULL) {
            $response->addError('Tidak Ada ATK yang di pilih', 'val_id');
        }
        foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
            if (!preg_match('/^\d+$/', $jumlah))
                $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
        }
        if ($response->isSuccess())
        {
            $postData['subbidang_id'] =  \Auth::user()->subbidang_id;
            $postData['permintaan_user_id'] = \Auth::user()->id;
            $postData['tanggal_permintaan'] = now();
            $arrBarang = [];
            $arrBarangID = $request->get('val_id');
            $arrBarangJumlah = $request->get('val_jumlah');
            $newPermintaanAtk = PermintaanAtk::create($postData);
            foreach ($arrBarangID as $k => $bar) 
            {
              $arrBarang[$k]['mst_atk_id'] = $arrBarangID[$k];
              $arrBarang[$k]['jumlah'] = $arrBarangJumlah[$k];
              $arrBarang[$k]['permintaan_atk_id'] = $newPermintaanAtk->id;
              $arrBarang[$k]['created_at'] = date('Y-m-d H:i:s');
              $arrBarang[$k]['updated_at'] = date('Y-m-d H:i:s');
          }
          $massNewDetPermintaanAtk = DetPermintaanAtk::insert($arrBarang);
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


        if ($request->wantsJson())
        {
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
        return $permintaan; 
    }
    return abort('404');
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permintaan = PermintaanAtk::with('user')->findOrFail($id);
        if (in_array($permintaan->is_paraf, ["Y", "N"]))
            abort(404);
        $requestedBarang = [];
        foreach ($permintaan->det_permintaan_atk as $key => $detPermintaan) {
            $requestedBarang[$key]['det_permintan_id'] = $detPermintaan->id;
            $requestedBarang[$key]['id'] = $detPermintaan->mst_atk_id;
            $requestedBarang[$key]['nama'] = $detPermintaan->mst_atk->nama;
            $requestedBarang[$key]['satuan'] = $detPermintaan->mst_atk->satuan;
            $requestedBarang[$key]['jumlah'] = $detPermintaan->jumlah;
        }
        $barangs = MstAtk::get();
        return view('permintaan_atk.edit', compact('permintaan', 'barangs', 'requestedBarang'));
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

        $permintaan = PermintaanAtk::findOrFail($id);
        $response = new \App\Libraries\Response();

        if (in_array($permintaan->is_paraf, ["Y", "N"]))
            $response->addError('Tidak Dapat Mengubah Data Permintaan', 'data');

        if ($request->get('val_id') == NULL) {
            $response->addError('Tidak Ada ATK yang di pilih', 'val_id');
        }
        foreach (($request->get('val_jumlah') ?? []) as $k => $jumlah) {
            if (!preg_match('/^\d+$/', $jumlah))
                $response->addError('Value Hanya Berisi Angka !', "val_jumlah[$k]");
        }
        if ($response->isSuccess()) {
           $MasterDetIDs = $permintaan->det_permintaan_atk->pluck('id');
           $postDetID = $request->get('val_det_id');
           $postJumlah = $request->get('val_jumlah');
           $postBarangID = $request->get('val_id');
        /**
         * aksi hapus det_permintaan_atk jika barang di hapus
         */
        foreach ($MasterDetIDs as $key => $value) {
            $index = array_search($value, $postDetID);
            if ($index === FALSE) {
                DetPermintaanAtk::findOrFail($value)->delete();
            } 
        }
        foreach ($postDetID as $key => $value) {
            if ($value == 'undefined') {
                $newDetPermintaan = new DetPermintaanAtk();
                $newDetPermintaan->permintaan_atk_id = $id;
                $newDetPermintaan->mst_atk_id = $postBarangID[$key];
                $newDetPermintaan->jumlah = $postJumlah[$key];
                $newDetPermintaan->save();
            } else 
            {
                $detPermintaan = DetPermintaanAtk::where('id', $value)->get()->first();
                if ($detPermintaan) {
                    $detPermintaan->mst_atk_id = $postBarangID[$key];
                    $detPermintaan->jumlah = $postJumlah[$key];
                    $detPermintaan->save();
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
      if (request()->wantsJson()) {
        $id = request()->get('id');
        $permintaan = PermintaanAtk::findOrFail($id);
        if (in_array($permintaan->is_paraf, ["Y", "N"]))
            return response()->json(['success' => false]);
        $permintaan->det_permintaan_atk->each(function($det) {
            $det->delete();
        });
        $permintaan->delete();
        return response()->json(['success' => true]);
    } else
    return abort('404');
}
    /**
     * Melakukan paraf pada form permintaan atk
     * value nya Y atau N.
     * Y = di paraf, kemudian masuk ke pembelian bulan terkaitr
     * N = ditolak.
     * 
     * url('paraf/permintaan_atk')
     *  
     */
    public function parafPermintaanAtk(Request $request)
    {
        $id = $request->get('permintaan_id');
        $status = $request->get('status');
        $user_id = '1';
        $updatePermintaanAtk = PermintaanAtk::where([
         'id' => $id
     ])->update([
         'is_paraf' => $status,
         'paraf_user_id' => $user_id,
     ]);
     if ($updatePermintaanAtk && $status == 'Y')
     {
        $newDistribusi = new DistribusiAtk();
        $newDistribusi->permintaan_atk_id = $id;
        $newDistribusi->status = 'never';
        $newDistribusi->save();
    }
    return response()->json(['success' => true, 'msg' => 'Berhasil']);
}
    /**
     * Cetak form pemintaan atk
     * url('cetak/permintaan_atk/{permintaan_id}')
     * 
     */
    public function cetakPermintaanAtk(Request $request, $id)
    {
        $permintaan = PermintaanAtk::findOrFail($id);
        $genPDF = PDF::loadView('permintaan_atk.cetak',compact('permintaan'));
        return $genPDF->stream();
    }
}
