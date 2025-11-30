<?php

namespace App\Http\Controllers\Api\Version1;

use Auth;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\{Categories, Category, Query, User, UserCategory};

class QueryController extends Controller
{
	public function createQuery(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			DB::beginTransaction();
			$rules = [
				// 'photos' => 'nullable|mimes:jpg,jpeg,png',
				'customer_name' => 'required|max:100',
				'customer_contact' => 'required|numeric|digits:10',
				'area' => 'required|max:50',
				'city' => 'required|numeric',
				'pincode' => 'required|numeric|digits:6',
				'working_address' => 'required',
				'about_work' => 'required',
				'commission_amount' => 'required',
				'policy' => 'required',
				'category_id' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {

				// $image_name = fileUploadStorage($request->file('photos'), 'query_images', 300, 300);

				$User = new Query();
				$User->created_by_id = $request->user()->id;
				$User->customer_name = trim($request->customer_name);
				$User->customer_contact = trim($request->customer_contact);
				$User->area = trim($request->area);
				// $User->profile_image = $image_name;
				$User->city_id = trim($request->city);
				$User->pincode = trim($request->pincode);
				$User->customer_address = trim($request->working_address);
				$User->description = trim($request->about_work);
				$User->commission_amount = trim($request->commission_amount);
				$User->category_id  = $request->category_id;
				$User->save();

				$data = $User;
				$success = true;
				$message =  'Query Created SuccessFully';
				DB::commit();
			}
		} catch (\Exception $e) {
			DB::rollback();
			$message = $e->getMessage();

			if (isset($image_name) && !empty($image_name)) {
				fileRemoveStorage($image_name);
			}
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function updateQuery(Request $request, $id)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		DB::beginTransaction();
		try {
			$rules = [
				// 'photos' => 'nullable|mimes:jpg,jpeg,png',
				'customer_name' => 'required|max:100',
				'customer_contact' => 'required|numeric|digits:10',
				'area' => 'required|max:50',
				'pincode' => 'required|numeric|digits:6',
				'working_address' => 'required',
				'about_work' => 'required',
				'commission_amount' => 'required',
				'category_id' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {

				// $image_name = fileUploadStorage($request->file('photos'), 'query_images', 300, 300);

				$User = Query::find($id);
				$User->created_by_id = $request->user()->id;
				$User->customer_name = trim($request->customer_name);
				$User->customer_contact = trim($request->customer_contact);
				$User->area = trim($request->area);
				// $User->profile_image = $image_name;
				// $User->city_id = trim($request->city);
				$User->pincode = trim($request->pincode);
				$User->customer_address = trim($request->working_address);
				$User->description = trim($request->about_work);
				$User->commission_amount = trim($request->commission_amount);
				$User->category_id  = $request->category_id;
				$User->save();

				$success = true;
				$message =  'Query updated Successfully';
				DB::commit();
			}
		} catch (\Exception $e) {
			DB::rollback();
			$message = $e->getMessage();

			if (isset($image_name) && !empty($image_name)) {
				fileRemoveStorage($image_name);
			}
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getQuery(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$limit = $request->limit ?? 15;
			$page = $request->input('page', 0);
			$status = $request->input('status', '');
			$subStatus = $request->input('substatus', '');

			$query = Query::select('id', 'customer_name', 'customer_contact', 'area', 'city_id', 'worker_id', 'working_date')
				->with(['city:id,name', 'worker:id,first_name,last_name,phone'])
				->where('created_by_id', $request->user()->id)
				->limit($limit)
				->skip($limit * $page);

			if ($status == 'sendwork') {
				$query = $query->where('status', 'requested');
			} else if ($status == 'pendingWork') {
				$query = $query->where('status', 'accepted');
				if ($subStatus == 'Today') {
					$query = $query->whereDate('working_date', now());
				}
				if ($subStatus == 'Tomorrow') {
					$query = $query->whereDate('working_date', now()->addDay(1));
				}
				if ($subStatus == 'This week') {
					$query = $query->whereDate('working_date', '>=', now()->startOfWeek())
						->whereDate('working_date', '<=', now()->endOfWeek());
				}
			} else if ($status == 'ignorework') {
				$query = $query->whereIn('status', ['rejected', 'pending']);
			} else if ($status == 'completedwork') {
				$query = $query->where('status', 'completed');
			} else if ($status == 'overduework') {
				$query = $query->where('status', 'overdue')->orwhereDate('working_date', '<', now());
			}

			$query = $query->get();
			$data = apiObject($query);

			$success = true;
			$message =  'Success';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getQueryDetails(Request $req, $id)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$query = Query::select('customer_name', 'customer_contact', 'area', 'city_id', 'worker_id', 'customer_address', 'commission_amount', 'description', 'pincode', 'created_at', 'assign_at', 'working_date', 'completed_at', 'status', 'category_id')
				->with(['city:id,name', 'worker:id,first_name,last_name,phone', 'category' => function ($q) {
					$q->select('id', 'name', 'parent_Category')
						->with(['parentCategory:id,name,parent_Category']);
				}])
				->where('created_by_id', $req->user()->id)->find($id);
			$query->is_editable = false;
			if (in_array($query->status, ['pending', 'requested', 'rejected'])) {
				$query->is_editable = true;
			}
			$data['query'] = $query->apiObject();

			$data['Categories'] = Category::select('id', 'name', 'parent_Category')
				->with(['subCategory:id,name,parent_Category'])
				->where('parent_Category', null)
				->where('status', 'active')
				->get();


			$success = true;
			$message =  'Success';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function assignUser(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		DB::beginTransaction();
		try {
			$rules = [
				'user_id' => 'required',
				'query_id' => 'required'
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				Query::where('id', $request->query_id)
					->where('created_by_id', $request->user()->id)
					->update(['worker_id' => $request->user_id, 'assign_at' => now(), 'status' => 'requested']);

				$notificationData = [
					'type' => 'lead',
					'id' => (string)$request->query_id
				];
				$data['noti'] = sendNotification('New lead', 'you have a new lead', $notificationData, $request->user_id);


				$success = true;
				$message =  'Work assigned successfully';
				DB::commit();
			}
		} catch (\Exception $e) {
			$message = $e->getMessage();
			DB::rollBack();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getLeads(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$limit = $request->limit ?? 15;
			$page = $request->input('page', 0);
			$status = $request->input('status', '');
			$subStatus = $request->input('substatus', '');

			$query = Query::select('id', 'customer_name', 'customer_contact', 'customer_address', 'area', 'city_id', 'created_by_id', 'description')
				->with(['city:id,name', 'creator:id,first_name,last_name,phone'])
				->where('worker_id', $request->user()->id)
				->limit($limit)
				->skip($limit * $page);

			if ($status == 'newlead') {
				$query = $query->where('status', 'requested');
			} else if ($status == 'pendinglead') {
				$query = $query->where('status', 'accepted');
				if ($subStatus == 'Today') {
					$query = $query->whereDate('working_date', now());
				}
				if ($subStatus == 'Tomorrow') {
					$query = $query->whereDate('working_date', now()->addDay(1));
				}
				if ($subStatus == 'This week') {
					$query = $query->whereDate('working_date', '>=', now()->startOfWeek())
						->whereDate('working_date', '<=', now()->endOfWeek());
				}
			} else if ($status == 'completedlead') {
				$query = $query->where('status', 'completed');
			} else if ($status == 'overduelead') {
				$query = $query->whereNull('completed_at')
					->where(function ($q) {
						$q->where('status', 'overdue')
							->orwhereDate('working_date', '<', now());
					});
			}

			$query = $query->get();
			$data = apiObject($query);

			$success = true;
			$message =  'Success';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getLeadDetails(Request $req, $id)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$query = Query::select('customer_name', 'customer_contact', 'area', 'city_id', 'worker_id', 'customer_address', 'commission_amount', 'description', 'pincode', 'created_at', 'assign_at', 'working_date', 'completed_at', 'status', 'category_id')
				->with(['city:id,name', 'creator:id,first_name,last_name,phone', 'category' => function ($q) {
					$q->select('id', 'name', 'parent_Category')
						->with(['parentCategory:id,name,parent_Category']);
				}])
				->where('worker_id', $req->user()->id)->find($id);
			$query->customer_visible = false;
			if (in_array($query->status, ['accepted', 'working', 'completed'])) {
				$query->customer_visible = true;
			}
			$data['query'] = $query->apiObject();

			$success = true;
			$message =  'Success';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function acceptLead(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			DB::beginTransaction();
			$rules = [
				'query_id' => 'required|exists:queries,id',
				'action' => 'required|in:accept,rejected',
				'working_date' => $request->action == 'accept' ? 'required|date' : 'nullable'
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				$query = Query::where('worker_id', $request->user()->id)->find($request->query_id);
				if ($query) {
					if ($request->action == 'accept') {
						$query->status = 'accepted';
						$query->accepted_at = Carbon::now();
						$query->working_date = Carbon::parse($request->working_date)->format('Y-m-d');
						$message = 'Query Accepted Successfully';

						$notificationData = [
							'type' => 'query',
							'id' => (string)$request->query_id
						];
						$data['noti'] = sendNotification('work Accepted', 'Work accepted and working on ' . $query->working_date, $notificationData, $query->created_by_id);
					} else {
						$query->status = 'rejected';
						$message = 'Query Rejected Successfully';

						$notificationData = [
							'type' => 'query',
							'id' => (string)$request->query_id
						];
						$data['noti'] = sendNotification('work Rejected', 'Work is rejected', $notificationData, $query->created_by_id);
					}
					$query->save();
					$success = true;
					DB::commit();
				} else {
					$message = 'Query not found or you are not authorized to perform this action.';
					$statuscode = 404;
				}
			}
		} catch (\Exception $e) {
			DB::rollback();
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function actionOnLead(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			DB::beginTransaction();
			$rules = [
				'query_id' => 'required|exists:queries,id',
				'action' => 'required|in:complete',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				// $message = $validator->errors();
				$message = $validator->errors()->first();
			} else {
				$query = Query::where('worker_id', $request->user()->id)->find($request->query_id);
				if ($query) {
					if ($request->action == 'complete') {
						$query->status = 'completed';
						$query->completed_at = Carbon::now();
						$message = 'Query Completed Successfully';

						$notificationData = [
							'type' => 'query',
							'id' => (string)$request->query_id
						];
						$data['noti'] = sendNotification('work Completed', 'Work is Completed Successfully', $notificationData, $query->created_by_id);

						$query->save();
						$success = true;
						DB::commit();
					}
				} else {
					$message = 'Query not found or you are not authorized to perform this action.';
					$statuscode = 404;
				}
			}
		} catch (\Exception $e) {
			DB::rollback();
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
}
