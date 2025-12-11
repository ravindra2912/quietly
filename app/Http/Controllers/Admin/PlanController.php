<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Plan::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('is_ad_free', function ($row) {
                    return $row->is_ad_free ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                })
                ->addColumn('is_active_multiple_group', function ($row) {
                    return $row->is_active_multiple_group ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">In-Active</span>';
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.plan.destroy', $row->id);
                    $url = "'" . $url . "'";
                    return ' <div class="text-center">
                    <a href="' . route('admin.plan.edit', $row->id) . '" class="btn btn-outline-primary btn-sm" title="edit"><i class="far fa-edit"></i></a>
                    <button onclick="destroy(' . $url . ', ' . $row->id . ')" class="btn btn-outline-danger btn-sm btn_delete-' . $row->id . '" title="Delete">
                        <i id="buttonText" class="far fa-trash-alt"></i>
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    </div>';
                })
                ->rawColumns(['is_ad_free', 'is_active_multiple_group', 'status', 'action'])
                ->make(true);
        }
        return view('admin.plan.index');
    }

    public function create()
    {
        return view('admin.plan.create');
    }

    public function store(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.plan.index');
        $data = array();

        try {
            $rules = [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'duration_in_month' => 'required|integer|min:1',
                'is_ad_free' => 'required|boolean',
                'group_active_timing' => 'required|string',
                'is_active_multiple_group' => 'required|boolean',
                'plan_purchase_limit_per_user' => 'required|in:unlimit,limited',
                'plan_purchase_limit' => 'nullable|integer|min:1',
                'status' => 'required|in:active,in-active',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $insert = new Plan();
                $insert->name = $request->name;
                $insert->price = $request->price;
                $insert->duration_in_month = $request->duration_in_month;
                $insert->is_ad_free = $request->is_ad_free;
                $insert->group_active_timing = $request->group_active_timing;
                $insert->is_active_multiple_group = $request->is_active_multiple_group;
                $insert->plan_purchase_limit_per_user = $request->plan_purchase_limit_per_user;
                $insert->plan_purchase_limit = $request->plan_purchase_limit;
                $insert->status = $request->status;
                $insert->save();

                $success = true;
                $message = 'Plan added successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function edit($id)
    {
        $plan = Plan::find($id);
        return view('admin.plan.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.plan.index');
        $data = array();

        try {
            $rules = [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'duration_in_month' => 'required|integer|min:1',
                'is_ad_free' => 'required|boolean',
                'group_active_timing' => 'required|string',
                'is_active_multiple_group' => 'required|boolean',
                'plan_purchase_limit_per_user' => 'required|in:unlimit,limited',
                'plan_purchase_limit' => 'nullable|integer|min:1',
                'status' => 'required|in:active,in-active',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $update = Plan::find($id);
                $update->name = $request->name;
                $update->price = $request->price;
                $update->duration_in_month = $request->duration_in_month;
                $update->is_ad_free = $request->is_ad_free;
                $update->group_active_timing = $request->group_active_timing;
                $update->is_active_multiple_group = $request->is_active_multiple_group;
                $update->plan_purchase_limit_per_user = $request->plan_purchase_limit_per_user;
                $update->plan_purchase_limit = $request->plan_purchase_limit;
                $update->status = $request->status;
                $update->save();

                $success = true;
                $message = 'Plan updated successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function destroy($id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.plan.index');
        $data = array();

        try {
            $delete = Plan::find($id);
            if ($delete) {
                $delete->delete();
                $success = true;
                $message = 'Plan deleted successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
