@extends('stocks.layout')
@include('navbar')
@section('content')
<div class="card">
  <div class="card-header">Contactus Page</div>
  <div class="card-body">
  
        <div class="card-body">
        <h5 class="card-title">Name : {{ $stocks->name }}</h5>
        <p class="card-text">Quantity : {{ $stocks->quantity }}</p>
        <p class="card-text">Description : {{ $stocks->description }}</p>
  </div>
      
    </hr>
  
  </div>
</div>