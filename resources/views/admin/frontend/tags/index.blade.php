@extends('admin.frontend.partials.app')

@section('content')
<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        <!--  Header Start -->
        @include('admin.frontend.partials.header')
        <!--  Header End -->

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4">Tags List</h5>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-success mb-3">
                                <i class="bi bi-plus-lg me-1"></i> Create Tag
                            </a>

                            <div class="table-responsive">
                                <table id="tagsTable" class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tags as $index => $tag)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tag->name }}</td>
                                                <td>{{ $tag->category->name ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="py-6 px-6 text-center">
                                <p class="mb-0 fs-4">
                                    Design and Developed by
                                    <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore.com</a>
                                    Distributed by
                                    <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->
    </div> <!-- body-wrapper -->
</div> <!-- page-wrapper -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#tagsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthChange: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search tags...",
                paginate: {
                    next: "›",
                    previous: "‹"
                }
            }
        });

        // SweetAlert2 for delete confirmation
        $('.delete-form').on('submit', function (e) {
            e.preventDefault();
            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

