<?php

namespace App\Http\Controllers\Api\Version1;

use Auth;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\{Categories, Query, QueryRequistToFriend, User, UserCategory};

class MyQueryController extends Controller
{
	public function getMyQuery(Request $req)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			if (!isset($req->limit) || $req->limit == '') {
				$req->limit = 15;
			}
			if (!isset($req->page) || $req->page == '') {
				$req->page = 0;
			}

			$query = Query::where('created_by_id', $req->user()->id)
				->limit($req->limit)
				->skip($req->limit * $req->page);

			if ($req->status != 'all') {
				if ($req->status != '') {
					if ($req->status == 'today') {
						$query = $query->whereDate('working_date', now());
					} else {
						$query = $query->where('status', $req->status);
					}
				}
			}
			$query = $query->get();

			$arra = array();
			foreach ($query as $q) {
				$arra[] = $q->apiCreaterObject();
			}
			$data = $arra;

			$success = true;
			$message =  'Success';
			DB::commit();
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getMyQueryDetais(Request $req)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$query = Query::with(['bids'])->where('created_by_id', $req->user()->id)->where('id', $req->query_id)->first();

			// $bids = QueryRequistToFriend::where('query_id', $req->query_id)->get();

			$data['query'] = $query->apiCreaterObject();

			// bids list
			$bids = array();
			foreach ($query->bids as $bd) {
				$bids[] = $bd->apiCreaterObject();
			}
			$data['bids'] = $bids;

			$Categories = Categories::with('sub_cat')->where('parent_Category', null)->where('status', 1)->get();
			$cats = array();
			foreach ($Categories as $cat) {
				$catss = $cat->apiObject();
				$catss['sub_cat'] = [];
				foreach ($cat->sub_cat as $subcat) {
					$catss['sub_cat'][] = $subcat->apiObject();
				}
				$cats[] = $catss;
			}
			$data['Categories'] = $cats;


			$success = true;
			$message =  'Success';
			DB::commit();
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function sendQueryToFriend(Request $req)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'query_id' => 'required|numeric|exists:queries,id',
				'friend_id' => 'required|numeric|exists:users,id',
			];


			$validator = Validator::make($req->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {

				$check = QueryRequistToFriend::where('query_id', $req->query_id)->where('friend_id', $req->friend_id)->first();
				if (!$check) {
					$send = new QueryRequistToFriend();
					$send->user_id = $req->user()->id;
					$send->query_id = $req->query_id;
					$send->friend_id = $req->friend_id;
					$send->save();
					$message =  'Requist send successfylly';

					sendNotification('New lead', 'you have a new lead', $req->friend_id);
				} else {
					$message =  'Requist already sended';
				}

				$success = true;
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function removeRequist(Request $req)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'requist_id' => 'required|numeric|exists:query_requist_to_friends,id',
			];


			$validator = Validator::make($req->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {

				QueryRequistToFriend::where('id', $req->requist_id)->where('user_id', $req->user()->id)->delete();
				$message =  'Remove successfully';

				$success = true;
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
}
