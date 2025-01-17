<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Job;
use App\Models\Newspaper_category;
use App\Models\Qualification;
use App\Models\Organization;
use App\Models\WebSetting;
use App\Models\Job_type;
use App\Models\faqs;
use App\Models\BlogCategory;
use App\Models\BlogArticle;

class SiteController extends Controller
{
    public function index()
    {
        $title = "Welcome to my App";
        return view('pages.index', compact('title'));
    }
    public function faqpage()
    {
        $data = faqs::all();
        $setting = WebSetting::first();
        return view("website_news.contact_us", compact('data', 'setting'));
    }
    public function alljobs()
    {
        $data = Job::all();
        return view("website_news.alljobs", compact('data'));
    }




    public function main()
    {
        // Group jobs by date and limit to the latest 7 per date
        $jobs = Job::with('category')
            ->orderBy('job_date', 'desc')
            ->get()
            ->groupBy(function ($job) {
                return \Carbon\Carbon::parse($job->job_date)->format('d M, Y');
            });

        // Fetch all newspaper categories
        $newspaperCategories = Newspaper_category::all();

        return view('website_news.index', compact('jobs', 'newspaperCategories'));
    }

    public function jobs($cat_id = null, $job_type_id = null)
    {


        $jobQuery = Job::query();

        if ($cat_id == null || $cat_id == 'job-by-type') {
            $Newspaper_category = null;
        } else {
            $jobQuery->where('cat_id', $cat_id);
            $Newspaper_category = Newspaper_category::where('id', $cat_id)->first();
        }


        if ($job_type_id == null || $job_type_id == 'job-by-cat') {
            $Job_type = null;
        } else {
            $jobQuery->where('job_type_id', $job_type_id);
            $Job_type = Job_type::where('id', $job_type_id)->first();
        }

        // Execute the query to get the filtered jobs
        $Job = $jobQuery->get();
        // dd($Job);
        // Pass the results to the view
        return view("website_news.jobs", [
            'Job' => $Job,
            'Newspaper_category' => $Newspaper_category,
            'Job_type' => $Job_type,
        ]);
    }



    // public function showByJobType($job_type_slug)
    // {
    //     // Fetch the job type by slug
    //     $jobType = Job_type::where('slug', $job_type_slug)->firstOrFail();

    //     // Fetch jobs associated with this job type
    //     $job = Job::where('job_type_id', $jobType->id)->with(['organization_titles', 'city_titles'])->get();

    //     // Return the view with jobs and the job type
    //     return view('website_news.jobs', compact('job', 'jobType'));
    // }


    public function incity($city_slug = null)
    {
        if ($city_slug) {
            $city = City::where('slug', $city_slug)->first();

            if ($city) {

                $Job = Job::where('city_id', 'like', '%' . $city->id . '%')->get();
            } else {
                $Job = collect(); // Empty collection if city is not found
            }
        } else {
            $Job = Job::all(); // Default behavior if no city is selected
        }

        // dd($Job);

        return view("website_news.jobs", ['Job' => $Job]);
    }

    public function inqual($qual_slug = null)
    {
        if ($qual_slug) {
            $qual = Qualification::where('slug', $qual_slug)->first();

            if ($qual) {

                $Job = Job::where('qual_id', 'like', '%' . $qual->id . '%')->get();
            } else {
                $Job = collect(); // Empty collection if qual is not found
            }
        } else {
            $Job = Job::all(); // Default behavior if no qual is selected
        }

        // dd($Job);

        return view("website_news.jobs", ['Job' => $Job]);
    }

    public function filterByDate($date)
    {
        $formattedDate = date('Y-m-d', strtotime($date));


        $Job = Job::where('job_date', $formattedDate)->get();
        // dd($job);

        return view('website_news.jobs', ['Job' => $Job]);
    }

    public function show($p_slug = '')
    {
        $job = Job::where('slug', $p_slug)->first();
        // $data = Job::all();
        return view('website_news.show_detail', ['job' => $job]);
    }
    // public function showJobs($city_slug)
    // {
    //     $city = City::where('slug', $city_slug)->firstOrFail();

    //     // Retrieve jobs related to the city using the city_id field
    //     $job = Job::all()->filter(function ($job) use ($city) {
    //         $cityIds = json_decode($job->city_id, true);

    //         return is_array($cityIds) && in_array($city->id, $cityIds);
    //     });

    //     return view('website_news.jobs', ['city' => $city, 'job' => $job]);
    // }
    public function product_blog($acat_slug = '')
    {
        if (isset($acat_slug) && !empty($acat_slug)) {
            $cat = BlogCategory::where('slug', $acat_slug)->first();
            $cat_id = $cat->id;
            $article = BlogArticle::where('category_id', $cat_id)->get();
        } else {
            $article = BlogArticle::all();
        }
        $ac_data = BlogCategory::all();

        // dd($BlogArticle);
        return view('website_news.blog', ['article' => $article, 'ac_data' => $ac_data]);
    }


    public function blog_show($b_slug = '')
    {

        $article = BlogArticle::where('slug', $b_slug)->first();
        $ac_data = BlogCategory::all();
        // $article = BlogArticle::first();
        $latest_articles = BlogArticle::orderBy('created_at', 'desc')->take(3)->get();

        return view('website_news.show_blog', ['article' => $article, 'ac_data' => $ac_data, 'latest_articles' => $latest_articles]);
    }





    public function getcity(Request $request)
    {
        $cities = [];
        $cities = City::query()
            ->where('title', 'LIKE', "%{$request->name}%")
            ->get();

        return response()->json($cities);
    }
    public function getqualification(Request $request)
    {
        $qualifications = Qualification::query()
            ->where('title', 'LIKE', "%{$request->name}%")
            ->get();

        return response()->json($qualifications);
    }

    public function getorganization(Request $request)
    {
        $organizations = Organization::query()
            ->where('title', 'LIKE', "%{$request->name}%")
            ->get();

        return response()->json($organizations);
    }

    public function search(Request $request)
    {
        // $query = $request->input('q');

        // // Search for jobs by title
        // $data = Job::where('title', 'LIKE', "%{$query}%")->get();
        $query = $request->input('q');

        // Search for jobs by title or related city titles
        $data = Job::where('title', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%");
            })
            ->get();
        return view('website_news.alljobs', compact('data', 'query'));
    }
}
