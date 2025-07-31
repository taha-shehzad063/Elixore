@extends('front.default.partials.app')

@section('content')
<div class="container position-relative" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <!-- Background Shapes -->
    <div class="background position-absolute top-0 start-0 w-100 h-100">
        <div class="shapes"></div>
        <div class="shapes"></div>
    </div>

    <!-- Register Card -->
    <div class="card shadow-lg p-4 col-md-6 col-lg-6 col-12 animate__animated animate__fadeIn" style="z-index: 2; border-radius: 18px; background: #fff;">
        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#71cd14;">Create Your Account</h2>
            <p class="text-muted">Sign up to get started</p>
            <hr class="mx-auto" style="border: 1px solid #71cd14; width: 60px;">
        </div>

        <!-- Register Form -->
        <form id="registerForm" action="{{ route('user.register') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold" for="name"><i class="bi bi-person me-2"></i>Full Name</label>
                <input type="text" name="name" id="name" class="form-control rounded-pill px-4 py-2" placeholder="Enter Name" required>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold" for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                <input type="email" name="email" id="email" class="form-control rounded-pill px-4 py-2" placeholder="Enter Email" required>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold" for="password"><i class="bi bi-lock me-2"></i>Password</label>
                <input type="password" name="password" id="password" class="form-control rounded-pill px-4 py-2" placeholder="Enter Password" required>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold" for="password_confirmation"><i class="bi bi-lock me-2"></i>Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill px-4 py-2" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn custom-register-btn w-100 py-2 rounded-pill fw-bold animated-btn mt-2" style="font-size:1.1rem;">
                Register
            </button>
        </form>
          <!-- Divider with OR text -->
            <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1">
                <span class="mx-3 text-muted">OR</span>
                <hr class="flex-grow-1">
            </div>
             <a href="{{ route('google.login') }}" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold animated-btn mb-3">
                <i class="bi bi-google me-2"></i> Continue with Google
            </a>
 <div class="text-center ">
                <a href="{{ route('user.login') }}" class="btn btn-link text-decoration-none" style="color:#71cd14;">Already have Account then Login</a>
            </div>
        
    </div>
</div>

<!-- Email Verification Modal -->
<div class="modal fade" id="verifyEmailModal" tabindex="-1" aria-labelledby="verifyEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:18px;">
      <div class="modal-header" style="background:#f3ffe7;">
        <h5 class="modal-title" id="verifyEmailModalLabel" style="color:#71cd14;">Verify Your Email</h5>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-envelope-check" style="font-size:2.5rem;color:#71cd14;"></i>
        <p class="mt-3 mb-0" style="font-size:1.1rem;">
          A verification link has been sent to your email address.
          <br>
          Please check your inbox and click the link to activate your account.
        </p>
        <a href="https://mail.google.com" target="_blank" class="btn btn-sm btn-outline-success mt-4 rounded-pill px-4" style="color:#71cd14; border-color:#71cd14;">
          Open Gmail
        </a>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 rounded-pill" data-bs-dismiss="modal" style="background:#71cd14;">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Styles -->
<style>
    .background .shapes {
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: #f0f0f0;
        opacity: 0.4;
        top: -50px;
        left: -50px;
        z-index: 0;
    }
    .card {
        border-radius: 18px;
        background: #fff;
    }
    .form-control {
        border-radius: 50px;
        border: 1px solid #eaeaea;
        box-shadow: none;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #71cd14;
        box-shadow: 0 0 0 2px rgba(113,205,20,0.08);
    }
    .custom-register-btn {
        background-color: #71cd14;
        border-color: #71cd14;
        color: white;
        transition: background 0.2s, border 0.2s;
    }
    .custom-register-btn:hover {
        background-color: #66b812;
        border-color: #66b812;
    }
    .custom-register-btn:active {
        background-color: #5aa30f !important;
        border-color: #5aa30f !important;
    }
    .animated-btn {
        transition: all 0.3s ease-in-out;
    }
    .animated-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(113,205,20,0.12);
    }
    .animated-btn:active {
        transform: scale(0.98);
        box-shadow: none;
    }
</style>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

@endsection

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#registerForm').on('submit', function (e) {
            e.preventDefault(); // prevent default form submission
            
            // Get form data
            var formData = $(this).serialize();
            
            // Show the modal immediately
            var verifyEmailModal = new bootstrap.Modal(document.getElementById('verifyEmailModal'));
            verifyEmailModal.show();
            
            // Submit the form via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // The modal is already shown, no need to do anything else here
                    // The form will be submitted normally after modal is closed
                },
                error: function(xhr) {
                    verifyEmailModal.hide();
                    
                    // Handle validation errors
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Display errors to the user
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key).after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    } else {
                        // Handle other errors
                        alert('An error occurred. Please try again.');
                    }
                }
            });
            
            // When modal closes (OK button or dismissal), redirect to login page
            $('#verifyEmailModal').one('hidden.bs.modal', function () {
                // window.location.href = "{{ route('user.login') }}";
            });
        });
        
        // Remove validation errors when user starts typing
        $('input').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    });
</script>

