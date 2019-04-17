<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductShowResource;
use App\Order;
use App\OrderItem;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $orders = Order::select('*');
        if($request->get('store_id')) $orders->where('store_id', '=', $request->get('store_id'));
        if($request->get('user_id')) $orders->where('user_id', '=', $request->get('user_id'));
        return OrderResource::collection($orders->orderByDesc('id')->paginate(config('app.paginate.orders')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'stores' => ['required'],
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required']
        ]);
        foreach ($request->get('stores') as $store){
            $total = 0;
            $order = new Order;
            $order->name = $request->get('name');
            $order->phone = $request->get('phone');
            $order->address = $request->get('address');
            $order->store_id = $store['id'];
            $order->save();
            if(auth()->check()) $order->user_id = auth()->id();
            foreach ($store['items'] as $item){
                $orderItem = new OrderItem;
                $orderItem->product_id = $item['id'];
                $product = Product::find($item['id']);
                if(!$product) continue;
                if($item['color']) $orderItem->color_id = $item['color']['id'];
                $orderItem->quantity = $item['quantity']?:1;
                if($sale = $product->sale()) {
                    $orderItem->sale = $sale;
                    $total += $orderItem->total = $orderItem->quantity*($product->price/100*(100-$orderItem->sale));
                }
                else $total += $orderItem->total = $orderItem->quantity*$product->price;
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }
            $order->total_price = $total;
            $order->update();
        }
        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
