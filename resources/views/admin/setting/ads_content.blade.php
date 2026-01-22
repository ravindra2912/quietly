<div class="card shadow mb-4">
  <div class="card-header py-3 bg-white">
    <h5 class="m-0 font-weight-bold text-primary">Ads Settings</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.setting.ads.update') }}" data-action="reload" class="formaction" method="POST">
      @csrf

      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Enable Ads</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_ads" name="is_ads" value="1" 
                {{ $settings && $settings->is_ads ? 'checked' : '' }} />
              <label class="form-check-label" for="is_ads">
                Enable advertisements in the application
              </label>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Ads Key <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{ $settings ? $settings->ads_key : '' }}" 
              name="ads_key" placeholder="Enter your ads key/API key" />
            <small class="text-muted">Enter your advertising network key or API key</small>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-secondary" type="button" onclick="history.back()">Back</button>
        <button class="btn btn-primary btn_action" type="submit">
          <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          <span id="buttonText">Update Settings</span>
        </button>
      </div>

    </form>
  </div>
</div>
