
@extends('layout')
@section('title','Update Item')
@section('style')
<link rel="stylesheet" href="{{ asset('/css/addItem.css') }}"/>
@endsection

@section('content')
<div class="container">

@if(Session::get('user')['role'] === 'admin')
<form action="/updateItem/{{$product->id}}" method="post" class="item-form" enctype="multipart/form-data">
  @method('put')
  @csrf

    <h3>Update Item</h3>

    <div class="form-row">
      <div class="form-input">
        <label for="id">Item ID</label>
        <input type="hidden" name="id" value="{{$product->id}}">
        <div class="unchanged">
          {{$product->id}}
        </div>
      </div>

      <div class="form-input">
        <label for="name">Item Name</label>
        <input type="text" id="name" name="name" value=" {{old('name', $product->name)}}">
        @error('name')
        <p class="text-danger">
         {{$message}}
       </p>
       @enderror
      </div>

    <div class="form-input">
      <label for="price">Price (IDR)</label>
      <input type="text" id="price" name="price" value="{{old('price', $product->price)}}" >
      @error('price')
      <p class="text-danger">
       {{$message}}
     </p>
     @enderror
    </div>
    </div>

    <div class="form-row">
      <div class="form-input">
        <label for="category">Category</label>
        <select name="category" id="category">
          <optgroup label="Choose category">
          <option value="select">Select a category</option>
          <option value="Second"
          @if(old('category', $product->category) === 'Second')
          selected="selected"
          @endif
          >Second</option>
          <option value="Recycle"
          @if(old('category', $product->category)  === 'Recycle')
          selected="selected"
          @endif
          >Recycle</option>
        </select>
        @error('category')
        <p class="text-danger">
         {{$message}}
       </p>
       @enderror
      </div>
    </div>




    <div class="form-row">
      <div class="image update-image-form">
        <div class="form-row">
          <div class="update-image">
            <label for="image">Update Image</label>
            <input type="file" class="form-control" name="image" id="image">
          </div>
          <div class="update-image">
            <label for="">Old Image File</label>
            <div class="unchanged unchanged-img mb-2">
              {{$product->image}}
            </div>
          </div>
        </div>
      </div>
    </div>
      @error('image')
      <p class="text-danger">
      {{$message}}
      </p>
     @enderror


    <div class="preview">

      @if (Storage::disk('public')->exists($product->image))
        <img id="preview-image" src="{{Storage::url($product->image)}}"
        alt="preview image" style="max-height: 120px">
      @else
        <img id="preview-image" src="{{$product->image}}"
        alt="preview image" style="max-height: 120px">
      @endif
    </div>

    <div class="form-row">
      <div class="form-input">
        <label for="desc" >Description</label>
        <input type="text" name="description" id="desc" value=" {{old('description', $product->description) }}">
          @error('description')
          <p class="text-danger">
          {{$message}}
          </p>
        @enderror
      </div>
    </div>

  <button type="submit" class="btn btn-primary">Update Item</button>
</form>


{{-- Script for image preview --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function (e) {
     $('#image').change(function(){
      const reader = new FileReader();
      reader.onload = (e) => {
        $('#preview-image').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
     });
  });
  </script>

@else
<div class="container">
  <h2 class="text-center mt-4 mb-3">Access Denied</h2>
  <p class="text-center">Please login as admin to access this page</p>
  <a href="/login"><p  class="text-center">if you are an admin, login here 👈</p> </a>
</div>

@endif

</div>
@endsection
