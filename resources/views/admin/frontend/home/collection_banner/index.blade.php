@extends('admin.frontend.partials.app')

@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">

  <div class="body-wrapper">
    @include('admin.frontend.partials.header')

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Collection Banner</h5>
          <div class="card">
            <div class="card-body">

              <form method="POST" action="{{ route('admin.collection-banner.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-3">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" required value="{{ old('title', $banner->title ?? '') }}">
                </div>

                <!-- Heading -->
                <div class="mb-3">
                  <label for="heading" class="form-label">Heading</label>
                  <input type="text" name="heading" class="form-control" required value="{{ old('heading', $banner->heading ?? '') }}">
                </div>

                <!-- Button URL -->
                <div class="mb-3">
                  <label for="button_url" class="form-label">Button URL</label>
                  <input type="url" name="button_url" class="form-control" required value="{{ old('button_url', $banner->button_url ?? '') }}">
                </div>

                <!-- Button Text -->
                <div class="mb-3">
                  <label for="button_text" class="form-label">Button Text</label>
                  <input type="text" name="button_text" class="form-control" required value="{{ old('button_text', $banner->button_text ?? '') }}">
                </div>

                <!-- Sale Text -->
                <div class="mb-3">
                  <label for="sale_text" class="form-label">Sale Text</label>
                  <input type="text" name="sale_text" class="form-control" required value="{{ old('sale_text', $banner->sale_text ?? '') }}">
                </div>

                <!-- Image Upload with Preview -->
                <div class="mb-3">
                  <label class="form-label">Upload Banner Image (optional)</label>
                  <div id="drop-area" class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
                    <input type="file" name="image" id="image" class="d-none" accept="image/*">
                    <div id="preview-container">
                      @if(!empty($banner->image))
                        <img id="preview" src="{{ asset('storage/' . $banner->image) }}" alt="Image Preview" class="img-fluid rounded" style="max-height: 150px;">
                      @else
                        <img id="preview" src="#" alt="Image Preview" style="display: none; max-height: 150px;" class="img-fluid rounded">
                      @endif
                    </div>
                    <div>
                      <i style="font-size: 4.125rem !important;" class="bi bi-cloud-arrow-up text-primary"></i>
                      <p class="text-muted">Click to Upload or drag & drop</p>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Banner</button>
              </form>

            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
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
