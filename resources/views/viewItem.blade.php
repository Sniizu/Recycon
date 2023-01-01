@extends('layout')
@section('title','Manage Item')

@section('style')
<link rel="stylesheet" href="{{ asset('/css/manageItem.css') }}"/>
@endsection

@section('content')

@auth


<div class="container">
  <h1>Manage Item</h1>
  @if(session()->has('success'))
  <div class="alert alert-dark alert-dismissible fade show d-flex" role="alert">
    <span>{{session('success')}}</span>
    <button type="button" class="close bg-danger text-light border-1 border-danger ms-auto px-2 rounded" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif

    @if($products->count())
    <div class="table-responsive mt-4">
      <table class="table table-striped table-bordered table-sm">
        <thead>
          <tr>
            <th>No</th>
            <th>Item ID</th>
            <th>Item Image</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Item Price</th>
            <th>Item Category</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $p)
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{$p->id}}</td>
              <td>
                @if (Storage::disk('public')->exists($p->image))
                  <img class="item-img" src="{{Storage::url($p->image)}}" alt="product-image">
                @else
                  <img class="item-img" src="{{$p->image}}" alt="product-image">
                @endif

              </td>
              <td>{{$p->name}}</td>
              <td>{{$p->description}}</td>
              <td>{{$p->price}}</td>
              <td>{{$p->category}}</td>
              <td class="update-delete" class="btn-group">
                <a href="/updateItem/{{$p->id}}" class="btn btn-sm btn-warning update">Update</a>
                <form action="/deleteItem/{{$p->id}}" method="post" class="btn btn-sm" style="padding:0; margin-left:0">
                  @method('delete')
                  @csrf
                  <input type="hidden" name="id" value="{{$p->id}}" >
                  <button type="submit"  class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
    </div>
  </div>
  @else
  <div class="no-items mt-5">
    <h3 class="text-center">Oops!</h3>
    <h5 class="text-center">No items yet</h5>
    <p class="text-center"><a href="/addItem" class="text-decoration-none" >Add an item first 👉</a></p>
  </div>
@endif
@endauth
@endsection
