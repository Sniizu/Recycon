

@extends('layout')

@section('style')
<link rel="stylesheet" href="{{ asset('/css/home.css') }}"/>
@endsection

@section('title','Home')

@section('content')
    <section class="hometitle">
        <h1>RECYCON SHOP</h1>
    </section>
    <section class="about" style="padding-bottom: 50px">
        <h3>ABOUT US</h3>
        <h5>We are a shop for buying recycle things and second hand thing</h5>
    </section>
@endsection
