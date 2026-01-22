<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{User, Setting};

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
}
