<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      @php
      // Cache permissions to avoid redundant calls
      $permissions = json_decode(Auth::guard('admin')->user()?->role?->permissions, true);
      @endphp

      <ul>
        <!-- Main Section -->
        <li class="menu-title">
          <span>Main</span>
        </li>
        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
        </li>

        <!-- Problem Management Section -->
        <li class="menu-title">
          <span>Problem Management</span>
        </li>
        <li class="submenu {{ Request::is('problems*') ? 'open active-parent' : '' }}">
          <a href="#" aria-expanded="{{ Request::is('problems*') ? 'true' : 'false' }}">
            <i class="fe fe-warning"></i> <span>Problems</span> <span class="menu-arrow"></span>
          </a>
          <ul>
            @if(in_array('problems', $permissions ?? []))
              {{-- Admin View --}}
              <li><a href="{{ route('problems.index') }}" class="{{ Request::routeIs('problems.index') ? 'active' : '' }}">
                <span class="icon"></span> All Problems
              </a></li>
              <li><a href="{{ route('problems.index') }}?status=pending" class="{{ Request::routeIs('problems.index') && request('status') == 'pending' ? 'active' : '' }}">
                <span class="icon"></span> Pending Problems
              </a></li>
              <li><a href="{{ route('problems.trashed') }}" class="{{ Request::routeIs('problems.trashed') ? 'active' : '' }}">
                <span class="icon"></span> Trash
              </a></li>
            @else
              {{-- User View --}}
              <li><a href="{{ route('problems.index') }}" class="{{ Request::routeIs('problems.index') ? 'active' : '' }}">
                <span class="icon"></span> My Problems
              </a></li>
              <li><a href="{{ route('problems.create') }}" class="{{ Request::routeIs('problems.create') ? 'active' : '' }}">
                <span class="icon"></span> Report Problem
              </a></li>
            @endif
          </ul>
        </li>

        <!-- Slider -->
        @if (in_array('Slider', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('slider*') ? 'active' : '' }}">
            <a href="{{ route('slider.index') }}"><i class="fe fe-desktop"></i> <span>Slider</span></a>
          </li>
        @endif

        <!-- Testimonials -->
        @if (in_array('Testimonials', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('testimonials*') ? 'active' : '' }}">
            <a href="  "><i class="fe fe-commenting"></i> <span>Testimonials</span></a>
          </li>
        @endif

        <!-- Posts -->
        @if (in_array('Posts', isset($permissions) ? $permissions : []))
          <li class="submenu {{ Request::is('posts*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('posts*') ? 'true' : 'false' }}">
              <i class="fe fe-document"></i> <span> Posts</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="  " class="{{ Request::is('posts') ? 'active' : '' }}"><span class="icon"></span> All Posts</a></li>
              <li><a href="  " class="{{ Request::is('posts/categories') ? 'active' : '' }}"><span class="icon"></span> Categories</a></li>
              <li><a href="  " class="{{ Request::is('posts/tag') ? 'active' : '' }}"><span class="icon"></span> Tag</a></li>
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

        <!-- Hall Options -->
        @if (in_array('Admins', isset($permissions) ? $permissions : []))
          <li class="menu-title">
            <span>Hall Options</span>
          </li>
          <li class="submenu {{ Request::is('hall*') || Request::is('role*') || Request::is('permission*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('hall*') || Request::is('role*') || Request::is('permission*') ? 'true' : 'false' }}">
              <i class="fa fa-university" style="font-size: 18px;"></i> <span> Manage Halls</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="{{ route('hall.index') }}" class="{{ Request::routeIs('hall.index') ? 'active' : '' }}"><span class="icon"></span> Halls</a></li>
              <li><a href="{{ route('hall-room.index') }}" class="{{ Request::routeIs('hall-room.index') ? 'active' : '' }}"><span class="icon"></span> Rooms</a></li>
              <li><a href="{{ route('hall-seat.index') }}" class="{{ Request::routeIs('hall-seat.index') ? 'active' : '' }}"><span class="icon"></span> Seats</a></li>
            </ul>
          </li>
        @endif

        <!-- Admin Options -->
        @if (in_array('Admins', isset($permissions) ? $permissions : []))
          <li class="menu-title">
            <span>Admin Options</span>
          </li>
          <li class="submenu {{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'true' : 'false' }}">
              <i class="fe fe-check-verified"></i> <span> Admin User</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="{{ route('admin-user.index') }}" class="{{ Request::routeIs('admin-user.index') ? 'active' : '' }}"><span class="icon"></span> Users</a></li>
              <li><a href="{{ route('role.index') }}" class="{{ Request::routeIs('role.index') ? 'active' : '' }}"><span class="icon"></span> Role</a></li>
              <li><a href="{{ route('permission.index') }}" class="{{ Request::routeIs('permission.index') ? 'active' : '' }}"><span class="icon"></span> Permission</a></li>
            </ul>
          </li>
        @endif

        <!-- Notice Options -->
        @if (in_array('Notices', isset($permissions) ? $permissions : []))
          <li class="submenu {{ Request::is('admin/notices*') ? 'open active-parent' : '' }}">
            <a href="#"><i class="fe fe-document"></i> <span>Notices</span> <span class="menu-arrow"></span></a>
            <ul>
              <li><a href="{{ route('admin.notices.index') }}" class="{{ Request::routeIs('admin.notices.index') ? 'active' : '' }}"><span class="icon"></span> All Notices</a></li>
              <li><a href="{{ route('admin.notices.create') }}" class="{{ Request::routeIs('admin.notices.create') ? 'active' : '' }}"><span class="icon"></span> Add Notice</a></li>
              <li><a href="{{ route('admin.notices.trashed') }}" class="{{ Request::routeIs('admin.notices.trashed') ? 'active' : '' }}"><span class="icon"></span> Trash</a></li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</div>
<!-- /Sidebar -->
