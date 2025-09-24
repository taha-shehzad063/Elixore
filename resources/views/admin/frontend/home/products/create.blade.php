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
.image-link-row {
    margin-bottom: 10px;
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
                  <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                  @error('name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="category_id" class="form-label">Category</label>
                  <select name="category_id" id="category-select" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                  </select>
                  @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="sub_category_id" class="form-label">Subcategory</label>
                  <select name="sub_category_id" id="subcategory-select" class="form-select" required>
                    <option value="">-- Select Subcategory --</option>
                    @foreach($subcategories as $subcategory)
                      <option value="{{ $subcategory->id }}" {{ old('sub_category_id') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                    @endforeach
                  </select>
                  @error('sub_category_id')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea id="description" name="description" class="form-control summernote" rows="5">{{ old('description') }}</textarea>
                  @error('description')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="info" class="form-label">Short Info</label>
                  <textarea id="info" name="info" class="form-control summernote">{{ old('info') }}</textarea>
                  @error('info')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="link" class="form-label">Link</label>
                  <textarea id="link" name="link" class="form-control summernote">{{ old('link') }}</textarea>
                  @error('link')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">Availability</label>
                  <div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="availability" id="in_stock" value="in stock" {{ old('availability') == 'in stock' ? 'checked' : '' }}>
                      <label class="form-check-label" for="in_stock">In Stock</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="availability" id="out_of_stock" value="out of stock" {{ old('availability') == 'out of stock' ? 'checked' : '' }}>
                      <label class="form-check-label" for="out_of_stock">Out of Stock</label>
                    </div>
                  </div>
                  @error('availability')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="tags" class="form-label">Tags</label>
                  <select name="tags[]" id="tags" class="form-select" multiple>
                    @foreach($tags as $tag)
                      <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                  </select>
                  @error('tags')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

             <div class="mb-3">
    <label class="form-label">Colors</label>
    <div id="color-wrapper">
        @if(old('color'))
            @foreach(old('color') as $index => $color)
                <div class="row mb-2 color-row">
                    <div class="col-md-10">
                        <input type="text" name="color[]" class="form-control" placeholder="Enter color (e.g. Red, Blue)" value="{{ $color }}">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-color">X</button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row mb-2 color-row">
                <div class="col-md-10">
                    <input type="text" name="color[]" class="form-control" placeholder="Enter color (e.g. Red)">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-color">X</button>
                </div>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-primary" id="add-color">Add Color</button>
</div>


                <div class="mb-3">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
                  @error('price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="discount_price" class="form-label">Discount Price (optional)</label>
                  <input type="number" name="discount_price" step="0.01" class="form-control" value="{{ old('discount_price') }}">
                  @error('discount_price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">Gallery Images</label>
                  <div class="mb-3">
                    <label class="form-label">Add Image Links</label>
                    <div id="image-links-wrapper">
                      @if(old('image_links'))
                        @foreach(old('image_links') as $index => $link)
                          <div class="row mb-2 image-link-row">
                            <div class="col-md-10">
                              <input type="text" name="image_links[{{ $index }}]" class="form-control" placeholder="Enter image URL (e.g., https://example.com/image.jpg)" value="{{ $link }}">
                            </div>
                            <div class="col-md-2">
                              <button type="button" class="btn btn-danger remove-image-link">X</button>
                            </div>
                          </div>
                        @endforeach
                      @else
                        <div class="row mb-2 image-link-row">
                          <div class="col-md-10">
                            <input type="text" name="image_links[0]" class="form-control" placeholder="Enter image URL (e.g., https://example.com/image.jpg)">
                          </div>
                          <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-image-link">X</button>
                          </div>
                        </div>
                      @endif
                    </div>
                    <button type="button" class="btn btn-primary" id="add-image-link">Add Image Link</button>
                    @error('image_links.*')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div id="gallery-drop-area" class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
                    <input type="file" name="gallery_images[]" id="gallery-images" class="d-none" multiple accept="image/*">
                    <div id="gallery-preview-container" class="d-flex flex-wrap gap-3 justify-content-start mt-3"></div>
                    <div>
                      <i style="font-size: 4.125rem !important;" class="bi bi-cloud-arrow-up text-primary"></i>
                      <p class="text-muted">Click to Upload or drag & drop multiple images</p>
                    </div>
                  </div>
                  @error('gallery_images.*')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<script>
$(document).ready(function () {
    // Summernote init
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

    // Select2 init for tags
    $('#tags').select2({
        placeholder: "Search and select tags",
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Gallery image handling
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
        files.forEach(file => addToGallery(file));
    });

    function addToGallery(file) {
        selectedImages.push(file);
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewBox = $(`
                <div class="preview-box position-relative">
                    <span class="remove-btn">&times;</span>
                    <img src="${e.target.result}" alt="Preview" class="img-fluid rounded border" style="max-height: 120px;">
                </div>
            `);
            previewBox.find('.remove-btn').on('click', function () {
                const idx = selectedImages.indexOf(file);
                if (idx > -1) {
                    selectedImages.splice(idx, 1);
                    previewBox.remove();
                    updateFileList();
                }
            });
            $('#gallery-preview-container').append(previewBox);
        };
        reader.readAsDataURL(file);
        updateFileList();
    }

    function updateFileList() {
        const dataTransfer = new DataTransfer();
        selectedImages.forEach(file => dataTransfer.items.add(file));
        document.getElementById('gallery-images').files = dataTransfer.files;
    }

    // Image link preview
    $(document).on('input', 'input[name^="image_links"]', function () {
        $('#gallery-preview-container').find('.url-preview').remove();
        $('input[name^="image_links"]').each(function (index) {
            const link = $(this).val().trim();
            if (link && isValidImageUrl(link)) {
                const previewBox = $(`
                    <div class="preview-box position-relative url-preview">
                        <span class="remove-btn" data-index="${index}">&times;</span>
                        <img src="${link}" alt="Preview" class="img-fluid rounded border" style="max-height: 120px;">
                    </div>
                `);
                previewBox.find('.remove-btn').on('click', function () {
                    $(this).closest('.preview-box').remove();
                    $('input[name="image_links[' + $(this).data('index') + ']"]').val('');
                });
                $('#gallery-preview-container').append(previewBox);
            }
        });
    });

    function isValidImageUrl(string) {
        try {
            new URL(string);
            return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(string);
        } catch (_) {
            return false;
        }
    }

    // Image link handling
    let linkIndex = {{ old('image_links') ? count(old('image_links')) : 1 }};
    $('#add-image-link').on('click', function () {
        const html = `
            <div class="row mb-2 image-link-row">
                <div class="col-md-10">
                    <input type="text" name="image_links[${linkIndex}]" class="form-control" placeholder="Enter image URL (e.g., https://example.com/image.jpg)">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-image-link">X</button>
                </div>
            </div>
        `;
        $('#image-links-wrapper').append(html);
        linkIndex++;
    });

    $(document).on('click', '.remove-image-link', function () {
        $(this).closest('.image-link-row').remove();
    });

    // Options handling
    let optionIndex = {{ old('options') ? count(old('options')) : 1 }};
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

    $(document).on('click', '.remove-option', function () {
        $(this).closest('.option-row').remove();
    });
});
</script>
<script>
document.getElementById('add-color').addEventListener('click', function () {
    let wrapper = document.getElementById('color-wrapper');
    let index = wrapper.querySelectorAll('.color-row').length;

    let row = document.createElement('div');
    row.classList.add('row', 'mb-2', 'color-row');

    row.innerHTML = `
        <div class="col-md-10">
            <input type="text" name="color[]" class="form-control" placeholder="Enter color (e.g. Blue)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-color">X</button>
        </div>
    `;

    wrapper.appendChild(row);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-color')) {
        e.target.closest('.color-row').remove();
    }
});
</script>

@endsection