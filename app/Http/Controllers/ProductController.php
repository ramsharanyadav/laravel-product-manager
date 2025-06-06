<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $file = "products.json";

    public function index()
    {
        return view('products');
    }

    public function list() 
    {
        $data = json_decode(Storage::get($this->file), true) ?? [];

        usort($data, fn($a, $b) => strtotime($a['datetime']) <=> strtotime($b['datetime']));

        $total = 0;
        $rows = '';

        if(!empty($data)) {
            foreach($data as $key => $item) {
                $value = $item['quantity'] * $item['price'];
                $total += $value;

                $editUrl = route('product_edit', ['index' => $key]);
                $deleteUrl = route('product_delete', ['index' => $key]);

                $rows .= "<tr>
                        <td>". ($key + 1)."</td>
                        <td>{$item['product_name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['price']}</td>
                        <td>{$item['datetime']}</td>
                        <td>" . number_format($value, 2) . "</td>
                        <td>
                            <button class='btn btn-sm btn-warning edit-btn' data-url='{$editUrl}' data-index='{$key}' data-name='{$item['product_name']}' data-quantity='{$item['quantity']}' data-price='{$item['price']}'>Edit</button>
                            <button class='btn btn-sm btn-danger delete-btn' data-url='{$deleteUrl}' data-index='{$key}'>Delete</button>
                        </td>
                </tr>";
            }

            $rows .= "<tr class='fw-bold'><td colspan='5' class='text-end'>Total</td><td colspan='2'>" . number_format($total, 2) . "</td></tr>";

            return response("<table class='mt-4 table table-bordered table-striped shadow'>
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Datetime</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>$rows</tbody>
                                    </table>");
        }
       
    }

    public function store(Request $request) 
    {
        $validator = validator::make($request->all(), [
           'product_name' => 'required|string|min:3|max:255',
           'quantity' => 'required|integer|min:1',
           'price' => 'required|numeric|min:0.01',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = json_decode(Storage::get($this->file), true) ?? [];

        $requestData = [
            'product_name' => $request->input('product_name'),
            'quantity' => (int) $request->input('quantity'),
            'price' => (float) $request->input('price'),
            'datetime' => now()->toDateTimeString()
        ];

        $data[] = $requestData;

        Storage::put($this->file, json_encode($data, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }

    public function edit(Request $request, $index)
    {
        $validator = validator::make($request->all(), [
            'product_name' => 'required|string|min:3|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
        ]);
 
        if($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = json_decode(Storage::get($this->file), true) ?? [];

        if (isset($data[$index])) {
            $data[$index]['name'] = $request->input('product_name');
            $data[$index]['quantity'] = (int) $request->input('quantity');
            $data[$index]['price'] = (float) $request->input('price');
            $data[$index]['datetime'] = now()->toDateTimeString();

            Storage::put($this->file, json_encode($data, JSON_PRETTY_PRINT));
        }

        return response()->json(['success' => true]);
    }

    public function delete(Request $request, $index)
    {
        $data = json_decode(Storage::get($this->file), true) ?? [];

        if (!isset($data[$index])) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        unset($data[$index]);
    
        $data = array_values($data);
    
        Storage::put($this->file, json_encode($data, JSON_PRETTY_PRINT));
    
        return response()->json(['success' => true]);
    }
}
