<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ContactUs::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i A');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2 justify-content-center">
                        <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-outline-info btn-sm view_record" title="View"><i class="bi bi-eye-fill"></i></a>
                        <a href="javascript:void(0)" data-url="' . route('admin.contact-us.destroy', $row->id) . '" class="btn btn-outline-danger btn-sm delete_record" title="Delete"><i class="bi bi-trash-fill"></i></a>
                    </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.contact_us.index');
    }

    public function show($id)
    {
        $contact = ContactUs::find($id);
        if ($contact) {
            return response()->json(['success' => true, 'data' => $contact]);
        }
        return response()->json(['success' => false, 'message' => 'Record not found!']);
    }

    public function updateStatus(Request $request)
    {
        $contact = ContactUs::find($request->id);
        if ($contact) {
            $contact->status = $request->status;
            $contact->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong!']);
    }

    public function destroy($id)
    {
        $delete = ContactUs::find($id);
        if ($delete) {
            $delete->delete();
            return response()->json(['success' => true, 'message' => 'Contact request deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong!']);
    }
}
