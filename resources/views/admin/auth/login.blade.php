<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Elixore Admin Panel</title>
  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png" />
  <link rel="stylesheet" href="{{ asset('admin/assets/css/styles.min.css') }}" />
  <style>
    body {
      background: linear-gradient(90deg, #71cd14 0%, #eafbe2 100%);
      min-height: 100vh;
    }

    .login-card {
      background-color: #ffffff;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      padding: 3rem 2.5rem;
    }

    .form-control {
      height: 50px;
      font-size: 1rem;
    }

    .form-control:focus {
      border-color: #71cd14;
      box-shadow: 0 0 0 0.2rem rgba(113, 205, 20, 0.25);
    }

    .btn-primary {
      background-color: #71cd14;
      border-color: #71cd14;
      font-size: 1.1rem;
      padding: 12px;
    }

    .btn-primary:hover {
      background-color: #63b812;
      border-color: #63b812;
    }

    .form-check-input:checked {
      background-color: #71cd14;
      border-color: #71cd14;
    }

    .logo-img img {
      height: 90px;
    }

    h5 {
      font-size: 1.5rem;
    }

    @media (min-width: 992px) {
      .col-lg-5 {
        max-width: 520px;
      }
    }
  </style>
</head>

<body>
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5 col-xl-4">
          <div class="card login-card">
            <div class="text-center mb-4">
              <a href="#" class="logo-img d-block">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Elixore Logo">
              </a>
              <h5 class="fw-bold mt-3" style="color: #71cd14;">Admin Login</h5>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
              @csrf
              <div class="mb-4">
                <label for="email" class="form-label">Admin Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>

              <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label text-muted" for="remember">
                    Remember this device
                  </label>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('admin/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
