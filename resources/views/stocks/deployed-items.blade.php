@extends('stocks.layout')
@include('navbar')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        Deployed Items
                        <div class="float-right">
                            <form action="{{ route('deployedItems.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($deployedItems->isEmpty())
                            <div class="alert alert-danger">No deployed items found.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Receiver's Name</th>
                                            <th>Sender's Name</th>
                                            <th>Item Details</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($deployedItems as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->receiver_name }}</td>
                                                <td>{{ $item->sender_name }}</td>
                                                <td>{{ $item->item_details }}</td>
                                                <td>
                                                    <a href="{{ route('deployedItems.downloadPdf', $item->id) }}" class="btn btn-primary btn-sm" title="Download PDF">
                                                        <i class="fa fa-download" aria-hidden="true"></i> Download PDF
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
