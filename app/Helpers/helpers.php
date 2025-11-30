<?php

use Carbon\Carbon;

use App\Models\Faq;
use App\Models\LegalPage;
use App\Models\Seo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


function apiResponce($statuscode, $status, $message, $data = [])
{
    return response()->json(["code" => $statuscode, "success" => $status, "message" => $message, "data" => $data]);
}

// ************ image function start ***************

function fileRemoveStorage($imageObject)
{
    if ($imageObject != null) {
        return Storage::disk('public')->delete($imageObject);
    }
}

function fileUploadStorage($imageObject, $directory = "", $width = "", $hieght = "", $converto = "webp")
{
    if (!empty($imageObject)) {
        $imgname = time() . "_" . rand(11111, 99999) . '.' . $imageObject->getClientOriginalExtension();
        $imageName = $directory . "/" . $imgname;

        if ($width != "" && $hieght != "") {

            // create folder if not exist
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $image = Image::read($imageObject->path());
            $image->scale($width, $hieght); //resize

            $image->toWebp()->save(public_path('/storage/' . $imageName));
            // 
            // if($converto == 'webp'){
            //     $image->toWebp()->save(public_path('/storage/' . $imageName));
            // }else if($converto == 'png'){
            //     $image->toPng()->save(public_path('/storage/' . $imageName));
            // }else if($converto == 'jpg'){
            //     dd();
            //     $image->toJpeg()->save(public_path('/storage/' . $imageName));
            // }



        } else {

            $storage = Storage::disk('public');

            $uploaded = $storage->put($imageName, file_get_contents($imageObject), 'public');
        }
        return $imageName;
    }
    return "";
}

function getImage($url = "", $type = '')
{
    $image = "storage/" . $url;
    if (!empty($url)) {
        if (file_exists(public_path($image))) {
            return asset("storage/" . $url);
        }
    }
    if ($type == 'user') {
        return asset('assets/images/user.webp');
    }
    return asset('assets/images/default.png');
}

// ************ image function end ***************


// ************ date function end ***************

function get_date($date, $format = 'd-m-Y')
{
    return !empty($date) ? Carbon::parse($date)->translatedFormat($format) : null;
}

function get_time($date, $format = 'h:i A')
{
    return Carbon::parse($date)->translatedFormat($format);
}

function getDateTime($date, $format = 'd-m-Y h:i A')
{
    return Carbon::parse($date)->translatedFormat($format);
}

// ************ date function end ***************


function apiObject($arrey, $newObj = null, $data = null)
{
    $temp = [];
    foreach ($arrey as $arr) {
        if ($newObj != null) {
            if ($data != null) {
                $temp[] = $arr->$newObj($data);
            } else {
                $temp[] = $arr->$newObj();
            }
        } else {
            if ($data != null) {
                $temp[] = $arr->apiObject($data);
            } else {
                $temp[] = $arr->apiObject();
            }
        }
    }
    return $temp;
}

function getFaqs()
{
    return Cache::rememberForever('Faqs', function () {
        return Faq::get();
    });
}


function generateUniqueSlug($model, $text, $field = 'slug')
{
    $slug = Str::slug($text);
    $originalSlug = $slug;
    $i = 1;

    while ($model::where($field, $slug)->exists()) {
        $slug = $originalSlug . '-' . $i;
        $i++;
    }

    return $slug;
}

function getLegalPage($page)
{
    return Cache::rememberForever('get' . $page, function () use ($page) { // 1440/60 = 1 day
        return LegalPage::select('description')->where('status', 'active')->where('page_type', $page)->first();
    });
}

function getSeo($full_url)
{
    $data = [];
    // 1. Extract the host (domain name)
    $host = parse_url($full_url, PHP_URL_HOST); // Returns "ravirental.com"

    // 2. Remove "www." if present (optional, but good practice)
    $host = str_replace('www.', '', $host); // Still "ravirental.com"

    // 3. Remove the Top-Level Domain (TLD) and any following characters
    $base_name = explode('.', $host);


    $full_url = rtrim($full_url, '/');
    $data['domain'] = ucfirst($base_name[0]);
    $data['domainwithdot'] = $base_name[0] . 'dot' . (isset($base_name[1]) ? $base_name[1] : '');
    $data['seo'] = Cache::rememberForever('seo-' . $full_url, function () use ($full_url) { // 1440/60 = 1 day
        return Seo::where('site_url', $full_url)->first();
    });

    return $data;
}
