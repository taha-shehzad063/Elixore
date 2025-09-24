@extends('front.default.partials.app')

@section('content')
<section class="py-5" style="background:#f8f9fa;">
    <div class="container-fluid px-lg-5 px-2">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <i class="ti-heart fs-2 me-2" style="color:#71cd14;"></i>
                            <h3 class="fw-bold mb-0" style="color:#222;">My Wishlist</h3>
                        </div>

                        <div id="cart-alert" class="alert alert-success d-none" role="alert">
                            Product added to cart successfully!
                        </div>

                        @if($wishlistItems->count())
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="wishlistTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th style="min-width:180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wishlistItems as $item)
                                    <tr data-item="{{ $item->id }}">
                                        <td class="fw-semibold align-middle">
                                            {{ $item->product->name }}
                                        </td>
                                        <td>
                                         @php
    $imagePath = $item->product->galleries->first()->image ?? 'default.jpg';

    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        $finalImage = $imagePath; // ✅ External URL
    } elseif (\Illuminate\Support\Facades\Storage::exists($imagePath)) {
        $finalImage = Storage::url($imagePath); // ✅ Storage
    } else {
        $finalImage = asset($imagePath); // ✅ Public or default
    }
@endphp

    <img src="{{ $finalImage }}"
         alt="{{ $item->product->name }}"
         class="img-fluid rounded shadow-sm"
         style="max-width:150px; height:auto;">


                                        </td>
                                        <td class="fw-bold align-middle" style="color:#71cd14;">
                                            Rs{{ number_format($item->product->price, 2) }}
                                        </td>
                                        <td class="align-middle">
                                            <a href="javascript:void(0);"
                                               class="btn btn-success btn-sm rounded-pill add-to-cart-btn me-2"
                                               data-id="{{ $item->product->id }}"
                                               style="background:#71cd14;border:none;">
                                                <i class="ti-shopping-cart"></i> Add to Cart
                                            </a>
                                            <a href="javascript:void(0);"
                                               class="btn btn-outline-danger btn-sm rounded-pill remove-wishlist-item"
                                               data-id="{{ $item->id }}"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Remove from Wishlist">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="ti-heart fs-1 mb-3" style="color:#71cd14;"></i>
                            <h4 class="fw-bold mb-2" style="color:#222;">Your wishlist is empty</h4>
                            <p class="text-muted mb-4">
                                Start adding products you love. Click the 
                                <span style="color:#71cd14;"><i class="ti-heart"></i></span> icon 
                                on any product to save it here!
                            </p>
                            <a href="{{ route('main') }}" class="btn btn-success rounded-pill px-4" style="background:#71cd14;border:none;">
                                Continue Shopping
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to Cart from Wishlist
    $(document).on('click', '.add-to-cart-btn', function () {
        var productId = $(this).data('id');
        var $row = $(this).closest('tr');
        var wishlistId = $row.data('item');

        $.ajax({
            url: '{{ route("cart.add.wishlsit") }}',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1
            },
            success: function (res) {
                Swal.fire({
                    icon: res.status ? 'success' : 'info',
                    title: res.status ? 'Added!' : 'Already in Cart',
                    text: res.status ? 'Product added to cart successfully.' : 'This product is already in your cart.',
                    confirmButtonText: 'OK'
                });

                $.ajax({
                    url: '/wishlist/remove/' + wishlistId,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res2) {
                        if (res2.status) {
                            $row.fadeOut(300, function () {
                                $(this).remove();
                                if ($('#wishlistTable tbody tr').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Remove with confirmation
    $(document).on('click', '.remove-wishlist-item', function () {
        var itemId = $(this).data('id');
        var $row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be removed from your wishlist.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#71cd14',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/wishlist/remove/' + itemId,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: 'Product has been removed from your wishlist.',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $row.fadeOut(300, function () {
                                $(this).remove();
                                if ($('#wishlistTable tbody tr').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
