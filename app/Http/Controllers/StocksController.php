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

        $query = Stocks::query();

        if (!empty($search)) {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $stocks = $query->get();

        $originalIds = Stocks::pluck('id');

        if ($stocks->isEmpty()) {
            $errorMessage = 'No stocks found.';
        } else {
            $errorMessage = null;
        }

        // Get the cart count
        $cartCount = CartItem::sum('quantity');

        return view('stocks.index', compact('stocks', 'errorMessage', 'originalIds', 'cartCount'));
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


    public function show(string $id)
    {
        $stocks = Stocks::find($id);
        return view('stocks.show')->with('stocks', $stocks);
    }


    public function edit(string $id)
    {
        $stocks = Stocks::find($id);
        return view('stocks.edit')->with('stocks', $stocks);
    }


    public function update(Request $request, string $id)
    {
        $stocks = Stocks::find($id);

        $rules = [
            'quantity' => 'required|numeric',

        ];

        $messages = [
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity field must be a number.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        if (!$stocks) {
            return redirect()->back()->with('error', 'Stock not found!');
        }


        $existingQuantity = $stocks->quantity;
        $newQuantity = $existingQuantity + $request->input('quantity', 0);


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

        if (!$stock) {
            return redirect()->back()->with('error', 'Stock not found!');
        }

        if ($stock->quantity <= 0) {
            return redirect()->back()->with('error', 'Insufficient stock quantity!');
        }

        $cartItem = CartItem::where('stock_id', $stock->id)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $cartItem = new CartItem();
            $cartItem->stock_id = $stock->id;
            $cartItem->quantity = 1;
            $cartItem->save();
        }

        $stock->quantity -= 1;
        $stock->save();

        // Update the cart count
        $cartCount = CartItem::sum('quantity');

        return redirect()->route('stocks.index')->with('success', 'Item added to cart successfully!')
            ->with('cartCount', $cartCount);
    }
    public function removeFromCart(string $id)
    {
        $cartItem = CartItem::findOrFail($id);

        $stock = Stocks::find($cartItem->stock_id);

        if ($stock) {
            $stock->quantity += $cartItem->quantity;
            $stock->save();
        }

        $cartItem->delete();

        return redirect()->route('cart.view')->with('success', 'Item removed from cart successfully!');
    }


    public function clearCart()
    {
        $cartItems = CartItem::all();

        foreach ($cartItems as $cartItem) {
            $stock = Stocks::find($cartItem->stock_id);

            if ($stock) {
                $stock->quantity += $cartItem->quantity;
                $stock->save();
            }
        }

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
        $currentDate = Carbon::now(); // Get the current date and time

        DeployedItem::create([
            'receiver_name' => $receiverName,
            'sender_name' => $senderName,
            'item_details' => $itemDetails,
            'created_at' => $currentDate,
        ]);

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
            $path = public_path() . '/logo3.png';
            $data = file_get_contents($path);
        }

        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $currentDate = Carbon::now()->format('Y/m/d');


        $fileName = $deployedItem->receiver_name . '-Request-form-' . $currentDate . '.pdf'; // Customize the file name here

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadview('pdf.deployed-item', compact('deployedItem', 'currentDate', 'image'));

        return $pdf->download($fileName);
    }
    public function downloadReports()
    {
        // Get today's date
        $today = Carbon::today();

        // Retrieve deployed items added today
        $deployedItems = DeployedItem::whereDate('created_at', $today)->get();

        // Set the default image path and type
        $defaultImagePath = public_path() . '/annaplogo.jpg';
        $defaultImageType = pathinfo($defaultImagePath, PATHINFO_EXTENSION);
        $defaultImageData = file_get_contents($defaultImagePath);

        // Generate the HTML table for the report
        $html = '
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                th {
                    background-color: #f5f5f5;
                }
                .logo-container {
                    text-align: center;
                    align-items: center;
                    margin-bottom: 20px;
                }
                .logo-container img {
                    width: 450px;
                    height: 120px;
                }
            </style>
        </head>
        <body>
            <div class="logo-container">
                <img src="' . 'data:image/' . $defaultImageType . ';base64,' . base64_encode($defaultImageData) . '" alt="image">
            </div>
            <h1>Deployed Items Report ' . $today->format('Y-m-d') . '</h1>
            <table>
                <thead>
                    <tr>
                        <th>Receiver Name</th>
                        <th>Deployed By</th>
                        <th>Item Details</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($deployedItems as $item) {
            $html .= '
        <tr>
            <td>' . $item->receiver_name . '</td>
            <td>' . $item->sender_name . '</td>
            <td>' . $item->item_details . '</td>
        </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
    </html>';

        // Configure PDF options
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Create a new DOMPDF instance
        $dompdf = new Dompdf($options);

        // Load the HTML into DOMPDF
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Generate the PDF file name
        $fileName = 'deployed_items_report_' . $today->format('Y-m-d') . '.pdf';

        // Download the PDF file
        $dompdf->stream($fileName, ['Attachment' => true]);
    }

    public function table()
    {
        return view('stocks.table');
    }
}
