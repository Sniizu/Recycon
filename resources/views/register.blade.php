@extends('layout')
@section('title','Register')
@section('style')
    <link rel="stylesheet" href="{{ asset('/css/register.css') }}"/>
@endsection

@section('content')
<form action="/register" method="post" style="margin-left: 550px">
    @csrf
    <div class="container p-5"></div>
    <h1 style="margin-top: 15px">Register Form</h1>
    <div class="form-group" style="margin-top: 25px">
        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
      </div>
    <div class="form-group">
      <input type="text" class="form-control" id="email" name="email" placeholder="Email">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"placeholder="Confirm Password">
      </div>

    <button type="submit" class="btn btn-primary">Register</button>
    @if($errors->any())
    <div class="alert alert-danger mt-4" role="alert">
        {{$errors->first()}}
    </div>

    @elseif(session()->has('success_message'))
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>

    @endif
</form>

@endsection
