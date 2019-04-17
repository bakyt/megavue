<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SectionResource;
use App\Http\Resources\SectionShowResource;
use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SectionResource::collection(Section::whereRaw('active=1')->whereNull('parent_id')->orderBy('order')->get());
    }

    /**
     * @param $id
     * @return SectionShowResource
     */
    public function show($id){
        if($id == 'all') return response()->json([
            'categories'=>[],
            'children' => SectionShowResource::collection(Section::where('parent_id', '=', null)->get())
        ]);
        return new SectionShowResource(Section::findOrFail($id));
    }
}
