<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherApp;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class OtherAppController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = OtherApp::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . getImage($row->image) . '" alt="' . $row->name . '" class="table-img" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '';
                })
                ->addColumn('status', function ($row) {
                    $badgeClass = $row->status == 'active' ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.other-apps.destroy', $row->id);
                    $url = "'" . $url . "'";
                    return ' <div class="text-center">
                    <a href="' . route('admin.other-apps.edit', $row->id) . '" class="btn btn-outline-primary btn-sm" title="edit"><i class="bi bi-pencil-fill"></i></a>
                    <button onclick="destroy(' . $url . ', ' . $row->id . ')" class="btn btn-outline-danger btn-sm btn_delete-' . $row->id . '" title="Delete">
                        <i id="buttonText" class="bi bi-trash-fill"></i>
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('admin.other_apps.index');
    }

    public function create()
    {
        return view('admin.other_apps.create');
    }

    public function store(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.other-apps.index');
        $data = array();

        try {
            $rules = [
                'name' => 'required',
                'status' => 'required|in:active,in-active',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $insert = new OtherApp();
                $insert->name = $request->name;
                $insert->status = $request->status;

                if ($request->hasFile('image')) {
                    $image_name = fileUploadStorage($request->file('image'), 'other_apps', 500, 500);
                    $insert->image = $image_name;
                }

                $insert->save();

                $success = true;
                $message = 'Other App added successfully.';
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
        $otherApp = OtherApp::find($id);
        return view('admin.other_apps.edit', compact('otherApp'));
    }

    public function update(Request $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.other-apps.index');
        $data = array();

        try {
            $rules = [
                'name' => 'required',
                'status' => 'required|in:active,in-active',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $update = OtherApp::find($id);
                $update->name = $request->name;
                $update->status = $request->status;

                if ($request->hasFile('image')) {
                    $oldimage = $update->image;
                    $image_name = fileUploadStorage($request->file('image'), 'other_apps', 500, 500);
                    $update->image = $image_name;
                }

                $update->save();

                if (isset($oldimage) && isset($image_name)) {
                    fileRemoveStorage($oldimage);
                }

                $success = true;
                $message = 'Other App updated successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (isset($image_name) && !empty($image_name)) {
                fileRemoveStorage($image_name);
            }
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function destroy($id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.other-apps.index');
        $data = array();

        try {
            $delete = OtherApp::find($id);
            if ($delete) {
                if ($delete->image) {
                    fileRemoveStorage($delete->image);
                }
                $delete->delete();
                $success = true;
                $message = 'Other App deleted successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
