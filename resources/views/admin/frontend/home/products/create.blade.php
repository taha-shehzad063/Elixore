@extends('admin.frontend.partials.app')

@section('content')
<style>
.preview-box {
    position: relative;
    display: inline-block;
}
.preview-box img {
    max-height: 120px;
    border-radius: 6px;
}
.preview-box .remove-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 14px;
    cursor: pointer;
    z-index: 10;
}
</style>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">

  <div class="body-wrapper">
    @include('admin.frontend.partials.header')

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Create Product</h5>
          <div class="card">
            <div class="card-body">

              <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                  <label for="name" class="form-label">Product Name</label>
                  <input type="text" name="name" class="form-control" required>
                </div>
<div class="mb-3">
  <label for="category_id" class="form-label">Category</label>
  <select name="category_id" id="category-select" class="form-select" required>
    <option value="">-- Select Category --</option>
    @foreach($categories as $category)
      <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label for="description" class="form-label">Description</label>
  <textarea id="description" name="description" class="form-control summernote" rows="5">{{ old('description', $products->description ?? '') }}</textarea>
</div>

<div class="mb-3">
  <label for="info" class="form-label">Short Info</label>
  <textarea id="info" name="info" class="form-control summernote">{{ old('info', $products->info ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Availability</label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="availability" id="in_stock" value="in stock"
                {{ old('availability', $products->availability ?? '') == 'in stock' ? 'checked' : '' }}>
            <label class="form-check-label" for="in_stock">In Stock</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="availability" id="out_of_stock" value="out of stock"
                {{ old('availability', $products->availability ?? '') == 'out of stock' ? 'checked' : '' }}>
            <label class="form-check-label" for="out_of_stock">Out of Stock</label>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="tags" class="form-label">Tags</label>
    <select name="tags[]" id="tags" class="form-select" multiple>
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}"
                @if(isset($product) && $product->tags->contains($tag->id)) selected @endif>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
    @error('tags')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label">Specifications</label>
    <div id="specification-wrapper">
        <div class="row mb-2 spec-row">
            <div class="col-md-5">
                <input type="text" name="specifications[0][key]" class="form-control" placeholder="Key">
            </div>
            <div class="col-md-5">
                <input type="text" name="specifications[0][value]" class="form-control" placeholder="Value">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-spec">X</button>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" id="add-spec">Add Specification</button>
</div>

<div class="mb-3">
    <label class="form-label">Additional Options</label>
    <div id="option-wrapper">
        <div class="row mb-2 option-row">
            <div class="col-md-5">
                <input type="text" name="options[0][key]" class="form-control" placeholder="Option Name">
            </div>
            <div class="col-md-5">
                <input type="text" name="options[0][value]" class="form-control" placeholder="Option Value">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-option">X</button>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" id="add-option">Add Option</button>
</div>


                <div class="mb-3">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" name="price" step="0.01" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label for="discount_price" class="form-label">Discount Price (optional)</label>
                  <input type="number" name="discount_price" step="0.01" class="form-control">
                </div>

               
                <div class="mb-3">
    <label class="form-label">Upload Gallery Images (JPG/PNG)</label>
    <div id="gallery-drop-area" class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
        <input type="file" name="gallery_images[]" id="gallery-images" class="d-none" multiple accept="image/*">
        <div id="gallery-preview-container" class="d-flex flex-wrap gap-3 justify-content-start mt-3">
            </div>
        <div>
            <i style="font-size: 4.125rem !important;" class="bi bi-cloud-arrow-up text-primary"></i>
            <p class="text-muted">Click to Upload or drag & drop multiple images</p>
        </div>
    </div>
</div>


                <button type="submit" class="btn btn-primary">Create Product</button>
              </form>

            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
            class="pe-1 text-primary text-decoration-underline">Elixore.com</a> Distributed by <a target="_blank"
            class="pe-1 text-primary text-decoration-underline">Elixore</a></p>
      </div>
    </div>
  </div>
</div>
@endsection

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
<script>
  $(document).ready(function () {
    // Load subcategories on category change
    $('#category-select').on('change', function () {
      let categoryId = $(this).val();
      let subcategorySelect = $('#subcategory-select');

      subcategorySelect.empty().append('<option value="">-- Loading... --</option>');

      if (categoryId) {
        $.ajax({
          url: '/admin/categories/' + categoryId + '/subcategories',
          type: 'GET',
          success: function (data) {
            subcategorySelect.empty().append('<option value="">-- Select Subcategory --</option>');
            $.each(data, function (key, subcategory) {
              subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
            });
          },
          error: function () {
            subcategorySelect.empty().append('<option value="">-- Failed to load --</option>');
          }
        });
      } else {
        subcategorySelect.empty().append('<option value="">-- Select Subcategory --</option>');
      }
    });
  });
</script>
<script>
$(document).ready(function () {
    const selectedImages = [];

    $('#gallery-drop-area').on('click', function (e) {
        e.stopPropagation();
        $('#gallery-images').click();
    });

    $('#gallery-images').on('click', function (e) {
        e.stopPropagation();
    });

    $('#gallery-images').on('change', function () {
        const files = Array.from(this.files);

        files.forEach((file, index) => {
            selectedImages.push(file);

            const reader = new FileReader();
            reader.onload = function (e) {
                const previewBox = $(`
                    <div class="preview-box position-relative">
                        <span class="remove-btn">&times;</span>
                        <img src="${e.target.result}" alt="Preview" class="img-fluid">
                    </div>
                `);

                // Remove logic
                previewBox.find('.remove-btn').on('click', function () {
                    const idx = selectedImages.indexOf(file);
                    if (idx > -1) selectedImages.splice(idx, 1);
                    previewBox.remove();
                    updateFileList();
                });

                $('#gallery-preview-container').append(previewBox);
            };
            reader.readAsDataURL(file);
        });

        updateFileList();
    });

    // Keep input file in sync with selectedImages
    function updateFileList() {
        const dataTransfer = new DataTransfer();
        selectedImages.forEach(file => dataTransfer.items.add(file));
        document.getElementById('gallery-images').files = dataTransfer.files;
    }
});
</script>


<script>
  $(document).ready(function () {
    let specIndex = 1; // start from 1 if 0 is already used in default row

    // Add Specification
    $('#add-spec').on('click', function () {
      const html = `
        <div class="row mb-2 spec-row">
          <div class="col-md-5">
            <input type="text" name="specifications[${specIndex}][key]" class="form-control" placeholder="Key">
          </div>
          <div class="col-md-5">
            <input type="text" name="specifications[${specIndex}][value]" class="form-control" placeholder="Value">
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-spec">X</button>
          </div>
        </div>
      `;
      $('#specification-wrapper').append(html);
      specIndex++;
    });

    // Remove Specification
    $(document).on('click', '.remove-spec', function () {
      $(this).closest('.spec-row').remove();
    });

    let optionIndex = 1; // Separate index for options

    // Add Additional Option
    $('#add-option').on('click', function () {
      const html = `
        <div class="row mb-2 option-row">
          <div class="col-md-5">
            <input type="text" name="options[${optionIndex}][key]" class="form-control" placeholder="Option Name">
          </div>
          <div class="col-md-5">
            <input type="text" name="options[${optionIndex}][value]" class="form-control" placeholder="Option Value">
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-option">X</button>
          </div>
        </div>
      `;
      $('#option-wrapper').append(html);
      optionIndex++;
    });

    // Remove Additional Option
    $(document).on('click', '.remove-option', function () {
      $(this).closest('.option-row').remove();
    });
  });
</script>
<!-- CSS in <head> -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<!-- JS before </body> -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            placeholder: 'Type here...',
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
