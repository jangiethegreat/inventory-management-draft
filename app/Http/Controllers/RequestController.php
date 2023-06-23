<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('requests.create', compact('categories'));
    }

    public function store(HttpRequest $request)
    {
        $requestData = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
        ]);

        Request::create($requestData);

        return redirect()->route('requests.create')->with('success', 'Request submitted successfully.');
    }
}