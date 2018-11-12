<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MstBarang;
class MstBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst_barang.index');
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
        if ($request->wantsJson()) {
            $this->validate($request, [
                'nama' => 'required',
                'satuan' => 'required',
            ]);

            $barang = new MstBarang();
            $barang->nama = $request->get('nama');
            $barang->satuan = $request->get('satuan');
            $barang->save();

            return response()->json(['success' => true, 'msg' => 'Berhasil Menambah Data Barang']);
        }
        abort(404);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MstBarang::findOrFail($id);
        
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
        if ($request->wantsJson()) {
            $this->validate($request, [
                'nama' => 'required',
                'satuan' => 'required',
            ]);
            
            $barang = MstBarang::findOrFail($id);
            $barang->nama = $request->nama;
            $barang->satuan = $request->satuan;
            $barang->save();
            return response()->json(['success' => true]);
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang = MstBarang::findOrFail($id);
        $barang->delete();
        return response()->json(['success' => true]);
    }

    public function submitBarang(Request $request)
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
