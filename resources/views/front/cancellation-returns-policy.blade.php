@extends('front.layouts.main')
@section('content')
<!-- @section('title', 'About Us') -->

<!-- @push('style')

@endpush -->

<section id="hero_banner" class="round-header">
    <div class="container">
        <h1>Terms Conditions</h1>
        </form>
    </div>
</section>
<section class="pp bg-white">
    <div class="container pp-inner">
        {!! getLegalPage('CancellationAndReturnPolicy')->description !!}
    </div>
</section>



@endsection