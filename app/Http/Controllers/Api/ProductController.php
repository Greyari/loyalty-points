<?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Product;
// use Illuminate\Http\Request;
// use Illuminate\Validation\ValidationException;

// class ProductController extends Controller
// {
//     public function index()
//     {
//         return response()->json(Product::latest()->paginate(5));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'sku'  => 'required|unique:products,sku',
//             'name' => 'required|string|max:255',
//             'price'=> 'required|numeric|min:0',
//             'description' => 'nullable|string'
//         ]);

//         $product = Product::create($request->all());

//         return response()->json($product, 201);
//     }

//     public function show($id)
//     {
//         return response()->json(Product::findOrFail($id));
//     }

//     public function update(Request $request, $id)
//     {
//         $product = Product::findOrFail($id);

//         $request->validate([
//             'sku'  => 'sometimes|unique:products,sku,' . $id,
//             'name' => 'sometimes|string|max:255',
//             'price'=> 'sometimes|numeric|min:0',
//             'description' => 'nullable|string'
//         ]);

//         $product->update($request->all());

//         return response()->json($product);
//     }

//     public function destroy($id)
//     {
//         Product::findOrFail($id)->delete();

//         return response()->json(['message' => 'Product deleted']);
//     }
// }
