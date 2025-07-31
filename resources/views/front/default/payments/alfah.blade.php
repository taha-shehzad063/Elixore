<form method="POST" action="{{ url('pay/alfapay') }}">
    @csrf

    <input type="hidden" name="TransactionReferenceNumber" value="{{ uniqid('ORD-') }}">
    <input type="hidden" name="ReturnURL" value="{{ env('ALFAPAY_RETURN_URL') }}">

    <div class="mb-3">
        <label>Payment Method</label>
        <select name="TransactionTypeId" class="form-select" required>
            <option value="">Select</option>
            <option value="1">Alfa Wallet</option>
            <option value="2">Bank Account</option>
            <option value="3">Card</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Amount (PKR)</label>
        <input type="number" name="TransactionAmount" class="form-control" required value="1000">
    </div>

    <button type="submit" class="btn btn-primary">Pay Now</button>
</form>
