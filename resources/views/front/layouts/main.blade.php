<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	@php
		$customeseo = getSeo(url()->current());
		if ($customeseo['seo']) {
			$seo['title'] = $customeseo['seo']['meta_title'];
			$seo['description'] = $customeseo['seo']['meta_description'];
			$seo['keywords'] = $customeseo['seo']['meta_keywords'];
		}
	@endphp

	@if (isset($seo) && !empty($seo))
		<title>{{ $seo['title'] }}</title>

		<meta name="description" content="{{ $seo['description'] }}" />
		<meta name="keywords" content="{{ $seo['keywords'] }}" />

		<meta name="distribution" content="global">
		<meta http-equiv="content-language" content="en-gb">
		<meta name="city" content="{{ $seo['city'] }}">
		<meta name="state" content="{{ $seo['state'] }}">
		<meta name="geo.region" content="IN-GJ">
		<meta name="DC.title" content="{{ $seo['title'] }}">
		<meta name="geo.position" content="{{ $seo['position'] }}">
		<meta name="ICBM" content="{{ $seo['position'] }}">
		<meta name="geo.region" content="IN-{{ strtoupper(substr($seo['state'], 0, 2)) }}">
		<meta name="geo.placename" content="{{ $seo['city'] }}">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta property="al:web:url" content="{{ url()->current() }}">

		<meta name="copyright" content="{{$customeseo['domain']}}">

		<meta property="og:title" content="{{ $seo['title'] }}" />
		<meta property="og:description" content="{{ $seo['description'] }}" />
		<meta property="og:keywords" content="{{ $seo['keywords'] }}" />
		<meta property="og:url" content="{{ url()->current() }}">
		<meta property="og:type" content="Car and Taxi Booking Website" />
		<meta property="og:site_name" content="{{$customeseo['domain']}} - Car and Taxi Booking Website">
		<meta property="og:locale" content="en_GB">
		<meta property="og:image" content="{{ config('const.site_setting.fevicon') }}">
		<!-- <meta property="og:image:width" content="550" />
		<meta property="og:image:height" content="413" /> -->

		<meta name="author" content="{{$customeseo['domain']}} Car and Taxi Booking " />

		<meta property="twitter:card" content="summary">
		<meta property="twitter:site" content="{{$customeseo['domainwithdot']}}">
		<meta property="twitter:title" content="{{ $seo['title'] }}">
		<meta property="twitter:description" content="{{ $seo['description'] }}">
		<meta property="twitter:image" content="{{ config('const.site_setting.fevicon') }}">
		<meta property="twitter:url" content="{{ url()->current() }}">
		<meta name="twitter:domain" content="{{$customeseo['domain']}}">

		<meta name="robots" content="index,follow" />
	@else
		<title>{{ config('const.site_setting.name') }}</title>
	@endif

	<link rel="apple-touch-icon" sizes="180x180" href="{{ config('const.site_setting.fevicon') }}">
	<link rel="canonical" href="{{ url()->current() }}" />
	<link rel="icon" type="image/webp" href="{{ config('const.site_setting.fevicon') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
	<!--Toastr -->
	<link href="{{ asset('assets/ajax/toastr.css') }}" rel="stylesheet" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/frontstyle.css') }}">

	<!-- Bootstrap Icons CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

	<!-- Owl Carousel CSS
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"> -->


	@stack('style')
</head>

<body>

	<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 fixed-top">
		<div class="container">
			<a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
				<span class="icon-circle bg-dark">
					<i class="bi bi-volume-up-fill fs-4"></i>
				</span>
				Quietly
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
				aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="mainNav">
				<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
					<li class="nav-item"><a class="nav-link"
							href="{{ Route::is('home') ? '' : route('home') }}#features">Features</a></li>
					<li class="nav-item"><a class="nav-link"
							href="{{ Route::is('home') ? '' : route('home') }}#how-it-works">How it works</a></li>
					<li class="nav-item"><a class="nav-link"
							href="{{ Route::is('home') ? '' : route('home') }}#faq">FAQ</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
					<li class="nav-item"><a class="nav-link"
							href="{{ Route::is('home') ? '' : route('home') }}#contact">Contact</a></li>
					<li class="nav-item">
						<a class="btn btn-dark px-4 ms-lg-2" href="{{ config('const.app_playstore_url') }}">Download</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<main>
		@yield('content')
	</main>

	<footer class="py-5">
		<div class="container">
			<div class="row g-4">
				<div class="col-md-6">
					<a class="d-inline-flex align-items-center gap-2 mb-2 text-decoration-none text-dark fw-bold"
						href="#">
						<span class="icon-circle bg-dark">
							<i class="bi bi-volume-up-fill fs-5"></i>
						</span>
						Quietly
					</a>
					<p class="text-muted mb-1">Precision control over your phone’s attention.</p>
					<small class="text-muted">© {{ date('Y') }} Quietly. All rights reserved.</small>
				</div>
				<div class="col-md-3">
					<h6 class="fw-semibold mb-3">Product</h6>
					<ul class="list-unstyled mb-0">
						<li><a class="footer-link" href="#features">Features</a></li>
						<li><a class="footer-link" href="#how-it-works">How it works</a></li>
						<li><a class="footer-link" href="#faq">FAQ</a></li>
						<li><a class="footer-link" href="{{ route('blog.index') }}">Blog</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<h6 class="fw-semibold mb-3">Legal</h6>
					<ul class="list-unstyled mb-0">
						<li><a class="footer-link" href="{{ route('PrivacyPolicy') }}">Privacy policy</a></li>
						<li><a class="footer-link" href="{{ route('TermsConditions') }}">Terms of service</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>

	<script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>

	<!-- Owl Carousel JS -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script> -->


	<script src="{{ asset('assets/front/js/custom.js') }}"></script>
	<!--Toastr -->
	<script src="{{asset('assets/ajax/toastr.min.js')}}"></script>
	<script src="{{ asset('assets/ajax/ajax.js') }}"></script>

	@stack('js')

</body>


</html>
</body>

</html>