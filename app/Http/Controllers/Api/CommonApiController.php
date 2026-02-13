<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Contact, OtherApp, User, Setting};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommonApiController extends Controller
{

	public function checkLogin(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$data['is_login'] = false;
		$data['user'] = null;
		$statuscode = 200;

		try {

			$data['is_login'] = auth('api')->check();
			if ($data['is_login']) {

				User::where('id', auth('api')->user()->id)->update([
					'login_at' => now(),
				]);

				$data['user'] = User::select(
					'id',
					'first_name',
					'last_name',
					'email',
					'phone',
					'profile_image',
					'occupation',
					'status'
				)
					->with(['activePlan' => function ($q) {
						$q->select(
							'id',
							'user_id',
							'plan_id',
							'start_at',
							'expired_at',
							'plan_info',
							'status'
						);
					}])
					->where('status', 'active')
					->find(auth('api')->user()->id)
					->apiObject();
			}
			$settings = Setting::first();
			$data['is_ads'] = $settings->is_ads ?? false;
			$data['ads_key'] = $settings->ads_key ?? null;


			$success = true;
			$message = 'successfully';
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

	public function otherApps(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = [];
		$statuscode = 200;

		try {
			$data = OtherApp::where('status', 'active')->get()->map(function ($app) {
				return $app->apiObject();
			});
			$success = true;
			$message = 'Data Found';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	// contact us using contacts table
	public function contactUs(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {
			$rules = [
				'name' => 'required|max:191',
				'email' => 'required|email|max:191',
				'description' => 'required',
				'type' => 'required|in:ReportAnIssue,FeatureRequest,GeneralHelp',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();

				$Contact = new Contact();
				$Contact->name = trim($request->name);
				$Contact->email = trim($request->email);
				$Contact->description = trim($request->description);
				$Contact->type = trim($request->type);
				$Contact->save();

				$success = true;
				$message =  'Contact Us SuccessFully';
				DB::commit();
			}
		} catch (\Exception $e) {
			DB::rollback();
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}
}
