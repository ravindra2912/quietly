<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PlanPurchase, User, Plan};
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PlanPurchaseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PlanPurchase::with(['user', 'plan'])->latest();

            // Apply filters
            if ($request->filled('user_id')) {
                $data->where('user_id', $request->user_id);
            }

            if ($request->filled('plan_id')) {
                $data->where('plan_id', $request->plan_id);
            }

            if ($request->filled('status')) {
                $data->where('status', $request->status);
            }

            // Apply date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $data->whereDate('start_at', '>=', $request->start_date)
                    ->whereDate('start_at', '<=', $request->end_date);
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : 'N/A';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->plan_info['name'] ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $badges = [
                        'active' => 'success',
                        'pending' => 'warning',
                        'in-active' => 'secondary',
                        'expired' => 'danger',
                        'override' => 'info'
                    ];
                    $class = $badges[$row->status] ?? 'secondary';
                    return '<span class="badge badge-' . $class . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('dates', function ($row) {
                    return '<small><strong>Start:</strong> ' . $row->start_at->format('d M Y H:i') . '<br>' .
                        '<strong>Expiry:</strong> ' . $row->expired_at->format('d M Y H:i') . '</small>';
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.plan-purchase.destroy', $row->id);
                    $url = "'" . $url . "'";
                    return ' <div class="text-center">
                    <a href="' . route('admin.plan-purchase.edit', $row->id) . '" class="btn btn-outline-primary btn-sm" title="edit"><i class="far fa-edit"></i></a>
                    <button onclick="destroy(' . $url . ', ' . $row->id . ')" class="btn btn-outline-danger btn-sm btn_delete-' . $row->id . '" title="Delete">
                        <i id="buttonText" class="far fa-trash-alt"></i>
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    </div>';
                })
                ->rawColumns(['status', 'dates', 'action'])
                ->make(true);
        }

        // Pass filter data to view
        $plans = Plan::select('id', 'name')->orderBy('name')->get();

        return view('admin.plan_purchase.index', compact('plans'));
    }

    /**
     * Search users for Select2 AJAX
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('q', '');

        $users = User::select('id', 'first_name', 'last_name')
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            })
            ->orderBy('first_name')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->first_name . ' ' . $user->last_name
                ];
            });

        return response()->json(['results' => $users]);
    }

    public function create()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email')->where('status', 'active')->get();
        $plans = Plan::where('status', 'active')->get();
        return view('admin.plan_purchase.create', compact('users', 'plans'));
    }

    public function store(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.plan-purchase.index');
        $data = array();

        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'plan_id' => 'required|exists:plans,id',
                'start_at' => 'required|date',
                'status' => 'required|in:pending,active,in-active,expired,override',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $plan = Plan::find($request->plan_id);

                $insert = new PlanPurchase();
                $insert->user_id = $request->user_id;
                $insert->plan_id = $request->plan_id;
                $insert->duration_in_month = $plan->duration_in_month;
                $insert->start_at = $request->start_at;
                $insert->expired_at = \Carbon\Carbon::parse($request->start_at)->addMonths($plan->duration_in_month);
                $insert->price = $plan->price;
                $insert->plan_info = $plan->toArray();
                $insert->payment_details = ['source' => 'admin', 'created_by' => auth()->user()->email];
                $insert->status = $request->status;
                $insert->save();

                $success = true;
                $message = 'Plan purchase added successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function edit($id)
    {
        $planPurchase = PlanPurchase::with(['user', 'plan'])->find($id);
        $users = User::select('id', 'first_name', 'last_name', 'email')->where('status', 'active')->get();
        $plans = Plan::where('status', 'active')->get();
        return view('admin.plan_purchase.edit', compact('planPurchase', 'users', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.plan-purchase.index');
        $data = array();

        try {
            $rules = [
                'start_at' => 'required|date',
                'expired_at' => 'required|date|after:start_at',
                'status' => 'required|in:pending,active,in-active,expired,override',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors();
            } else {
                $update = PlanPurchase::find($id);
                $update->start_at = $request->start_at;
                $update->expired_at = $request->expired_at;
                $update->status = $request->status;
                $update->save();

                $success = true;
                $message = 'Plan purchase updated successfully.';
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
        $redirect = route('admin.plan-purchase.index');
        $data = array();

        try {
            $delete = PlanPurchase::find($id);
            if ($delete) {
                $delete->delete();
                $success = true;
                $message = 'Plan purchase deleted successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
