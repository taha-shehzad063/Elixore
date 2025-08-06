@extends('admin.frontend.partials.app')

@section('content')
<style>
.review-rating {
    color: #ffc107;
}
.review-item {
    transition: all 0.3s ease;
}
.review-item:hover {
    background-color: #f8f9fa;
}
.reply-item {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
}
</style>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        @include('admin.frontend.partials.header')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4">Products</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="search-input" class="form-control" placeholder="Search product by name...">
                        </div>
                        <div class="col-md-3">
                            <select id="per-page" class="form-control">
                                <option value="5">5 per page</option>
                                <option value="10" selected>10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-lg me-1"></i> Create Product
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Gallery Image</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products-body">
                                @include('admin.frontend.home.products.partials.products_table')
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3" id="pagination-links">
                        {{ $products->links('pagination::bootstrap-5') }}
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
<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewsModalLabel">Product Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewsContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    function fetch_data(url = '{{ route('admin.products.index') }}') {
        let query = $('#search-input').val();
        let perPage = $('#per-page').val();

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                search: query,
                per_page: perPage
            },
            success: function (data) {
                $('#products-body').html($(data).find('#products-body').html());
                $('#pagination-links').html($(data).find('#pagination-links').html());
            }
        });
    }

    // Live Search
    $('#search-input').on('keyup', function () {
        fetch_data();
    });

    // Per Page Change
    $('#per-page').on('change', function () {
        fetch_data();
    });

    // Pagination Click
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fetch_data(url);
    });

    // Delete confirmation
    $(document).on("submit", ".delete-form", function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>


<script>
    $(document).on('click', '.view-reviews', function() {
    const productId = $(this).data('product-id');
    const modal = $('#reviewsModal');
    
    // Show loading spinner
    $('#reviewsContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    // Load reviews via AJAX
    $.get(`/admin/products/${productId}/reviews`, function(data) {
        $('#reviewsContent').html(data);
    }).fail(function() {
        $('#reviewsContent').html(`
            <div class="alert alert-danger">
                Failed to load reviews. Please try again.
            </div>
        `);
    });
});

// Handle delete review/reply
// Handle delete review/reply
$(document).on('click', '.delete-review, .delete-reply', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    const isReview = $(this).hasClass('delete-review');
    const $itemToDelete = isReview ? $(this).closest('.list-group-item') : $(this).closest('.mb-2');
    
    Swal.fire({
        title: "Are you sure?",
        text: `You are about to delete this ${isReview ? 'review' : 'reply'}!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    // Remove the item from DOM
                    $itemToDelete.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Show success message
                        Swal.fire(
                            'Deleted!',
                            `The ${isReview ? 'review' : 'reply'} has been deleted.`,
                            'success'
                        );
                        
                        // If it was the last review, show empty message
                        if (isReview && $('.list-group-item').length === 0) {
                            $('#reviewsContent').html(`
                                <div class="alert alert-info">
                                    No reviews found for this product.
                                </div>
                            `);
                        }
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while deleting.',
                        'error'
                    );
                }
            });
        }
    });
});
</script>