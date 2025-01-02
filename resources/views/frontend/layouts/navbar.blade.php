<!---------------------------------------------- Navbar Start --------------------------------------------->
<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top p-0 mt-2 mb-2">
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
            data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav ms-auto p-3 p-lg-0">
        <a href="index.html" class="city-logo"><img src="frontend/assets/img/city-logo.png" alt="CU Logo" width="210px" height="80px"></a>
        <a href="index.html" class="nav-item nav-link active">Home</a>
        <!-- <a href="../view/pages/about.html" class="nav-item nav-link">About</a> -->
        <!-- <a href="admin.html" class="nav-item nav-link">Admin-panel</a> -->
        <a href="../view/pages/hallfacilities.html" class="nav-item nav-link">Hall Facilities</a>
        <a href="../view/pages/in-roomFacilities.html" class="nav-item nav-link">In-Room Facilities</a>
        <a href="../view/pages/admission.html" class="nav-item nav-link">Admission</a>
        <a href="../view/pages/faq.html" class="nav-item nav-link">FAQ</a>
        <a href="../view/pages/notice.html" class="nav-item nav-link">Notice</a>
        <a href="../view/pages/Help.html" class="nav-item nav-link">Help Desk</a>
        <a href="../view/pages/contact.html" class="nav-item nav-link">Contact</a>
      </div>
      <!---------------------------------------------- Header Profile Menu Section Start --------------------------------------------->
      <?php if (isset($_SESSION['user_id'])): ?>
	  <div id="welcomeMessage" class="welcome-message">
		Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
	  </div>
	  <?php endif; ?>

	  <div class="header">
		<?php if (isset($_SESSION['user_id'])): ?>
		<!-- Profile icon and dropdown for logged-in users -->
		<div class="profile-container">
		  <div class="profile-icon" onclick="toggleDropdown()">👤</div>
		  <div id="dropdownMenu" class="dropdown-menu">
			<a href="../view/admin/dashboard.php"><span class="icon">🏠</span>Dashboard</a>
			<a href="view/notifications.php"><span class="icon">🔔</span>Notifications</a>
			<a href="view/profile.php"><span class="icon">👤</span>Profile</a>
			<a href="view/profile_settings.php"><span class="icon">⚙️</span>Settings</a>
			<a href="../controller/auth/logout.php"><span class="icon">🚪</span>Logout</a>
		  </div>
		</div>
		<?php else: ?>
		<!-- Login/Signup link for guests -->
		<div class="combined-button">
		  <a href="../view/auth/signup.html" class="signup-btn" target="_blank"
			 title="Sign Up to Book Your Seat" aria-label="Sign Up to Book Your Seat">Sign Up</a>
		  <a href="../view/auth/login.html" class="login-btn" target="_blank"
			 title="Login to access your portal" aria-label="Login to access your portal">Login</a>
		</div>
		<?php endif; ?>
	  </div>
      <!---------------------------------------------- Header Profile Menu Section End --------------------------------------------->
    </div>
  </nav>
</div>
<!-- Navbar End -->
