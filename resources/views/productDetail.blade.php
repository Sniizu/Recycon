@extends('layout')
@section('title','Product Detail')


@section('style')
<link rel="stylesheet" href="{{ asset('/css/productDetail.css') }}"/>
@endsection

@section('content')
@if($product)
<div class="container">

  <div class="img-box">
    @if (Storage::disk('public')->exists($product->image))
      <img src="{{Storage::url($product->image)}}" alt="product-image">
    @else
      <img src="{{$product->image}}" alt="product-image">
    @endif
  </div>

    <div class="details">
        <div class="info m-4">
            <div class="title">
              <h3>{{$product->name}}</h3>
            </div>
            <div class="more">
              <h5>Category:</h5>
              <h6>{{$product->category}}</h6>
              <h5>Price:</h5>
              <h6 style="color: red">IDR. {{$product->price}}</h6>
              <h5>Description:</h5>
              <h6>{{$product->description}}</h6>
            </div>

          @if(!(Session::get('user')))
          <a href="/login">
            <div class="btn btn-warning btn-sm">
              Login to Buy
            </div>
          </a>
          @endif

          @if(Session::get('user') && Session::get('user')['role']==='customer')
          <div class="form_qty">


            <form action="/addcart" method="post" class="qty-form ">
              @csrf

                <input type="hidden" name="id" value="{{$product->id}}">
                <div class="form-group" style="display: flex; justify-content: center; align-content: center">
                    <label for="qty">Qty:  </label>
                    <input class="form-control mb-2 @error('qty') is-invalid  @enderror" type="number" name="qty" value="{{old('qty')}}">
                    <button class="btn btn-warning btn-sm" type="submit">Add to Cart</button>
                </div>

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




</div>
@else
<div class="h1">
  Product Not Found!
</div>
@endif

@endsection

