@extends('admin.frontend.partials.app')

@section('content')
<style>
    .tracking-progress {
        position: relative;
        margin-bottom: 30px;
    }

    .tracking-progress .progress {
        height: 8px;
        margin-bottom: 20px;
    }

    .tracking-steps {
        display: flex;
        justify-content: space-between;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .tracking-steps li {
        text-align: center;
        position: relative;
        flex: 1;
    }

    .tracking-steps li.active .step-icon {
        background-color: #0d6efd;
        color: white;
    }

    .tracking-steps li .step-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .tracking-steps li .step-text {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }

    .tracking-steps li.active .step-text {
        color: #0d6efd;
        font-weight: bold;
    }

    .no-orders-container {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .no-orders-container img {
        max-width: 200px;
        margin-bottom: 20px;
    }

    .no-orders-container .btn-primary {
        padding: 10px 20px;
        font-weight: 500;
    }

    #customDateRange {
        display: none;
    }
</style>

<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
                    <div class="mb-3 d-flex align-items-center">
                        <div class="me-3">
                            <label for="statusFilter" class="form-label fw-bold">Filter by Status:</label>
                            <select id="statusFilter" class="form-select w-auto d-inline-block">
                                <option value="">All</option>
                                <option value="Awaiting Verification">Awaiting Verification</option>
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="periodFilter" class="form-label fw-bold">Filter by Period:</label>
                            <select id="periodFilter" class="form-select w-auto d-inline-block">
                                <option value="all">All</option>
                                <option value="today">Today</option>
                                <option value="this_month">This Month</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3" id="customDateRange">
                        <label class="form-label fw-bold">Custom Date Range:</label>
                        <input type="date" id="fromDate" class="form-control d-inline-block w-auto">
                        to
                        <input type="date" id="toDate" class="form-control d-inline-block w-auto">
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
                            <div class="table-responsive">
                                <table id="ordersTable" class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0">
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>User ID</th>
                                            <th>Total</th>
                                            <th>Total Quantity</th>
                                            <th>Shipping Cost</th>
                                            <th>Status</th>
                                            <th>Cancel</th>
                                            <th>Refund</th>
                                            <th>Created At</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $order->id }}</td>
                                                <td>
                                                    @if($order->items && $order->items->isNotEmpty())
                                                        {{ $order->items->pluck('product.name')->join(', ') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $order->user_id }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>{{ $order->total_quantity }}</td>
                                                <td>${{ number_format($order->shipping_cost, 2) }}</td>
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            'awaiting_verification' => 'bg-light text-dark',
                                                            'pending' => 'bg-warning',
                                                            'processing' => 'bg-info',
                                                            'shipped' => 'bg-primary',
                                                            'delivered' => 'bg-success',
                                                            'cancelled' => 'bg-secondary',
                                                        ];
                                                        $badgeClass = $statusClasses[$order->status] ?? 'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                </td>
                                                <!-- Cancel Column -->
                                                <td>
                                                    @if($order->is_cancel)
                                                        <span class="badge bg-danger">Requested</span>
                                                    @else
                                                        <span class="badge bg-light text-dark">—</span>
                                                    @endif
                                                </td>
                                                <!-- Refund Column -->
                                                <td>
                                                    @if($order->is_refunded)
                                                        <span class="badge bg-warning text-dark">Requested</span>
                                                    @else
                                                        <span class="badge bg-light text-dark">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $order->id }}">View Details</button>
                                                    <button type="button" class="btn btn-sm btn-warning track-order" data-order-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#trackingModal">Track</button>
                                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                                                            <option value="awaiting_verification" {{ $order->status == 'awaiting_verification' ? 'selected' : '' }}>Awaiting Verification</option>
                                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </form>

                                                    <!-- Details Modal -->
                                                    <div class="modal fade" id="detailsModal{{ $order->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $order->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="detailsModalLabel{{ $order->id }}">Details for Order #{{ $order->id }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h6>User Information</h6>
                                                                    <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                                                                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                                                                    <hr>
                                                                    <h6>Order Total</h6>
                                                                    <p><strong>Total:</strong> {{ number_format($order->total, 2) }}</p>
                                                                    <h6>Payment Method</h6>
                                                                    <p><strong>Total:</strong> {{ $order->payment_method }}</p>
                                                                    <h6>Payment Proof</h6>
                                                                    @if($order->payment_proof)
                                                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="" width="80" class="rounded">
                                                                    @else
                                                                        <span>No Image</span>
                                                                    @endif
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
                                                    <!-- End Details Modal -->
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center py-5">
                                                    <div class="no-orders-container">
                                                        <img src="{{ asset('images/no-orders-admin.png') }}" alt="No Orders Found" class="mb-4">
                                                        <h4 class="fw-bold text-muted mb-3">No Orders Found</h4>
                                                        <p class="text-muted mb-4">There are no orders available at the moment. Check back later or encourage customers to place orders!</p>
                                                        <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking Modal -->
                    <div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="trackingModalLabel">Order Tracking #<span id="trackingOrderId"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="tracking-progress">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="progressBar"></div>
                                        </div>
                                        <ul class="tracking-steps" id="trackingSteps">
                                            <!-- Steps will be added dynamically -->
                                        </ul>
                                    </div>
                                    <div class="tracking-details mt-4">
                                        <h6>Tracking Information</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Tracking Number:</strong> <span id="trackingNumber"></span></p>
                                                <p><strong>Carrier:</strong> <span id="trackingCarrier"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Estimated Delivery:</strong> <span id="trackingDelivery"></span></p>
                                                <p><strong>Current Location:</strong> <span id="trackingLocation"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tracking-history mt-4">
                                        <h6>Tracking History</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Location</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="trackingHistory">
                                                    <!-- History will be added dynamically -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="updateTrackingBtn">Update Tracking</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Tracking Modal -->
                    <div class="modal fade" id="updateTrackingModal" tabindex="-1" aria-labelledby="updateTrackingModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateTrackingModalLabel">Update Tracking for Order #<span id="updateOrderId"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="trackingForm">
                                    @csrf
                                    <input type="hidden" name="order_id" id="formOrderId">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" name="status" id="status" required>
                                                <option value="awaiting_verification">Awaiting Verification</option>
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="in_transit">In Transit</option>
                                                <option value="out_for_delivery">Out for Delivery</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="returned">Returned</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" class="form-control" name="location" id="location">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tracking_number" class="form-label">Tracking Number</label>
                                            <input type="text" class="form-control" name="tracking_number" id="tracking_number">
                                        </div>
                                        <div class="mb-3">
                                            <label for="carrier" class="form-label">Carrier</label>
                                            <input type="text" class="form-control" name="carrier" id="carrier">
                                        </div>
                                        <div class="mb-3">
                                            <label for="estimated_delivery" class="form-label">Estimated Delivery</label>
                                            <input type="datetime-local" class="form-control" name="estimated_delivery" id="estimated_delivery">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="py-6 px-6 text-center">
                        <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore.com</a> Distributed by <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a></p>
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
    // Initialize DataTable
    let table = $('#ordersTable').DataTable({
        responsive: true,
        columnDefs: [
            { targets: [8, 9, 11], orderable: false } // Disable sorting on Cancel, Refund, and Actions columns
        ],
        drawCallback: function () {
            // Re-attach modal triggers after DataTable redraw
            $('[data-bs-toggle="modal"]').off('click').on('click', function () {
                var target = $(this).data('bs-target');
                $('.modal').modal('hide'); // Close other modals
                $(target).modal('show');
            });
        }
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        table.column(7).search(status, true, false).draw(); // Column 7 = Status
    });

    // Period filter
    $('#periodFilter').on('change', function() {
        let period = $(this).val();
        if (period === 'custom') {
            $('#customDateRange').show();
        } else {
            $('#customDateRange').hide();
            applyDateFilter(period);
        }
    });

    // Custom date range filter
    $('#fromDate, #toDate').on('change', function() {
        if ($('#periodFilter').val() === 'custom') {
            applyDateFilter('custom');
        }
    });

    function applyDateFilter(period) {
        let searchValue = '';
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
        let yyyy = today.getFullYear();

        if (period === 'today') {
            searchValue = yyyy + '-' + mm + '-' + dd;
        } else if (period === 'this_month') {
            searchValue = yyyy + '-' + mm;
        } else if (period === 'custom') {
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if (fromDate && toDate) {
                searchValue = fromDate + '|' + toDate;
            } else {
                searchValue = '';
            }
        }

        table.column(10).search(searchValue, true, false).draw(); // Column 10 = Created At
    }

    // Tracking functionality
    $(document).on('click', '.track-order', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var orderId = $(this).data('order-id');
        $('#trackingOrderId').text(orderId);
        
        $('#trackingHistory').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
        
        $.ajax({
            url: '/admin/orders/' + orderId + '/tracking',
            method: 'GET',
            success: function(data) {
                updateProgressBar(data.status);
                
                if(data.tracking_info) {
                    $('#trackingNumber').text(data.tracking_info.tracking_number || 'N/A');
                    $('#trackingCarrier').text(data.tracking_info.carrier || 'N/A');
                    $('#trackingDelivery').text(data.tracking_info.estimated_delivery ? 
                        new Date(data.tracking_info.estimated_delivery).toLocaleString() : 'N/A');
                    $('#trackingLocation').text(data.tracking_info.location || 'N/A');
                }
                
                var historyHtml = '';
                if(data.history && data.history.length > 0) {
                    data.history.forEach(function(item) {
                        historyHtml += `
                            <tr>
                                <td>${new Date(item.created_at).toLocaleString()}</td>
                                <td><span class="badge ${getStatusClass(item.status)}">${item.status.replace('_', ' ')}</span></td>
                                <td>${item.location || 'N/A'}</td>
                                <td>${item.description || 'N/A'}</td>
                            </tr>
                        `;
                    });
                } else {
                    historyHtml = '<tr><td colspan="4">No tracking history available</td></tr>';
                }
                $('#trackingHistory').html(historyHtml);
                
                $('#formOrderId').val(orderId);
                $('#updateOrderId').text(orderId);
                
                $('#updateTrackingBtn').off('click').on('click', function() {
                    $('.modal').modal('hide');
                    $('#updateTrackingModal').modal('show');
                });
            },
            error: function(xhr) {
                $('#trackingHistory').html('<tr><td colspan="4" class="text-danger">Error loading tracking data</td></tr>');
                console.error('Tracking error:', xhr.responseText);
            }
        });
    });

    $(document).on('submit', '#trackingForm', function(e) {
        e.preventDefault();
        var orderId = $('#formOrderId').val();
        var form = $(this);
        
        $.ajax({
            url: '/admin/orders/' + orderId + '/tracking',
            method: 'POST',
            data: form.serialize(),
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).html('Saving...');
            },
            success: function(response) {
                $('#updateTrackingModal').modal('hide');
                $('.track-order[data-order-id="' + orderId + '"]').trigger('click');
                alert('Tracking updated successfully');
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Unable to update tracking'));
                console.error('Update error:', xhr.responseText);
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).html('Save Changes');
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('input, select, textarea').blur();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    function updateProgressBar(status) {
        var progress = 0;
        var steps = [
            {status: 'awaiting_verification', label: 'Awaiting Verification', class: 'bg-light'},
            {status: 'pending', label: 'Pending', class: 'bg-secondary'},
            {status: 'processing', label: 'Processing', class: 'bg-info'},
            {status: 'shipped', label: 'Shipped', class: 'bg-primary'},
            {status: 'in_transit', label: 'In Transit', class: 'bg-warning'},
            {status: 'out_for_delivery', label: 'Out for Delivery', class: 'bg-warning'},
            {status: 'delivered', label: 'Delivered', class: 'bg-success'}
        ];
        
        var stepsHtml = '';
        var currentStep = steps.findIndex(step => step.status === status);
        
        steps.forEach(function(step, index) {
            var isActive = index <= currentStep;
            stepsHtml += `
                <li class="${isActive ? 'active' : ''}">
                    <span class="step-icon ${step.class}"></span>
                    <span class="step-text">${step.label}</span>
                </li>
            `;
            if (index <= currentStep) {
                progress = ((index + 1) / steps.length) * 100;
            }
        });
        
        $('#progressBar').css('width', progress + '%');
        $('#trackingSteps').html(stepsHtml);
    }

    function getStatusClass(status) {
        var classes = {
            'awaiting_verification': 'bg-light text-dark',
            'pending': 'bg-secondary',
            'processing': 'bg-info',
            'shipped': 'bg-primary',
            'in_transit': 'bg-warning',
            'out_for_delivery': 'bg-warning',
            'delivered': 'bg-success',
            'cancelled': 'bg-danger',
            'returned': 'bg-dark'
        };
        return classes[status] || 'bg-secondary';
    }
});
</script>
@endsection