@extends('stocks.layout')
@include('navbar')

@section('content')
    <div class="container custom-container">
        <div class="row justify-content-center">
            <div class="col-md-9 glow-table">
                <div class="card">
                    <div class="card-header">Stocks</div>
                    <div class="card-body">
                        @if(isset($errorMessage))
                            <div class="alert alert-danger">{{ $errorMessage }}</div>
                        @endif
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('stocks.index') }}" method="GET">
                            <a href="{{ url('/stocks/create') }}" class="btn btn-success btn-sm add-new-button" title="Add New Stocks">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
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
                                    <td>{{ $originalIds[$loop->index] }}</td>
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
@endsection
