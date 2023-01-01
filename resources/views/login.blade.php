@extends('layout')
@section('title','Login')
@section('style')
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}"/>
@endsection


@section('content')
    <div class="container p-5"></div>
  <form action="/login" method="post" style="margin-left: 550px">
    @csrf
    <h2 style="margin-top: 15px">Please Sign In</h2>
    <div class="form-group" style="margin-top: 25px">
      <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{((Cookie::get('email') !== null) ? Cookie::get('email') : '')}}">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{((Cookie::get('password') !== null) ? Cookie::get('password') : '')}}">
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{Cookie::get('email') === null ? '':'checked'}}>
        <label class="form-check-label" for="remember" name="remember">Remember me</label>
      </div>
    <button type="submit" class="btn btn-primary">Login</button>
    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        {{$errors->first()}}
    </div>
    @elseif(session()->has('register_success'))
        <div class="alert alert-success mt-4">
            {{ session()->get('register_success') }}
        </div>
    @endif
  </form>

@endsection
