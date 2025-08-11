<!DOCTYPE html>
<html>
<head>
    <title>JazzCash Debit Card Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>JazzCash Debit Card Payment</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="amount" class="form-label">Amount (PKR)</label>
        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', 100) }}" required min="1">
        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Proceed to JazzCash Payment</button>
</form>
    </div>
</body>
</html>