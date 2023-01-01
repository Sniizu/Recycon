@extends('layout')
@section('title','changePassword')
@section('style')
@endsection


@section('content')
    <div class="container p-5">
        <form action="{{ route('runChangePassword') }}" method="post" class="item-form" enctype="multipart/form-data">
            @method('put')
            @csrf
            <h2 style="margin-top: 15px">Change Password</h2>
            <div class="form-group" style="margin-top: 25px">
            <input type="password" class="form-control" id="password" name="password" placeholder="Old Password">
            </div>
            <div class="form-group" style="margin-top: 25px">
            <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password">
            </div>
            <div class="form-group" style="margin-top: 25px">
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 25px">Save</button>
            <br>
        </form>
        <br>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('fail'))
        <div class="alert alert-danger">
            {{ session('fail') }}
        </div>
        @endif
    </div>
@endsection
