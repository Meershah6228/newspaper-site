<?php

namespace App\Http\Controllers;

use App\Models\Job_Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobSectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all Job_Sector
        $Job_Sector = Job_Sector::all();

        return view('dashboard.admin.Job_Sector.index', compact('Job_Sector'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.Job_Sector.add');
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
            // 'slug' => 'required|string|unique:Job_Sector,slug',
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Save data into db
        $Job_Sector = Job_Sector::create($data);

        if ($Job_Sector) {
            return redirect('/admin/Job_Sector')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/Job_Sector')->with('error', 'Record not created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job_Sector  $Job_Sector
     * @return \Illuminate\Http\Response
     */
    public function show(Job_Sector $Job_Sector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job_Sector  $Job_Sector
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Job_Sector $Job_Sector, $id)
    {
        // Get single Job_Sector details
        $Job_Sector = Job_Sector::findOrFail($id);

        return view('dashboard.admin.Job_Sector.edit', compact('Job_Sector'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job_Sector  $Job_Sector
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job_Sector $Job_Sector, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:Job_Sector,slug,' . $id,
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),


        ];

        // Update data into db
        $Job_Sector = Job_Sector::find($id);
        $Job_Sector = $Job_Sector->update($data);

        if ($Job_Sector) {
            return redirect('/admin/Job_Sector')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/Job_Sector')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job_Sector  $Job_Sector
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job_Sector $Job_Sector, $id)
    {
        // Delete Job_Sector
        $Job_Sector = Job_Sector::destroy($id);

        if ($Job_Sector) {
            return redirect('/admin/Job_Sector')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/Job_Sector')->with('error', "Record not deleted!");
        }
    }
}
