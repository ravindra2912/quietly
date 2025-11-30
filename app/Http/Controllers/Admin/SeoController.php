<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Seo;

class SeoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Seo::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('admin.seo.destroy', $row->id);
                    $url = "'" . $url . "'";
                    return ' <div class="text-center">
                    <a href="' . route('admin.seo.edit', $row->id) . '" class="btn btn-outline-primary btn-sm" title="edit"><i class="far fa-edit"></i></a>
                    <button onclick="destroy(' . $url . ', ' . $row->id . ')" class="btn btn-outline-danger btn-sm btn_delete-' . $row->id . '" title="Delete">
                        <i id="buttonText" class="far fa-trash-alt"></i>
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.seo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.seo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = Route('admin.seo.index');
        $data = array();

        try {
            $rules = [
                'site_url' => 'required',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'meta_keywords' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { // Validation fails
                $message = $validator->errors();
                // $message = $validator->errors()->first();
            } else {

                $insert = new Seo();
                $insert->site_url = rtrim($request->site_url, '/');
                $insert->meta_title = $request->meta_title;
                $insert->meta_description = $request->meta_description;
                $insert->meta_keywords = $request->meta_keywords;
                $insert->save();

                Cache::forget('seo-' . $insert->site_url); // Clear Cache

                $success = true;
                $message = 'Seo add successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $seo = Seo::find($id);
        return view('admin.seo.edit', compact('seo'));
    }

    public function update(Request $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = Route('admin.seo.index');
        $data = array();

        try {
            $rules = [
                'site_url' => 'required',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'meta_keywords' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { // Validation fails
                $message = $validator->errors();
                // $message = $validator->errors()->first();
            } else {

                $update = Seo::find($id);
                $update->site_url = rtrim($request->site_url, '/');
                $update->meta_title = $request->meta_title;
                $update->meta_description = $request->meta_description;
                $update->meta_keywords = $request->meta_keywords;
                $update->save();

                Cache::forget('seo-' . $update->site_url); // Clear Cache

                $success = true;
                $message = 'Seo updated successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.seo.index');
        $data = array();

        try {
            $delete = Seo::find($id);
            if ($delete) {
                Cache::forget('seo-' . $delete->site_url); // Clear Cache
                $delete->delete();
                $success = true;
                $message = 'Seo deleted successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
