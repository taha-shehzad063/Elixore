@extends('front.default.partials.app')

@section('content')
<div class="container" style="max-width: 500px; margin-top: 50px;">
    @if(session('success'))
        <div class="alert alert-success text-center" style="background-color:#71cd14; color:#fff; border-radius:50px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center" style="border-radius:50px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('password.send.otp') }}" method="POST" class="p-4 shadow rounded" style="border-radius: 18px;">
        @csrf
        <h4 class="text-center mb-3" style="color:#71cd14;">Forgot Password</h4>
        <input type="email" name="email" class="form-control rounded-pill mb-3" placeholder="Enter your email" required>
        <button type="submit" class="btn w-100 py-2 rounded-pill" style="background:#71cd14; color:white;">Send OTP</button>
    </form>
</div>
@endsection
