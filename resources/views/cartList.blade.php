@extends('layout')

@section('title','Cart')

@section('style')
<link rel="stylesheet" href="{{ asset('/css/cart.css') }}"/>
@endsection

@section('content')
<div class="container p-4">
  <h1> My Cart</h1>
  @if($cartitems!=null && count($cartitems->cartDetail()->get())>0)
  @if(session()->has('success'))
  <div class="alert alert-dark alert-dismissible fade show d-flex" role="alert">
    <span>{{session('success')}}</span>
    <button type="button" class="close bg-danger text-light border-1 border-danger ms-auto px-2 rounded" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <table class="table table-striped cart-table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">No</th>
        <th scope="col">Item Image</th>
        <th scope="col">Item Name</th>
        <th scope="col">Item Price</th>
        <th scope="col">Item qty</th>
        <th scope="col">Total Price</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < count($cartitems->cartDetail()->get()); $i++)
      <form method="post">
      @csrf
      <tr>

          <th scope="row">{{$i+1}}</th>
          <td>
            @if (Storage::disk('public')->exists($cartitems->cartDetail()->get()[$i]->item()->first()->image))
              <img src="{{Storage::url($cartitems->cartDetail()->get()[$i]->item()->first()->image)}}" alt="card-image" width="200" height="200">
            @else
              <img src="{{$cartitems->cartDetail()->get()[$i]->item()->first()->image}}" alt="card-image" width="200" height="200">
            @endif
          <td>{{$cartitems->cartDetail()->get()[$i]->item()->first()->name}}</td>
          <td>IDR {{$cartitems->cartDetail()->get()[$i]->item()->first()->price}}</td>
          <td class="qty">
              {{$cartitems->cartDetail()->get()[$i]->qty}}
          </td>

          <td class="total-price">IDR {{$cartitems->cartDetail()->get()[$i]->qty*$cartitems->cartDetail()->get()[$i]->item()->first()->price}}</td>
          <td>
              <input type="hidden" name="cart_id" value="{{$cartitems->cartDetail()->get()[$i]->cart_id}}">
              <input type="hidden" name="item_id" value="{{$cartitems->cartDetail()->get()[$i]->item_id}}">
              <div id="action">

              <a href="/updateCartqty/{{$cartitems->cartDetail()->get()[$i]->item()->first()->id}}"><button type="button" class="btn btn-warning btn-sm" >Update</button></a>
              <button class="btn btn-danger btn-sm" formaction="/deleteCartItem" type="submit" >Delete</button>

              </div>
          </td>
      </tr>
      </form>
      @endfor
    </tbody>
  </table>
  <span > Grand Total: <span class="grand-total">IDR {{$cartitems->sum}}</span> </span>
</div>


<div class="checkout-form">
    <form action="/checkout" method="post">
      <h3>Receiver</h3>
      @csrf
      <input type="hidden" name="cart_id" value="{{$cartitems->id}}">
      <div class="form-group">
        <label for="name">Receiver name</label>
        <input type="name" class="form-control" id="name" name="name" placeholder="Enter name" value="{{Session::get('user')['username']}}">
      </div>
      <div class="form-group">
        <label for="address">Receiver address</label>
        <input type="address" class="form-control" id="address" name="address" placeholder="Enter address" >
      </div>
      <button class="btn btn-warning btn-sm" type="submit" >Checkout ({{$cartitems->ctr}})</button>
      @if($errors->any())
      <div class="alert alert-danger" role="alert">
          {{$errors->first()}}
      </div>
      @endif
  </form>
  @else
    <h3 class="text-center pb-5 mt-5 pt-5">Cart is empty! Let’s go shopping :) </h3>
  @endif
  </div>

  @endsection




