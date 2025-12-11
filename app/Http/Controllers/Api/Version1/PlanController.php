<?php

namespace App\Http\Controllers\Api\Version1;

use App\Models\{Plan, PlanPurchase, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
	public function getPlans(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = [];
		$statuscode = 200;

		try {

			$data = Plan::select(
				'id',
				'name',
				'price',
				'duration_in_month',
				'is_ad_free',
				'group_active_timing',
				'is_active_multiple_group',
				'plan_purchase_limit_per_user',
				'plan_purchase_limit',
				'status'
			)->where('status', 'active')->get();

			$success = true;
			$message = 'Plans retrieved successfully';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function getPlanDetails(Request $request, $planId)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = [];
		$statuscode = 200;

		try {

			$data = Plan::select(
				'id',
				'name',
				'price',
				'duration_in_month',
				'is_ad_free',
				'group_active_timing',
				'is_active_multiple_group',
				'plan_purchase_limit_per_user',
				'plan_purchase_limit',
				'status'
			)->where('status', 'active')->find($planId);

			$success = true;
			$message = 'Plans retrieved successfully';
		} catch (\Exception $e) {
			$message = $e->getMessage();
		}
		return apiResponce($statuscode, $success, $message, $data);
	}

	public function inAppPurchase(Request $request)
	{
		$success = false;
		$message = 'Something Wrong!';
		$data = array();
		$statuscode = 200;

		try {

			$rules = [
				'plan_id' => 'required|exists:plans,id',
				'payment_details' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) { // Validation fails
				$message = $validator->errors();
				// $message = $validator->errors()->first();
			} else {
				DB::beginTransaction();
				try {
					$userId = $request->user()->id;
					$planDetails = Plan::find($request->plan_id);

					$activePlan = PlanPurchase::where('user_id', $userId)
						->where('status', 'active')
						->where('expired_at', '>', now())
						->first();

					if ($activePlan) {
						$activePlan->update([
							'status' => 'override',
						]);
					}

					// unset($request->payment_details->status);
					$insert = PlanPurchase::create([
						'user_id' => $userId,
						'plan_id' => $request->plan_id,
						'duration_in_month' => $planDetails->duration_in_month,
						'start_at' => now(),
						'expired_at' => now()->addMonths($planDetails->duration_in_month),
						'price' => $planDetails->price,
						'plan_info' => $planDetails->toArray(),
						'payment_details' => $request->payment_details,
						'status' => 'active',
					]);

					$success = true;
					$message =  'Plan purchase SuccessFully';
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
