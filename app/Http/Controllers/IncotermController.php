<?php

namespace App\Http\Controllers;

use App\Incoterm;
use Illuminate\Http\Request;

class IncotermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('incoterms.index')
            ->withDocs(Incoterm::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('incoterms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Incoterm::create($request->all());
        return redirect('/incoterms');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incoterm  $incoterm
     * @return \Illuminate\Http\Response
     */
    public function show(Incoterm $incoterm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incoterm  $incoterm
     * @return \Illuminate\Http\Response
     */
    public function edit(Incoterm $incoterm)
    {
        return view('incoterms.edit')
            ->withIncoterm($incoterm);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Incoterm  $incoterm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incoterm $incoterm)
    {
        $incoterm->update($request->all());

        alert()->success('Added successfully','Success');

        return redirect('/incoterms');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incoterm  $incoterm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incoterm $incoterm)
    {
        $incoterm->delete();
        alert()->success('Deleted successfully','Success');
        return redirect('/incoterms');
    }
}
