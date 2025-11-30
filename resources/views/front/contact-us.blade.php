@extends('front.layouts.main')
@section('content')
<!-- @section('title', 'About Us') -->

<!-- @push('style')

@endpush -->

<section id="hero_banner" class="round-header">
    <div class="container round">
        <h1>Contact Us</h1>
        <p class="text-center text-white">Take a little break from the work strss of everyday. Discover plan trip and
            explore beautiful destinations.</p>
    </div>
</section>
<section id="contact-us-form">
    <div class="container">
        <h2>Contact Us</h2>
        <p>Reach Out , Ride On and Connect with {{ config('const.site_setting.name') }} Cabs & Taxi Service Today</p>
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="card">
                    <form action="{{ route('storeContactUs') }}" data-reset="true" method="post" class="serversidevalidation">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input value="" type="text" name="name" class="form-control required">

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email ID <span class="text-danger">*</span></label>
                                <input value="" type="email" name="email" class="form-control required">

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone No. <span class="text-danger">*</span></label>
                                <input value="" type="text" name="phone" class="form-control required">

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input value="" type="text" name="subject" class="form-control required">

                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-start">Your Message <span class="text-danger">*</span></label>
                                <textarea class="form-control ht-120 required" name="message" ></textarea>

                            </div>
                        </div>
                        <button type="submit" class="mt-3 btn_action">
                            <span id="buttonText">Submit</span>
                            <span id="loader" class="d-none">Loading ...</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-danger">
                            <div class="card-title">Drop a Mail</div>
                            <a href="mailto:{{ config('const.contactUs.email') }}">{{ config('const.contactUs.email') }}</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-title">Call Us</div>
                            <a href="tel:+91{{ config('const.contactUs.contact') }}">+91 {{ trim(preg_replace('/(.{5})/', '$1 ', config('const.contactUs.contact'))) }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <p>At {{ config('const.site_setting.name') }} Cabs, we’re committed to providing you with the best taxi service in Ahmedabad. Whether you need a quick ride across town, a comfortable airport transfer, or a reliable vehicle for a day trip, we’re here to help. Our team is dedicated to ensuring your journey is safe, timely, and hassle-free.<br><br>
                        If you have any questions, need assistance with booking, or want to learn more about our services, please don’t hesitate to reach out. We value your feedback and are always eager to assist you in any way we can. Your satisfaction is our top priority !<br><br>
                        Our customer service team is available 24/7 to assist you. Reach out to us anytime, and we’ll be happy to assist you with your travel needs.<br><br>
                        Thank you for choosing {{ config('const.site_setting.name') }} Cabs – where every ride is a great experience!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

</section>



@endsection