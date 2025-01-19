<!-- Streamlined Edit Modal -->
<div class="modal fade edit-modal" id="edit_details" aria-hidden="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
          @csrf
          @method('PUT')

          <!-- Profile Photo Section -->
          <div class="text-center mb-4">
            <div class="profile-photo-container">
              <img id="make-photo-preview"
                   src="{{ url('storage/image/profile/' . ($user->photo ?? 'avatar.png')) }}"
                   alt="Profile Photo" class="rounded-circle profile-photo"
                   style="width: 120px; height: 120px; object-fit: cover;">
                <input type="file" style="display:none;"
                       name="photo"
                       class="form-control"
                       id="photo-preview"
                       accept="image/*">
                <div class="photo-upload-overlay">
                  <label for="photo-preview" class="mb-0">
                    <i style="width:40px; cursor:pointer;" class="fa fa-camera"></i>
                  </label>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
              </div>

              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control" name="cell" value="{{ $user->cell }}" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="dob" value="{{ $user->dob }}">
              </div>

              <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="{{ $user->address }}">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Bio</label>
            <textarea class="form-control" name="bio" rows="4">{{ $user->bio }}</textarea>
            <small class="text-muted">Brief description for your profile.</small>
          </div>

          <div class="text-right mt-3">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /Streamlined Edit Modal -->

<!-- Update Photo Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Profile Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Choose Photo</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
              <label class="custom-file-label" for="photo">Select file</label>
            </div>
            <div id="photoPreview" class="mt-3 text-center" style="display: none;">
              <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload Photo</button>
        </div>
      </form>
    </div>
  </div>
</div>
