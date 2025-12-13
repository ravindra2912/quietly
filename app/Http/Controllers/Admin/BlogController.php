<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . getImage($row->image) . '" alt="' . $row->title . '" class="table-img">';
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.blog.destroy', $row->id);
                    $url = "'" . $url . "'";
                    return ' <div class="text-center">
                    <a href="' . route('admin.blog.edit', $row->id) . '" class="btn btn-outline-primary btn-sm" title="edit"><i class="bi bi-pencil-fill"></i></a>
                    <button onclick="destroy(' . $url . ', ' . $row->id . ')" class="btn btn-outline-danger btn-sm btn_delete-' . $row->id . '" title="Delete">
                        <i id="buttonText" class="bi bi-trash-fill"></i>
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.blog.index');
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.blog.index');
        $data = array();

        try {
            $rules = [
                'title' => 'required',
                'content' => 'required',
                'status' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $insert = new Blog();
                $insert->title = $request->title;
                $insert->slug = Str::slug($request->title);
                $insert->short_description = $request->short_description;
                $insert->content = $request->content;
                $insert->status = $request->status;

                if ($request->hasFile('image')) {
                    $image_name = fileUploadStorage($request->file('image'), 'blog_images', 500, 500);
                    $insert->image = $image_name;
                }

                $insert->published_at = now();
                $insert->save();

                $success = true;
                $message = 'Blog added successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (isset($image_name) && !empty($image_name)) {
                fileRemoveStorage($image_name);
            }
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.blog.index');
        $data = array();

        try {
            $rules = [
                'title' => 'required',
                'content' => 'required',
                'status' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $update = Blog::find($id);
                $update->title = $request->title;
                if ($update->title != $request->title) {
                    $update->slug = Str::slug($request->title);
                }
                $update->short_description = $request->short_description;
                $update->content = $request->content;
                $update->status = $request->status;

                if ($request->hasFile('image')) {
                    $oldimage = $update->image;
                    $image_name = fileUploadStorage($request->file('image'), 'blog_images', 500, 500);
                    $update->image = $image_name;
                }

                $update->save();

                if (isset($oldimage) && isset($image_name)) {
                    fileRemoveStorage($oldimage);
                }

                $success = true;
                $message = 'Blog updated successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (isset($image_name) && !empty($image_name)) {
                fileRemoveStorage($image_name);
            }
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    // Handle Summernote image upload
    public function uploadImage(Request $request)
    {
        $url = '';
        if ($request->hasFile('file')) {
            // Store image using existing helper, resize to 800x800
            $image_name = fileUploadStorage($request->file('file'), 'blog_images', 800, 800);
            $url = getImage($image_name);
        }
        return response()->json(['location' => $url]);
    }



    public function destroy($id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.blog.index');
        $data = array();

        try {
            $delete = Blog::find($id);
            if ($delete) {
                if ($delete->image) {
                    fileRemoveStorage($delete->image);
                }

                // Delete images uploaded via Summernote
                $content = $delete->content;
                if (!empty($content)) {
                    $dom = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    libxml_clear_errors();

                    $images = $dom->getElementsByTagName('img');
                    foreach ($images as $img) {
                        $src = $img->getAttribute('src');
                        // Identify our images by folder name 'blog_images/'
                        if (strpos($src, 'blog_images/') !== false) {
                            $path = substr($src, strpos($src, 'blog_images/'));
                            fileRemoveStorage($path);
                        }
                    }
                }

                $delete->delete();
                $success = true;
                $message = 'Blog deleted successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
