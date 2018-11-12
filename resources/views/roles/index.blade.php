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
  Daftar Permintaan ATK Anggota
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
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h2>Role Management</h2>
    </div>
    <div class="pull-right">
      {{-- @can('role-create') --}}
      <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
      {{-- @endcan --}}
    </div>
  </div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<table class="table table-bordered">
  <tr>
   <th>No</th>
   <th>Name</th>
   <th width="280px">Action</th>
 </tr>
 @foreach ($roles as $key => $role)
 <tr>
  <td>{{ ++$i }}</td>
  <td>{{ $role->name }}</td>
  <td>
    <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
    @can('role-edit')
    <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
    @endcan
    @can('role-delete')
    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
    @endcan
  </td>
</tr>
@endforeach
</table>
{!! $roles->render() !!}
@endsection
@section('custom-js')
@endsection
