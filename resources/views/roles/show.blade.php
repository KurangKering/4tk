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
      <h2> Show Role</h2>
    </div>
    <div class="pull-right">
      <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Name:</strong>
      {{ $role->name }}
    </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Permissions:</strong>
      @if(!empty($rolePermissions))
      @foreach($rolePermissions as $v)
      <label class="label label-success">{{ $v->name }},</label>
      @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
@section('custom-js')
@endsection
