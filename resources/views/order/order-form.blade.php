<!-- checkout.blade.php -->
<form action="{{ route('checkout.store') }}" method="POST">
    @csrf
    <div>
        <label for="sender_name">Sender Name:</label>
        <input type="text" name="sender_name" id="sender_name" value="{{ old('sender_name') }}" required>
    </div>
    <div>
        <label for="receiver_name">Receiver Name:</label>
        <input type="text" name="receiver_name" id="receiver_name" value="{{ old('receiver_name') }}" required>
    </div>
    <div>
        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
        <input type="hidden" name="stock_quantity" value="{{ $stock->quantity }}">
        <input type="hidden" name="stock_description" value="{{ $stock->description }}">
        <button type="submit">Place Order</button>
    </div>
</form>