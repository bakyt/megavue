<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreTypeResource;
use App\StoreType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if(!cache()->has('store_types')){
            $storeTypes = StoreType::all();
            cache()->forever('store_types', $storeTypes);
        }
        else $storeTypes = cache('store_types');
        return StoreTypeResource::collection($storeTypes);
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
     * @param  \App\StoreType  $storeType
     * @return \Illuminate\Http\Response
     */
    public function show(StoreType $storeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StoreType  $storeType
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreType $storeType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StoreType  $storeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreType $storeType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StoreType  $storeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreType $storeType)
    {
        //
    }
}
