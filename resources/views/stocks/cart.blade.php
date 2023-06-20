
@extends('stocks.layout')
@include('navbar')

@section('content')
    <div class="container custom-container">
        <div class="row justify-content-center">
            <div class="col-md-9 glow-table">
                <div class="card">
                    <div class="card-header">Cart</div>
                    <div class="card-body">
                        @if (count($cartItems) > 0)
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->stock->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('cart.remove', $item->id) }}" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Remove from Cart" onclick="return confirm('Are you sure you want to remove this item from the cart?')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                <a href="{{ route('cart.clear') }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear the cart?')">Clear Cart</a>
                                <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
                            </div>
                        @else
                            <p>Your cart is empty.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
