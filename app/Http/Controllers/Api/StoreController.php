<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreResource;
use App\Http\Resources\StoreShowResource;
use App\Http\Resources\StoreTypeResource;
use App\Store;
use App\StoreType;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic as Image;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'create', 'update', 'edit', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $stores = Store::where('active', '=', 1);
        if($request->get('type_id')){
            $stores->join('store_type', 'store_type.store_id', '=', 'stores.id')
                ->join('store_types', 'store_types.id', '=', 'store_type.type_id')
                ->where(function($query) use ($request)
                {
                    $query->where('store_type.type_id','=',$request->get('type_id'));
                    $query->where('store_types.id', '=', $request->get('type_id'));
                });
            if(!$request->get('search')) $stores->select('stores.*');
        }
        if($request->has('search')){
            $query = trim(trim(preg_replace("/[+\*.\<.\>.\(.\).\".\@]/", " ", $request->get('search')), '-'));
            foreach (explode(" ", $query) as $word)
                if (preg_match("/[-]/", $word))
                    $query = str_replace($word,'"'.$word.'"', $query);
            if($query and substr($query, -1, 1)!='"') $query = $query."* ";
            if($query and substr(explode(" | ", $query)[0], -1, 1)!='"') $query = str_replace(" | ","* ", $query);
            $stores->selectRaw("stores.*,MATCH(stores.name,stores.slug) AGAINST(?) as `score`", [$query])
                ->whereRaw("match (stores.name,stores.slug) against (? in boolean mode)", [$query])
                ->orderByDesc('score');
        }
        return StoreResource::collection($stores->paginate(config('app.paginate.stores')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function create()
    {
        return StoreTypeResource::collection(StoreType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return StoreResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:stores,slug'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $description = [
            "kg"=>"",
            "ru"=>"",
            "en"=>""
        ];
        $about = [
            "kg"=>"",
            "ru"=>"",
            "en"=>""
        ];
        $iconImage = null;
        $backgroundImage = null;
        $folderPath = 'stores/' . date('FY', strtotime(date('d.m.Y'))) . '/';
        if ($request->hasFile('icon')) {
            $image      = $request->file('icon');
            $fileName   = date('Ymdhis') . time() . 'i.' . explode('/', $image->getMimeType())[1];

            $img = Image::make($image->getRealPath());
            $img->resize(180, 180, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point
            $iconImage = $folderPath.$fileName;
            Storage::disk('public')->put($iconImage, $img, 'public');
        }
        if ($request->hasFile('background_image')){
            $image      = $request->file('background_image');
            $fileName   = date('Ymdhis') . time() . 'b.' . explode('/', $image->getMimeType())[1];

            $img = Image::make($image->getRealPath());
            $img->resize(1800, 1800, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
            $img->stream(); // <-- Key point
            $backgroundImage = $folderPath.$fileName;
            Storage::disk('public')->put($backgroundImage, $img, 'public');
        }
        $description[app()->getLocale()] = $request->get('description');
        $about[app()->getLocale()] = $request->get('about');
        $store = new Store();
        $store->name = $request->get('name');
        $store->slug = $request->get('slug');
        $store->description = $description;
        $store->about = $about;
        $store->contacts = json_decode($request->get('contacts'));
        $store->address = $request->get('address');
        $store->address_map = json_decode($request->get('address_map'));
        $store->user_id = auth()->id();
        $store->icon = $iconImage;
        $store->background_image = $backgroundImage;
        $store->save();
        $store->storeTypes()->attach(json_decode($request->get('types')));
        $store->users()->attach([auth()->id()]);
        return new StoreResource($store);
    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \App\Http\Resources\StoreShowResource
     */
    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if(!$store) return abort(404);
        return new StoreShowResource($store);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param $slug
     * @return StoreShowResource
     */
    public function edit(Request $request, $slug)
    {
        /* @var $store Store */
        $store = Store::where('slug', $slug)->first();
        if(!$store) return abort(404);
        if(!$request->user()->stores->contains($store->id)) return abort(403);
        $storeRes = new StoreShowResource($store);
        return $storeRes->additional([
            'storeTypes'=>StoreTypeResource::collection(StoreType::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $slug
     * @return StoreResource
     */
    public function update(Request $request, $slug)
    {
        /* @var $store Store
         */
        $store = Store::where('slug', $slug)->first();
        if(!$store) return abort(404);
        if(!$request->user()->stores->contains($store->id)) return abort(403);
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:stores,slug,' . $store->id],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $description = $store->description;
        $about = $store->about;
        $iconImage = null;
        $backgroundImage = null;
        $folderPath = 'stores/' . date('FY', strtotime(date('d.m.Y'))) . '/';
        if ($request->hasFile('icon')) {
            if($store->icon) Storage::disk('public')->delete($store->icon);
            $image      = $request->file('icon');
            $fileName   = date('Ymdhis') . time() . 'i.' . explode('/', $image->getMimeType())[1];

            $img = Image::make($image->getRealPath());
            $img->resize(180, 180, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point
            $iconImage = $folderPath.$fileName;
            Storage::disk('public')->put($iconImage, $img, 'public');
            $store->icon = $iconImage;
        }
        if ($request->hasFile('background_image')){
            if($store->background_image) Storage::disk('public')->delete($store->background_image);
            $image      = $request->file('background_image');
            $fileName   = date('Ymdhis') . time() . 'b.' . explode('/', $image->getMimeType())[1];

            $img = Image::make($image->getRealPath());
            $img->resize(1800, 1800, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
            $img->stream(); // <-- Key point
            $backgroundImage = $folderPath.$fileName;
            Storage::disk('public')->put($backgroundImage, $img, 'public');
            $store->background_image = $backgroundImage;
        }
        $description[app()->getLocale()] = $request->get('description');
        $about[app()->getLocale()] = $request->get('about');
        $store->name = $request->get('name');
        $store->slug = $request->get('slug');
        $store->description = $description;
        $store->about = $about;
        $store->contacts = json_decode($request->get('contacts'));
        $store->address = $request->get('address');
        $store->address_map = json_decode($request->get('address_map'));
        $store->user_id = auth()->id();
        $store->save();
        $store->storeTypes()->sync(json_decode($request->get('types')));
        $store->users()->sync([auth()->id()]);
        return new StoreResource($store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slug)
    {
        /* @var $store Store */
        $store = Store::where('slug', $slug)->first();
        if(!$store) return abort(404);
        if(!$request->user()->stores->contains($store->id)) return abort(403);
        $store->products()->delete();
        $store->delete();
        if($store->background_image) Storage::disk('public')->delete($store->background_image);
        if($store->icon) Storage::disk('public')->delete($store->icon);
        return response()->json($store);
    }
}
