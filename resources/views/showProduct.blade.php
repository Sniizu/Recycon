@extends('layout')
@section('title',$title)

@section('style')
<link rel="stylesheet" href="{{ asset('/css/products.css') }}"/>
@endsection

@section('content')
<div class="container">
<h1 class="text-center mb-4">Our Products</h1>

@if($products->count())
<div class="wrapper flex-d justify-content-center">
  <div class="row">
    @foreach ($products as $p)
    <div class="col-md-4 col-sm-12">
      <div class="card">
        <div class="card-img-top">
          @if (Storage::disk('public')->exists($p->image))
            <img src="{{Storage::url($p->image)}}" alt="card-image">
          @else
            <img src="{{$p->image}}" alt="card-image">
          @endif
        </div>
        <div class="custom-card-text">
          <div class="top-info">
            <div class="info ">
                <a href="/products/{{$p->id}}"><h4 class="card-title mb-4">{{$p->name}}</h4></a>
                <h5>IDR {{$p->price}}</h5>
            </div>
            <div class="info right">
                <h5>{{$p->category}}</h5>
            </div>
          </div>
          <br>
          <a href="/products/{{$p->id}}" >
            <div class="btn btn-primary btn-sm">Detail Product</div>
          </a>

        </div>
      </div>
    </div>
    @endforeach
    <div class="d-flex justify-content-center">
      {{$products->links()}}
    </div>
  </div>
</div>


@else
<div class="trash">
  <img src="https://www.bagbazaars.com/assets/img/no-product-found.png" alt="">

</div>
@endif
</div>
@endsection
