<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all organization
        $organization = Organization::all();

        return view('dashboard.admin.organization.index', compact('organization'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.organization.add');
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
            // 'slug' => 'required|string|unique:organization,slug',
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),

        ];

        // Save data into db
        $Organization = Organization::create($data);

        if ($Organization) {
            return redirect('/admin/organization')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/organization')->with('error', 'Record not created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $Organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $Organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $Organization
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $Organization, $id)
    {
        // Get single Organization details
        $Organization = Organization::findOrFail($id);

        return view('dashboard.admin.organization.edit', compact('Organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $Organization
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $Organization, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:organization,slug,' . $id,
        ]);

        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),


        ];

        // Update data into db
        $Organization = Organization::find($id);
        $Organization = $Organization->update($data);

        if ($Organization) {
            return redirect('/admin/organization')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/organization')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $Organization
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $Organization, $id)
    {
        // Delete Organization
        $Organization = Organization::destroy($id);

        if ($Organization) {
            return redirect('/admin/organization')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/organization')->with('error', "Record not deleted!");
        }
    }
}
