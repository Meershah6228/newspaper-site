<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all Province
        $Province = Province::all();

        return view('dashboard.admin.Province.index', compact('Province'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.Province.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:Province,slug',
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Save data into db
        $Province = Province::create($data);

        if ($Province) {
            return redirect('/admin/Province')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/Province')->with('error', 'Record not created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Province  $Province
     * @return \Illuminate\Http\Response
     */
    public function show(Province $Province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Province  $Province
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $Province, $id)
    {
        // Get single Province details
        $Province = Province::findOrFail($id);

        return view('dashboard.admin.Province.edit', compact('Province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Province  $Province
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $Province, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:Province,slug,' . $id,

        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Update data into db
        $Province = Province::find($id);
        $Province = $Province->update($data);

        if ($Province) {
            return redirect('/admin/Province')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/Province')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Province  $Province
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $Province, $id)
    {
        // Delete Province
        $Province = Province::destroy($id);

        if ($Province) {
            return redirect('/admin/Province')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/Province')->with('error', "Record not deleted!");
        }
    }
}
