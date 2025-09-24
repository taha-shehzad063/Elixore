@extends('admin.frontend.partials.app')
@section('content')
<style>
.contact-item {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}
.contact-item:hover {
    background-color: #e9ecef;
}
</style>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical">
    <div class="body-wrapper">
        @include('admin.frontend.partials.header')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4">Contact Messages</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="contactsTable" class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $index => $msg)
                                        <tr class="contact-item">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $msg->name }}</td>
                                            <td>{{ $msg->email }}</td>
                                            <td>{{ $msg->subject ?? '-' }}</td>
                                            <td>{{ Str::limit($msg->message, 50) }}</td>
                                            <td>{{ $msg->created_at ? $msg->created_at->format('d M Y') : '-' }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm view-message"
                                                        data-name="{{ $msg->name }}"
                                                        data-email="{{ $msg->email }}"
                                                        data-subject="{{ $msg->subject }}"
                                                        data-message="{{ $msg->message }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#messageModal">
                                                    View
                                                </button>
                                                <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
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

        <!-- View Message Modal -->
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel">Contact Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> <span id="modalName"></span></p>
                        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                        <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
                        <p><strong>Message:</strong></p>
                        <div id="modalMessage" class="border p-2 rounded bg-light"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('#contactsTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [6] }
        ]
    });

    // Delete confirmation
    $(document).on("submit", ".delete-form", function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: "Are you sure?",
            text: "This message will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Show message in modal
    $('.view-message').on('click', function() {
        $('#modalName').text($(this).data('name'));
        $('#modalEmail').text($(this).data('email'));
        $('#modalSubject').text($(this).data('subject') || '-');
        $('#modalMessage').text($(this).data('message'));
    });
});
</script>
