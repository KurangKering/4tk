<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use App\MstAtk;
use App\PermintaanAtk;
use App\PembelianAtk;
use App\DistribusiAtk;
use App\DetPermintaanAtk;
use App\DetPembelianAtk;
use App\DetDistribusiAtk;
use App\SubBidang;
use App\User;
use App\TahapDistribusiAtk;
use App\MstBarang;
use App\Perawatan;
use App\DetPerawatan;

class DataTablesController extends Controller
{

	/**
	 * url('datatables/daftar_tahap_perawatan')
	 * @return [type] [description]
	 */
	public function getTahapPerawatan()
	{
		$tahap = TahapPerawatan::with('perawatan.pengajuan.subbidang', 'det_perawatan')->get();
		return DataTables::of($tahap)
		->editColumn('tanggal_perawatan', function($q){
			return indonesian_date($q->tanggal_perawatan);
		})
		->addColumn('action', function($tahaP) {
			$button = 	"<button type=\"button\" onclick=\"show_modal('$tahaP->id')\" class=\"btn btn-info\">Detail</button>";
			$button .= " ";
			$button .= 	"<button type=\"button\" onclick=\"delete_distribusi('$tahaP->id')\" class=\"btn btn-danger\">Delete</button>";
			$button .= " ";
			$button .= 	"<button type=\"button\" onclick=\"cetak('$tahaP->id')\" class=\"btn btn-warning\">Cetak</a>";
			return $button;
		})
		->addColumn('nomor', null)
		->make(true);
	}


	/**
	 * url('datatables/daftar_tahap_distribusi')
	 * @return [type] [description]
	 */
	public function getTahapDistribusi()
	{
		$tahap = TahapDistribusiAtk::with('distribusi_atk.permintaan_atk.subbidang', 'det_distribusi_atk')->get();
		return DataTables::of($tahap)
		->editColumn('tanggal_distribusi', function($q){
			return indonesian_date($q->tanggal_distribusi);
		})
		->addColumn('action', function($tahaP) {
			$button = 	"<button type=\"button\" onclick=\"show_modal('$tahaP->id')\" class=\"btn btn-info\">Detail</button>";
			$button .= " ";
			$button .= 	"<button type=\"button\" onclick=\"delete_distribusi('$tahaP->id')\" class=\"btn btn-danger\">Delete</button>";
			$button .= " ";
			$button .= 	"<button type=\"button\" onclick=\"cetak('$tahaP->id')\" class=\"btn btn-warning\">Cetak</a>";
			return $button;
		})
		->addColumn('nomor', null)
		->make(true);
	}

	public function getStockOpname()
	{
		$stocks = MstAtk::get();
		return DataTables::of($stocks)
		->editColumn('stock', function($q) {
			return $q->stock . ' ' . ucwords(strtolower($q->satuan));
		})
		->editColumn('nama', function($q) {
			return ucwords(strtolower($q->nama));
		})
		->addColumn('action', function($stock) {
			return 'Edit | Delete';
		})
		->addColumn('nomor', null)
		->make(true);
	}
	/**
	 * url('datatables/mst_barang')
	 */
	public function getMstBarang()
	{	
		$isDisabled = \Auth::check() && \Auth::user()->hasRole('staff');
		$barangs = MstBarang::latest();

		$detPerawatan =  DetPerawatan::select('mst_barang_id')->pluck('mst_barang_id')->all();
		return DataTables::of($barangs)
		->addColumn('action', function($barang) use($isDisabled, $detPerawatan) {
			$disDelt = in_array($barang->id, $detPerawatan) ? 'disabled' : '';
			$button = '<button type="button" disabled class="btn btn-block">disabled</button>';
			if (!$isDisabled) {
				$button = 	"<button type=\"button\" onclick=\"show_modal('$barang->id')\" class=\"btn btn-info\">Edit</button>";
				$button .= " ";
				$button .= 	"<button type=\"button\" $disDelt onclick=\"delete_barang('$barang->id')\" class=\"btn btn-danger\">Delete</a>";
			}
			return $button;
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/mst_atk')
	 */
	public function getMstAtk()
	{	
		$isDisabled = \Auth::check() && \Auth::user()->hasRole('staff');
		$barangs = MstAtk::latest();
		$detPermintaan = DetPermintaanAtk::select('mst_atk_id');
		$detPembelian =  DetPembelianAtk::select('mst_atk_id');
		$detDistribusi =  DetDistribusiAtk::select('mst_atk_id');
		$usedAtkID = $detPermintaan->union($detPembelian)->union($detDistribusi)->pluck('mst_atk_id')->all();


		return DataTables::of($barangs)
		->addColumn('action', function($atk) use($isDisabled, $usedAtkID) {
			$disDelt = in_array($atk->id, $usedAtkID) ? 'disabled' : '';
			$button = '-';
			if (!$isDisabled) {
				$button = 	"<button type=\"button\" onclick=\"show_modal('$atk->id')\" class=\"btn btn-info\">Edit</button>";
				$button .= " ";
				$button .= 	"<button type=\"button\" $disDelt onclick=\"delete_barang('$atk->id')\" class=\"btn btn-danger\">Delete</a>";
			}
			return $button;
		})
		->addColumn('nomor', null)
		->make(true);
	}

/**
	 * url('datatables/daftar_distribusi_atk_complete')
	 */
public function getDaftarDistribusiAtkComplete()
{

	$permintaans = PermintaanAtk::whereHas('distribusi_atk', function($q) {
		$q->where('status', 'complete');
	})
	->where('is_paraf', 'Y')
	->with('distribusi_atk')
	->get();

	return DataTables::of($permintaans)
	->editColumn('tanggal_permohonan', function($permintaan) {
		return indonesian_date($permintaan->tanggal_permohonan);
	})
	->addColumn('subbidang', function($permintaan) {
		$subbidang = SubBidang::where('id', $permintaan->subbidang_id)->get()->first();
		return $subbidang->nama;
	})
	->addColumn('action', function($permintaan) {
		return "
		<button type=\"button\" class=\"btn btn-info\" onclick=\"show_modal($permintaan->id)\">Detail</button>
		<button 
		type=\"button\" 
		onclick=\"cetak('".$permintaan->distribusi_atk->id."')\"  
		class=\"btn btn-warning\">
		Cetak
		</button>"
		;
	})
	->addColumn('nomor', null)
	->make(true);
}

	/**
	 * url('datatables/daftar_distribusi_atk_belum')
	 */
	public function getDaftarDistribusiAtkIncomplete()
	{
		$permintaans = PermintaanAtk::whereHas('distribusi_atk', function($q) {
			$q->whereIn('status', ['never','incomplete']);
		})
		->where('is_paraf', 'Y')
		->with('distribusi_atk')
		->get();
		return DataTables::of($permintaans)
		->editColumn('tanggal_permohonan', function($permintaan) {
			return indonesian_date($permintaan->tanggal_permohonan);
		})
		->addColumn('subbidang', function($permintaan) {
			$subbidang = SubBidang::where('id', $permintaan->subbidang_id)->get()->first();
			return $subbidang->nama;
		})
		->addColumn('action', function($permintaan) {
			return "
			<button type=\"button\" class=\"btn btn-info\" onclick=\"show_modal($permintaan->id)\">Detail</button>
			<a 
			href=\"".route('distribusi_atk.distribusi', $permintaan->distribusi_atk->id)."\"
			class=\"btn btn-warning\" 
			>
			Distribusi
			</a>
			";
		})
		->editColumn('status', function($permintaan) {
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
			return $status;
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/daftar_perawatan_belum')
	 */
	public function getDaftarPerawatanIncomplete()
	{
		$perawatans = Perawatan::where('status', '1')->with('subbidang')->get();
		return DataTables::of($perawatans)
		->editColumn('tanggal_pengajuan', function($pengajuan) {
			return indonesian_date($pengajuan->tanggal_pengajuan);
		})
		->addColumn('action', function($pengajuan) {
			return "
			<button type=\"button\" class=\"btn btn-info\" onclick=\"show_modal($pengajuan->id)\">Detail</button>
			<a 
			href=\"".url("perawatan/$pengajuan->id/input_perawatan")."\"
			class=\"btn btn-warning\" 
			>
			Input
			</a>
			";
		})
		->editColumn('status', function($pengajuan) {
			$status_perawatan = \Config::get('enums.status_perawatan');
			return $status_perawatan[$pengajuan->status];
		})
		->addColumn('nomor', null)
		->make(true);
	}


	/**
	 * url('datatables/daftar_perawatan_complete')
	 */
	public function getDaftarPerawatanComplete()
	{

		
		$perawatans = Perawatan::where('status', '2')->with('subbidang')->get();
		return DataTables::of($perawatans)
		->editColumn('tanggal_pengajuan', function($pengajuan) {
			return indonesian_date($pengajuan->tanggal_pengajuan);
		})
			->editColumn('tanggal_perawatan', function($pengajuan) {
			return indonesian_date($pengajuan->tanggal_perawatan);
		})
		->addColumn('action', function($pengajuan) {
			return "
			<button type=\"button\" class=\"btn btn-info\" onclick=\"show_modal($pengajuan->id)\">Detail</button>
			<a 
			href=\"".url("perawatan/$pengajuan->id/edit_perawatan")."\"
			class=\"btn btn-primary\" 
			>
			Edit
			</a>
			<a
			onclick='delete_perawatan(".$pengajuan->id.")'
			class=\"btn btn-danger\" 
			>
			Delete
			</a>
			<a
			class=\"btn btn-info\" 
			onclick='cetak(".$pengajuan->id.")'
			>
			Cetak
			</a>
			";
		})
		->editColumn('status', function($pengajuan) {
			$status_perawatan = \Config::get('enums.status_perawatan');
			return $status_perawatan[$pengajuan->status];
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/permintaan_atk_anggota')
	 * 
	 */
	public function getPermintaanAtkAnggota()
	{
		$user = \Auth::user();
		$permintaans = PermintaanAtk::with('distribusi_atk')->where('subbidang_id', $user->subbidang_id)->orderBy('id', 'desc');
		return DataTables::of($permintaans)
		->editColumn('distribusi_atk.status', function($q) {
			return !empty($q->distribusi_atk) ? $q->distribusi_atk->status : '-'; 
		})
		->editColumn('tanggal_permintaan', function($permintaan) {
			return indonesian_date($permintaan->tanggal_permintaan);
		})
		->editColumn('status', function($permintaan) {
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
			return $status;
		})
		
		->addColumn('action', function($permintaan) {
			$disabled = $permintaan->is_paraf == 'Y' || $permintaan->is_paraf == 'N' ? 'disabled' : '';
			return "<button 
			type=\"button\" 
			class=\"btn btn-info btn-detail\" 
			onclick=\"show_modal($permintaan->id)\"
			\">
			Detail
			</button>
			<button 
			type=\"button\" 
			class=\"btn btn-primary\"
			".$disabled." 
			onclick=\"location.href='".route('permintaan_atk.edit',$permintaan->id)."'\">
			Edit
			</button>
			<button 
			type=\"button\" 
			class=\"btn btn-danger\"
			".$disabled." 
			onclick=\"show_delete($permintaan->id)\">
			Delete
			</button>
			
			";
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/permintaan_atk_kepala')
	 * 
	 */
	public function getPermintaanAtkKepala()
	{
		
		$permintaans = PermintaanAtk::where('subbidang_id', \Auth::user()->subbidang_id)->orderBy('id', 'desc');
		return DataTables::of($permintaans)
		->editColumn('tanggal_permohonan', function($permintaan) {
			return indonesian_date($permintaan->tanggal_permohonan);
		})
		->editColumn('status', function($permintaan) {
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
			return $status;
		})
		
		->addColumn('action', function($permintaan) {
			$disabled = '';
			if ($permintaan->is_paraf == 'Y' || $permintaan->is_paraf ==  'N') {
				$disabled = 'disabled';
			}
			return "<button 
			type=\"button\" 
			class=\"btn btn-info\" 
			onclick=\"show_modal($permintaan->id)\">
			Detail
			</button>
			<button 
			type=\"button\"
			".$disabled." 
			class=\"btn btn-warning\" 
			onclick=\"showDialog($permintaan->id, 'Y')\">
			Paraf
			</button>
			<button 
			type=\"button\" 
			".$disabled." 
			class=\"btn btn-danger\" 
			onclick=\"showDialog($permintaan->id, 'N')\">
			Tolak
			</button>
			";
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/pengajuan_anggota')
	 * 
	 */
	public function getPengajuanAnggota()
	{
		$user = \Auth::check() ? \Auth::user() : abort(404);
		$perawatans = Perawatan::with('det_perawatan')->where('subbidang_id', $user->subbidang_id)->latest();
		
		return DataTables::of($perawatans)
		->editColumn('tanggal_pengajuan', function($pengajuan) {
			return indonesian_date($pengajuan->tanggal_pengajuan);
		})
		->editColumn('tanggal_perawatan', function($pengajuan) {
			$tgl = $pengajuan->tanggal_perawatan;
			return $tgl ? indonesian_date($tgl) : '-'; 
		})
		->editColumn('status', function($pengajuan) {
			$status_perawatan = \Config::get('enums.status_perawatan');
			return $status_perawatan[$pengajuan->status];
		})
		
		->addColumn('action', function($pengajuan) {
			$urlEdit = "perawatan/$pengajuan->id/edit_pengajuan";
			$disabled = $pengajuan->status != '0' ? 'disabled' : '';
			return "<button 
			type=\"button\" 
			class=\"btn btn-info btn-detail\" 
			onclick=\"show_modal($pengajuan->id)\"
			\">
			Detail
			</button>
			<button 
			type=\"button\" 
			class=\"btn btn-primary\"
			".$disabled." 
			onclick=\"location.href='".url($urlEdit)."'\">
			Edit
			</button>
			<button 
			type=\"button\" 
			class=\"btn btn-danger\"
			".$disabled." 
			onclick=\"show_delete($pengajuan->id)\">
			Delete
			</button>
			
			";
		})
		->addColumn('nomor', null)
		->make(true);
	}

	/**
	 * url('datatables/pengajuan_kepala')
	 * 
	 */
	public function getPengajuanKepala()
	{
		$user = \Auth::check() ? \Auth::user() : abort(404);
		$perawatans = Perawatan::with('det_perawatan')->where('subbidang_id', $user->subbidang_id);

		return DataTables::of($perawatans)
		->editColumn('tanggal_pengajuan', function($pengajuan) {
			return indonesian_date($pengajuan->tanggal_pengajuan);
		})
		->editColumn('tanggal_perawatan', function($pengajuan) {
			$tgl = $pengajuan->tanggal_perawatan;
			return $tgl ? indonesian_date($tgl) : '-'; 
		})
		->editColumn('status', function($pengajuan) {
			$status_perawatan = \Config::get('enums.status_perawatan');
			return $status_perawatan[$pengajuan->status];
		})
		
		->addColumn('action', function($pengajuan) {
			$disabled = '';
			if (in_array($pengajuan->status, ["-1", '1', '2'])) {
				$disabled = 'disabled';
			}
			return "<button 
			type=\"button\" 
			class=\"btn btn-info\" 
			onclick=\"show_modal($pengajuan->id)\">
			Detail
			</button>
			<button 
			type=\"button\"
			".$disabled." 
			class=\"btn btn-warning\" 
			onclick=\"showDialog($pengajuan->id, '1')\">
			Paraf
			</button>
			<button 
			type=\"button\" 
			".$disabled." 
			class=\"btn btn-danger\" 
			onclick=\"showDialog($pengajuan->id, '-1')\">
			Tolak
			</button>
			";
		})
		->addColumn('nomor', null)
		->make(true);
	}


	


}
