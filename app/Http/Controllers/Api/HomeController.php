<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Role;
use App\Supplier;
use App\SupplierOrder;
use App\Thing;
use App\User;
use App\Color;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return response()->json([
            'users'=>['route'=>'users.index', 'name'=>'Пользователи', 'count'=>User::all()->count()]
        ]);
    }
}
