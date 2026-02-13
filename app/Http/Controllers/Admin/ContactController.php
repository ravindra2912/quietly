<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Admin\StoreContactRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::query()->orderBy('created_at', 'desc');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name_with_read', function ($row) {
                    $badge = !$row->is_read ? '<span class="badge bg-danger ms-2">Unread</span>' : '';
                    return '<strong>' . $row->name . '</strong>' . $badge;
                })
                ->addColumn('type', function ($row) {
                    return '<span class="badge bg-' . $row->getTypeBadgeColor() . '">' . $row->type . '</span>';
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-' . $row->getStatusBadgeColor() . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
                })
                ->addColumn('is_read', function ($row) {
                    return $row->is_read ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-exclamation-circle-fill text-warning"></i>';
                })
                ->addColumn('created_at', function ($row) {
                    return '<small class="text-muted">' . $row->created_at->format('d M Y, h:i A') . '</small>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="text-center">
                        <a href="' . route('admin.contact.show', $row->id) . '" class="btn btn-outline-primary btn-sm" title="View">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['name_with_read', 'type', 'status', 'is_read', 'created_at', 'action'])
                ->make(true);
        }

        return view('admin.contact.index');
    }

    /**
     * Display the specified contact.
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // Mark as read if not already
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        $statuses = ['pending', 'in_progress', 'resolved', 'closed'];

        return view('admin.contact.show', compact('contact', 'statuses'));
    }

    /**
     * Update the specified contact.
     */
    public function update(StoreContactRequest $request, $id)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = route('admin.contact.index');
        $data = array();

        try {
            $contact = Contact::find($id);
            
            if (!$contact) {
                $message = 'Contact not found.';
                return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
            }

            $contact->update(['status' => $request->status]);
            
            $success = true;
            $message = 'Contact status updated successfully.';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
