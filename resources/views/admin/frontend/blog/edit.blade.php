@extends('admin.frontend.partials.app')

@section('content')

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        @include('admin.frontend.partials.header')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Edit Product</h5>

                    <div class="card">
                        <div class="card-body">

                            <form method="POST"
                                action="{{ route('admin.blogs.update', $blogs->id) }}"
                                enctype="multipart/form-data">

                                @csrf

                                 <!-- Blog Title -->
                <div class="mb-3">
                  <label for="name" class="form-label">Blog Title</label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', $blogs->name) }}" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
<textarea name="description" id="summernote" class="form-control" required>{{ old('description', $blogs->description) }}</textarea>
                </div>

                <!-- Tags -->
            <div class="mb-3">
  <label for="tags" class="form-label">Tags</label>
<select name="tags[]" id="tags" class="form-select select2" multiple required>
  @foreach($tags as $tag)
    <option value="{{ $tag->id }}" 
      {{ in_array($tag->id, old('tags', $blogs->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
      {{ $tag->name }}
    </option>
  @endforeach
</select>


</div>


                             

                                <!-- Image Upload -->
                                  <div class="mb-3">
                  <label class="form-label">Upload blogs Image (optional)</label>
                  <div id="drop-area" class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
                    <input type="file" name="image" id="image" class="d-none" accept="image/*">
                    <div id="preview-container">
                      @if(!empty($blogs->image))
                        <img id="preview" src="{{ asset('storage/' . $blogs->image) }}" alt="Image Preview" class="img-fluid rounded" style="max-height: 150px;">
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
                                <button type="submit" class="btn btn-primary">Update Blog</button>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Design and Developed by
                    <a href="https://adminmart.com/" target="_blank"
                        class="pe-1 text-primary text-decoration-underline">Elixore.com</a>
                    Distributed by
                    <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {
    // Clicking anywhere on drop area or on preview image opens file select
    $('#drop-area, #preview').on('click', function (e) {
        e.stopPropagation();
        $('#image').click();
    });

    // Prevent file input click from bubbling up
    $('#image').on('click', function (e) {
        e.stopPropagation();
    });

    // When user selects new image, update preview
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
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<!-- jQuery (before Summernote JS) -->

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<!-- Initialize Summernote -->
<script>
  $(document).ready(function () {
    $('#summernote').summernote({
      placeholder: 'Write blog description here...',
      tabsize: 2,
      height: 300,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>
