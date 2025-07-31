@extends('front.default.partials.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center text-primary">💳 JazzCash Payment</h3>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                  <form action="{{ url('/pay/store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="amount" class="form-label">Enter Amount (PKR)</label>
        <input type="number" name="amount" id="amount" class="form-control rounded-3" placeholder="e.g. 500" required>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-3">
            <i class="fas fa-wallet me-1"></i> Pay with JazzCash
        </button>
    </div>
</form>


                    <p class="text-muted text-center mt-3" style="font-size: 0.875rem;">
                        Powered by JazzCash. Payments are securely processed.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
