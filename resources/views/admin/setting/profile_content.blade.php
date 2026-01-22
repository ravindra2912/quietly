<style>
  .avtar_img {
    height: 160px;
    width: 160px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
  }

  .avtar {
    position: relative;
    width: fit-content;
    margin: 0 auto;
  }

  .avtar label {
    position: absolute;
    bottom: 0;
    right: 0;
    background: var(--primary-color);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
  }

  .avtar label:hover {
    transform: scale(1.1);
    background: var(--bs-primary-dark);
  }

  .avtar_input {
    display: none;
  }
</style>

<div class="card shadow mb-4">
  <div class="card-header py-3 bg-white">
    <h5 class="m-0 font-weight-bold text-primary">Edit Profile</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.setting.profile.update', $user->id) }}" data-action="reload" class="formaction" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-4 mb-4">
        <!-- Avatar Section -->
        <div class="col-md-12 text-center">
          <div class="avtar">
            <img src="{{ getImage($user->profile_image) }}" class="avtar_img" alt="Profile Image" />
            <label for="profile" title="Change Image"><i class="bi bi-pencil-fill"></i></label>
          </div>
          <input type="file" name="profile" class="avtar_input" id="profile" accept="image/png, image/webp, image/jpeg" />
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{ $user->first_name }}" name="first_name" placeholder="First Name" required />
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{ $user->last_name }}" name="last_name" placeholder="Last Name" required />
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" value="{{ $user->email }}" name="email" placeholder="Email Address" required />
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Contact <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{ $user->phone }}" name="contact" placeholder="Phone Number" required />
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Change Password <small class="text-muted">(Leave blank to keep current)</small></label>
            <input type="password" class="form-control" name="password" placeholder="New Password" />
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-secondary" type="button" onclick="history.back()">Back</button>
        <button class="btn btn-primary btn_action" type="submit">
          <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          <span id="buttonText">Update Profile</span>
        </button>
      </div>

    </form>
  </div>
</div>
