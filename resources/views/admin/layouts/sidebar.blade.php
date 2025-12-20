<div class="" id="sidebar-wrapper">
  <div class="sidebar-heading text-center py-2 primary-text fs-4 fw-bold text-uppercase border-bottom text-dark">
    {{ config('const.site_setting.name') }}
  </div>
  <div class="list-group list-group-flush my-3">
    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="{{ route('admin.user.index') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
      <i class="bi bi-people me-2"></i>Users
    </a>
    <a href="{{ route('admin.plan.index') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.plan*') && !request()->routeIs('admin.plan-purchase*') ? 'active' : '' }}">
      <i class="bi bi-tags me-2"></i>Plans
    </a>
    <a href="{{ route('admin.plan-purchase.index') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.plan-purchase*') ? 'active' : '' }}">
      <i class="bi bi-cart me-2"></i>Purchases
    </a>
    <a href="{{ route('admin.lagel-pages') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.lagel-pages*') ? 'active' : '' }}">
      <i class="bi bi-file-earmark-text me-2"></i>Legal Pages
    </a>
    <a href="{{ route('admin.blog.index') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
      <i class="bi bi-journal-richtext me-2"></i>Blog
    </a>
    <a href="{{ route('admin.contact-us.index') }}" class="list-group-item list-group-item-action second-text {{ Route::is('admin.contact-us.index') ? 'active' : '' }}">
      <i class="bi bi-envelope me-2"></i>Contact Us
    </a>
    <a href="{{ route('admin.faq.index') }}" class="list-group-item list-group-item-action second-text {{ Route::is('admin.faq.index') ? 'active' : '' }}">
      <i class="bi bi-question-circle me-2"></i>FAQ
    </a>
    <a href="{{ route('admin.seo.index') }}" class="list-group-item list-group-item-action second-text {{ request()->routeIs('admin.seo*') ? 'active' : '' }}">
      <i class="bi bi-globe me-2"></i>SEO
    </a>

    <!-- Settings Dropdown logic in BS5 usually handled differently or just flat list -->
    <a href="#settingsSubmenu" class="list-group-item list-group-item-action second-text fw-bold" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.setting*') ? 'true' : 'false' }}">
      <i class="bi bi-gear me-2"></i>Settings
    </a>
    <div class="collapse {{ request()->routeIs('admin.setting*') ? 'show' : '' }}" id="settingsSubmenu">
      <a href="{{ route('admin.setting.profile') }}" class="list-group-item list-group-item-action second-text ps-5 {{ request()->routeIs('admin.setting.profile*') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i>Profile
      </a>
    </div>

    <a href="{{ route('admin.logout') }}" class="list-group-item list-group-item-action text-danger fw-bold">
      <i class="bi bi-power me-2"></i>Logout
    </a>
  </div>
</div>