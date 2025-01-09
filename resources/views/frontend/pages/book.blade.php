<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Hall Room Viewer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
     .room-card {
       height: 100%;
       transition: transform 0.2s, box-shadow 0.2s;
       background: white;
       border-radius: 8px;
       overflow: hidden;
     }

     .room-card:hover {
       transform: translateY(-5px);
       box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
     }

     .room-card img {
       width: 100%;
       height: 200px;
       object-fit: cover;
     }

     .room-card .card-body {
       padding: 1.25rem;
     }

     .badge-male {
       background-color: #e3f2fd;
       color: #1976d2;
     }

     .badge-female {
       background-color: #fce4ec;
       color: #c2185b;
     }

     .search-box {
       position: relative;
       display: flex;
       align-items: center;
     }

     .search-box i.fas.fa-search {
       position: absolute;
       left: 12px;
       top: 50%;
       transform: translateY(-50%);
       color: #6c757d;
     }

     .search-box input {
       padding-left: 35px;
       height: 38px;
       width: 100%;
     }

     /* Add hover effect for reset button */
     #resetFilters:hover {
       background-color: #e9ecef;
     }

     .filters-row {
       background-color: #f8f9fa;
       border-radius: 8px;
       padding: 20px;
       margin-bottom: 30px;
     }

     .availability-badge {
       position: absolute;
       top: 10px;
       right: 10px;
       background: rgba(25, 135, 84, 0.9);
       color: white;
       padding: 5px 10px;
       border-radius: 20px;
     }
    </style>
  </head>
  <body>

    <div class="container py-5">
      <!-- Header -->
      <div class="row mb-4">
        <div class="col">
          <h2 class="text-center mb-4">Available Rooms</h2>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="filters-row">
        <div class="row g-3">
          <!-- Search Box -->
          <div class="col-md-5">
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" class="form-control" placeholder="Search by hall or room name...">
            </div>
          </div>

          <!-- Hall Filter -->
          <div class="col-md-3">
            <select id="hallFilter" class="form-select">
              <option value="">All Halls</option>
              @foreach($halls as $hall)
                <option value="{{ strtolower($hall->name) }}">{{ $hall->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Gender Filter -->
          <div class="col-md-3">
            <select id="genderFilter" class="form-select">
              <option value="">All Types</option>
              <option value="male">Male Halls</option>
              <option value="female">Female Halls</option>
            </select>
          </div>

          <!-- Reset Button -->
          <div class="col-md-1">
            <button id="resetFilters" class="btn btn-outline-secondary w-100" title="Reset Filters">
              <i class="fas fa-undo"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Results Count -->
      <div class="row mb-3">
        <div class="col">
          <p class="text-muted" id="resultsCount"></p>
        </div>
      </div>

      <!-- Rooms Grid -->
      <div class="row g-4" id="roomGrid">
        @foreach($rooms as $room)
          <div class="col-lg-3 col-md-4 col-sm-6 room-item"
               data-hall="{{ $room->hall->name }}"
               data-gender="{{ strtolower($room->hall->gender) }}"
               data-room="{{ $room->name }}">
            <div class="room-card">
              <div class="position-relative">
                <img src="{{ route('room.photo', ['filename' => $room->photo ?? 'default-room.jpg']) }}"
                     alt="{{ $room->hall->name }}"
                     class="card-img-top"
                     onerror="this.onerror=null; console.log('Failed to load image:', this.src); this.src='{{ asset('storage/image/room/default-room.jpg') }}';">
                <span class="availability-badge">
                  <i class="bi bi-person-fill"></i> {{ $room->seats->count() }} seats available
                </span>
              </div>
              <div class="card-body">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-door-open"></i> Room: {{ $room->name }}</span>

                  <!-- Gender Badge (on the right side of Room name) -->
                  <span class="badge {{ strtolower($room->hall->gender) === 'male' ? 'badge-male' : 'badge-female' }}">
                    {{ $room->hall->gender }}
                  </span>
                </h5>

                <p class="card-text mb-2">
                  <i class="bi bi-building"></i> {{ $room->hall->name }}<br>
                  <i class="bi bi-geo-alt"> <small>{{ $room->hall->location }}</small></i>
                </p>

                <!-- Attached Buttons -->
                <div class="btn-group mt-3 w-100" role="group">
                  <!-- Preview Button -->
                  <a href="" class="btn btn-warning btn-sm">
                    <i class="bi bi-eye"></i> Preview
                  </a>

                  <!-- Book Now Button -->
                  <a href="{{ route('hall-booking.booking', ['room' => $room->id]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-hotel"></i> Book
                  </a>

                  <!-- Reviews Button -->
                  <a href="" class="btn btn-primary btn-sm">
                    <i class="bi bi-star"></i> Reviews
                  </a>
                </div>
              </div>

            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        {{ $rooms->links('pagination::bootstrap-5') }}
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    <script>
     $(document).ready(function() {
       // Debug helper - remove in production
       console.log('Total rooms:', $('.room-item').length);

       function filterRooms() {
         const searchTerm = $('#searchInput').val().toLowerCase().trim();
         const selectedHall = $('#hallFilter').val().toLowerCase().trim();
         const selectedGender = $('#genderFilter').val().toLowerCase().trim();

         // Debug helper - remove in production
         console.log('Filtering with:', { searchTerm, selectedHall, selectedGender });

         let visibleCount = 0;

         $('.room-item').each(function() {
           const $item = $(this);

           // Get data attributes
           const hallName = $item.data('hall').toString().toLowerCase();
           const roomName = $item.data('room').toString().toLowerCase();
           const gender = $item.data('gender').toString().toLowerCase();

           // Debug helper - remove in production
           console.log('Room data:', { hallName, roomName, gender });

           // Search match
           const matchesSearch = !searchTerm ||
                                 hallName.includes(searchTerm) ||
                                 roomName.includes(searchTerm);

           // Hall filter match
           const matchesHall = !selectedHall || hallName === selectedHall;

           // Gender filter match
           const matchesGender = !selectedGender || gender === selectedGender;

           // Debug helper - remove in production
           console.log('Matches:', { matchesSearch, matchesHall, matchesGender });

           if (matchesSearch && matchesHall && matchesGender) {
             $item.show();
             visibleCount++;
           } else {
             $item.hide();
           }
         });

         // Update results count
         $('#resultsCount').text(`Showing ${visibleCount} of ${$('.room-item').length} rooms`);
       }

       // Event listeners
       $('#searchInput').on('input', function() {
         console.log('Search input:', $(this).val());
         filterRooms();
       });

       $('#hallFilter').on('change', function() {
         console.log('Hall selected:', $(this).val());
         filterRooms();
       });

       $('#genderFilter').on('change', function() {
         console.log('Gender selected:', $(this).val());
         filterRooms();
       });

       // Initial filtering
       filterRooms();

       // Reset filters function
       function resetFilters() {
         $('#searchInput').val('');
         $('#hallFilter').val('');
         $('#genderFilter').val('');
         filterRooms();
       }

       // Reset button click handler
       $('#resetFilters').click(function() {
         resetFilters();
       });
     });

    </script>

  </body>
</html>
