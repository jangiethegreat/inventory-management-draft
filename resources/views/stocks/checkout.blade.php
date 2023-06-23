@extends('stocks.layout')
@include('navbar')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">Checkout</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('deployItems') }}">
                            @csrf
                            <div class="form-group">
                                <label for="receiver_name">Receiver's Name</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" required>
                                @error('receiver_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sender_name">Deployed By</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" value="{{ old('sender_name') }}" required>
                                @error('sender_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
    <label for="item_details">Item Description</label>
    @php
    $item_details = '';
@endphp
@foreach ($cartItems as $cartItem)
    @php
        $item_details .= $cartItem->stock->name . ' - '  . 'Qty: '.  $cartItem->quantity . ' - ' . $cartItem->stock->description . ' , ';
    @endphp
    
@endforeach



    <input type="text" class="form-control" id="item_details" name="item_details" value="{{ rtrim($item_details, ', ') }}" >
    @error('item_details')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
                            <button type="submit" class="btn btn-primary">Deploy Items</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection