<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductShowResource;
use App\Product;
use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if($request->get('search')) $this->validate($request, [
            'search' => ['string', 'min:4']
        ]);
        $products = Product::where('active', 1);
        if($request->get('store_id')){
            $products->where('store_id', '=', $request->get('store_id'));
        }
        if($request->get('category_id')){
            $products->where('category_id', '=', $request->get('category_id'));
        }

        if($request->get('color_id')){
            $products->join('product_color', 'product_color.product_id', '=', 'products.id')
                ->join('colors', 'colors.id', '=', 'product_color.color_id')
                ->where(function($query) use ($request)
                {
                    $query->where('product_color.color_id','=',$request->get('color_id'));
                    $query->where('colors.id', '=', $request->get('color_id'));
                })->select('products.*');
        }

        if($request->get('brand_id')){
            $products->where('brand_id', '=', $request->get('brand_id'));
        }

        if($request->get('amount_start')){
            $products->where('price', '>=', $request->get('amount_start'));
        }

        if($request->get('amount_end')){
            $products->where('price', '<=', $request->get('amount_end'));
        }

        if($request->get('product_ids')){
            $products->whereIn('id', $request->get('product_ids'));
        }

        if($request->get('section_id')){
            $products->where('section_id', '=', $request->get('section_id'));
        }
        if($request->get('sort_by') == 'by_price'){
            $products->orderBy('price');
        }
        if($request->get('search')){
            $query = trim(trim(preg_replace("/[+\*.\<.\>.\(.\).\".\@]/", " ", $request->get('search')), '-'));
            foreach (explode(" ", $query) as $word)
                if (preg_match("/[-]/", $word))
                    $query = str_replace($word,'"'.$word.'"', $query);
            if($query and substr($query, -1, 1)!='"') $query = $query."* ";
            if($query and substr(explode(" | ", $query)[0], -1, 1)!='"') $query = str_replace(" | ","* ", $query);
            $products->selectRaw("products.*, MATCH(title,body,additional_info) AGAINST(?) as `score`", [$query])
                ->whereRaw("match (title,body,additional_info) against (? in boolean mode)", [$query])
                ->orderByDesc('score');
        }

        return ProductResource::collection($products->paginate(config('app.paginate.products')));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return ProductShowResource
     */
    public function show(Product $product)
    {
        return new ProductShowResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
