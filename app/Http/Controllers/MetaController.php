<?php

namespace App\Http\Controllers;

use App\Meta;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meta = Meta::all();
        return view('admin.meta', compact('meta'));
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
        $a = Meta::find(1);
        $a->value = $request->meta1;
        $a->save();
        $b = Meta::find(2);
        $b->value = $request->meta2;
        $b->save();
        $c = Meta::find(3);
        $c->value = $request->meta3;
        $c->save();
        $d = Meta::find(4);
        $d->value = $request->meta4;
        $d->save();
        if($request->meta5 === 'on'){$shuffle = 1;}else{$shuffle = 0;};
        $e = Meta::find(5);
        $e->value = $shuffle;
        $e->save();

        return redirect('admin')->with(['adminStatus' => 'Meta data updated successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meta  $meta
     * @return \Illuminate\Http\Response
     */
    public function show(Meta $meta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meta  $meta
     * @return \Illuminate\Http\Response
     */
    public function edit(Meta $meta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meta  $meta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meta $meta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meta  $meta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meta $meta)
    {
        //
    }
}
