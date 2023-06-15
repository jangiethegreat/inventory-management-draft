@extends('stocks.layout')
@include('navbar')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Stocks</div>
                    <div class="card-body">
                        @if(isset($errorMessage))
                            <div class="alert alert-danger">{{ $errorMessage }}</div>
                        @endif
                        @if (session()->has('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        @endif
                        <form action="{{ route('stocks.index') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <a href="{{ url('/stocks/create') }}" class="btn btn-success btn-sm" title="Add New Stocks">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <a href="{{ url('/cart') }}" title="View Cart">
                            <button class="btn btn-info btn-sm">
                                <i class="fa fa-eye" aria-hidden="true"></i> View Cart
                            </button>
                        </a>
                        <a href="{{ url('/deployed-items') }}" title="View Deployed Items">
                            <button class="btn btn-info btn-sm">
                                <i class="fa fa-eye" aria-hidden="true"></i> View Deployed Items
                            </button>
                        </a>
                        <br/><br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach($stocks as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                <a href="{{ route('cart.add', $item->id) }}" title="Add to Cart">
                                                    <button class="btn btn-success btn-sm">
                                                        <i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart
                                                    </button>
                                                </a>
                                                <a href="{{ url('/stocks/' . $item->id . '/edit') }}" title="Edit Stocks">
                                                    <button class="btn btn-primary btn-sm">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                    </button>
                                                </a>
                                                <form method="POST" action="{{ url('/stocks' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete stocks" onclick="return confirm(&quot;Confirm delete?&quot;)">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
