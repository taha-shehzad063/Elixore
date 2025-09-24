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

                    <div class="text-end mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-lg me-1"></i> Create Product
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="productsTable" class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Gallery Image</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('admin.frontend.home.products.partials.products_table')
                            </tbody>
                        </table>
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

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#productsTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [6,7] } // Gallery Image & Actions column non-orderable
        ]
    });

    // Delete confirmation
    $(document).on("submit", ".delete-form", function(e) {
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

    // View reviews
    $('#productsTable').on('click', '.view-reviews', function() {
        const productId = $(this).data('product-id');
        $('#reviewsContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        let reviewsUrl = "{{ route('admin.products.reviews', ['product' => ':id']) }}";
        reviewsUrl = reviewsUrl.replace(':id', productId);

        $.get(reviewsUrl, function(data) {
            $('#reviewsContent').html(data);
        }).fail(function() {
            $('#reviewsContent').html(`
                <div class="alert alert-danger">
                    Failed to load reviews. Please try again.
                </div>
            `);
        });

        $('#reviewsModal').modal('show');
    });

    // Delete review/reply
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
                    success: function () {
                        $itemToDelete.fadeOut(300, function () {
                            $(this).remove();
                            Swal.fire('Deleted!', `The ${isReview ? 'review' : 'reply'} has been deleted.`, 'success');
                            if (isReview && $('.list-group-item').length === 0) {
                                $('#reviewsContent').html(`
                                    <div class="alert alert-info">
                                        No reviews found for this product.
                                    </div>
                                `);
                            }
                        });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
