<!-- SVG template for Sidebar submenus -->
<template id="commit-node-icon">
  <svg aria-hidden="true" focusable="false" class="Octicon-sc-9kayk9-0" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom; overflow: visible;">
    <path d="M11.93 8.5a4.002 4.002 0 0 1-7.86 0H.75a.75.75 0 0 1 0-1.5h3.32a4.002 4.002 0 0 1 7.86 0h3.32a.75.75 0 0 1 0 1.5Zm-1.43-.75a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0Z"></path>
  </svg>
</template>

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

        <!-- Slider -->
        @if (in_array('Slider', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('slider*') ? 'active' : '' }}">
            <a href="{{ route('slider.index') }}"><i class="fe fe-desktop"></i> <span>Slider</span></a>
          </li>
        @endif

        <li class="menu-title">
          <span>Admin Options</span>
        </li>

        <!-- Hall Options -->
        @if (in_array('Admins', isset($permissions) ? $permissions : []))
          <!-- <li class="menu-title"> <span>Hall Options</span> </li> -->
          <li class="submenu {{ Request::is('hall*') || Request::is('role*') || Request::is('permission*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('hall*') || Request::is('role*') || Request::is('permission*') ? 'true' : 'false' }}">
              <i class="fa fa-university" style="font-size: 20px;"></i> <span> Manage Halls</span> <span class="menu-arrow"></span>
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
          <li class="submenu {{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'open active-parent' : '' }}">
            <a href="#" aria-expanded="{{ Request::is('admin-user*') || Request::is('role*') || Request::is('permission*') ? 'true' : 'false' }}">
              <i class="fe fe-check-verified"></i> <span> Admin User</span> <span class="menu-arrow"></span>
            </a>
            <ul>
              <li><a href="{{ route('admin-user.index') }}" class="{{ Request::routeIs('admin-user.index') ? 'active' : '' }}" aria-current="{{ Request::routeIs('admin-user.index') ? 'page' : '' }}"><span class="icon"></span> Hall Residents</a></li>
              <li><a href="{{ route('admin-user.admin') }}" class="{{ Request::routeIs('admin-user.admin') ? 'active' : '' }}" aria-current="{{ Request::routeIs('admin-user.admin') ? 'page' : '' }}"><span class="icon"></span> Admins</a></li>
              <li><a href="{{ route('role.index') }}" class="{{ Request::routeIs('role.index') ? 'active' : '' }}" aria-current="{{ Request::routeIs('role.index') ? 'page' : '' }}"><span class="icon"></span> Role</a></li>
              <li><a href="{{ route('permission.index') }}" class="{{ Request::routeIs('permission.index') ? 'active' : '' }}" aria-current="{{ Request::routeIs('permission.index') ? 'page' : '' }}"><span class="icon"></span> Permission</a></li>
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

        <!-- Pages Section -->
        <li class="menu-title">
          <span>Pages</span>
        </li>
        <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
          <a href="{{ route('profile.index') }}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
        </li>

        <!-- Transaction -->
        @if (in_array('Transaction', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('transaction*') ? 'active' : '' }}">
            <a href=""><i class="fa-regular fa-money-bill-1" style="font-size: 18px;"></i> <span>Transaction</span></a>
          </li>
        @endif

        <!-- Problem Section -->
        <li class="submenu {{ Request::is('problems*') ? 'open active-parent' : '' }}">
          <a href="#" aria-expanded="{{ Request::is('problems*') ? 'true' : 'false' }}">
            <i class="fe fe-warning"></i> <span>Problems</span> <span class="menu-arrow"></span>
          </a>
          <ul>
            @if(in_array('problems', $permissions ?? []))
              {{-- Admin View --}}
              <li><a href="{{ route('problems.index') }}" class="{{ Request::routeIs('problems.index') ? 'active' : '' }}">
                <span class="icon"></span> All Posts
              </a></li>
              <li><a href="{{ route('problems.index') }}?status=pending" class="{{ Request::routeIs('problems.index') && request('status') == 'pending' ? 'active' : '' }}">
                <span class="icon"></span> Pending Posts
              </a></li>
              <li><a href="{{ route('problems.trashed') }}" class="{{ Request::routeIs('problems.trashed') ? 'active' : '' }}">
                <span class="icon"></span> Trashed Posts
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

        <!-- Applications -->
        @if (in_array('Applications', $permissions ?? []))
          <li class="{{ Request::is('applications*') ? 'active' : '' }}">
            <a href="{{ route('applications.index') }}">
              <i class="fe fe-bug"></i> <span>Applications</span>
            </a>
          </li>
        @else
          <!-- For users to see their own applications -->
          <li class="{{ Request::is('application*') ? 'active' : '' }}">
            <a href="{{ route('applications.user.index') }}">
              <i class="fe fe-bug"></i> <span>Application</span>
            </a>
          </li>
        @endif

        <!-- Meals -->
        @if (in_array('Meals', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('meals*') ? 'active' : '' }}">
            <a href=""><i class="fa-solid fa-utensils" style="font-size: 18px;"></i> <span>Meals</span></a>
          </li>
        @endif

        <!-- Fields -->
        @if (in_array('Fields', isset($permissions) ? $permissions : []))
          <li class="{{ Request::is('fields*') ? 'active' : '' }}">
            <a href=""><i class="fa-regular fa-map" style="font-size: 18px;"></i> <span>Fields</span></a>
          </li>
        @endif

      </ul>
    </div>
  </div>
</div>
<!-- /Sidebar -->
