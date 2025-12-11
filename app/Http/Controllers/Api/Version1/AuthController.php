<?php

namespace App\Http\Controllers\Api\Version1;

use Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\{User};
use App\Mail\ResetPasswordMail;

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
				'profile_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120', // 5 MB images
				'first_name' => 'required|max:191',
				'last_name' => 'required|max:191',
				'email' => 'required|email|unique:users,email|max:191',
				'contact' => 'required|numeric|digits:10|unique:users,phone',
				'occupation' => 'required',
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
					$User = new User();
					if ($request->hasFile('profile_image')) {
						$image_name = fileUploadStorage($request->file('profile_image'), 'user_profile_images', 500, 500);
						$User->profile_image = $image_name;
					}

					$User->first_name = trim($request->first_name);
					$User->last_name = trim($request->last_name);
					$User->email = trim($request->email);
					$User->phone = trim($request->contact);
					$User->occupation = trim($request->occupation);
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

	public function forgotPassword(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'email' => 'required|email|exists:users,email',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
				$message = $validator->errors()->first();
			} else {
				$user = User::where('email', $request->email)->first();

				if ($user) {
					// Generate 6-digit OTP
					$otp = rand(100000, 999999);

					// Store OTP in database (expires in 10 minutes)
					DB::table('password_reset_tokens')->updateOrInsert(
						['email' => $request->email],
						[
							'email' => $request->email,
							'token' => $otp,
							'created_at' => now()
						]
					);

					// Send OTP via email
					try {
						$userName = $user->first_name . ' ' . $user->last_name;
						Mail::to($user->email)->send(new ResetPasswordMail($otp, $userName));

						$success = true;
						$message = 'OTP sent to your email successfully';
						// $data['otp'] = $otp; // Uncomment for testing only
					} catch (\Exception $mailException) {
						// If email fails, still return success but log the error
						\Log::error('Failed to send reset password email: ' . $mailException->getMessage());
						$success = true;
						$message = 'OTP generated successfully';
						$data['otp'] = $otp; // Return OTP if email fails (for testing)
					}
				} else {
					$message = 'User not found';
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function resetPassword(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'email' => 'required|email|exists:users,email',
				'otp' => 'required|numeric|digits:6',
				'password' => 'required|min:6',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
				$message = $validator->errors()->first();
			} else {
				// Verify OTP
				$resetRecord = DB::table('password_reset_tokens')
					->where('email', $request->email)
					->where('token', $request->otp)
					->first();

				if (!$resetRecord) {
					$message = 'Invalid OTP';
				} else {
					// Check if OTP is expired (10 minutes)
					$createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
					if ($createdAt->addMinutes(10)->isPast()) {
						$message = 'OTP has expired. Please request a new one.';

						// Delete expired OTP
						DB::table('password_reset_tokens')
							->where('email', $request->email)
							->delete();
					} else {
						// Update password
						$user = User::where('email', $request->email)->first();
						$user->password = Hash::make($request->password);
						$user->save();

						// Delete used OTP
						DB::table('password_reset_tokens')
							->where('email', $request->email)
							->delete();

						$success = true;
						$message = 'Password reset successfully';
					}
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}
}
