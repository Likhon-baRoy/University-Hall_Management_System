<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
	<div id="sidebar-menu" class="sidebar-menu">
	  <h2 style="text-align: center; color: #91f1ff; font-size: 24px; margin-bottom: 20px; font-weight: bold;">ADMIN PANEL</h2> <hr/>
	  <ul>
		<li class="menu-title">
          <span>Main</span>
		</li>
		<li>
		  <a href="#"><i class="fe fe-home"></i> <span>Dashboard</span></a>
		</li>

        @if (in_array('Slider', json_decode(Auth::guard('admin') -> user() -> role -> permissions)))
          <li>
		    <a href="#"><i class="fe fe-desktop"></i> <span>Slider</span></a>
		  </li>
        @endif

        @if (in_array('Testimonials', json_decode(Auth::guard('admin') -> user() -> role -> permissions)))
          <li>
		    <a href="#"><i class="fe fe-commenting"></i> <span>Testimonials</span></a>
		  </li>
        @endif

        @if (in_array('Clients', json_decode(Auth::guard('admin') -> user() -> role -> permissions)))
          <li>
		    <a href="#"><i class="fe fe-user"></i> <span>Our Clients</span></a>
		  </li>
        @endif
        <li>

          @if( in_array('Protfolio', json_decode(Auth::guard('admin') -> user() -> role -> permissions)) )
            <li class="submenu">
              <a href="#"><i class="fe fe-beginner"></i> <span> Protfolio</span> <span class="menu-arrow"></span></a>
              <ul style="display: none;">
                <li><a href="#">Protfolio</a></li>
                <li><a href="#">Tag</a></li>
                <li><a href="#">Categories</a></li>
              </ul>
            </li>
		</li>
        <li>
		  <a href="#"><i class="fe fe-users"></i> <span>Our Team</span></a>
		</li>
          @endif

          @if( in_array('Posts', json_decode(Auth::guard('admin') -> user() -> role -> permissions)) )
		    <li class="submenu">
		      <a href="#"><i class="fe fe-document"></i> <span> Posts</span> <span class="menu-arrow"></span></a>
		      <ul style="display: none;">
			    <li><a href="#">All Posts</a></li>
			    <li><a href="#">Categories</a></li>
			    <li><a href="#">Tag</a></li>
		      </ul>
		    </li>
          @endif

          @if( in_array('Admins', json_decode(Auth::guard('admin') -> user() -> role -> permissions)) )
            <li class="menu-title">
		      <span>Admin Options</span>
		    </li>
            <li class="submenu">
		      <a href="#"><i class="fe fe-check-verified"></i> <span> Admin User</span> <span class="menu-arrow"></span></a>
		      <ul style="display: none;">
			    <li><a href="{{ route('admin-user.index') }}">Users</a></li>
			    <li><a href="{{ route('role.index') }}">Role</a></li>
			    <li><a href="{{ route('permission.index') }}">Permission</a></li>
		      </ul>
		    </li>
          @endif

	  </ul>
	</div>
  </div>
</div>
<!-- /Sidebar -->
