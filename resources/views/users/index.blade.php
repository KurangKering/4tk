@extends('layouts.backend')
@section('custom-css')
<style type="text/css">
th, td {
  white-space: nowrap;
}
</style>
@endsection
@section('content-header')
<h1>
  Daftar Pengguna
  {{-- <small>advanced tables</small> --}}
</h1>
{{-- <ol class="breadcrumb">
  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="#">Tables</a></li>
  <li class="active">Data tables</li>
</ol> --}}
@endsection
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header">
        <div class="box-title"></div>
        <div class="box-tools pull-right">
          <a class="btn btn-success" onclick="show_modal()"> Create New User</a>
          
        </div>
      </div>
      <div class="box-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
          <p>{{ $message }}</p>
        </div>
        @endif
        <table class="table table-bordered" nowrap id="table-user">
         <thead>
           <tr>
           <th>Name</th>
           <th>Sub Bidang</th>
           <th>Username</th>
           <th>Email</th>
           <th>Roles</th>
           <th width="280px">Action</th>
         </tr>
         </thead>
         <tbody>
         @foreach ($data as $key => $user)
         <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->subbidang['nama'] ?? '-' }}</td>
          <td>{{ $user->username }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
            <label class="badge badge-success">{{ $v }}</label>
            @endforeach
            @endif
          </td>
          <td style="width: 1%">
            <button type="button" class="btn btn-info" onclick="show_detail({{ $user->id }})">Detail</button>
            <a class="btn btn-primary" onclick="show_modal({{ $user->id }})">Edit</a>
            <button type="button" class="btn btn-danger" onclick="delete_user({{ $user->id }})">Delete</button>

           {{--  {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!} --}}
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
      {{-- {!! $data->render() !!} --}}

    </div>
  </div>
</div>
</div>
@include('users.modal_user')
@endsection
@section('custom-js')

<script>
  let tableUser = $("#table-user").DataTable();
</script>
@endsection
