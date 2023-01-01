@extends('layout')
@section('title','Update Cart')


@section('style')
<link rel="stylesheet" href="{{ asset('/css/productDetail.css') }}"/>
@endsection

@section('content')
@if($product)
<div class="container">

  <div class="img-box">
    @if (Storage::disk('public')->exists($product->image))
      <img src="{{Storage::url($product->image)}}" alt="card-image">
    @else
      <img src="{{$product->image}}" alt="card-image">
    @endif
  </div>


  <div class="info">
    <div class="title">
      <h3>{{$product->name}}</h3>
    </div>
    <div class="more">
      <h6>Category:</h6>
      <p>{{$product->category}}</p>
      <h6>Price:</h6>
      <p>IDR {{$product->price}}</p>
      <h6>Description:</h6>
      <p>{{$product->description}}</p>
    </div>

  @if(!(Session::get('user')))
  <a href="/login">
    <div class="btn btn-warning btn-sm">Login to buy</div>
  </a>
  @endif

  @if(Session::get('user') && Session::get('user')['role']==='customer')
  <div class="form_qty">


    <form action="/updateCartItem" method="post" class="qty-form ">
      @csrf
      @method('put')
        <input type="hidden" name="id" value="{{$product->id}}">
        <label for="qty">qty: </label>
        <input class="form-control mb-2 @error('qty') is-invalid  @enderror" type="number" name="qty" value="{{$qty}}">

      <button class="btn btn-success btn-sm" type="submit">Update Cart</button>
      <a href="/cartList">
      <button class="btn btn-secondary btn-sm" type="button">Back to Cart</button>
      </a>

      @error('qty')
        <small class="error-message">
          {{$message}}
        </small>
      @enderror
      @if(session()->has('success'))
        <div class="alert alert-success mt-4">
            {{ session()->get('success') }}
        </div>
    @endif

    </form>
  </div>
  </div>
  @endif
</div>
@else
<div class="h1">
  Product Not Found!
</div>
@endif

@endsection
