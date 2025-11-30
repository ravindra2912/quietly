@extends('front.layouts.main')

@section('title', 'Privacy Policy')

@push('style')
<style>
    .legal-hero {
        background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.2), transparent 55%),
            linear-gradient(135deg, #111827, #1f2937);
        color: #fff;
    }

    .legal-hero .badge {
        letter-spacing: 0.08em;
    }

    .legal-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(15, 23, 42, 0.08);
        padding: clamp(1.5rem, 4vw, 3rem);
    }
</style>
@endpush

@section('content')
    <section class="legal-hero py-5">
        <div class="container py-4">
            <span class="badge bg-light text-dark mb-3 text-uppercase">Policy</span>
            <h1 class="display-5 fw-bold mb-3">Privacy Policy</h1>
            <p class="lead text-white-50 mb-0">Learn how Quietly collects, stores, and protects your information.</p>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="legal-card">
                {!! getLegalPage('PrivacyPolicy')->description !!}
            </div>
        </div>
    </section>
@endsection