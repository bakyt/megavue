<?php

namespace App\Http\Controllers\Api;

use App\Section;
use App\SectionItem;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return response()->json(Section::getByUserPermissions($request->user()->getAllPermissions()));
    }
}
