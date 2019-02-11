<?php

use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

//    Route::get('/generate_permissions/{controller}', function (Request $request, $controller) {
////        \Spatie\Permission\Models\Role::create(['name'=>'admin', 'guard_name'=>'api']);
////        $permissions = [];
////        array_push($permissions, \Spatie\Permission\Models\Permission::create(['name'=>$controller, 'guard_name'=>'api']));
////        array_push($permissions, \Spatie\Permission\Models\Permission::create(['name'=>$controller.'.index', 'guard_name'=>'api']));
////        array_push($permissions, \Spatie\Permission\Models\Permission::create(['name'=>$controller.'.create', 'guard_name'=>'api']));
////        array_push($permissions, \Spatie\Permission\Models\Permission::create(['name'=>$controller.'.edit', 'guard_name'=>'api']));
////        array_push($permissions, \Spatie\Permission\Models\Permission::create(['name'=>$controller.'.destroy', 'guard_name'=>'api']));
//        $role = \Spatie\Permission\Models\Role::findByName('admin');
////        $request->user()->assignRole($role);
//        $role->givePermissionTo('tool-users.destroy');
//        return response()->json($role);
//    });
    Route::get('/home', 'Api\HomeController@index');
    Route::resource('roles', 'Api\RoleController');
    Route::resource('sections', 'Api\SectionController');
    Route::resource('users', 'Api\UserController');
    Route::resource('tool-users', 'Api\UserController');
    Route::resource('site-roles', 'Api\SiteRoleController');
    Route::resource('positions', 'Api\PositionController');
});
Route::post('/login', 'Api\AuthController@login');
