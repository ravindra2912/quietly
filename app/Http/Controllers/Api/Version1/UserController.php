<?php

namespace App\Http\Controllers\Api\Version1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FriendList;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

	public function getUserProfile(Request $request, $user_id = null)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;
		try {

			if ($user_id == null) {
				$user_id = $request->user()->id;
			}

			$profile = User::select(
				'users.id',
				'users.first_name',
				'users.last_name',
				'users.business_name',
				'users.profile_image',
				'users.email',
				'users.phone',
				'users.address',
				'users.state',
				'users.city',
				'users.pincode',
				DB::raw("
							CASE
								WHEN fl1.status IS NOT NULL THEN fl1.status
								WHEN fl2.status = 'requisted' THEN 'requestRecived'
								ELSE ''
							END as friend_status
						")
			)
			->with(['stateDetail:id,name', 'cityDetail:id,name'])
				->where('role', 'user')
				->leftJoin('friend_lists as fl1', function ($join) use ($request) {
					$join->on('fl1.user_id', '=', DB::raw($request->user()->id))
						->on('fl1.friend_id', '=', 'users.id')
						->whereNull('fl1.deleted_at');
				})
				->leftJoin('friend_lists as fl2', function ($join) use ($request) {
					$join->on('fl2.user_id', '=', 'users.id')
						->on('fl2.friend_id', '=', DB::raw($request->user()->id))
						->whereNull('fl2.deleted_at');
				})
				->find($user_id);

			$data['profile'] = $profile ? $profile->apiObject():null;

			$success = true;
			$message = 'User Found!';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function UpdateProfile(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			$rules = [
				'profile_image' => 'nullable|mimes:jpg,jpeg,png|max:5120', // 5 MB images
				'business_name' => 'required|max:191',
				'first_name' => 'required|max:191',
				'last_name' => 'required|max:191',
				'email' => 'required|email|max:191|unique:users,email,' . $request->user()->id,
				'contact' => 'required|numeric|digits:10|unique:users,phone,' . $request->user()->id,
				'state' => 'required',
				'city' => 'required',
				'pincode' => 'required|numeric|digits:6',
				// 'listing_type' => 'required',
				// 'categoreis' => 'required',
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

						$image_name = fileUploadStorage($request->file('profile_image'), 'user_profile_images', 300, 300);
						$User->profile_image = $image_name;
					}

					$User->first_name = trim($request->first_name);
					$User->last_name = trim($request->last_name);
					$User->business_name = trim($request->business_name);

					$User->email = trim($request->email);
					$User->phone = trim($request->contact);
					$User->state = trim($request->state);
					$User->city = trim($request->city);
					$User->pincode = trim($request->pincode);
					$User->is_update_whatsapp = $request->whatsapp == 'true' ? 1 : 0;
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

	public function searchUser(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;
		try {

			$limit = (int) ($request->limit ?? 15);
			$page = (int) ($request->page ?? 0);

			$query = User::select(
				'users.id',
				'users.first_name',
				'users.last_name',
				'users.profile_image',
				'users.email',
				'users.phone',
				'users.address',
				DB::raw("
							CASE
								WHEN fl1.status IS NOT NULL THEN fl1.status
								WHEN fl2.status = 'requisted' THEN 'requestRecived'
								ELSE ''
							END as friend_status
						")
			)
				->where('role', 'user')
				->leftJoin('friend_lists as fl1', function ($join) use ($request) {
					$join->on('fl1.user_id', '=', DB::raw($request->user()->id))
						->on('fl1.friend_id', '=', 'users.id')
						->whereNull('fl1.deleted_at');
				})
				->leftJoin('friend_lists as fl2', function ($join) use ($request) {
					$join->on('fl2.user_id', '=', 'users.id')
						->on('fl2.friend_id', '=', DB::raw($request->user()->id))
						->whereNull('fl2.deleted_at');
				})
				->limit($limit)
				->skip($limit * $page);

			if (!empty($request->search)) {
				$search = $request->search;
				$query->where(function ($q) use ($search) {
					$q->where('users.first_name', 'like', "%{$search}%")
						->orWhere('users.last_name', 'like', "%{$search}%")
						->orWhere('users.phone', 'like', "%{$search}%");
				});
			}

			$users = $query->get();

			$data['friends'] = apiObject($users);

			$success = true;
			$message = 'User Found!';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getFriends(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;
		try {

			$limit = (int) ($request->limit ?? 15);
			$page = (int) ($request->page ?? 0);

			$query = User::select(
				'users.id',
				'users.first_name',
				'users.last_name',
				'users.profile_image',
				'users.email',
				'users.phone',
				'users.address'
			)
				->where('role', 'user')
				->rightjoin('friend_lists as fl1', function ($join) use ($request) {
					$join->on('fl1.user_id', '=', DB::raw($request->user()->id))
						->on('fl1.friend_id', '=', 'users.id')
						->where('fl1.status', 'friend')
						->whereNull('fl1.deleted_at');
				})
				->limit($limit)
				->skip($limit * $page);

			if (!empty($request->search)) {
				$search = $request->search;
				$query->where(function ($q) use ($search) {
					$q->where('users.first_name', 'like', "%{$search}%")
						->orWhere('users.last_name', 'like', "%{$search}%")
						->orWhere('users.phone', 'like', "%{$search}%");
				});
			}

			$users = $query->get();

			$data['friends'] = apiObject($users);

			$success = true;
			$message = 'User Found!';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function sendFriendRequest(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'user_id' => 'required|max:191',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {

					$checkReqest = FriendList::where('user_id', $request->user_id)
						->where('friend_id', $request->user()->id)
						->first();

					if ($checkReqest && $checkReqest->status == 'requisted') {
						$checkReqest->status = 'friend';
						$checkReqest->save();

						$newEntry = new FriendList();
						$newEntry->user_id = $request->user()->id;
						$newEntry->friend_id = $request->user_id;
						$newEntry->status = 'friend';
						$newEntry->save();

						$success = true;
						$message =  'Friend request accept SuccessFully';
						DB::commit();
					} else {
						$newEntry = new FriendList();
						$newEntry->user_id = $request->user()->id;
						$newEntry->friend_id = $request->user_id;
						$newEntry->status = 'requisted';
						$newEntry->save();

						$success = true;
						$message =  'Friend request sent SuccessFully';
						DB::commit();
					}
				} catch (\Exception $e) {
					DB::rollback();
					$message = $e->getMessage();
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function acceptFriendRequest(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'user_id' => 'required|max:191',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$checkReqest = FriendList::where('user_id', $request->user_id)
						->where('friend_id', $request->user()->id)
						->first();

					if ($checkReqest && $checkReqest->status == 'requisted') {
						$checkReqest->status = 'friend';
						$checkReqest->save();

						$newEntry = new FriendList();
						$newEntry->user_id = $checkReqest->friend_id;
						$newEntry->friend_id = $checkReqest->user_id;
						$newEntry->status = 'friend';
						$newEntry->save();

						$success = true;
						$message =  'Friend request accept SuccessFully';
						DB::commit();
					} else {
						$message =  'Friend request not found!';
					}
				} catch (\Exception $e) {
					DB::rollback();
					$message = $e->getMessage();
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function removeToFriend(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'user_id' => 'required|max:191',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {

					FriendList::where(function ($q) use ($request) {
						$q->where('user_id', $request->user_id)
							->where('friend_id', $request->user()->id);
					})
						->orWhere(function ($q) use ($request) {
							$q->where('user_id', $request->user()->id)
								->where('friend_id', $request->user_id);
						})
						->delete();

					$success = true;
					$message =  'Un-Friend SuccessFully';
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
					$message = $e->getMessage();
				}
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
}
