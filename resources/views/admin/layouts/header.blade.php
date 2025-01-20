<!-- Header -->
<div class="header">

  <!-- Logo -->
  <div class="header-left">
    <a href="index.html" class="logo">
      <img src="{{ url('admin/assets/img/logo.png') }}" alt="Logo">
    </a>
    <a href="index.html" class="logo logo-small">
      <img src="{{ url('admin/assets/img/logo-small.png') }}" alt="Logo" width="30" height="30">
    </a>
  </div>
  <!-- /Logo -->

  <a href="javascript:void(0);" id="toggle_btn">
    <i class="fe fe-text-align-left"></i>
  </a>

  <div class="top-nav-search">
    <form>
      <input type="text" class="form-control" placeholder="Search here">
      <button class="btn" type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>

  <!-- Mobile Menu Toggle -->
  <a class="mobile_btn" id="mobile_btn">
    <i class="fa fa-bars"></i>
  </a>
  <!-- /Mobile Menu Toggle -->

  <!-- Header Right Menu -->
  <ul class="nav user-menu">

    <!-- Notifications -->
    <li class="nav-item dropdown noti-dropdown">
      <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <i class="fe fe-bell"></i>
        <span class="badge badge-pill" id="notification-count">
          {{ Auth::guard('admin')->user()->unreadNotifications->count() }}
        </span>
      </a>
      <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
          <span class="notification-title">Notifications</span>
          <a href="javascript:void(0)" class="clear-noti" id="mark-all-read">Clear All</a>
        </div>
        <div class="noti-content">
          <ul class="notification-list">
            @forelse(Auth::guard('admin')->user()->notifications()->latest()->take(5)->get() as $notification)
              <li class="notification-message {{ $notification->read_at ? '' : 'unread' }}">
                <a href="{{ isset($notification->data['problem_id']) ? route('problems.show', $notification->data['problem_id']) : '#' }}"
                   data-notification-id="{{ $notification->id }}">
                  <div class="media">
                    <span class="avatar avatar-sm">
                      <img class="avatar-img rounded-circle" alt="User Image"
                           src="{{ asset('storage/image/profile/' . ($notification->data['user_photo'] ?? 'avatar.png')) }}">
                    </span>
                    <div class="media-body">
                      <p class="noti-details">
                        <span class="noti-title">{{ $notification->data['message'] }}</span>
                      </p>
                      <p class="noti-time">
                        <span class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                      </p>
                    </div>
                  </div>
                </a>
              </li>
            @empty
              <li class="notification-message">
                <div class="media">
                  <div class="media-body">
                    <p class="noti-details text-center">No new notifications</p>
                  </div>
                </div>
              </li>
            @endforelse
          </ul>
        </div>
        <div class="topnav-dropdown-footer">
          <a href="{{ route('notifications.index') }}">View all Notifications</a>
        </div>
      </div>
    </li>
    <!-- /Notifications -->

    <!-- User Menu -->
    <li class="nav-item dropdown has-arrow">
      <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <span class="user-img"><img class="rounded-circle" src="{{ url('storage/image/profile/' . Auth::guard('admin')?->user()?->photo) }}" width="31" alt="Ryan Taylor"></span>
      </a>
      <div class="dropdown-menu">
        <div class="user-header">
          <div class="avatar avatar-sm">
            <img src="{{ url('storage/image/profile/' . Auth::guard('admin')?->user()?->photo) }}" alt="User Image" class="avatar-img rounded-circle">
          </div>
          <div class="user-text">
            <h6>{{ Auth::guard('admin')?->user()?->name }}</h6>
            <p class="text-muted mb-0">{{ Auth::guard('admin')?->user()?->role?->name }}</p>
          </div>
        </div>
        <a class="dropdown-item" href="{{ route('profile.index') }}">My Profile</a>
        <a class="dropdown-item" href="settings.html">Settings</a>
        <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
      </div>
    </li>
    <!-- /User Menu -->

  </ul>
  <!-- /Header Right Menu -->

</div>
<!-- /Header -->
