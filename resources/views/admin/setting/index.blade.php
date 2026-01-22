@extends('admin.layouts.main')
@section('title', 'Settings')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Settings</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
      </ol>
    </nav>
  </div>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="ads-tab" data-bs-toggle="tab" data-bs-target="#ads" type="button" role="tab" aria-controls="ads" aria-selected="false">Ads</button>
  </li>
</ul>

<!-- Tab content -->
<div class="tab-content" id="settingsTabContent">
  <!-- Profile Tab -->
  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    @include('admin.setting.profile_content', ['user' => $user])
  </div>

  <!-- Ads Tab -->
  <div class="tab-pane fade" id="ads" role="tabpanel" aria-labelledby="ads-tab">
    @include('admin.setting.ads_content', ['settings' => $settings])
  </div>
</div>

@endsection

@push('js')
<script>
  $('.avtar_input').on('change', function(event) {
    var input = event.target;
    var image = $('.avtar_img');
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        image.attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  })
</script>
@endpush
