<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\CartItem;
use App\Models\DeployedItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Yajra\DataTables\Facades\DataTables;

use PDF;

use Illuminate\Http\Request;


class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $stocks = Stocks::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })->get();

        if ($stocks->isEmpty()) {
            $errorMessage = 'No stocks found.';
        } else {
            $errorMessage = null;
        }

        return view('stocks.index', compact('stocks', 'errorMessage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        Stocks::create($input);
        return redirect('stocks')->with('flash_message', 'Stocks Addedd!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stocks = Stocks::find($id);
        return view('stocks.show')->with('stocks', $stocks);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stocks = Stocks::find($id);
        return view('stocks.edit')->with('stocks', $stocks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stocks = Stocks::find($id);

        $rules = [
            'quantity' => 'required|numeric',
            // other validation rules for the description field, if any
        ];

        $messages = [
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity field must be a number.',
            // error messages for other validation rules, if any
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validate the stock data
        if (!$stocks) {
            return redirect()->back()->with('error', 'Stock not found!');
        }

        // Calculate the new quantity
        $existingQuantity = $stocks->quantity;
        $newQuantity = $existingQuantity + $request->input('quantity', 0);

        // Update the stock information
        $stocks->quantity = $newQuantity;
        $stocks->description = $request->input('description');
        $stocks->save();

        return redirect('stocks')->with('flash_message', 'Stocks Updated!');
    }


    public function destroy(string $id)
    {
        Stocks::destroy($id);
        return redirect('stocks')->with('flash_message', 'Stocks deleted!');
    }

    public function cartView()
    {
        $cartItems = CartItem::all();
        return view('stocks.cart')->with('cartItems', $cartItems);
    }

    public function addToCart(Request $request, string $id)
    {
        $stock = Stocks::find($id);

        // Validate the stock data
        if (!$stock) {
            return redirect()->back()->with('error', 'Stock not found!');
        }

        // Check if there is sufficient quantity available
        if ($stock->quantity <= 0) {
            return redirect()->back()->with('error', 'Insufficient stock quantity!');
        }

        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->stock_id = $stock->id;
        $cartItem->quantity = 1; // Assuming the default quantity is 1
        $cartItem->save();

        // Update the stocks quantity
        $stock->quantity -= $cartItem->quantity;
        $stock->save();

        return redirect()->route('stocks.index')->with('success', 'Item added to cart successfully!');

    }
    public function removeFromCart(string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.view')->with('success', 'Item removed from cart successfully!');
    }

    public function clearCart()
    {
        CartItem::truncate();

        return redirect()->route('cart.view')->with('success', 'Cart cleared successfully!');
    }

    public function checkout()
    {
        $cartItems = CartItem::all();

        return view('stocks.checkout', compact('cartItems'));
    }


    public function deployItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_name' => 'required',
            'sender_name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $receiverName = $request->input('receiver_name');
        $senderName = $request->input('sender_name');
        $itemDetails = $request->input('item_details');

        // Store the deployed items in the deployed_items table
        DeployedItem::create([
            'receiver_name' => $receiverName,
            'sender_name' => $senderName,
            'item_details' => $itemDetails,
        ]);

        // Clear the cart
        CartItem::truncate();

        return redirect()->route('stocks.index')->with('success', 'Items deployed successfully!');
    }

    public function showDeployedItems(Request $request)
    {

        // $deployedItems = DeployedItem::orderBy('id', 'desc')->first();

        // $deployedItems = DeployedItem::all();
        // return view('stocks.deployed-items', compact('deployedItems'));

        // $deployedItems = DeployedItem::all()->sortByDesc('id');
        // $lastId = $deployedItems->first()->id;

        // return view('stocks.deployed-items', compact('deployedItems', 'lastId'));
        $deployedItems = DeployedItem::paginate(10); // Retrieve 10 deployed items per page
        $search = $request->input('search');

        $deployedItems = DeployedItem::where(function ($query) use ($search) {
            $query->where('receiver_name', 'like', "%$search%")
                ->orWhere('sender_name', 'like', "%$search%")
                ->orWhere('item_details', 'like', "%$search%");
        })->orderByDesc('id')->get();

        $lastId = $deployedItems->isEmpty() ? null : $deployedItems->first()->id;
        $errorMessage = '';

        if ($deployedItems->isEmpty()) {
            $errorMessage = "No Records found.";
        }

        return view('stocks.deployed-items', compact('deployedItems', 'lastId', 'errorMessage'));

    }


    public function downloadPdf($id)
    {
        // ------ Use this to edit your pdf without downloading ------//

        // $deployedItem = DeployedItem::find($id);

        // $path = public_path(). '\annaplogo.jpg';
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        // $data = file_get_contents($path);
        // $image = 'data:image/'. $type . ';base64,' . base64_encode($data);

        // $currentDate = Carbon::now()->toFormattedDateString();

        // $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true])-> loadview('pdf.deployed-item', compact('deployedItem','currentDate','image'));
        // return $pdf ->stream();

        // if (!$deployedItem) {
        //     return redirect()->route('deployedItems.index')->with('error', 'Deployed item not found.');
        // }





        //--------use this to directly download pdf



        //     $deployedItem = DeployedItem::find($id);

        // if (!$deployedItem) {
        //     return redirect()->route('deployedItems.index')->with('error', 'Deployed item not found.');
        // }

        // $path = public_path('annaplogo.jpg');
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        // $data = file_get_contents($path);
        // $image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // $currentDate = Carbon::now()->toFormattedDateString();

        // $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.deployed-item', compact('deployedItem', 'currentDate', 'image'));

        // $filename = 'deployed-item-' . $id . '.pdf';

        // // Save the PDF to a specific directory
        // $pdf->save(storage_path('app/public/pdf/' . $filename));

        // // Return a download response with the generated PDF
        // return response()->download(storage_path('app/public/pdf/' . $filename))->deleteFileAfterSend(true);


        //----------this has image logic for pdf click to download --------------//
        $deployedItem = DeployedItem::find($id);

        if (!$deployedItem) {
            return redirect()->route('deployedItems.index')->with('error', 'Deployed item not found.');
        }

        $path = public_path() . '/annaplogo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);


        if ($deployedItem->receiver_name == 'Annap 2') {
            $path = public_path() . '/logo.jpg';
            $data = file_get_contents($path);
        }

        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->format('H-i-s');

        $fileName = 'Request-form-' . $currentDate . '-' . $currentTime . '.pdf'; // Customize the file name here

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadview('pdf.deployed-item', compact('deployedItem', 'currentDate', 'image'));

        return $pdf->download($fileName);

    }




}