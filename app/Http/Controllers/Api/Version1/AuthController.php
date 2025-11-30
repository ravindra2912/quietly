<?php

namespace App\Http\Controllers\Api\Version1;

use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Categories, Category, User, UserCategory};

class AuthController extends Controller
{
	public function register(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			$rules = [
				'profile_image' => 'required|mimes:jpg,jpeg,png|max:5120', // 5 MB images
				'business_name' => 'required|max:191',
				'first_name' => 'required|max:191',
				'last_name' => 'required|max:191',
				'email' => 'required|email|unique:users,email|max:191',
				'contact' => 'required|numeric|digits:10|unique:users,phone',
				'state' => 'required',
				'city' => 'required',
				'pincode' => 'required|numeric|digits:6',
				'listing_type' => 'required',
				'categoreis' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$image_name = fileUploadStorage($request->file('profile_image'), 'user_profile_images', 300, 300);

					$User = new User();
					$User->first_name = trim($request->first_name);
					$User->last_name = trim($request->last_name);
					$User->business_name = trim($request->business_name);
					$User->profile_image = $image_name;
					$User->email = trim($request->email);
					$User->phone = trim($request->contact);
					$User->state = trim($request->state);
					$User->city = trim($request->city);
					$User->pincode = trim($request->pincode);
					$User->is_update_whatsapp = $request->whatsapp ? 1 : 0;
					$User->save();

					// sevice category
					foreach (explode(',', $request->categoreis) as $ucat) {
						$insert = new UserCategory();
						$insert->user_id = $User->id;
						$insert->category_id = $ucat;
						$insert->save();
					}

					$success = true;
					$message =  'User Registered SuccessFully, please login';
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
					$message = $e->getMessage();

					if (isset($image_name) && !empty($image_name)) {
						fileRemoveStorage($image_name);
					}
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function sendOtp(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;


		$rules = [
			'contact' => 'required|numeric|digits:10',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) { // Validation fails
			$message = $validator->errors()->first();
		} else {
			if ($user = User::where('phone', $request->contact)->first()) {
				$otp = random_int(100000, 999999);
				$data['otp'] = $otp;

				$user->otp = $otp;
				$user->otp_at = Carbon::now();
				$user->save();

				$message = 'Otp Sent Successfully';
				$success = true;
			} else {
				$message = 'User not registered';
			}
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function verifyOtp(Request $request)
	{

		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		$rules = [
			'contact' => 'required|numeric|digits:10',
			'otp' => 'required|numeric|digits:6',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) { // Validation fails
			$message = $validator->errors()->first();
		} else {
			$userdata = User::select('id', 'first_name', 'last_name', 'email', 'profile_image', 'phone', 'otp', 'otp_at', 'state')->where('phone', $request->contact)->first();
			if ($userdata) {
				$to = Carbon::parse($userdata->otp_at);
				$from = Carbon::now();
				$diffInMinutes = $to->diffInMinutes($from);
				if ($diffInMinutes >= 50) {
					$message = 'Otp Expired';
				} elseif ((int)$request->otp == $userdata->otp) {
					if (Auth::loginUsingId($userdata->id)) {
						$accessToken = auth()->user()->createToken('authToken')->accessToken;
						$success = true;
						$message = 'Loging SuccessFully';
						$data['token'] = $accessToken;
						$data['user'] = $userdata->apiObject();

						User::where('id', auth()->user()->id)->update(['otp' => null, 'otp_at' => null, 'notification_token' => $request->notification_token]);
					} else {
						$message = 'Invalid Credentials';
					}
				} else {
					$message = 'Invalid Credentials';
				}
			} else {
				$message = "Invalid verification code";
			}
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getCategories(Request $request)
	{

		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$categories = Category::select('id', 'name', 'image', 'parent_Category')
				->with(['subCategory' => function ($q) {
					$q->select('id', 'name', 'parent_Category')
						->where('status', 'active');
				}])
				->where('status', 'active')
				->where('parent_Category', null)
				->get();

			$data['categories'] = apiObject($categories);
			$success = true;
			$message = 'success';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}
}
