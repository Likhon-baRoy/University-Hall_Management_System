
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      @php
      // Cache permissions to avoid redundant calls
      $permissions = json_decode(Auth::guard('admin')->user()->role->permissions, true);
      @endphp

      <ul>
        <!-- Main Section -->
        <li class="menu-title">
          <span>Main</span>
        </li>
        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
        </li>

        <!-- Slider -->
        @if (in_array('Slider', $permissions))
          <li class="{{ Request::is('slider*') ? 'active' : '' }}">
            <a href="  "><i class="fe fe-desktop"></i> <span>Slider</span></a>
          </li>
        @endif

        <!-- Testimonials -->
        @if (in_array('Testimonials', $permissions))
          <li class="{{ Request::is('testimonials*') ? 'active' : '' }}">
            <a href="  "><i class="fe fe-commenting"></i> <span>Testimonials</span></a>
          </li>
        @endif

        <!-- Our Clients -->
        @if (in_array('Clients', $permissions))
          <li class="{{ Request::is('clients*') ? 'active' : '' }}">
            <a href="  "><i class="fe fe-user"></i> <span>Our Clients</span></a>
          </li>
        @endif

        <!-- Protfolio -->
        @if (in_array('Protfolio', $permissions))
          <li class="submenu {{ Request::is('protfolio*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('protfolio*') ? 'true' : 'false' }}">
              <i class="fe fe-beginner"></i> <span> Protfolio</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="  " class="{{ Request::is('protfolio') ? 'active' : '' }}">Protfolio</a></li>
              <li><a href="  " class="{{ Request::is('protfolio/tag') ? 'active' : '' }}">Tag</a></li>
              <li><a href="  " class="{{ Request::is('protfolio/categories') ? 'active' : '' }}">Categories</a></li>
            </ul>
          </li>
        @endif

        <!-- Posts -->
        @if (in_array('Posts', $permissions))
          <li class="submenu {{ Request::is('posts*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('posts*') ? 'true' : 'false' }}">
              <i class="fe fe-document"></i> <span> Posts</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="  " class="{{ Request::is('posts') ? 'active' : '' }}">All Posts</a></li>
              <li><a href="  " class="{{ Request::is('posts/categories') ? 'active' : '' }}">Categories</a></li>
              <li><a href="  " class="{{ Request::is('posts/tag') ? 'active' : '' }}">Tag</a></li>
            </ul>
          </li>
        @endif

        <!-- Pages Section -->
        <li class="menu-title">
          <span>Pages</span>
        </li>
        <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
          <a href="{{ route('profile.index') }}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
        </li>

        <!-- Admin Options -->
        @if (in_array('Admins', $permissions))
          <li class="menu-title">
            <span>Admin Options</span>
          </li>
          <li class="submenu {{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'true' : 'false' }}">
              <i class="fe fe-check-verified"></i> <span> Admin User</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="{{ route('admin-user.index') }}" class="{{ Request::routeIs('admin-user.index') ? 'active' : '' }}">Users</a></li>
              <li><a href="{{ route('role.index') }}" class="{{ Request::routeIs('role.index') ? 'active' : '' }}">Role</a></li>
              <li><a href="{{ route('permission.index') }}" class="{{ Request::routeIs('permission.index') ? 'active' : '' }}">Permission</a></li>
            </ul>
          </li>
        @endif

      </ul>
    </div>
  </div>
</div>
<!-- /Sidebar -->
