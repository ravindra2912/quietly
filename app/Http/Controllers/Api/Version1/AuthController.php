<?php

namespace App\Http\Controllers\Api\Version1;

use Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{User};

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;
		try {
			$rules = [
				'login_type' => 'required|in:normal,google',
			];

			if ($request->login_type == 'google') {
				$rules['google_auth_token'] = 'required';
			} else {
				$rules['email'] = 'required|email|max:191';
				$rules['password'] = 'required';
			}

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) { // Validation fails
				$message = $validator->errors()->first();
			} else {

				$user = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'profile_image', 'password')
					->where('role', 'user');
				if ($request->login_type == 'google') {
					$user = $user->where('google_auth_token', $request->google_auth_token);
				} else {
					$user = $user->where('email', $request->email);
				}
				$user = $user->first();

				if ($user) {
					if ($user->status == 'banned') {
						$message = 'Your account has been banned, please contact to admin.';
					} else if ($user->status == 'in-active') {
						$message = 'Your account has been in-active, please contact to admin.';
					} else {
						if (Hash::check($request->password, $user->password)) {
							if (Auth::loginUsingId($user->id)) {
								$accessToken = auth()->user()->createToken('authToken')->accessToken;
								$success = true;
								$message = 'Loging SuccessFully';
								$data['token'] = $accessToken;
								$data['user'] = $user->apiObject();
							} else {
								$message = 'Invalid Password';
							}
						} else {
							$message = 'Invalid Password';
						}
					}
				} else {
					$message = 'User Not Found';
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
	public function registration(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			$rules = [
				'login_type' => 'required|in:normal,google',
				'profile_image' => 'required|mimes:jpg,jpeg,png,webp|max:5120', // 5 MB images
				'first_name' => 'required|max:191',
				'last_name' => 'required|max:191',
				'email' => 'required|email|unique:users,email|max:191',
				'contact' => 'required|numeric|digits:10|unique:users,phone',
			];

			if ($request->login_type == 'google') {
				$rules['google_auth_token'] = 'required';
			} else {
				$rules['password'] = 'required';
			}

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$image_name = fileUploadStorage($request->file('profile_image'), 'user_profile_images', 500, 500);

					$User = new User();
					$User->first_name = trim($request->first_name);
					$User->last_name = trim($request->last_name);
					$User->profile_image = $image_name;
					$User->email = trim($request->email);
					$User->phone = trim($request->contact);
					if ($request->login_type == 'google') {
						$User->google_auth_token = $request->google_auth_token;
					} else {
						$User->password = Hash::make($request->password);
					}

					$User->save();

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

	public function logout(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$request->user()->token()->revoke();

			$success = true;
			$message = 'Logged out successfully';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}
}
