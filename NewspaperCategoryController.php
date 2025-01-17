<?php

namespace App\Http\Controllers;

// use App\Models\Newspaper_category;
use App\Models\Newspaper_category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;




class NewspaperCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all Newspaper_category
        $Newspaper_category = Newspaper_category::all();

        return view('dashboard.admin.Newspaper_category.index', compact('Newspaper_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.Newspaper_category.add');
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
            // 'slug' => 'required|string|unique:Newspaper_category,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048', // Added validation for image
        ]);

        $imageName = null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Generate a unique name for the image using time and the original file extension
            $imageName = time() . '.' . $request->image->extension();
            // Move the uploaded image to the 'images' directory within the public folder
            $request->image->move(public_path('images'), $imageName);
        }

        // Prepare data for insertion
        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            'slug' => Str::slug($request->title, '-'), // Generate slug from title
            'image' => $imageName, // Store image name in the database
        ];

        // Save data into the database
        $Newspaper_category = Newspaper_category::create($data);

        // Debug the data array
        // dd($data);

        // Check if the record was successfully created
        if ($Newspaper_category) {
            return redirect('/admin/Newspaper_category')->with('success', 'Record created successfully.');
        } else {
            return redirect('/admin/Newspaper_category')->with('error', 'Record not created!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Newspaper_category  $Newspaper_category
     * @return \Illuminate\Http\Response
     */
    public function show(Newspaper_category $Newspaper_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Newspaper_category  $Newspaper_category
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Newspaper_category $Newspaper_category, $id)
    {
        // Get single Newspaper_category details
        $Newspaper_category = Newspaper_category::findOrFail($id);

        return view('dashboard.admin.Newspaper_category.edit', compact('Newspaper_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Newspaper_category  $Newspaper_category
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Newspaper_category $Newspaper_category, $id)
    {
        // Validate data
        $valid = $this->validate($request, [
            'title' => 'required|string',
            'metatitle' => 'nullable|string',
            'metadiscription' => 'nullable|string',
            // 'slug' => 'required|string|unique:Newspaper_category,slug,' . $id,
        ]);
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($Newspaper_category->image && file_exists(public_path('images/' . $Newspaper_category->image))) {
                unlink(public_path('images/' . $Newspaper_category->image));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $Newspaper_category->image = $imageName;
        }


        $data = [
            'title' => $valid['title'],
            'metatitle' => $valid['metatitle'],
            'metadiscription' => $valid['metadiscription'],
            // 'slug' => $valid['slug'],
            'slug' => Str::slug($request->title, '-'),
            // 'image' => $imageName,
            'image' => $Newspaper_category->image,



        ];

        // Update data into db
        $Newspaper_category = Newspaper_category::find($id);
        $Newspaper_category = $Newspaper_category->update($data);

        if ($Newspaper_category) {
            return redirect('/admin/Newspaper_category')->with('success', 'Record updated successfully.');
        } else {
            return redirect('/admin/Newspaper_category')->with('error', 'Record not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newspaper_category  $Newspaper_category
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newspaper_category $Newspaper_category, $id)
    {
        // Delete Newspaper_category
        $Newspaper_category = $Newspaper_category->select('id', 'image')->where('id', $id)->first();

        if ($Newspaper_category->image && file_exists(public_path('images/' . $Newspaper_category->image))) {
            unlink(public_path('images/' . $Newspaper_category->image));
        }
        // $Newspaper_category = Newspaper_category::destroy($id);

        $Newspaper_category = $Newspaper_category->delete();

        if ($Newspaper_category) {
            return redirect('/admin/Newspaper_category')->with('success', 'Record Deleted Successfully.');
        } else {
            return redirect('/admin/Newspaper_category')->with('error', "Record not deleted!");
        }
    }
}
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index($id)
//     {
//         // Get all Newspaper_category
//         $Newspaper_category = Newspaper_category::all();
//         $sector = Newspaper_category::where('id', $id)->first();


//         return view('dashboard.admin.Newspaper_category.index', compact('Newspaper_category', 'sector'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create($id)
//     {
//         $sector = Newspaper_category::findOrFail($id);


//         return view("dashboard.admin.Newspaper_category.add", compact('sector'));
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request, $id)
//     {
//         // Validate data
//         $valid = $this->validate($request, [
//             'title' => 'required|string',
//             'metatitle' => 'nullable|string',
//             'metadiscription' => 'nullable|string',
//             // 'slug' => 'required|string|unique:Newspaper_category,slug',
//         ]);

//         $data = [
//             'title' => $valid['title'],
//             'Newspaper_category_id' => $id,
//             'metatitle' => $valid['metatitle'],
//             'metadiscription' => $valid['metadiscription'],
//             // 'slug' => $valid['slug'],
//             'slug' => Str::slug($request->title, '-'),

//         ];

//         // Save data into db
//         $Newspaper_category = Newspaper_category::create($data);

//         if ($Newspaper_category) {
//             // return redirect('view.Newspaper_category_by_sector', ['sectorid' => $id])->with('success', 'Record created successfully.');
//             return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $id])
//                 ->with('success', 'Record created successfully.');
//         } else {
//             return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $id])->with('error', 'Record not created!');
//         }
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\Newspaper_category  $Newspaper_category
//      * @return \Illuminate\Http\Response
//      */
//     public function show(Newspaper_category $Newspaper_category)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\Newspaper_category  $Newspaper_category
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(Newspaper_category $Newspaper_category, $id)
//     {
//         // Get single Newspaper_category details
//         // $Newspaper_category = Newspaper_category::findOrFail($id);
//         $Newspaper_category = Newspaper_category::where('id', $id)->first();
//         $Newspaper_category = Newspaper_category::where('id', $Newspaper_category->Newspaper_category_id)->first();



//         return view('dashboard.admin.Newspaper_category.edit', ['Newspaper_category' => $Newspaper_category, 'Newspaper_category' => $Newspaper_category]);
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\Newspaper_category  $Newspaper_category
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, Newspaper_category $Newspaper_category, $id)
//     {
//         // Validate data
//         $valid = $this->validate($request, [
//             'title' => 'required|string',
//             'metatitle' => 'nullable|string',
//             'metadiscription' => 'nullable|string',
//             // 'slug' => 'required|string|unique:Newspaper_category,slug,' . $id,

//         ]);
//         $Newspaper_category = Newspaper_category::findOrFail($id);

//         $data = [
//             'title' => $valid['title'],
//             // 'Newspaper_category_id' => $valid['Newspaper_category_id'],
//             'metatitle' => $valid['metatitle'],

//             'metadiscription' => $valid['metadiscription'],
//             // 'slug' => $valid['slug'],
//             'slug' => Str::slug($request->title, '-'),

//         ];

//         // Update data into db
//         // $Newspaper_category = Newspaper_category::find($id);
//         // $Newspaper_category = $Newspaper_category->update($data);
//         $Newspaper_category = $Newspaper_category->where('id', $id)->update($data);


//         if ($Newspaper_category) {
//             return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $request->Newspaper_category_id])->with('success', 'Record updated successfully.');
//         } else {
//             return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $request->Newspaper_category_id])->with('error', 'Record not updated!');
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\Newspaper_category  $Newspaper_category
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Newspaper_category $Newspaper_category, $id)
//     {
//         // Delete Newspaper_category
//         // $Newspaper_category = Newspaper_category::destroy($id);
//         $Newspaper_category = $Newspaper_category->select('id', 'Newspaper_category_id')->where('id', $id)->first();
//         $success = $cproduct->delete();


//         if ($success) {
//             return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $cproduct->Newspaper_category_id])->with('success', 'Record Deleted Successfully');
//         }
//         return redirect()->route('view.Newspaper_category_by_sector', ['sectorid' => $cproduct->Newspaper_category_id])->with('error', 'Record Deletion Failed');
//     }
// }
