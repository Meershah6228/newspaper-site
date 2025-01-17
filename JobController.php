<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Job_Sector;
use App\Models\Job_type;
use App\Models\Qualification;
use App\Models\City;
use App\Models\Province;
use App\Models\Organization;
use App\Models\Newspaper_category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if ($id) {
            $Job = Job::where('cat_id', $id)->get();
            $Newspaper_category = Newspaper_category::where('id', $id)->first();
        } else {
            $Job = Job::all(); // Or some other default behavior
            $Newspaper_category = null; // Or handle this appropriately
        }


        return view("dashboard.admin.Job.index", ['Job' => $Job, 'Newspaper_category' => $Newspaper_category]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // Get all Job
        // $Newspaper_category = Newspaper_category::orderby('title', 'desc')->get();
        $Newspaper_category = Newspaper_category::findOrFail($id);

        $Job_Sector = Job_Sector::orderby('title', 'desc')->get();
        $Qualification = Qualification::orderby('title', 'desc')->get();
        $Job_type = Job_type::orderby('title', 'desc')->get();
        $City = City::orderby('title', 'desc')->get();
        $Province = Province::orderby('title', 'desc')->get();
        $Organization = Organization::orderby('title', 'desc')->get();
        // $category = Newspaper_category::where('id', $id)->first();



        $Job = Job::all();

        return view('dashboard.admin.Job.add', compact('Job', 'Newspaper_category', 'Job_Sector', 'Qualification', 'Job_type', 'City', 'Organization', 'Province'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_desc' => 'nullable|string',
            // 'slug' => 'required|string|unique:Job,slug',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        $data = [
            'title' => $valid['title'],
            'meta_title' => $valid['meta_title'],
            'meta_desc' => $valid['meta_desc'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),
            'cat_id' => $id,
            'sec_id' => $request->sec_id,
            'qual_id' => json_encode($request->qual_id),
            'job_type_id' => $request->job_type_id,
            'province_id' => $request->province_id,
            // 'city_id' => implode(',', (array) $request->city_id), // ensure it's an array.

            'city_id' => json_encode($request->city_id),
            'org_id' => json_encode($request->org_id),
            'content' => $request->content,
            'image' => $imageName,

            'experience' => $request->experience,
            'salary' => $request->salary,
            'job_date' => $request->job_date,
            'expected_last_date' => $request->expected_last_date,



        ];

        // Save data into db
        $Job = Job::create($data);

        if ($Job) {
            return redirect()->route('view.Job_by_category', ['categoryid' => $id])->with('success', 'Record Inserted Successfully');
        } else {
            return redirect()->route('view.Job_by_category', ['categoryid' => $id])->with('error', 'Record Insertion Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $Job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $Job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $Job
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit(Job $Job, $id)
    // {
    //     // Get single Job details
    //     $job = Job::where('id', $id)->first();


    //     $Newspaper_category = Newspaper_category::where('id', $Job->cat_id)->first();
    //     return view("dashboard.admin.Job.edit", ['job' => $job, 'Newspaper_category' => $Newspaper_category]);
    // }
    public function edit(Job $Job, $id)
    {
        // Get single Job details
        $job = Job::where('id', $id)->first();

        // Decode JSON fields
        $job->qual_id = json_decode($job->qual_id);
        $job->city_id = json_decode($job->city_id);
        $job->org_id = json_decode($job->org_id);

        // Get related models
        $Newspaper_category = Newspaper_category::where('id', $job->cat_id)->first();
        $Job_Sector = Job_Sector::orderby('title', 'desc')->get();
        $Qualification = Qualification::orderby('title', 'desc')->get();
        $Job_type = Job_type::orderby('title', 'desc')->get();
        $City = City::orderby('title', 'desc')->get();
        $Province = Province::orderby('title', 'desc')->get();
        $Organization = Organization::orderby('title', 'desc')->get();

        return view("dashboard.admin.Job.edit", compact('job', 'Newspaper_category', 'Job_Sector', 'Qualification', 'Job_type', 'City', 'Organization', 'Province'));
    }
    // public function filterByDate($date)
    // {
    //     $formattedDate = date('Y-m-d', strtotime($date));


    //     $job = Job::where('job_date', $formattedDate)->get();

    //     return view('website_news.jobs', ['job' => $job]);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $Job
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Job $Job, $id)
    // {
    //     // Validate data
    //     $valid = $this->validate($request, [
    //         'title' => 'required|string',
    //         'meta_title' => 'nullable|string',
    //         'meta_desc' => 'nullable|string',
    //         // 'slug' => 'required|string|unique:Job,slug,' . $id,
    //     ]);
    //     $Job = Job::findOrFail($id);

    //     if ($request->hasFile('image')) {
    //         // Delete the old image if it exists
    //         if ($Job->image && file_exists(public_path('images/' . $Job->image))) {
    //             unlink(public_path('images/' . $Job->image));
    //         }

    //         // Upload the new image
    //         $imageName = time() . '.' . $request->image->extension();
    //         $request->image->move(public_path('images'), $imageName);
    //         $Job->image = $imageName;
    //     }

    //     $data = [
    //         'title' => $valid['title'],
    //         'meta_title' => $valid['meta_title'],
    //         'meta_desc' => $valid['meta_desc'],
    //         // 'slug' => $valid['slug'],
    //         'slug' => Str::slug($request->title, '-'),
    //         // 'cat_id' => $id,
    //         'sec_id' => $request->sec_id,
    //         'qual_id' => $request->qual_id,
    //         'job_type_id' => $request->job_type_id,
    //         'city_id' => $request->city_id,
    //         'org_id' => $request->org_id,
    //         'content' => $request->content,
    //         // 'image' => $imageName,
    //         'image' => $Job->image,

    //         'experience' => $request->experience,
    //         'job_date' => $request->job_date,
    //         'expected_last_date' => $request->expected_last_date,


    //     ];

    //     // Update data into db
    //     // $Job = Job::find($id);
    //     // $Job = $Job->update($data);
    //     $update = $Job->where('id', $id)->update($data);

    //     if ($update) {
    //         return redirect()->route('view.Job_by_category', ['categoryid' => $request->cat_id])->with('success', 'Record Updated Successfully');
    //     } else {
    //         return redirect()->route('view.Job_by_category', ['categoryid' => $request->cat_id])->with('error', 'Record Update Failed');
    //     }
    // }
    public function update(Request $request, Job $Job, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_desc' => 'nullable|string',
        ]);

        $Job = Job::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($Job->image && file_exists(public_path('images/' . $Job->image))) {
                unlink(public_path('images/' . $Job->image));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $Job->image = $imageName;
        }

        $data = [
            'title' => $valid['title'],
            'meta_title' => $valid['meta_title'],
            'meta_desc' => $valid['meta_desc'],
            'slug' => Str::slug($request->title, '-'),
            'sec_id' => $request->sec_id,
            'qual_id' => json_encode($request->qual_id),
            'job_type_id' => $request->job_type_id,
            'city_id' => json_encode($request->city_id),
            'org_id' => json_encode($request->org_id),
            'content' => $request->content,
            'image' => $Job->image,
            'experience' => $request->experience,
            'province_id' => $request->province_id,

            'salary' => $request->salary,

            'job_date' => $request->job_date,
            'expected_last_date' => $request->expected_last_date,
        ];

        // Update data in the database
        $update = $Job->where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('view.Job_by_category', ['categoryid' => $request->cat_id])->with('success', 'Record Updated Successfully');
        } else {
            return redirect()->route('view.Job_by_category', ['categoryid' => $request->cat_id])->with('error', 'Record Update Failed');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $Job
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $Job, $id)
    {
        $cproduct = $Job->select('id', 'cat_id', 'image')->where('id', $id)->first();

        if ($cproduct->image && file_exists(public_path('images/' . $cproduct->image))) {
            unlink(public_path('images/' . $cproduct->image));
        }
        $success = $cproduct->delete();
        //Delete user data
        // $result = $Job->destroy($id);
        // $success = $Job->destroy($id);
        if ($success) {
            return redirect()->route('view.Job_by_category', ['categoryid' => $cproduct->cat_id])->with('success', 'Record Deleted Successfully');
        }
        return redirect()->route('view.Job_by_category', ['categoryid' => $cproduct->cat_id])->with('error', 'Record Deletion Failed');
    }
}
