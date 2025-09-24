<aside class="left-sidebar" style="background:#fff; color:#222;">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between py-3 px-3 border-bottom" style="background:#f8f9fa;">
      <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img">
        <img style="height:60px; width:auto;" src="{{ asset('assets/img/logo.jpg') }}" alt="">
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8" style="color:#71cd14;"></i>
      </div>
    </div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav" class="pt-3">
        <li class="nav-small-cap text-uppercase fw-bold mb-2" style="color:#71cd14;">
          <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
          <span class="hide-menu">Main</span>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Dashboard</span>
          </a>
        </li>
       <li class="sidebar-item mb-1">
  <a class="sidebar-link {{ Request::routeIs('admin.banner-images.*') ? 'active' : '' }}" 
     href="{{ route('admin.banner-images.index') }}">
     
    <span>
      <iconify-icon icon="mdi:image-multiple-outline" class="fs-6"></iconify-icon>
    </span>
    
    <span class="hide-menu ms-2">Banner Images</span>
  </a>
</li>

        <!-- Products Dropdown -->
        <li class="sidebar-item mb-1">
          <a class="sidebar-link has-arrow {{ Request::routeIs('admin.products.*') ? 'active' : '' }}" href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Request::routeIs('admin.products.*') ? 'true' : 'false' }}">
            <span><iconify-icon icon="solar:box-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Products</span>
          </a>
          <ul class="collapse list-unstyled {{ Request::routeIs('admin.products.*') ? 'show' : '' }}" id="productsSubmenu">
            <li class="sidebar-subitem">
              <a class="sidebar-link {{ Request::routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <span class="ms-4">Index</span>
              </a>
            </li>
            <li class="sidebar-subitem">
              <a class="sidebar-link {{ Request::routeIs('admin.products.create') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">
                <span class="ms-4">Create</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
            <span><iconify-icon icon="solar:file-text-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Blog</span>
          </a>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
<span>
  <iconify-icon icon="mdi:tag" class="fs-6"></iconify-icon>
</span>
            <span class="hide-menu ms-2">Tag</span>
          </a>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <span><iconify-icon icon="solar:box-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Categories</span>
          </a>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('subcategories.*') ? 'active' : '' }}" href="{{ route('admin.subcategories.index') }}">
            <span><iconify-icon icon="solar:box-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Sub Categories</span>
          </a>
        </li>
        <li class="sidebar-item mb-1">
          <a class="sidebar-link {{ Request::routeIs('checkout-options.*') ? 'active' : '' }}" href="{{ route('admin.checkout-options.index') }}">
<span><iconify-icon icon="solar:cart-check-bold-duotone" class="fs-6"></iconify-icon></span>
            <span class="hide-menu ms-2">Checkout Options</span>
          </a>
        </li>
      <a class="sidebar-link {{ Request::routeIs('admin.contact-messages.*') ? 'active' : '' }}"
   href="{{ route('admin.contact-messages.index') }}">
   <span><iconify-icon icon="solar:cart-check-bold-duotone" class="fs-6"></iconify-icon></span>
   <span class="hide-menu ms-2">Contact</span>
</a>

       <li class="sidebar-item mb-1">
  <a class="sidebar-link {{ Request::routeIs('policy.*') ? 'active' : '' }}" href="{{ route('admin.policy.index') }}">
    <span><iconify-icon icon="mdi:file-document-edit-outline" class="fs-6"></iconify-icon></span>
    <span class="hide-menu ms-2">Policy</span>
  </a>
</li>
 <li class="sidebar-item mb-1">
    <a class="sidebar-link {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
      <span><iconify-icon icon="mdi:clipboard-list-outline" class="fs-6"></iconify-icon></span>
      <span class="hide-menu ms-2">Orders</span>
    </a>
  </li>

        <!-- Add more main routes as needed -->
      </ul>
    </nav>
  </div>
</aside>

<style>
.left-sidebar {
  min-height: 100vh;
  box-shadow: 0 0 20px rgba(0,0,0,0.08);
  background: #fff;
}
.sidebar-link {
  display: flex;
  align-items: center;
  padding: 10px 18px;
  border-radius: 6px;
  color: #222;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
  font-weight: 500;
}
.sidebar-link.active, .sidebar-link:hover {
  background: #71cd14 !important;
  color: #222 !important;   /* Black text for active/hover */
  box-shadow: 0 2px 8px rgba(113,205,20,0.08);
}
.hide-menu {
  font-size: 1rem;
}
.nav-small-cap {
  font-size: 0.95rem;
  letter-spacing: 1px;
}
.sidebar-subitem .sidebar-link {
  padding-left: 36px;
  font-size: 0.98rem;
}
.sidebar-link.has-arrow:after {
  content: "\25BC";
  font-size: 0.7em;
  margin-left: auto;
  color: #71cd14;
  transition: transform 0.2s;
}
.sidebar-link[aria-expanded="true"].has-arrow:after {
  transform: rotate(-180deg);
}
@media (max-width: 991px) {
  .left-sidebar {
    width: 100%;
    position: fixed;
    z-index: 999;
  }
}
</style>