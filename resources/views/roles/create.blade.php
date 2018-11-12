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
      <h2>Create New Role</h2>
    </div>
    <div class="pull-right">
      <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
    </div>
  </div>
</div>
@if (count($errors) > 0)
<div class="alert alert-danger">
  <strong>Whoops!</strong> There were some problems with your input.<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
{!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Name:</strong>
      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
    </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Permission:</strong>
      <br/>
      @foreach($permission as $value)
      <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
        {{ $value->name }}</label>
        <br/>
        @endforeach
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
  {!! Form::close() !!}
  @endsection
  @section('custom-js')
  @endsection
