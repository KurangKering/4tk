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
  Tambah Pengguna Baru
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
        <div class="box-title">

        </div>
        <div class="box-tools pull-right">
          <a class="btn btn-primary" href="{{ route('users.index') }}"> Kembali</a>
        </div>
      </div>
      <div class="box-body">
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
       {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
       <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Nama:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Username:</strong>
            {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
          </div>
        </div><div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Sub Bidang:</strong>
            {!! Form::select('subbidang_id',$subbidang, null, array('class' => 'form-control')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Role:</strong>
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
</div>


@endsection
@section('custom-js')
@endsection
