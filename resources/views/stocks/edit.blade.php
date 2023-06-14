@extends('stocks.layout')
@include('navbar')
@section('content')
<div class="card">
  <div class="card-header">stocksus Page</div>
  <div class="card-body">
      
      <form action="{{ url('stocks/' .$stocks->id) }}" method="post">
        {!! csrf_field() !!}
        @method("PATCH")
        <input type="hidden" name="id" id="id" value="{{$stocks->id}}" id="id" />
        <label>Name</label></br>
        <input type="text" name="name" id="name" value="{{$stocks->name}}" class="form-control" readonly></br>
        <label>Quantity</label></br>
        <input type="number" name="quantity" id="quantity" value="0" class="form-control"></br>
        <label>Description</label></br>
        <input type="text" name="description" id="description" value="{{$stocks->description}}" class="form-control"></br>
        <input type="submit" value="Update" class="btn btn-success"></br>
    </form>
  
  </div>
</div>
@stop