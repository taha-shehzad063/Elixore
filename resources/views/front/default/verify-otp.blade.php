@extends('front.default.partials.app')

@section('content')
<div class="container" style="max-width: 500px; margin-top: 50px;">

    @if(session('error'))
        <div class="alert alert-danger text-center" style="border-radius:50px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('password.verify.otp') }}" method="POST" class="p-4 shadow rounded" style="border-radius: 18px;">
        @csrf
        {{-- Hidden email field from session --}}
        <input type="hidden" name="email" value="{{ session('reset_email') }}">

        <h4 class="text-center mb-3" style="color:#71cd14;">Verify OTP & Reset Password</h4>

        <input type="number" name="otp" class="form-control rounded-pill mb-3" placeholder="Enter OTP" required>

        {{-- New Password --}}
        <div class="mb-3 position-relative">
            <input type="password" name="password" id="password" class="form-control rounded-pill" placeholder="New Password" required>
            <span class="toggle-password" data-target="password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                👁
            </span>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3 position-relative">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill" placeholder="Confirm New Password" required>
            <span class="toggle-password" data-target="password_confirmation" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                👁
            </span>
        </div>

        <button type="submit" class="btn w-100 py-2 rounded-pill" style="background:#71cd14; color:white;">Reset Password</button>
    </form>
</div>

@endsection
{{-- Password toggle script --}}
<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        let targetInput = document.getElementById(this.getAttribute('data-target'));
        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            this.textContent = '🙈';
        } else {
            targetInput.type = 'password';
            this.textContent = '👁';
        }
    });
});
</script>
