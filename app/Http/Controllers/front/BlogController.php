<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::where('status', 'active')->latest('published_at')->paginate(9);

        if ($request->ajax()) {
            $view = view('front.blog.data', compact('blogs'))->render();
            return response()->json(['html' => $view]);
        }

        return view('front.blog.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $latestBlogs = Blog::where('id', '!=', $blog->id)->latest('published_at')->take(3)->get();
        return view('front.blog.show', compact('blog', 'latestBlogs'));
    }
}
