<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
 /**
 * GET /api/v1/products
 * Products list for Cashier POS add-product modal.
 */
 public function index(Request $request): JsonResponse
 {
 $query = Product::active()
 ->with('category:id,name,code');

 if ($search = $request->get('search')) {
 $query->search($search);
 }

 if ($categoryId = $request->get('category_id')) {
 $query->where('category_id', $categoryId);
 }

 $products = $query
 ->orderBy('name')
 ->limit($request->get('limit', 100))
 ->get();

 return response()->json([
 'data' => $products->map(fn($p) => [
 'id' => $p->id,
 'name' => $p->name,
 'sku' => $p->sku,
 'selling_price' => $p->selling_price,
 'unit' => $p->unit,
 'category' => $p->category?->name,
 ]),
 ]);
 }
}
