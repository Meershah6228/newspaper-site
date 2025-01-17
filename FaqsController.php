<?php

namespace App\Http\Controllers;

use App\Models\faqs;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = faqs::all();
        return view("dashboard.admin.faqs.manage", compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.admin.faqs.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',

        ]);


        $data = faqs::create([

            'question' => $request->question,
            'answer' => $request->answer,


        ]);

        if ($data) {
            return redirect()->route('faqs.manage')->with('success', 'Record Inserted Successfully');
        } else {
            return redirect()->route('faqs.manage')->with('error', 'Record Insertion Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $data = $faqs::where('id', $id);
        $faqs  = faqs::findOrFail($id);
        return view("dashboard.admin.faqs.edit", compact('faqs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, faqs $faqs, $id)
    {


        $data = [
            'question' => $request->question,
            'answer' => $request->answer,
        ];

        $update = $faqs->where('id', $id)->update($data);


        // $update = $faqs->where('id', $id)->update($data);
        if ($update) {
            return redirect()->route('faqs.manage')->with('success', 'Record Updated Successfully');
        } else {
            return redirect()->route('faqs.manage')->with('error', 'Record Update Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(faqs $faqs, int $id)
    {
        // $success = $faqs->destroy($id);



        $success = $faqs->destroy($id);

        if ($success) {
            return redirect()->route('faqs.manage')->with('success', 'Record Deleted Successfully');
        }
        return redirect()->route('faqs.manage')->with('error', 'Record Deletion Failed');
    }
}
