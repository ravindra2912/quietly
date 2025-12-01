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
