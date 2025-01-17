<?php

namespace App\Http\Controllers;


use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all qualification
        $qualification = Qualification::all();

        return view('dashboard.admin.qualification.index', compact('qualification'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.qualification.add');
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
            // 'slug' => 'required|string|unique:qualification,slug',
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Save data into db
        $Qualification = Qualification::create($data);

        if ($Qualification) {
            return redirect('/admin/qualification')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/qualification')->with('error', 'Record not created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Qualification  $Qualification
     * @return \Illuminate\Http\Response
     */
    public function show(Qualification $Qualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Qualification  $Qualification
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Qualification $qualification, $id)
    {
        // Get single Qualification details
        $qualification = Qualification::findOrFail($id);

        return view('dashboard.admin.qualification.edit', compact('qualification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Qualification  $Qualification
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Qualification $qualification, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:qualification,slug,' . $id,

        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Update data into db
        $qualification = Qualification::find($id);
        $qualification = $qualification->update($data);

        if ($qualification) {
            return redirect('/admin/qualification')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/qualification')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Qualification  $Qualification
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Qualification $qualification, $id)
    {
        // Delete Qualification
        $qualification = Qualification::destroy($id);

        if ($qualification) {
            return redirect('/admin/qualification')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/qualification')->with('error', "Record not deleted!");
        }
    }
}
