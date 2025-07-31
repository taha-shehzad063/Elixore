@extends('admin.frontend.partials.app')

@section('content')
<style>
/* Add the preview-box styles if they are not already in app.blade.php or another global CSS */
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

                            <form method="POST" action="{{ route('admin.products.update', $products->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $products->name) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" id="category-select" class="form-control" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $products->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="info" class="form-label">Short Info</label>
        <textarea name="info" class="form-control">{{ old('info', $products->info ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $products->description ?? '') }}</textarea>
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
        <label class="form-label">Tags</label>
        <div class="row">
            @foreach($tags as $tag)
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                            {{ in_array($tag->id, old('tags', $products->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Specifications</label>
        <div id="specification-wrapper">
            @if(old('specifications'))
                @foreach(old('specifications') as $index => $spec)
                    <div class="row mb-2 spec-row">
                        <div class="col-md-5">
                            <input type="text" name="specifications[{{ $index }}][key]" class="form-control" placeholder="Key" value="{{ $spec['key'] ?? '' }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="specifications[{{ $index }}][value]" class="form-control" placeholder="Value" value="{{ $spec['value'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-spec">X</button>
                        </div>
                    </div>
                @endforeach
            @elseif(isset($products) && $products->specifications->count())
                @foreach($products->specifications as $index => $spec)
                    <div class="row mb-2 spec-row">
                        <div class="col-md-5">
                            <input type="text" name="specifications[{{ $index }}][key]" class="form-control" placeholder="Key" value="{{ $spec->key }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="specifications[{{ $index }}][value]" class="form-control" placeholder="Value" value="{{ $spec->value }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-spec">X</button>
                        </div>
                    </div>
                @endforeach
            @else
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
            @endif
        </div>
        <button type="button" class="btn btn-primary" id="add-spec">Add Specification</button>
    </div>

    <div class="mb-3">
        <label class="form-label">Additional Options</label>
        <div id="option-wrapper">
            @if(old('options'))
                @foreach(old('options') as $index => $option)
                    <div class="row mb-2 option-row">
                        <div class="col-md-5">
                            <input type="text" name="options[{{ $index }}][key]" class="form-control" placeholder="Option Name" value="{{ $option['key'] ?? '' }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="options[{{ $index }}][value]" class="form-control" placeholder="Option Value" value="{{ $option['value'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-option">X</button>
                        </div>
                    </div>
                @endforeach
            @elseif(isset($products) && $products->options->count())
                @foreach($products->options as $index => $option)
                    <div class="row mb-2 option-row">
                        <div class="col-md-5">
                            <input type="text" name="options[{{ $index }}][key]" class="form-control" placeholder="Option Name" value="{{ $option->key }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="options[{{ $index }}][value]" class="form-control" placeholder="Option Value" value="{{ $option->value }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-option">X</button>
                        </div>
                    </div>
                @endforeach
            @else
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
            @endif
        </div>
        <button type="button" class="btn btn-primary" id="add-option">Add Option</button>
    </div>


    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" step="0.01" class="form-control" required value="{{ old('price', $products->price) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Discount Price (optional)</label>
        <input type="number" name="discount_price" step="0.01" class="form-control" value="{{ old('discount_price', $products->discount_price) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload Gallery Images (Multiple)</label>
        <div class="border border-primary border-dashed rounded p-4 text-center" style="cursor: pointer;">
            <input type="file" name="gallery_images[]" id="gallery_images" class="d-none" accept="image/*" multiple>

            <div id="gallery-preview-container" class="d-flex flex-wrap justify-content-center gap-3">
                @foreach($products->galleries as $img)
                    <div class="position-relative preview-box"> {{-- Added preview-box class --}}
                        <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid rounded border" style="max-height: 120px;">
                        <a href="{{ route('admin.products.gallery.delete', $img->id) }}"
                           class="remove-btn delete-gallery-image" {{-- Changed classes for consistent styling --}}
                           data-url="{{ route('admin.products.gallery.delete', $img->id) }}"
                           title="Remove">
                            &times;
                        </a>
                    </div>
                @endforeach
            </div>

            <div onclick="document.getElementById('gallery_images').click();">
                <i style="font-size: 4.125rem !important;" class="bi bi-cloud-arrow-up text-primary"></i>
                <p class="text-muted">Click to Upload or drag & drop</p>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Product</button>
</form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Design and Developed by
                    <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore.com</a>
                    Distributed by
                    <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
// Keep original gallery images logic separate if it's working for new uploads
$(document).ready(function () {
    // This part handles initial category-subcategory loading, if applicable
    // If you need to pre-select subcategory based on product's category, you'll need more logic here
    $('#category-select').on('change', function () {
        let categoryId = $(this).val();
        let subcategorySelect = $('#subcategory-select'); // Assuming you eventually add a subcategory select

        subcategorySelect.html('<option value="">-- Loading... --</option>');

        if (categoryId) {
            $.ajax({
                url: '/admin/categories/' + categoryId + '/subcategories',
                type: 'GET',
                success: function (data) {
                    subcategorySelect.html('<option value="">-- Select Subcategory --</option>');
                    $.each(data, function (key, subcategory) {
                        subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                    });
                    // If you have a current subcategory for the product and it's loaded, select it here
                    // let currentSubcategoryId = {{ isset($products->subcategory_id) ? $products->subcategory_id : 'null' }};
                    // if (currentSubcategoryId) {
                    //     subcategorySelect.val(currentSubcategoryId);
                    // }
                },
                error: function () {
                    subcategorySelect.html('<option value="">-- Failed to load --</option>');
                }
            });
        } else {
            subcategorySelect.html('<option value="">-- Select Subcategory --</option>');
        }
    }).trigger('change'); // Trigger on page load to populate subcategories if a category is already selected


    // Logic for new gallery image previews (client-side only)
    $('#gallery_images').on('change', function () {
        // Clear only the *newly selected* previews, existing ones are handled by the server-side loop
        // If you want to replace all previews, adjust this logic
        const newPreviewContainer = $('#gallery-preview-container');
        // Filter out existing images when adding new ones to prevent duplication
        newPreviewContainer.find('.new-upload-preview').remove(); // Remove temporary previews

        const files = this.files;
        if (files) {
            [...files].forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    newPreviewContainer.append(`
                        <div class="position-relative preview-box new-upload-preview">
                            <img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 120px;">
                            {{-- No remove button for newly added images via JS, as they are part of the new file input --}}
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
        }
    });
});
</script>

<script>
$(document).on('click', '.delete-gallery-image', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    let url = $(this).data('url'); // Get the URL from the data attribute
    let csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

    Swal.fire({
        title: 'Are you sure?',
        text: "This image will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a hidden form and submit it via POST
            $('<form>', {
                "id": "deleteGalleryImageForm",
                "action": url,
                "method": "POST" // Your route is defined as POST
            }).append(
                $('<input>', {
                    "type": "hidden",
                    "name": "_token",
                    "value": csrfToken // Include CSRF token
                })
                // REMOVE THE _METHOD HIDDEN INPUT ENTIRELY
                // Do NOT add this: $('<input>', { "type": "hidden", "name": "_method", "value": "DELETE" })
            ).appendTo('body').submit();
        }
    });
});
</script>

<script>
// Dynamic Specifications and Options Logic
$(document).ready(function () {
    // Initialize specIndex based on existing specifications count or 0 if none
    let specIndex = $('#specification-wrapper .spec-row').length;
    if (specIndex === 0) { // If there are no existing specs and no old input, start with 0 for the first empty row
        specIndex = 0;
    }


    $('#add-spec').click(function () {
      $('#specification-wrapper').append(`
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
      `);
      specIndex++;
    });

    $(document).on('click', '.remove-spec', function () {
      $(this).closest('.spec-row').remove();
    });

    // Initialize optionIndex based on existing options count or 0 if none
    let optionIndex = $('#option-wrapper .option-row').length;
    if (optionIndex === 0) { // If no existing options and no old input, start with 0 for the first empty row
        optionIndex = 0;
    }

    $('#add-option').click(function () {
      $('#option-wrapper').append(`
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
      `);
      optionIndex++;
    });

    $(document).on('click', '.remove-option', function () {
      $(this).closest('.option-row').remove();
    });
});
</script>