@extends('front.layouts.main', ['seo' => [
'title' => 'Home',
'description' => 'Home',
'keywords' => 'Home' ,
'city' => '',
'state' => '',
'position' => ''
]
])
@section('content')
<!-- @section('title', 'About Us') -->

<!-- @push('style')

@endpush -->

<section class="hero-gradient py-5">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 text-white">
                <p class="text-uppercase fw-semibold text-white-50 mb-3">Android · Notification Intelligence</p>
                <h1 class="display-4 fw-bold mb-3">Control who can reach you and when</h1>
                <p class="lead mb-4 text-white-75">Create contact groups, set schedules, and fine-tune alerts so
                    every notification is intentional.</p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a class="btn btn-light btn-lg px-4 text-dark fw-semibold" href="{{ config('const.app_playstore_url') }}">Download for
                        Android</a>
                    <a class="btn btn-outline-light btn-lg px-4" href="#features">Explore product</a>
                </div>
                <div class="mt-4 d-flex gap-3 flex-wrap">
                    <span class="badge bg-light text-dark px-3 py-2">Unlimited groups</span>
                    <span class="badge bg-light text-dark px-3 py-2">Time-boxed modes</span>
                    <span class="badge bg-light text-dark px-3 py-2">On-device privacy</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card p-4">
                    <div class="bg-light rounded-4 p-5 text-center">
                        <i class="bi bi-phone display-1 text-dark d-block mb-4"></i>
                        <p class="fw-semibold text-secondary mb-0">Personalized notification stack preview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-semibold text-secondary mb-2">Core features</p>
            <h2 class="fw-bold">Everything you need to stay intentional</h2>
            <p class="text-muted">Quietly combines granular controls with a calm interface so you never miss the
                moments that matter.</p>
        </div>

        <div class="row g-4">
            @php
            $features = [
            ['title' => 'Group management', 'copy' => 'Create unlimited groups to separate work, family, emergencies, and custom scenarios.', 'icon' => 'bi-people-fill'],
            ['title' => 'Time-based activation', 'copy' => 'Enable groups for a couple of hours or a whole weekend and we revert when it ends.', 'icon' => 'bi-clock-history'],
            ['title' => 'Volume control', 'copy' => 'Tune ring, vibration, SMS, and app notifications from 0% to 100% per group.', 'icon' => 'bi-volume-up-fill'],
            ['title' => 'Custom settings', 'copy' => 'Give included and excluded contacts their own ringtones, vibration patterns, or behaviors.', 'icon' => 'bi-sliders'],
            ['title' => 'Smart notifications', 'copy' => 'Calls trigger the group profile instantly, then automatically fall back to default.', 'icon' => 'bi-bell-fill'],
            ['title' => 'Privacy focused', 'copy' => 'Quietly works entirely offline on your phone. No cloud sync, no tracking, no ads.', 'icon' => 'bi-shield-lock-fill'],
            ];
            @endphp
            @foreach ($features as $feature)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="icon-circle mb-4">
                            <span class="visually-hidden">{{ $feature['title'] }}</span>
                            <i class="bi {{ $feature['icon'] }} fs-4"></i>
                        </div>
                        <h5 class="fw-semibold">{{ $feature['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $feature['copy'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="how-it-works" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase text-secondary fw-semibold mb-2">How it works</p>
            <h2 class="fw-bold mb-3">Four simple steps to reclaim quiet</h2>
            <p class="text-muted">Quietly keeps your phone intentional with a lightweight workflow.</p>
        </div>
        @php
        $steps = [
        ['title' => 'Create groups', 'copy' => 'Label scenarios like “Focus”, “Family”, “Launch room”, or “Travel”.'],
        ['title' => 'Add contacts', 'copy' => 'Assign contacts or labels straight from your address book.'],
        ['title' => 'Configure rules', 'copy' => 'Define audio, vibration, fallback logic, and custom tones.'],
        ['title' => 'Activate & relax', 'copy' => 'Start a timer or toggle manually—Quietly handles the rest.'],
        ];
        @endphp
        <div class="row g-4">
            @foreach ($steps as $index => $step)
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center p-3">
                    <div class="step-circle mx-auto mb-3">{{ $index + 1 }}</div>
                    <h6 class="fw-semibold mb-2">{{ $step['title'] }}</h6>
                    <p class="text-muted mb-0">{{ $step['copy'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="granular-control" class="py-5 bg-light">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="text-uppercase text-secondary fw-semibold mb-2">Granular control</p>
                <h2 class="fw-bold mb-3">Fine-tune every signal</h2>
                <p class="text-muted mb-4">Quietly mirrors the original granular experience—three taps to adjust
                    tone levels, vibration intensity, and SMS behavior for both included and excluded contacts.</p>
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex gap-3">
                        <span class="control-icon">
                            <i class="bi bi-volume-up-fill fs-5"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-1">Ring & notification volume</h6>
                            <p class="text-muted mb-0">Set levels from silent to 100% for both sides of the group.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="control-icon">
                            <i class="bi bi-bell-fill fs-5"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-1">Smart notifications</h6>
                            <p class="text-muted mb-0">Automatically switch into the active profile the moment a call hits.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="control-icon">
                            <i class="bi bi-phone-vibrate fs-5"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-1">Vibration & SMS presets</h6>
                            <p class="text-muted mb-0">Dial in custom vibration strength or silence threads entirely.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="device-panel d-flex flex-column justify-content-center align-items-center text-center">
                    <i class="bi bi-phone-flip display-2 text-dark mb-3"></i>
                    <p class="text-muted mb-0">Live preview of your active Quietly profile.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-7">
                <p class="text-uppercase text-secondary fw-semibold mb-2">FAQ</p>
                <h2 class="fw-bold">Answers for the most common questions</h2>
                <p class="text-muted">Need more details? Open a ticket or drop us a note and we’ll help you
                    decide if Quietly matches your workflow.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    @foreach (getFaqs() as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $index }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section id="blog" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase text-secondary fw-semibold mb-2">Our Blog</p>
            <h2 class="fw-bold mb-3">Latest insights</h2>
            <p class="text-muted">Stay updated with the latest news and stories from Quietly.</p>
        </div>
        <div class="row g-4">
            @foreach ($latestBlogs as $blog)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    @if($blog->image)
                    <img src="{{ getImage($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">{{ $blog->published_at->format('M d, Y') }}</small>
                        </div>
                        <h5 class="fw-semibold mb-3">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none text-dark">{{ $blog->title }}</a>
                        </h5>
                        <p class="text-muted mb-4 flex-grow-1">{{ Str::limit($blog->short_description, 100) }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="fw-semibold text-primary text-decoration-none">Read more <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('blog.index') }}" class="btn btn-outline-dark px-4">View all articles</a>
        </div>
    </div>
</section>

<section class="cta-section py-5 text-center">
    <div class="container py-4">
        <h2 class="fw-bold display-6 mb-3">Take control of your notifications today</h2>
        <p class="lead text-white-50 mb-4">Download Quietly and replace chaos with intentional connectivity.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a class="btn btn-light btn-lg text-dark px-5" target="_blank" href="{{ config('const.app_playstore_url') }}">Download for Android</a>
            <a class="btn btn-outline-light btn-lg px-5" href="https://play.google.com" target="_blank"
                rel="noopener">View on Google Play</a>
        </div>
    </div>
</section>

<section id="contact" class="py-5 bg-light">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <p class="text-uppercase text-secondary fw-semibold mb-2">Contact us</p>
                <h2 class="fw-bold mb-3">We’re here when you need us</h2>
                <p class="text-muted mb-4">Questions about enterprise deployments, support, or partnerships?
                    Reach out and a Quietly specialist will respond within one business day.</p>
                <div class="contact-card p-4 mb-3 bg-white">
                    <div class="d-flex align-items-start gap-3">
                        <span class="icon-circle">
                            <i class="bi bi-envelope-fill fs-5"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-1">Email</h6>
                            <p class="mb-1 text-muted">{{ config('const.contactUs.email') }}</p>
                            <a href="mailto:{{ config('const.contactUs.email') }}" class="link-dark fw-semibold">Send an email</a>
                        </div>
                    </div>
                </div>
                <div class="contact-card p-4 bg-white">
                    <div class="d-flex align-items-start gap-3">
                        <span class="icon-circle">
                            <i class="bi bi-telephone-fill fs-5"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-1">Phone & chat</h6>
                            <p class="mb-1 text-muted"><a href="tel:{{ config('const.contactUs.contact') }}" class="text-muted ">+91 {{ config('const.contactUs.contact') }}</a></p>
                            <small class="text-muted">Mon–Fri · 9am – 6pm PST</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="contact-card p-4 bg-white h-100">
                    <h5 class="fw-semibold mb-2">Send us a message</h5>
                    <p class="text-muted mb-4">Tell us about your use case and we’ll tailor setup guidance.</p>
                    <form action="{{ route('storeContactUs') }}" method="POST" data-reset="true" class="row g-3 formaction">
                        @csrf
                        <div class="col-md-6">
                            <label for="contactName" class="form-label">Name</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="contactName"
                                placeholder="Jane Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contactEmail" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" name="email" id="contactEmail"
                                placeholder="you@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contactPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control form-control-lg" name="phone" id="contactPhone"
                                placeholder="1234567890" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contactSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control form-control-lg" name="subject" id="contactSubject"
                                placeholder="Subject" required>
                        </div>
                        <div class="col-12">
                            <label for="contactMessage" class="form-label">Message</label>
                            <textarea name="message" id="contactMessage" rows="4" class="form-control form-control-lg"
                                placeholder="How can we help?" required></textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <small class="text-muted">We’ll never share your details.</small>
                            <button type="submit" class="btn btn-primary-custom btn-lg px-4">Send message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')

<script>

</script>

@endpush

@endsection