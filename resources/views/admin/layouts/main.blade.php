<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('const.site_setting.name') }} | Admin</title>

  <!-- Bootstrap 5 CSS -->
  <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="{{ asset('assets/admin/css/bootstrap-icons.min.css') }}" rel="stylesheet">
  <!--Toastr -->
  <link href="{{ asset('assets/ajax/toastr.css') }}" rel="stylesheet" />
  <!-- Custom Admin CSS -->
  <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  @stack('style')
</head>

<body>

  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
      <!-- Top Navigation -->
      @include('admin.layouts.navbar')

      <!-- Main Content -->
      <div class="container-fluid px-4 py-4">
        @yield('content')
      </div>

      <!-- Footer -->
      @include('admin.layouts.footer')
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
  <!-- jQuery (Still useful for some plugins) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Toastr & Custom JS if needed -->
  <script src="{{ asset('assets/ajax/toastr.min.js') }}"></script>
  <script src="{{ asset('assets/ajax/ajax.js') }}"></script>

  <script>
    // Toggle Sidebar
    document.getElementById("menu-toggle")?.addEventListener("click", function(e) {
      e.preventDefault();
      document.getElementById("wrapper").classList.toggle("toggled");
    });

    // Dark Mode Toggle
    const toggleButton = document.getElementById('dark-mode-toggle');
    const icon = toggleButton.querySelector('i');
    const html = document.documentElement;

    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
      html.setAttribute('data-theme', 'dark');
      icon.classList.remove('bi-moon');
      icon.classList.add('bi-sun');
    }

    toggleButton.addEventListener('click', function() {
      if (html.getAttribute('data-theme') === 'dark') {
        html.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
        icon.classList.remove('bi-sun');
        icon.classList.add('bi-moon');
      } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        icon.classList.remove('bi-moon');
        icon.classList.add('bi-sun');
      }
    });
  </script>

  @stack('js')

</body>

</html>