@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('main-section')
  <!-- Page Header -->
  <div class="page-header">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="page-title">Welcome {{ Auth::guard('admin')->user()->name }}!</h3>
        <ul class="breadcrumb">
          <li class="breadcrumb-item active">Dashboard</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /Page Header -->

  <div class="row">
    <div class="col-xl-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-body">
          <div class="dash-widget-header">
            <span class="dash-widget-icon text-primary border-primary">
              <i class="fa-solid fa-users-line"></i>
            </span>
            <div class="dash-count">
              <h3>{{ $total_students }}</h3>
            </div>
          </div>
          <div class="dash-widget-info">
            <h6 class="text-muted">Students</h6>
            <div class="progress progress-sm">
              <div class="progress-bar bg-primary" style="width: {{ ($total_students / $total_users) * 100 }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-body">
          <div class="dash-widget-header">
            <span class="dash-widget-icon text-success">
              <i class="fas fa-user-graduate"></i>
            </span>
            <div class="dash-count">
              <h3>{{ $total_staff }}</h3>
            </div>
          </div>
          <div class="dash-widget-info">
            <h6 class="text-muted">Teachers & Staff</h6>
            <div class="progress progress-sm">
              <div class="progress-bar bg-success" style="width: {{ ($total_staff / $total_users) * 100 }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-body">
          <div class="dash-widget-header">
            <span class="dash-widget-icon text-danger border-danger">
              <i class="fa-solid fa-door-open"></i>
            </span>
            <div class="dash-count">
              <h3>{{ $total_rooms }}</h3>
            </div>
          </div>
          <div class="dash-widget-info">
            <h6 class="text-muted">Total Rooms</h6>
            <div class="progress progress-sm">
              <div class="progress-bar bg-danger" style="width: 100%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-body">
          <div class="dash-widget-header">
            <span class="dash-widget-icon text-warning border-warning">
              <i class="fa-solid fa-bed"></i>
            </span>
            <div class="dash-count">
              <h3>{{ $available_seats }}</h3>
            </div>
          </div>
          <div class="dash-widget-info">
            <h6 class="text-muted">Available Seats</h6>
            <div class="progress progress-sm">
              <div class="progress-bar bg-warning" style="width: {{ ($total_seats > 0 ? ($available_seats / $total_seats) * 100 : 0 ) }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-lg-6">
      <!-- Hall Occupancy Chart -->
      <div class="card card-chart">
        <div class="card-header">
          <h4 class="card-title">Hall Occupancy by Gender</h4>
        </div>
        <div class="card-body">
          <div id="hallOccupancyChart"></div>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-6">
      <!-- Resident Distribution Chart -->
      <div class="card card-chart">
        <div class="card-header">
          <h4 class="card-title">Resident Distribution</h4>
        </div>
        <div class="card-body">
          <div id="residentDistributionChart"></div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
 // Initialize Morris Charts when document is ready
 $(document).ready(function() {
   // Hall Occupancy Chart
   Morris.Bar({
     element: 'hallOccupancyChart',
     data: {!! json_encode($hall_occupancy) !!},
     xkey: 'hall',
     ykeys: ['total', 'occupied'],
     labels: ['Total Capacity', 'Occupied'],
     barColors: ['#7E84A3', '#2E37A4'],
     hideHover: 'auto'
   });

   // Resident Distribution Chart
   Morris.Donut({
     element: 'residentDistributionChart',
     data: {!! json_encode($resident_distribution) !!},
     colors: ['#2E37A4', '#00D3C7', '#FFA114'],
     resize: true
   });
 });
</script>
@endpush
