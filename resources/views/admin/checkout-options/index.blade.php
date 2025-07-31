@extends('admin.frontend.partials.app')
@section('content')

  <!--  Body Wrapper -->
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

            <h5 class="card-title fw-semibold mb-4">Checkout Options</h5>
            <div class="card">
              <div class="card-body">
                <a href="{{ route('admin.checkout-options.create') }}" class="btn btn-success mb-3">
                  <i class="bi bi-plus-lg me-1"></i> Create Checkout Option
                </a>

                <div class="table-responsive">
                  <table id="checkoutOptionsTable" class="table text-nowrap align-middle mb-0">
                    <thead>
                      <tr class="border-2 border-bottom border-primary border-0">
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Key</th>
                        <th scope="col">Label</th>
                        <th scope="col">Shipping Cost</th>
                        <th scope="col">Message</th>
                        <th scope="col">Bank Name</th>
                        
                        <th scope="col">Account Name</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($options as $index => $option)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst($option->type) }}</td>
                        <td>{{ $option->key }}</td>
                        <td>{{ $option->label ?? '-' }}</td>
                        <td>
                          @if($option->shipping_cost !== null)
                            {{ number_format($option->shipping_cost, 2) }}
                          @else
                            -
                          @endif
                        </td>
                        <td>{{ $option->message ?? '-' }}</td>
                                <td>{{ $option->bank_name ?? '-' }}</td>

                        <td>{{ $option->account_name ?? '-' }}</td>
                        <td>{{ $option->account_number ?? '-' }}</td>
                        <td>
                          @if($option->status == 1)
                            <span class="badge bg-success">Active</span>
                          @else
                            <span class="badge bg-secondary">Inactive</span>
                          @endif
                        </td>
                        <td class="text-center">
                          <a href="{{ route('admin.checkout-options.edit', $option->id) }}" class="btn btn-sm btn-warning">Edit</a>

                          <form action="{{ route('admin.checkout-options.destroy', $option->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

          <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
                class="pe-1 text-primary text-decoration-underline">Elixore.com</a> Distributed by <a  target="_blank"
                class="pe-1 text-primary text-decoration-underline">Elixore</a></p>
          </div>
        </div>
      </div>
    </div>

@endsection

<!-- Include jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize DataTable -->
<script>
  $(document).ready(function () {
    $('#checkoutOptionsTable').DataTable({
      responsive: true
    });
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll(".delete-form");

    deleteForms.forEach(form => {
      form.addEventListener("submit", function (e) {
        e.preventDefault(); // prevent default form submit

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
            form.submit(); // manually submit the form
          }
        });
      });
    });
  });
</script>
