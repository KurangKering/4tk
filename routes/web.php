<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/dummy', 'DummyController@index')->name('dummy.index');
// Route::get('/dummy/create', 'DummyController@create')->name('dummy.create');
// Route::post('/dummy', 'DummyController@store')->name('dummy.store');
// Route::get('/dummy/{id}', 'DummyController@show')->name('dummy.show');
// Route::get('/dummy/{id}/edit', 'DummyController@edit')->name('dummy.edit');
// Route::patch('/dummy/{id}', 'DummyController@update')->name('dummy.update');
// Route::put('/dummy/{id}', 'DummyController@update')->name('dummy.update');
// Route::delete('/dummy/{id}', 'DummyController@destroy')->name('dummy.destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout', function() {
	return redirect('');
});
Route::group(['middleware' => ['auth']], function () {

	Route::get('datatables/mst_atk', 'DataTablesController@getMstAtk');
	Route::get('datatables/pengajuan_anggota', 'DataTablesController@getPengajuanAnggota');
	Route::get('datatables/pengajuan_kepala', 'DataTablesController@getPengajuanKepala');
	Route::get('datatables/permintaan_atk_anggota', 'DataTablesController@getPermintaanAtkAnggota');
	Route::get('datatables/permintaan_atk_kepala', 'DataTablesController@getPermintaanAtkKepala');
	Route::get('datatables/daftar_distribusi_atk_belum', 'DataTablesController@getDaftarDistribusiAtkIncomplete');
	Route::get('datatables/daftar_perawatan_belum', 'DataTablesController@getDaftarPerawatanIncomplete');
	Route::get('datatables/daftar_distribusi_atk_complete', 'DataTablesController@getDaftarDistribusiAtkComplete');
	Route::get('datatables/daftar_perawatan_complete', 'DataTablesController@getDaftarPerawatanComplete');
	Route::get('datatables/daftar_tahap_distribusi', 'DataTablesController@getTahapDistribusi');
	Route::get('datatables/daftar_tahap_perawatan', 'DataTablesController@getTahapPerawatan');
	Route::get('datatables/mst_barang', 'DataTablesController@getMstBarang');

	Route::get('/', 'DashboardController@index');


	
Route::group(['middleware' => ['role:kepala']], function () {
	Route::get('/permintaan_atk', 'PermintaanAtkController@index')->name('permintaan_atk.index');

	Route::post('paraf/permintaan_atk', 'PermintaanAtkController@parafPermintaanAtk');
	Route::get('permintaan_atk/index_kepala', 'PermintaanAtkController@indexKepala')->name('permintaan_atk.index_kepala');	

	Route::get('perawatan/index_kepala', 'PerawatanController@indexKepala');
	Route::post('perawatan/paraf_pengajuan', 'PerawatanController@parafPengajuan');


});
Route::group(['middleware' => ['role:staff']], function () {

	Route::get('permintaan_atk/index_anggota', 'PermintaanAtkController@indexAnggota')->name('permintaan_atk.index_anggota');
	Route::get('/permintaan_atk', 'PermintaanAtkController@index')->name('permintaan_atk.index');
	Route::get('/permintaan_atk/create', 'PermintaanAtkController@create')->name('permintaan_atk.create');
	Route::post('/permintaan_atk', 'PermintaanAtkController@store')->name('permintaan_atk.store');
	Route::get('/permintaan_atk/{id}/edit', 'PermintaanAtkController@edit')->name('permintaan_atk.edit');
	Route::patch('/permintaan_atk/{id}', 'PermintaanAtkController@update')->name('permintaan_atk.update');
	Route::put('/permintaan_atk/{id}', 'PermintaanAtkController@update')->name('permintaan_atk.update');
	Route::delete('/permintaan_atk/{id}', 'PermintaanAtkController@destroy')->name('permintaan_atk.destroy');

	Route::get('perawatan/index_anggota', 'PerawatanController@indexAnggota');
	Route::get('perawatan/pengajuan', 'PerawatanController@pengajuan');
	Route::post('perawatan/store_pengajuan', 'PerawatanController@storePengajuan');
	Route::get('perawatan/{id}/edit_pengajuan', 'PerawatanController@editPengajuan');
	Route::post('perawatan/{id}/update_pengajuan', 'PerawatanController@updatePengajuan');
	Route::post('perawatan/delete_pengajuan', 'PerawatanController@DestroyPengajuan');

});
Route::group(['middleware' => ['role:staff|kepala|humas']], function () {
	Route::get('/permintaan_atk/{id}', 'PermintaanAtkController@show')->name('permintaan_atk.show');
	Route::get('cetak/permintaan_atk/{permintaan_id}', 'PermintaanAtkController@cetakPermintaanAtk');
	Route::get('perawatan/{id}/show_pengajuan', 'PerawatanController@showPengajuan');


});

Route::group(['middleware' => ['role:administrator']], function () {

	Route::post('users/operate', 'UserController@operate');
	Route::resource('roles','RoleController');
	Route::resource('users','UserController');
});



Route::group(['middleware' => ['role:humas|staff']], function() {
	Route::get('/mst_atk', 'MstAtkController@index')->name('mst_atk.index');
	Route::get('/mst_barang', 'MstBarangController@index')->name('mst_barang.index');


});



Route::group(['middleware' => ['role:humas']], function () {

	Route::get('perawatan/index_humas_belum', 'PerawatanController@indexHumasBelum');
	Route::get('perawatan/index_humas_selesai', 'PerawatanController@indexHumasSelesai');
	Route::get('perawatan/{id}/input_perawatan', 'PerawatanController@inputPerawatan');
	Route::post('perawatan/store_perawatan', 'PerawatanController@storePerawatan');
	Route::get('perawatan/{id}/show_perawatan', 'PerawatanController@showPerawatan');
	Route::get('perawatan/{id}/edit_perawatan', 'PerawatanController@editPerawatan');
	Route::post('perawatan/{id}/update_perawatan', 'PerawatanController@updatePerawatan');
	Route::post('perawatan/delete_perawatan', 'PerawatanController@destroyPerawatan');
	Route::get('perawatan/{id}/cetak_perawatan', 'PerawatanController@cetakPerawatan');


	Route::get('distribusi_atk/{permintaan_id}/detail', 'DistribusiAtkController@detailDistribusi');
	Route::get('distribusi_atk/{distribusi_atk_id}/distribusi', 'DistribusiAtkController@doDistribusiAtk')->name('distribusi_atk.distribusi');
	Route::get('distribusi_atk/{distribusi_atk_id}/cetak', 'DistribusiAtkController@cetakDistribusiAtk')->name('distribusi_atk/{distribusi_id}/cetak');
	Route::post('distribusi_atk/post/distribusi', 'DistribusiAtkController@postDistribusiAtk');
	Route::get('distribusi_atk/riwayat', 	'DistribusiAtkController@tahapDistribusi')->name('distribusi_atk.riwayat');
	Route::get('distribusi_atk/selesai', 	'DistribusiAtkController@selesaiDistribusi')->name('distribusi_atk.selesai');
	Route::get('distribusi_atk/detail_riwayat/{id}', 	'DistribusiAtkController@detailRiwayat');
	Route::get('distribusi_atk/edit_distribusi/{tahap_id}', 	'DistribusiAtkController@editTahapDistribusi');
	Route::post('distribusi_atk/delete_tahap', 	'DistribusiAtkController@deleteTahapDistribusi');
	Route::get('distribusi_atk/riwayat/{tahap_id}/cetak', 	'DistribusiAtkController@cetakTahapDistribusi');


	Route::get('pembelian_atk/{pembelian_id}/cetak', 'PembelianAtkController@cetakPembelianAtk');
	Route::post('pembelian_atk/import_laporan_kebutuhan', 'PembelianAtkController@importLaporanKebutuhan');
	Route::get('pembelian_atk/cetak_laporan_kebutuhan', 'PembelianAtkController@cetakLaporanKebutuhan');
	
	Route::get('/mst_atk/create', 'MstAtkController@create')->name('mst_atk.create');
	Route::post('/mst_atk', 'MstAtkController@store')->name('mst_atk.store');
	Route::get('/mst_atk/{id}', 'MstAtkController@show')->name('mst_atk.show');
	Route::get('/mst_atk/{id}/edit', 'MstAtkController@edit')->name('mst_atk.edit');
	Route::patch('/mst_atk/{id}', 'MstAtkController@update')->name('mst_atk.update');
	Route::put('/mst_atk/{id}', 'MstAtkController@update')->name('mst_atk.update');
	Route::delete('/mst_atk/{id}', 'MstAtkController@destroy')->name('mst_atk.destroy');
	Route::post('mst_atk/submit_atk', 'MstAtkController@submitAtk');

	Route::get('/mst_barang/create', 'MstBarangController@create')->name('mst_barang.create');
	Route::post('/mst_barang', 'MstBarangController@store')->name('mst_barang.store');
	Route::get('/mst_barang/{id}', 'MstBarangController@show')->name('mst_barang.show');
	Route::get('/mst_barang/{id}/edit', 'MstBarangController@edit')->name('mst_barang.edit');
	Route::patch('/mst_barang/{id}', 'MstBarangController@update')->name('mst_barang.update');
	Route::put('/mst_barang/{id}', 'MstBarangController@update')->name('mst_barang.update');
	Route::delete('/mst_barang/{id}', 'MstBarangController@destroy')->name('mst_barang.destroy');
	Route::post('mst_barang/submit_barang', 'MstBarangController@submitBarang');

	Route::get('laporan/front_opname', 'LaporanController@frontOpname');
	Route::get('laporan/cetak_opname', 'LaporanController@cetakOpname');
	Route::get('laporan/front_perawatan', 'LaporanController@frontPerawatan');
	Route::get('laporan/cetak_perawatan', 'LaporanController@cetakPerawatan');




	Route::resource('distribusi_atk', 'DistribusiAtkController');
	Route::resource('pembelian_atk', 'PembelianAtkController');
});


	
});






