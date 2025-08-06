@extends('front.default.partials.app')

@section('content')
<!-- Contact Section -->
<section class="contact-section py-5">
  <div class="container">

    <!-- Google Map -->
    <div class="mb-5">
<div class="d-none d-md-flex"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3399.948398036717!2d74.33628071067679!3d31.553030745420976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391904c87c03f811%3A0xde393c60de69f2e6!2sShahrah-e-Quaid-e-Azam%2C%20Lahore%2C%20Pakistan!5e0!3m2!1sen!2s!4v1754226322067!5m2!1sen!2s" width="1250" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>    </div>
<div class="d-flex d-md-none"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3399.948398036717!2d74.33628071067679!3d31.553030745420976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391904c87c03f811%3A0xde393c60de69f2e6!2sShahrah-e-Quaid-e-Azam%2C%20Lahore%2C%20Pakistan!5e0!3m2!1sen!2s!4v1754226322067!5m2!1sen!2s" width="1250" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>    </div>
</div>
    <!-- Get in Touch -->
    <div class="row">
      <!-- Contact Form -->
      <div class="col-md-7 mb-4">
        <h2 class="mb-4 fw-bold text-success" style="color: #71cd14 !important;">Get in Touch</h2>
        <form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
          @csrf
          <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control rounded-3" required>
          </div>
          <div class="form-group mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control rounded-3">
          </div>
          <div class="form-group mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control rounded-3" required>
          </div>
          <div class="form-group mb-4">
            <label>Comment</label>
            <textarea name="message" class="form-control rounded-3" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn text-white px-4 py-2 rounded-3" style="background-color: #71cd14;">Submit Contact</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="col-md-5">
        <div class="bg-light p-4 rounded shadow-sm">
          <h5 class="mb-3" style="color: #71cd14;">Live Help</h5>
          <p>If you have an issue or question that requires immediate assistance, you can click the button below to chat with a Customer Service representative.</p>
<a href="https://wa.me/923273546753?text=I%20need%20info%20about%20your%20product" class="btn mb-3 text-white" style="background-color: #71cd14;">WhatsApp Chat</a>
          <ul class="list-unstyled">
            <li><strong>Phone:</strong> <a href="tel:+923273546753">032 73546753</a></li>
            <li><strong>Email:</strong> <a href="mailto:elixore@gmail.com">elixore@gmail.com</a></li>
            <li><strong>Address:</strong> Mall Road Lahore, 64200</li>
            <li><strong>Availability:</strong> Always Open 24/7</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- jQuery (required for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Google Maps -->
<script>
  function initMap() {
    const location = { lat: 28.4202, lng: 70.3007 }; // Example coords
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: location,
      styles: [
        {
          featureType: "all",
          stylers: [{ saturation: -100 }, { lightness: 30 }]
        }
      ],
    });
    new google.maps.Marker({ position: location, map: map });
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>

<!-- Contact Form AJAX Script -->
<script>
  $(document).ready(function () {
    $('#contactForm').on('submit', function (e) {
      e.preventDefault();
      let $form = $(this);
      let formData = $form.serialize();

      $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (res) {
          Swal.fire({
            icon: 'success',
            title: 'Thank you!',
            text: 'Your message has been sent.',
            confirmButtonColor: '#71cd14'
          });
          $form[0].reset();
        },
        error: function (xhr) {
          let msg = 'Something went wrong.';
          if (xhr.status === 422 && xhr.responseJSON?.errors) {
            msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: msg,
            confirmButtonColor: '#71cd14'
          });
        }
      });
    });
  });
</script>
@endsection
