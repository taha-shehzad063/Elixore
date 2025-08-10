@extends('front.default.partials.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h3 class="mb-4">Change Password</h3>

            <form id="changePasswordForm">
                @csrf

                <!-- Current Password -->
                <div class="mb-3 position-relative">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-danger error-message" id="error-current_password"></small>
                </div>

                <!-- New Password -->
                <div class="mb-3 position-relative">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-danger error-message" id="error-new_password"></small>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 position-relative">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-danger error-message" id="error-new_password_confirmation"></small>
                </div>

                <button type="submit" class="btn w-100" style="background:#71cd14 !important; color:white;">
                    Change Password
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        let targetId = $(this).data('target');
        let input = $('#' + targetId);
        let icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Handle form submission
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        $('.error-message').text(''); // clear old errors

        $.ajax({
            url: "{{ route('change.password.ajax') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('.error-message').text('');

                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Changed!',
                        text: response.message,
                        confirmButtonColor: '#71cd14'
                    });
                    $('#changePasswordForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Handle Laravel validation + your custom error
                    let res = xhr.responseJSON;

                    if (res.errors) {
                        $.each(res.errors, function(key, value) {
                            $('#error-' + key).text(value[0]);
                        });
                    } else if (res.field) {
                        $('#error-' + res.field).text(res.message);
                    }
                }
            }
        });
    });
});
</script>
