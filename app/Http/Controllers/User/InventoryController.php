<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Http;
use Storage;
use Auth;
use App\Models\CloudApi;
use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $userId = Auth::id();
    $inventoryItems = Inventory::with('products')
        ->where('user_id', $userId)
        ->latest()
        ->paginate(20);
    $cloudapis = CloudApi::where('user_id', $userId)
        ->where('status', 1)
        ->latest()
        ->get();

    $totalProducts = Product::whereHas('inventory', function ($query) use ($userId) {
        $query->where('user_id', $userId);
    })->count();

    $inStockProducts = Product::whereHas('inventory', function ($query) use ($userId) {
        $query->where('user_id', $userId);
    })->where('status', 1)->count();

    $outOfStockProducts = Product::whereHas('inventory', function ($query) use ($userId) {
        $query->where('user_id', $userId);
    })->where('status', 0)->count();

    foreach($inventoryItems as $inventoryItem){
        dd($inventoryItem);
    }
    //dd($inventory);

    return view('user.inventory.index', compact('inventoryItems', 'totalProducts', 'inStockProducts', 'outOfStockProducts', 'cloudapis'));
}

 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
    $newCloudApiId = $request->cloudapi;
    $existingCloudApiId = null;
    $inventory = Inventory::where('user_id', Auth::id())->first();

    if ($inventory) {
        $existingCloudApiId = $inventory->cloudapi_id;
    }

    if ($newCloudApiId !== $existingCloudApiId) {
        $cloudapi = CloudApi::where('user_id', Auth::id())->findOrFail($newCloudApiId);

        $inventory = Inventory::create([
            'user_id' => Auth::id(),
            'cloudapi_id' => $cloudapi->id,
        ]);
    }

        $product = new Product();
        $product->inventory_id = $inventory->id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->status = $request->status;
        $product->save();

        return response()->json([
            'message'  => __('Product Created Successfully'),
            'redirect' => route('user.inventory.index')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventory->update($request->all());
        return response()->json($inventory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory->delete();
        return response()->json(null, 204);
    }
}
