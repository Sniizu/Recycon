@extends('layout')
@section('title','editProfile')
@section('style')
@endsection


@section('content')
    <div class="container p-5">
        <form action="{{ route('runEditProfile') }}" method="post" class="item-form" enctype="multipart/form-data">
            @method('put')
            @csrf
            <h2>Edit Profile</h2>
            <div class="form-group" style="margin-top: 25px">
                <input type="text" class="form-control" id="username" name="username" placeholder="New Username" value="{{$user->username}}">
              </div>
            <div class="form-group" style="margin-top: 25px">
              <input type="text" class="form-control" id="email" name="email" placeholder="New Email" value="{{$user->email}}">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 25px">Save</button>
          </form>
          <br>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
    </div>
@endsection
