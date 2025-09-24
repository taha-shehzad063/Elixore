@extends('admin.frontend.partials.app')
@section('content')
<style>
.comment-item {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}
.comment-item:hover {
    background-color: #e9ecef;
}
.reply-item {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
}
</style>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="body-wrapper">
        @include('admin.frontend.partials.header')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4">Blogs</h5>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.blogs.create') }}" class="btn btn-success mb-3">
                                <i class="bi bi-plus-lg me-1"></i> Create Blog
                            </a>
                            <div class="table-responsive">
                                <table id="blogsTable" class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0"> 
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Image</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($blogs as $index => $blog)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $blog->name }}</td>
                                            <td>
                                                @if($blog->image)
                                                    <img src="{{ asset($blog->image) }}" alt="blog" width="80" class="rounded">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                                <button class="btn btn-info btn-sm view-comments" 
                                                        data-blog-id="{{ $blog->id }}" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#commentsModal">
                                                    <i class="bi bi-chat-left-text"></i> Comments
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Modal -->
        <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentsModalLabel">Blog Comments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="commentsContent">
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

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Design and Developed by 
                <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore.com</a> 
                Distributed by 
                <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
            </p>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable with proper options
    var table = $('#blogsTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [3] } // Make actions column non-orderable
        ]
    });

    // Delete confirmation for blogs
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

    // Load comments modal - using event delegation to work with DataTable
    $('#blogsTable').on('click', '.view-comments', function() {
        const blogId = $(this).data('blog-id');
        const modal = $('#commentsModal');
        
        // Show loading spinner
        $('#commentsContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        
        // Load comments via AJAX
        $.get(`/admin/blogs/${blogId}/comments`, function(data) {
            $('#commentsContent').html(data);
        }).fail(function() {
            $('#commentsContent').html(`
                <div class="alert alert-danger">
                    Failed to load comments. Please try again.
                </div>
            `);
        });
    });

    // Handle delete comment/reply
    $(document).on('click', '.delete-comment, .delete-reply', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const isComment = $(this).hasClass('delete-comment');
        const $itemToDelete = isComment ? $(this).closest('.comment-item') : $(this).closest('.reply-item');
        
        Swal.fire({
            title: "Are you sure?",
            text: `You are about to delete this ${isComment ? 'comment' : 'reply'}!`,
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
                        // Remove the item from DOM with animation
                        $itemToDelete.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Show success message
                            Swal.fire(
                                'Deleted!',
                                `The ${isComment ? 'comment' : 'reply'} has been deleted.`,
                                'success'
                            );
                            
                            // If no comments left, show empty message
                            if (isComment && $('.comment-item').length === 0) {
                                $('#commentsContent').html(`
                                    <div class="alert alert-info">
                                        No comments found for this blog.
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
});
</script>