<?php

namespace App\Http\Controllers\Api\Version1;

use Carbon\Carbon;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\{adInquiry, Categories, Category, City, FriendList, LegalPage, State};

class CommonController extends Controller
{
	public function get_parent_categories()
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		$Categories = Category::with('subCategory')->where('parent_Category', null)->where('status', 'active')->get();

		$cats = array();
		foreach ($Categories as $cat) {
			$catss = $cat->apiObject();
			$catss['sub_cat'] = [];
			foreach ($cat->sub_cat as $subcat) {
				$catss['sub_cat'][] = $subcat->apiObject();
			}
			$cats[] = $catss;
		}

		if ($cats) {
			$data['Categories'] = $cats;
			$success = true;
			$message = 'Data Found';
		} else {
			$message = 'Data Not Found';
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function get_sub_categories(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;


		if (is_array($request->parent_id)) {
			$Categories = Categories::with('parent_cat')->whereIn('id', $request->parent_id)->where('status' . 1)->get();
		} else {
			$Categories = Categories::with('parent_cat')->where('id', $request->parent_id)->where('status' . 1)->get();
		}

		if ($Categories) {
			$data['Categories'] = $Categories;
			$success = true;
			$message = 'Data Found';
		} else {
			$message = 'Data Not Found';
		}
		return apiResponce($statuscode, $success, $message, $data);
	}


	public function getStates(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$states = State::with('cities')->where('country_id', 101)->get();
			$data['states'] = $states;
			$success = true;
			$message = 'Data Found';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getCities(Request $request, $state_id = null)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			if ($state_id == null) {
				$state_id = $request->user()->state;
			}
			$cities = City::where('state_id', $state_id)->get();
			$data['cities'] = $cities;
			$success = true;
			$message = 'Data Found';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getLegalPage($page)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$pageDetail = getLegalPage($page);
			$data['pageinfo'] = $pageDetail ? $pageDetail->description : '';
			$success = true;
			$message = 'Data Found';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getDashboardData(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;
		try {

			

			$categories = Category::select('id', 'name', 'image', 'parent_Category')
				->with(['subCategory' => function ($q) {
					$q->select('id', 'name', 'image', 'parent_Category')
						->where('status', 'active');
				}])
				->where('status', 'active')
				->where('parent_Category', null)
				->get();

			$data['Categories'] = apiObject($categories);

			$success = true;
			$message = 'Data Found!';
			$notificationData = [ 
				'type' => 'query',
				'id' => '12'
			];
			//  $data['noti'] = sendNotification('test title', 'test data', $notificationData);
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

		$limit = $request->limit ?? 15;
		$page = $request->page ?? 0;

		$friendlist = FriendList::with(['friendDetails:id,first_name,last_name,phone,address'])
			->where('user_id', $request->user()->id)
			->limit($limit)
			->skip($limit * $page)
			->where('status', 'friend')
			->get();

		$data = $friendlist;
		$success = true;
		$message = 'Dada Found!';

		return apiResponce($statuscode, $success, $message, $data);
	}

	public function StoreAdInquiry(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'business_name' => 'required|max:100',
				'email' => 'required|email|max:50,' . $request->user()->id,
				'mobile' => 'required|numeric|digits:10,' . $request->user()->id,
				'ad_details' => 'required'
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$insert = new adInquiry();
					$insert->business_name = trim($request->business_name);
					$insert->mobile = trim($request->mobile);
					$insert->email = trim($request->email);
					$insert->ad_details = trim($request->ad_details);
					$insert->save();

					$success = true;
					$message =  'Inquiry submited successFully';
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
