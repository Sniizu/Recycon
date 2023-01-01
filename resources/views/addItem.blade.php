@extends('layout')
@section('title','Add Item')
@section('style')
<link rel="stylesheet" href="{{ asset('/css/addItem.css') }}"/>
@endsection

@section('content')

@if(Session::get('user')['role'] === 'admin')
<form action="/addItem" method="post" class="item-form" enctype="multipart/form-data">
  @csrf

    <h3>Add Item</h3>

    <div class="form-row">
      <div class="form-input">
        <label for="id">Item ID</label>
        <input type="text" id="id" name="id" value="{{old('id')}}">
        @error('id')
        <p class="text-danger">
         {{$message}}
       </p>
       @enderror
      </div>

      <div class="form-input">
        <label for="name">Item Name</label>
        <input type="text" id="name" name="name" value="{{old('name')}}">
        @error('name')
        <p class="text-danger">
         {{$message}}
       </p>
       @enderror
      </div>

    <div class="form-input">
      <label for="price">Price (IDR)</label>
      <input type="text" id="price" name="price" value="{{old('price')}}" >
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
          <option value="Second">Second</option>
          <option value="Recycle">Beverage</option>
        </select>
        @error('category')
        <p class="text-danger">
         {{$message}}
       </p>
       @enderror
      </div>
    </div>

    <div class="form-row">
      <div class="image">
        <label for="image">Add Image</label>
        <input type="file" class="form-control" name="image" id="image">
        @error('image')
         <p class="text-danger">
          {{$message}}
        </p>
        @enderror
      </div>
    </div>

    <div class="preview">
      <img id="preview-image" src="http://flxtable.com/wp-content/plugins/pl-platform/engine/ui/images/image-preview.png"
      alt="preview image" style="max-height: 120px">
    </div>

    <div class="form-row">
      <div class="form-input">
        <label for="desc" >Description</label>
        <input type="text" name="description" id="desc" value="{{old('description')}}">
        @error('description')
        <p class="text-danger">
        {{$message}}
        </p>
      @enderror
      </div>
    </div>

  <button type="submit" class="btn btn-primary">Add Item</button>
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


@endsection
