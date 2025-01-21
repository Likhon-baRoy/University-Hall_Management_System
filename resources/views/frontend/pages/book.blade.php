<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Hall Room Viewer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
     :root {
       --primary-color: #2563eb;
       --secondary-color: #475569;
       --success-color: #16a34a;
       --warning-color: #ca8a04;
     }

     body {
       background-color: #f8fafc;
     }

     .page-header {
       background: linear-gradient(135deg, var(--primary-color), #1e40af);
       color: white;
       padding: 1.5rem 0;
       margin-bottom: 0.5rem;
       border-radius: 0 0 1rem 1rem;
     }

     .header-subtitle {
       color: rgba(255, 255, 255, 0.9);
       font-size: 1.1rem;
       margin-top: 0.5rem;
     }

     .filters-container {
       background: white;
       border-radius: 1rem;
       padding: 1.5rem;
       box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
       margin-bottom: 2rem;
     }

     .search-box {
       position: relative;
     }

     .search-box i {
       position: absolute;
       left: 1rem;
       top: 50%;
       transform: translateY(-50%);
       color: var(--secondary-color);
     }

     .search-box input {
       padding-left: 2.5rem;
       border-radius: 0.5rem;
       border: 1px solid #e2e8f0;
     }

     .room-card {
       height: 100%;
       background: white;
       border-radius: 1rem;
       overflow: hidden;
       border: none;
       box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
       transition: all 0.3s ease;
     }

     .room-card:hover {
       transform: translateY(-0.25rem);
       box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
     }

     .room-image-container {
       position: relative;
       height: 200px;
       overflow: hidden;
     }

     .room-image {
       width: 100%;
       height: 100%;
       object-fit: cover;
       transition: transform 0.3s ease;
     }

     .room-card:hover .room-image {
       transform: scale(1.05);
     }

     .availability-badge {
       position: absolute;
       top: 1rem;
       right: 1rem;
       background: rgba(22, 163, 74, 0.9);
       color: white;
       padding: 0.5rem 1rem;
       border-radius: 2rem;
       font-size: 0.875rem;
       backdrop-filter: blur(4px);
     }

     .gender-badge {
       padding: 0.5rem 1rem;
       border-radius: 2rem;
       font-weight: 500;
     }

     .badge-male {
       background-color: #dbeafe;
       color: #1d4ed8;
     }

     .badge-female {
       background-color: #fce7f3;
       color: #be185d;
     }

     .room-info {
       padding: 1.5rem;
     }

     .room-name {
       font-size: 1.25rem;
       font-weight: 600;
       color: #1e293b;
       margin-bottom: 0.5rem;
     }

     .room-location {
       color: var(--secondary-color);
       font-size: 0.875rem;
       margin-bottom: 1rem;
     }

     .action-buttons {
       display: grid;
       grid-template-columns: repeat(3, 1fr);
       gap: 0.5rem;
     }

     .btn-action {
       padding: 0.5rem;
       border-radius: 0.5rem;
       font-size: 0.875rem;
       transition: all 0.2s ease;
     }

     .btn-preview {
       background-color: var(--warning-color);
       color: white;
     }

     .btn-book {
       background-color: var(--success-color);
       color: white;
     }

     .btn-reviews {
       background-color: var(--primary-color);
       color: white;
     }

     .results-count {
       color: var(--secondary-color);
       font-size: 0.875rem;
       margin-bottom: 1rem;
     }

     .form-select {
       border-radius: 0.5rem;
       border: 1px solid #e2e8f0;
       padding: 0.5rem 1rem;
     }

     .pagination {
       margin-top: 2rem;
     }

     .page-link {
       color: var(--primary-color);
       border-radius: 0.5rem;
       margin: 0 0.25rem;
     }

     .page-item.active .page-link {
       background-color: var(--primary-color);
       border-color: var(--primary-color);
     }

     @media (max-width: 768px) {
       .filters-container {
         padding: 1rem;
       }

       .room-card {
         margin-bottom: 1rem;
       }

       .action-buttons {
         grid-template-columns: 1fr;
       }
     }

     .zoom-button {
       position: absolute;
       bottom: 1rem;
       right: 1rem;
       background: rgba(0, 0, 0, 0.5);
       color: white;
       border: none;
       border-radius: 50%;
       width: 2.5rem;
       height: 2.5rem;
       display: flex;
       align-items: center;
       justify-content: center;
       cursor: pointer;
       transition: all 0.2s ease;
       z-index: 2;
     }

     .zoom-button:hover {
       background: rgba(0, 0, 0, 0.7);
       transform: scale(1.1);
     }

     /* Image Modal Styles */
     .image-modal {
       display: none;
       position: fixed;
       top: 0;
       left: 0;
       width: 100%;
       height: 100%;
       background-color: rgba(0, 0, 0, 0.9);
       z-index: 1000;
       overflow: auto;
       align-items: center;
       justify-content: center;
     }

     .modal-content {
       position: relative;
       max-width: 90%;
       max-height: 90vh;
       margin: auto;
       display: block;
     }

     .modal-image {
       width: auto;
       height: auto;
       max-width: 100%;
       max-height: 90vh;
       object-fit: contain;
       border-radius: 0.5rem;
     }

     .close-modal {
       position: absolute;
       top: 1rem;
       right: 1rem;
       color: white;
       font-size: 2rem;
       cursor: pointer;
       z-index: 1001;
       width: 2.5rem;
       height: 2.5rem;
       background: rgba(0, 0, 0, 0.5);
       border: none;
       border-radius: 50%;
       display: flex;
       align-items: center;
       justify-content: center;
       transition: all 0.2s ease;
     }

     .close-modal:hover {
       background: rgba(0, 0, 0, 0.7);
       transform: scale(1.1);
     }
    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="page-header">
      <div class="container text-center">
        <h1>City University Hall Room Booking</h1>
        <hr>
        <p class="header-subtitle"><i>Find and Book Your Perfect Campus Accommodation</i></p>
      </div>
    </header>

    <!-- Image Modal -->
    <div class="image-modal" id="imageModal">
      <button class="close-modal" onclick="closeModal()">
        <i class="bi bi-x"></i>
      </button>
      <div class="modal-content">
        <img id="modalImage" class="modal-image" src="" alt="Room Preview">
      </div>
    </div>

    <div class="container">
      <!-- Filters Section -->
      <div class="filters-container">
        <div class="row g-3">
          <div class="col-md-5">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input type="text" id="searchInput" class="form-control" placeholder="Search halls or rooms...">
            </div>
          </div>

          <div class="col-md-3">
            <select id="genderFilter" class="form-select">
              <option value="">All Types</option>
              <option value="male">Male Halls</option>
              <option value="female">Female Halls</option>
            </select>
          </div>

          <div class="col-md-3">
            <select id="hallFilter" class="form-select">
              <option value="">All Halls</option>
              @foreach($halls as $hall)
                <option value="{{ strtolower($hall->name) }}">{{ $hall->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-1">
            <button id="resetFilters" class="btn btn-outline-secondary w-100" title="Reset Filters">
              <i class="bi bi-arrow-counterclockwise"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Results Count -->
      <div class="results-count" id="resultsCount"></div>

      <!-- Rooms Grid -->
      <div class="row g-4" id="roomGrid">
        @foreach($rooms as $room)
          <div class="col-lg-3 col-md-4 col-sm-6 room-item"
               data-hall="{{ $room->hall->name }}"
               data-gender="{{ strtolower($room->hall->gender) }}"
               data-room="{{ $room->name }}">
            <div class="room-card">
              <div class="room-image-container">
                <img src="{{ route('room.photo', ['filename' => $room->photo ?? 'default-room.jpg']) }}"
                     alt="{{ $room->hall->name }}"
                     class="room-image"
                     onerror="this.src='{{ asset('storage/image/room/default-room.jpg') }}';">
                <button class="zoom-button" onclick="openModal(this.parentElement.querySelector('.room-image').src)">
                  <i class="bi bi-zoom-in"></i>
                </button>
                <div class="availability-badge">
                  <i class="bi bi-person-fill"></i> {{ $room->seats->count() }} seats
                </div>
              </div>

              <div class="room-info">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h3 class="room-name">Room {{ $room->name }}</h3>
                  <span class="gender-badge {{ strtolower($room->hall->gender) === 'male' ? 'badge-male' : 'badge-female' }}">
                    {{ $room->hall->gender }}
                  </span>
                </div>

                <div class="room-location">
                  <div><i class="bi bi-building"></i> {{ $room->hall->name }}</div>
                  <div><i class="bi bi-geo-alt"></i> {{ $room->hall->location }}</div>
                </div>

                <div class="action-buttons">
                  <button class="btn btn-action btn-preview" onclick="previewRoom({{ $room->id }})">
                    <i class="bi bi-eye-fill"></i> Preview
                  </button>
                  <a href="{{ route('auth.register', ['room' => $room->id]) }}" class="btn btn-action btn-book">
                    <i class="bi bi-bookmark-fill"></i> Book
                  </a>
                  <button class="btn btn-action btn-reviews" onclick="showReviews({{ $room->id }})">
                    <i class="bi bi-star-fill"></i> Reviews
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center">
        {{ $rooms->links('pagination::bootstrap-5') }}
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
     $(document).ready(function() {
       function filterRooms() {
         const searchTerm = $('#searchInput').val().toLowerCase().trim();
         const selectedHall = $('#hallFilter').val().toLowerCase().trim();
         const selectedGender = $('#genderFilter').val().toLowerCase().trim();

         let visibleCount = 0;

         $('.room-item').each(function() {
           const $item = $(this);
           const hallName = $item.data('hall').toString().toLowerCase();
           const roomName = $item.data('room').toString().toLowerCase();
           const gender = $item.data('gender').toString().toLowerCase();

           const matchesSearch = !searchTerm ||
                                 hallName.includes(searchTerm) ||
                                 roomName.includes(searchTerm);
           const matchesHall = !selectedHall || hallName === selectedHall;
           const matchesGender = !selectedGender || gender === selectedGender;

           if (matchesSearch && matchesHall && matchesGender) {
             $item.show();
             visibleCount++;
           } else {
             $item.hide();
           }
         });

         $('#resultsCount').text(`Showing ${visibleCount} of ${$('.room-item').length} rooms`);
       }

       // Event listeners
       $('#searchInput').on('input', filterRooms);
       $('#hallFilter, #genderFilter').on('change', filterRooms);

       // Reset filters
       $('#resetFilters').click(function() {
         $('#searchInput').val('');
         $('#hallFilter').val('');
         $('#genderFilter').val('');
         filterRooms();
       });

       // Initial filtering
       filterRooms();
     });

     // Preview Room Function
     function previewRoom(roomId) {
       // Implement room preview functionality
       console.log('Preview room:', roomId);
       // Add your preview logic here
     }

     // Show Reviews Function
     function showReviews(roomId) {
       // Implement reviews display functionality
       console.log('Show reviews for room:', roomId);
       // Add your reviews logic here
     }

     // Add new zoom functionality
     const modal = document.getElementById('imageModal');
     const modalImg = document.getElementById('modalImage');

     function openModal(imgSrc) {
       modal.style.display = 'flex';
       modalImg.src = imgSrc;
       document.body.style.overflow = 'hidden'; // Prevent background scrolling
     }

     function closeModal() {
       modal.style.display = 'none';
       document.body.style.overflow = 'auto'; // Restore scrolling
     }

     // Close modal when clicking outside the image
     modal.addEventListener('click', function(e) {
       if (e.target === modal) {
         closeModal();
       }
     });

     // Close modal with escape key
     document.addEventListener('keydown', function(e) {
       if (e.key === 'Escape') {
         closeModal();
       }
     });
    </script>
  </body>
</html>
