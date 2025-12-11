<?php


return [

    "site_setting" => [
        "name" => "Quietly",
        "logo" => env('APP_URL') . '/assets/images/footer-logo.webp',
        "footer_logo" => env('APP_URL') . '/assets/images/footer-logo.webp',
        "fevicon" => env('APP_URL') . '/assets/images/fevicon-icon.webp',
    ],

    "admin_notify_emails" => ['goswamirvi@gmail.com', 'bhargav@gmail.com'],

    "contactUs" => [
        "address" => "12/4, SWAMI PRMANAND SOCIETY, Bungalow Area Road, Kuber Nagar, Bhavnagar, Gujarat, 382340",
        "contact" => 8320857096,
        "email" => 'quietly@gmail.com',
    ],

    "socialMedia" => [
        "facebook" => "https://www.facebook.com",
        "instagram" => "https://www.instagram.com",
        "linkedin" => "https://www.linkedin.com",
        "pinterest" => "https://www.pinterest.com",
        "youtube" => "https://www.youtube.com",
        "geolocation" => "https://maps.app.goo.gl/jhRP3FB36GXA1Jno8",
    ],

    "common_status" => ["active", "in-active"],
    "blog_status" => ["active", "in-active"],

    "legal_page_type" => ["PrivacyPolicy", "TermsAndCondition", "CancellationAndReturnPolicy", "AboutUs"],
    "plan_purchase_status" => ["pending", "active", "in-active", "expired", "override"],


    "rating" => [
        0 => 'No Review',
        1 => 'Bad',
        2 => 'Poor',
        3 => 'Average',
        4 => 'Good',
        5 => 'Excellent',
    ],

    "gender" => ['male', 'female', 'other'],
    "user_role" => ['admin', 'user'],
    "user_status" => ['active', 'in-active', 'banned'],
    "app_playstore_url" => 'https://play.google.com/store/apps/details?id=com.quietly.app',


];
