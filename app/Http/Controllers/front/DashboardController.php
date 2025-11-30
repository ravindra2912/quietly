<?php

namespace App\Http\Controllers\front;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\DailyInquiry;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $latestBlogs = Blog::where('status', 'active')->latest('published_at')->take(3)->get();
        return view('front.home', compact('latestBlogs'));
    }

    public function getRoutes(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = '';
        $data = array();

        try {

            $routes = Route::with(['drop_address:id,address'])
                ->where('status', 'active')
                ->where('pickup_id', $request->id)
                ->get();

            $html = '<option value="">Select Drop City</option>';
            foreach ($routes as $address) {
                $html .= '<option value="' . $address->drop_id . '">' . $address->drop_address?->address . '</option>';
            }
            $data = $html;
            $success = true;
            $message = 'success';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function storeContactUs(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = '';
        $data = array();

        try {

            $rules = [
                'name' => 'required|max:100',
                'email' => 'required|email',
                'phone' => 'required|numeric|digits_between:10,12',
                'subject' => 'required',
                'message' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { // Validation fails
                $message = $validator->errors();
                // $message = $validator->errors()->first();
            } else {
                $insert = new ContactUs();
                $insert->name = $request->name;
                $insert->email = $request->email;
                $insert->phone = $request->phone;
                $insert->subject = $request->subject;
                $insert->message = $request->message;
                $insert->save();

                $success = true;
                $message = 'Thank you for contacting us! We’ll get back to you soon';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }

    public function getfindRoute(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = '';
        $data = array();

        try {
            $rules = [
                'from_city' => 'required',
                'drop_City' => 'required',
                'route_type' => 'required',
                'number' => 'required|numeric|digits_between:10,12'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { // Validation fails
                $message = $validator->errors();
                // $message = $validator->errors()->first();
            } else {
                $today = Carbon::today();

                $check = DailyInquiry::where('contact', $request->number)
                    ->where('route_type', $request->route_type)
                    ->where('pickup_id', $request->from_city)
                    ->where('drop_id', $request->drop_City)
                    ->whereDate('created_at', $today)
                    ->first();

                if ($check) {
                    $check->daily_count = $check->daily_count + 1;
                    $check->save();
                } else {
                    $insert = new DailyInquiry();
                    $insert->contact = $request->number;
                    $insert->route_type = $request->route_type;
                    $insert->pickup_id = $request->from_city;
                    $insert->drop_id = $request->drop_City;
                    $insert->daily_count = 1;
                    $insert->save();
                }

                $route = Route::where('pickup_id', $request->from_city)
                    ->where('drop_id', $request->drop_City)
                    ->first();

                if ($route) {

                    $redirect = route('oneWayRouteCarList', $route->slug);

                    $success = true;
                    $message = 'success';
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
