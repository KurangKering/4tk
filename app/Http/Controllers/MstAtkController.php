<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MstAtk; 
use App\DetPermintaanAtk;
use App\DetPembelianAtk;
use App\DetDistribusiAtk;

class MstAtkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst_atk.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $barang = new MstAtk();
        $barang->nama = $request->get('nama');
        $barang->satuan = $request->get('satuan');
        $barang->stock = 0;
        $barang->save();

        return 'Berhasil Menambah Data Barang';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MstAtk::findOrFail($id);

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
        $barang = MstAtk::findOrFail($id);
        $barang->nama = $request->nama;
        $barang->satuan = $request->satuan;
        $barang->save();
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang = MstAtk::findOrFail($id);
        $barang->delete();
        return 'Berhasil Menghapus Data Barang';
    }


    public function submitAtk(Request $request)
    {   
        $id = $request->get('id');
        $type = $request->get('type');
        $response = '';
        if ($type == 'new') {
            $response = $this->store($request);
        } else
        if ($type == 'edit') {
            $response = $this->update($request, $id);
        } else
        if ($type =='delete') {

            $response = $this->destroy($id);
        }

        return response()->json(['success' => true, 'msg' => $response]);
    }
}
