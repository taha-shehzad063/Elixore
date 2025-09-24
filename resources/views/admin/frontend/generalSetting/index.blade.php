@extends('admin.frontend.partials.app')

@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">

  <div class="body-wrapper">
    @include('admin.frontend.partials.header')

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">General Settings</h5>
          <div class="card">
            <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Home Page Settings</h5>

              <form method="POST" action="{{ route('admin.general-settings.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Heading 0-->
                <div class="mb-3">
                  <label for="heading" class="form-label">Heading</label>
                  <input type="text" name="heading_0" class="form-control" required value="{{ old('heading_0', $setting->heading_0 ?? '') }}">
                </div>

                <!-- Info 0-->
                <div class="mb-3">
                  <label for="info" class="form-label">Info</label>
                  <input type="text" name="intro_0" class="form-control" required value="{{ old('intro_0', $setting->intro_0 ?? '') }}">
                </div>
                <!-- Heading -->
                <div class="mb-3">
                  <label for="heading" class="form-label">Heading</label>
                  <input type="text" name="heading" class="form-control" required value="{{ old('heading', $setting->heading ?? '') }}">
                </div>

                <!-- Info -->
                <div class="mb-3">
                  <label for="info" class="form-label">Info</label>
                  <input type="text" name="info" class="form-control" required value="{{ old('info', $setting->info ?? '') }}">
                </div>

                <!-- Heading 1 -->
                <div class="mb-3">
                  <label for="heading_1" class="form-label">Heading 1</label>
                  <input type="text" name="heading_1" class="form-control" required value="{{ old('heading_1', $setting->heading_1 ?? '') }}">
                </div>

                <!-- Heading 2 -->
                <div class="mb-3">
                  <label for="heading_2" class="form-label">Heading 2</label>
                  <input type="text" name="heading_2" class="form-control" required value="{{ old('heading_2', $setting->heading_2 ?? '') }}">
                </div>

                <!-- Heading 3 -->
                <div class="mb-3">
                  <label for="heading_3" class="form-label">Heading 3</label>
                  <input type="text" name="heading_3" class="form-control" required value="{{ old('heading_3', $setting->heading_3 ?? '') }}">
                </div>

                <!-- Intro 3 -->
                <div class="mb-3">
                  <label for="intro_3" class="form-label">Intro 3</label>
                  <input type="text" name="intro_3" class="form-control" required value="{{ old('intro_3', $setting->intro_3 ?? '') }}">
                </div>

                <!-- Optional Image Upload (if needed) -->
                <!-- <div class="mb-3">
                  <label class="form-label">Upload Intro Image (optional)</label>
                  <div id="drop-area" class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
                    <input type="file" name="image" id="image" class="d-none" accept="image/*">
                    <div id="preview-container">
                      @if(!empty($setting->image))
                        <img id="preview" src="{{ asset($setting->image) }}" alt="Image Preview" class="img-fluid rounded" style="max-height: 150px;">
                      @else
                        <img id="preview" src="#" alt="Image Preview" style="display: none; max-height: 150px;" class="img-fluid rounded">
                      @endif
                    </div>
                    <div>
                      <i style="font-size: 4.125rem !important;" class="bi bi-cloud-arrow-up text-primary"></i>
                      <p class="text-muted">Click to Upload or drag & drop</p>
                    </div>
                  </div>
                </div> -->

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Settings</button>
              </form>

            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Design and Developed by <a href="https://elixore.com/" target="_blank"
            class="pe-1 text-primary text-decoration-underline">Elixore.com</a></p>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- jQuery for image preview -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
  $(document).ready(function () {
    $('#drop-area').on('click', function (e) {
      e.stopPropagation();
      $('#image').click();
    });

    $('#image').on('click', function (e) {
      e.stopPropagation();
    });

    $('#image').on('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          $('#preview').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
      }
    });
  });
</script>
