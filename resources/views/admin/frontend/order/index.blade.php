@extends('admin.frontend.partials.app')
@section('content')

<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        <!-- Header Start -->
        @include('admin.frontend.partials.header')
        <!-- Header End -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <h5 class="card-title fw-semibold mb-4">Orders</h5>
                    <div class="card">
                        <div class="card-body">
                            <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

                            <div class="table-responsive">
                                <table id="ordersTable" class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0">
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>User ID</th>
                                            <th>Total</th>
                                            <th>Total Quantity</th>
                                            <th>Shipping Cost</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->user_id }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>{{ $order->total_quantity }}</td>
                                                <td>${{ number_format($order->shipping_cost, 2) }}</td>
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'bg-warning',
                                                            'processing' => 'bg-info',
                                                            'shipped' => 'bg-primary',
                                                            'delivered' => 'bg-success',
                                                            'cancelled' => 'bg-secondary',
                                                        ];
                                                        $badgeClass = $statusClasses[$order->status] ?? 'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $order->id }}">View Details</button>

                                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </form>

                                                    <!-- Modal Inside Action TD -->
                                                    <div class="modal fade" id="detailsModal{{ $order->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $order->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="detailsModalLabel{{ $order->id }}">Details for Order #{{ $order->id }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h6>Order Total</h6>
                                                                    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>

                                                                    <h6>Products</h6>
                                                                    @if($order->items->isNotEmpty())
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Product</th>
                                                                                    <th>Image</th>
                                                                                    <th>Quantity</th>
                                                                                    <th>Price</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($order->items as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                                                        <td>
                                                                                            @if($item->product && $item->product->galleries->first() && $item->product->galleries->first()->image)
                                                                                                <img src="{{ asset('storage/' . $item->product->galleries->first()->image) }}" alt="{{ $item->product->name ?? 'Product Image' }}" width="80" class="rounded">
                                                                                            @else
                                                                                                <span>No Image</span>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>{{ $item->quantity ?? 'N/A' }}</td>
                                                                                        <td>${{ number_format($item->price ?? 0, 2) }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    @else
                                                                        <p>No products found for this order.</p>
                                                                    @endif

                                                                    <h6>Shipping Address</h6>
                                                                    @if($order->shippingAddress)
                                                                        <p><strong>Name:</strong> {{ $order->shippingAddress->name }}</p>
                                                                        <p><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                                                                        <p><strong>Address:</strong> {{ $order->shippingAddress->address }}</p>
                                                                        <p><strong>City:</strong> {{ $order->shippingAddress->city }}</p>
                                                                        <p><strong>State:</strong> {{ $order->shippingAddress->state ?? 'N/A' }}</p>
                                                                        <p><strong>Zip:</strong> {{ $order->shippingAddress->zip ?? 'N/A' }}</p>
                                                                        <p><strong>Country:</strong> {{ $order->shippingAddress->country }}</p>
                                                                    @else
                                                                        <p>No shipping address found.</p>
                                                                    @endif
<hr>
                                                                    <h6>Billing Address</h6>
                                                                    @if($order->billingAddress)
                                                                        <p><strong>Name:</strong> {{ $order->billingAddress->name }}</p>
                                                                        <p><strong>Phone:</strong> {{ $order->billingAddress->phone }}</p>
                                                                        <p><strong>Address:</strong> {{ $order->billingAddress->address }}</p>
                                                                        <p><strong>City:</strong> {{ $order->billingAddress->city }}</p>
                                                                        <p><strong>State:</strong> {{ $order->billingAddress->state ?? 'N/A' }}</p>
                                                                        <p><strong>Zip:</strong> {{ $order->billingAddress->zip ?? 'N/A' }}</p>
                                                                        <p><strong>Country:</strong> {{ $order->billingAddress->country }}</p>
                                                                    @else
                                                                        <p>No billing address found.</p>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No orders found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#ordersTable').DataTable({
            responsive: true,
            drawCallback: function () {
                $('[data-bs-toggle="modal"]').on('click', function () {
                    var target = $(this).data('bs-target');
                    $(target).modal('show');
                });
            }
        });
    });
</script>

@endsection
