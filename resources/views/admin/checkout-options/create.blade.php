@extends('admin.frontend.partials.app')

@section('content')

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">

  <div class="body-wrapper">
    @include('admin.frontend.partials.header')

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Create Checkout Option</h5>
          <div class="card">
            <div class="card-body">

              <form method="POST" action="{{ route('admin.checkout-options.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Type -->
                <div class="mb-3">
                  <label for="type" class="form-label">Type</label>
                  <select name="type" id="type" class="form-control" required>
                    <option value="" disabled selected>Select Type</option>
                    <option value="shipping">Shipping</option>
                    <option value="payment">Payment</option>
                    <option value="billing">Billing</option>
                  </select>
                </div>

                <!-- Key -->
                <div class="mb-3">
                  <label for="key" class="form-label">Key <small>(e.g. cod, express)</small></label>
                  <input type="text" name="key" id="key" class="form-control" required>
                </div>

                <!-- Label -->
                <div class="mb-3">
                  <label for="label" class="form-label">Label</label>
                  <input type="text" name="label" id="label" class="form-control">
                </div>

                <!-- Shipping Cost -->
                <div class="mb-3">
                  <label for="shipping_cost" class="form-label">Shipping Cost</label>
                  <input type="number" step="0.01" name="shipping_cost" id="shipping_cost" class="form-control" placeholder="Enter amount if type is shipping">
                </div>

                <!-- Message -->
                <div class="mb-3">
                  <label for="message" class="form-label">Message</label>
                  <textarea name="message" id="message" rows="3" class="form-control"></textarea>
                </div>
<!-- Bank Name -->
<div class="mb-3">
    <label for="bank_name" class="form-label">Bank Name</label>
    <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="e.g. HBL, Meezan">
</div>
                <!-- Account Name -->
                <div class="mb-3">
                  <label for="account_name" class="form-label">Account Name</label>
                  <input type="text" name="account_name" id="account_name" class="form-control" placeholder="For payment method">
                </div>

                <!-- Account Number -->
                <div class="mb-3">
                  <label for="account_number" class="form-label">Account Number</label>
                  <input type="text" name="account_number" id="account_number" class="form-control" placeholder="For payment method">
                </div>

                <!-- Status -->
                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select name="status" id="status" class="form-control" required>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Create Option</button>
              </form>

            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
            class="pe-1 text-primary text-decoration-underline">Elixore.com</a> Distributed by <a target="_blank"
            class="pe-1 text-primary text-decoration-underline">Elixore</a></p>
      </div>
    </div>
  </div>
</div>

@endsection
