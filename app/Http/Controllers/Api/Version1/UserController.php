<?php

namespace App\Http\Controllers\Api\Version1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	public function UpdateProfile(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			$rules = [
				'profile_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120', // 5 MB images
				'first_name' => 'required|max:191',
				'last_name' => 'required|max:191',
				'occupation' => 'required',
				'email' => 'required|email|max:191|unique:users,email,' . $request->user()->id,
				'contact' => 'required|numeric|digits:10|unique:users,phone,' . $request->user()->id,
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$User = User::find($request->user()->id);
					if ($request->hasFile('profile_image')) {
						$image_old = $User->profile_image;
						$image_name = fileUploadStorage($request->file('profile_image'), 'user_profile_images', 500, 500);
						$User->profile_image = $image_name;
					}
					$User->first_name = trim($request->first_name);
					$User->last_name = trim($request->last_name);
					$User->email = trim($request->email);
					$User->phone = trim($request->contact);
					$User->occupation = trim($request->occupation);
					$User->save();

					if (isset($image_old) && !empty($image_old)) {
						fileRemoveStorage($image_old);
					}

					$success = true;
					$message =  'Profile update SuccessFully';
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

	public function removeAccount(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$user = $request->user();

			// Revoke current token
			$user->token()->revoke();

			DB::table('oauth_refresh_tokens')
				->where('access_token_id', $user->token()->id)
				->update(['revoked' => true]);

			// Remove profile picture
			if (!empty($user->profile_image)) {
				fileRemoveStorage($user->profile_image);
			}

			// Soft delete user
			$user->delete();

			$success = true;
			$message =  'Account removed SuccessFully';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
}
