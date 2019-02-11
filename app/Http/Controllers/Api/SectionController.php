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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        foreach (Section::all() as $section){
//            $menuItem = new SectionItem();
//            $menuItem->section_id = $section->id;
//            $menuItem->name = 'Обзор';
//            $menuItem->route = $section->link.'.index';
//            $menuItem->icon = null;
//            $menuItem->save();
//
//            $menuItem = new SectionItem();
//            $menuItem->section_id = $section->id;
//            $menuItem->name = 'Создать';
//            $menuItem->route = $section->link.'.store';
//            $menuItem->icon = null;
//            $menuItem->save();
//
//            $menuItem = new SectionItem();
//            $menuItem->section_id = $section->id;
//            $menuItem->name = 'Редактировать';
//            $menuItem->route = $section->link.'.update';
//            $menuItem->icon = null;
//            $menuItem->save();
//
//            $menuItem = new SectionItem();
//            $menuItem->section_id = $section->id;
//            $menuItem->name = 'Показать';
//            $menuItem->route = $section->link.'.show';
//            $menuItem->icon = null;
//            $menuItem->save();
//
//            $menuItem = new SectionItem();
//            $menuItem->section_id = $section->id;
//            $menuItem->name = 'Удалить';
//            $menuItem->route = $section->link.'.destroy';
//            $menuItem->icon = null;
//            $menuItem->save();
//        }
        return response()->json('ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
