<?php

namespace App\Http\Controllers;

use App\Models\Job_type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all job_type
        $job_type = job_type::all();

        return view('dashboard.admin.job_type.index', compact('job_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.job_type.add');
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
            // 'slug' => 'required|string|unique:job_type,slug',
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Save data into db
        $job_type = job_type::create($data);

        if ($job_type) {
            return redirect('/admin/job_type')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/job_type')->with('error', 'Record not created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\job_type  $job_type
     * @return \Illuminate\Http\Response
     */
    public function show(job_type $job_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\job_type  $job_type
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(job_type $job_type, $id)
    {
        // Get single job_type details
        $job_type = job_type::findOrFail($id);

        return view('dashboard.admin.job_type.edit', compact('job_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\job_type  $job_type
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, job_type $job_type, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:job_type,slug,' . $id,

        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Update data into db
        $job_type = job_type::find($id);
        $job_type = $job_type->update($data);

        if ($job_type) {
            return redirect('/admin/job_type')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/job_type')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\job_type  $job_type
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(job_type $job_type, $id)
    {
        // Delete job_type
        $job_type = job_type::destroy($id);

        if ($job_type) {
            return redirect('/admin/job_type')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/job_type')->with('error', "Record not deleted!");
        }
    }
}
