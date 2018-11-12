<ul class="sidebar-menu" data-widget="tree">
	<li class="header">MAIN NAVIGATION</li>
	@if (Auth::check())

	<li class="{{ active_sidebar() }}"><a href="{{ url('/') }}"><i class="fa fa-circle-o"></i> <span>Dashboard</span></a></li>
	@if(Auth::user()->hasRole('humas'))
	
	<li class="treeview {{ active_sidebar('mst_*') }}">
		<a href="#">
			<i class="fa fa-circle-o"></i> <span>Data Master</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ active_sidebar('mst_atk*') }}"><a href="{{ route('mst_atk.index') }}"><i class="fa fa-circle-o"></i> Master ATK </a></li>
			<li class="{{ active_sidebar('mst_barang*') }}"><a href="{{ route('mst_barang.index') }}"><i class="fa fa-circle-o"></i> Master Barang </a></li>

		</ul>
	</li>
	<li class="treeview {{ active_sidebar('distribusi_atk*') }}">
		<a href="#">
			<i class="fa fa-circle-o"></i> <span>Distribusi ATK</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ active_sidebar('distribusi_atk') }}"><a href="{{ route('distribusi_atk.index') }}"><i class="fa fa-circle-o"></i> Daftar Distribusi </a></li>
			<li class="{{ active_sidebar('distribusi_atk/selesai') }}"><a href="{{ route('distribusi_atk.selesai') }}"><i class="fa fa-circle-o"></i> Daftar Selesai </a></li>
			<li class="{{ active_sidebar('distribusi_atk/riwayat') }}"><a href="{{ route('distribusi_atk.riwayat') }}"><i class="fa fa-circle-o"></i> Riwayat </a></li>

		</ul>
	</li>
	<li class="treeview {{ active_sidebar('perawatan*') }}">
		<a href="#">
			<i class="fa fa-circle-o"></i> <span>Perawatan Barang</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ active_sidebar('*/index_humas_belum') }}"><a href="{{ url('perawatan/index_humas_belum') }}"><i class="fa fa-circle-o"></i> Daftar Perawatan </a></li>
			<li class="{{ active_sidebar('*/index_humas_selesai') }}"><a href="{{ url('perawatan/index_humas_selesai') }}"><i class="fa fa-circle-o"></i> Daftar Selesai </a></li>

		</ul>
	</li>
	<li class="{{ active_sidebar('pembelian_atk*') }}"><a href="{{ route('pembelian_atk.index') }}"><i class="fa fa-circle-o"></i> <span>Pembelian ATK</span></a></li>
	<li class="treeview {{ active_sidebar('laporan*') }}">
		<a href="#">
			<i class="fa fa-circle-o"></i> <span>Laporan</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ active_sidebar('*front_opname*') }}"><a href="{{ url('laporan/front_opname') }}"><i class="fa fa-circle-o"></i> Laporan Opname ATK </a></li>
			<li class="{{ active_sidebar('*front_perawatan*') }}"><a href="{{ url('laporan/front_perawatan') }}"><i class="fa fa-circle-o"></i> Laporan Perawatan Fasilitas</a></li>

		</ul>
	</li>
	@elseif (Auth::user()->hasRole('staff'))
	<li class="{{ active_sidebar('mst_atk') }}"><a href="{{ route('mst_atk.index') }}"><i class="fa fa-circle-o"></i> Stock ATK</a></li>

	<li class="{{ active_sidebar('permintaan_atk*') }}"><a href="{{ route('permintaan_atk.index_anggota') }}"><i class="fa fa-circle-o"></i> <span>Permintaan ATK Anggota</span></a></li>
	<li class="{{ active_sidebar('perawatan*') }}"><a href="{{ url('perawatan/index_anggota') }}"><i class="fa fa-circle-o"></i> <span>Pengajuan Perawatan</span></a></li>
	@elseif (Auth::user()->hasRole('kepala'))

	<li class="{{ active_sidebar('permintaan_atk*') }}"><a href="{{ route('permintaan_atk.index_kepala') }}"><i class="fa fa-circle-o"></i> <span>Permintaan ATK Kepala</span></a></li>
	<li class="{{ active_sidebar('perawatan*') }}"><a href="{{ url('perawatan/index_kepala') }}"><i class="fa fa-circle-o"></i> <span>Pengajuan Perawatan</span></a></li>
	@elseif (Auth::user()->hasRole('administrator'))
	<li class="{{ active_sidebar('users*') }}"><a href="{{ route('users.index') }}"><i class="fa fa-circle-o"></i> <span>Pengguna</span></a></li>
	{{-- <li><a href="{{ route('roles.index') }}"><i class="fa fa-circle-o"></i> <span>Hak Akses</span></a></li> --}}

	@endif
	@endif
</ul>