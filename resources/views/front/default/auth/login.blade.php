@extends('front.default.partials.app')

@section('content')

<div class="container position-relative" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <!-- Background Shapes -->
    <div class="background position-absolute top-0 start-0 w-100 h-100">
        <div class="shapes"></div>
        <div class="shapes"></div>
    </div>

    <!-- Login Card -->
    <div class="card shadow-lg p-4 col-md-6 col-lg-6 col-12 animate__animated animate__fadeIn" style="z-index: 2; border-radius: 18px; background: #fff;">
        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#71cd14;">Welcome Back</h2>
            <p class="text-muted">Login to your account</p>
            <hr class="mx-auto" style="border: 1px solid #71cd14; width: 60px;">
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success rounded-pill px-4 py-2 text-center mb-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
                    <!-- Register Form --><form id="loginForm" action="{{ route('user.login.store') }}" method="POST" autocomplete="off">

            @csrf

            <div id="loginResponseMsg" class="mb-3"></div>

            <div class="mb-3 position-relative">
                <label for="email" class="form-label fw-semibold"><i class="bi bi-envelope me-2"></i>Email Address</label>
                <input type="email" name="email" id="email" class="form-control rounded-pill px-4 py-2 @error('email') is-invalid @enderror" placeholder="Enter Email" autocomplete="email" required value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback d-block ms-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label fw-semibold"><i class="bi bi-lock me-2"></i>Password</label>
                <input type="password" name="password" id="password" class="form-control rounded-pill px-4 py-2 @error('password') is-invalid @enderror" placeholder="Enter Password" required>
                <i class="bi bi-eye-slash toggle-password" data-target="#password" style="position: absolute; top: 50%; right: 18px; transform: translateY(-50%); cursor: pointer; color:#71cd14;"></i>
                @error('password')
                    <div class="invalid-feedback d-block ms-2">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn login-btn w-100 py-2 rounded-pill fw-bold animated-btn mt-2" style="font-size:1.1rem;">
                Login Now
            </button>

            <div class="text-center my-3 text-muted">or login with</div>

            <div class="d-flex justify-content-between gap-2 mb-2">
               <a href="{{ route('google.login') }}" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold animated-btn mb-3">
                <i class="bi bi-google me-2"></i> Continue with Google
            </a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('user.register.get') }}" class="btn btn-link text-decoration-none" style="color:#71cd14;">Create New Account</a>
            </div>
        </form>
    </div>
</div>

<!-- Custom Styles -->
<style>
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
.login-btn {
    background-color: #71cd14;
    border-color: #71cd14;
    color: white;
    transition: background 0.2s, border 0.2s;
}
.login-btn:hover {
    background-color: #66b812;
    border-color: #66b812;
}
.login-btn:active {
    background-color: #5aa30f !important;
    border-color: #5aa30f !important;
}
.toggle-password:hover {
    color: #66b812;
}
.animated-btn {
    transition: all 0.3s ease-in-out;
}
.animated-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(113,205,20,0.12);
}
</style>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Toggle password visibility
    $('.toggle-password').on('click', function () {
        const input = $($(this).data('target'));
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('bi-eye bi-eye-slash');
    });

    // Handle login via AJAX
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $('#loginResponseMsg').html('');
        $.ajax({
            type: 'POST',
            url: "{{ route('user.login.store') }}",
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#loginResponseMsg').html('<div class="alert alert-success rounded-pill px-4 py-2 text-center mb-3">'+response.message+'</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1200);
                } else {
                    $('#loginResponseMsg').html('<div class="alert alert-danger rounded-pill px-4 py-2 text-center mb-3">'+response.message+'</div>');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = '<ul class="mb-0" style="color:#e74c3c;">';
                $.each(errors, function(key, value) {
                    errorMsg += `<li>${value[0]}</li>`;
                });
                errorMsg += '</ul>';
                $('#loginResponseMsg').html('<div class="alert alert-danger rounded-pill px-4 py-2 text-center mb-3">'+errorMsg+'</div>');
            }
        });
    });
});
</script>
@endsection
