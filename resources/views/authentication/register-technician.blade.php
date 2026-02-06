<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <title>Technician Registration</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  @include('partials/bootstrap')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* =========================
           ServeIT Core Theme
           ========================= */
    :root {
      --brand-500: #3b82f6;
      --brand-600: #2563eb;
      --brand-700: #1d4ed8;
      --bg-900: #0b1220;
      --border: #1f2a44;
      --text: #e5e7eb;
      --text-muted: #94a3b8;
      --radius-card: 24px;
      --radius-btn: 12px;
    }

    html,
    body {
      height: 100%;
      font-family: 'Inter', system-ui, sans-serif;
      color: var(--text);
      background: var(--bg-900) !important;
      overflow-x: hidden;
    }

    /* ---- Background Effects ---- */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.15), transparent 70%);
      pointer-events: none;
      z-index: -2;
    }

    body::after {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(rgba(11, 18, 32, 0.8), rgba(11, 18, 32, 0.8)),
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231f2a44' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      z-index: -1;
    }

    /* ---- Layout ---- */
    .stage {
      min-height: 100vh;
      padding: 80px 20px 40px 20px;
      /* Top padding for fixed back button */
    }

    /* ---- Glass Card ---- */
    .auth-card {
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      height: 100%;
      /* Equal height columns */
    }

    .card-header-custom {
      border-bottom: 1px solid var(--border);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .heading-grad {
      background: linear-gradient(90deg, #fff, var(--brand-500));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-weight: 700;
    }

    /* ---- Forms ---- */
    .form-label {
      color: var(--text);
      font-weight: 500;
      font-size: 0.9rem;
      margin-bottom: 0.4rem;
    }

    .form-control {
      background: rgba(11, 18, 32, 0.6);
      border: 1px solid var(--border);
      color: white;
      padding: 0.7rem 1rem;
      border-radius: var(--radius-btn);
      transition: all 0.2s;
    }

    .form-control:focus {
      background: rgba(11, 18, 32, 0.9);
      border-color: var(--brand-500);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
      color: white;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.2);
    }

    .input-group-text,
    .btn-eye {
      background: rgba(11, 18, 32, 0.6);
      border: 1px solid var(--border);
      border-left: none;
      color: var(--text-muted);
    }

    .btn-eye:hover {
      color: var(--brand-500);
      background: rgba(11, 18, 32, 0.8);
      border-color: var(--border);
    }

    /* ---- Buttons ---- */
    .btn-brand {
      background: var(--brand-600);
      color: white;
      font-weight: 600;
      padding: 0.8rem;
      border-radius: var(--radius-btn);
      border: none;
      width: 100%;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-brand:hover {
      background: var(--brand-700);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
      color: white;
    }

    /* ---- Back Button Base Styles ---- */
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;

      /* Glass Style */
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 50px;

      color: #e2e8f0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      z-index: 9999;
      transition: all 0.2s ease;
    }

    .back-link:hover {
      background: rgba(37, 99, 235, 0.2);
      color: white;
      transform: translateX(-3px);
    }

    /* ---- DESKTOP (Floating) ---- */
    @media (min-width: 768px) {
      .back-link {
        position: fixed;
        /* Floats in corner */
        top: 30px;
        left: 30px;
      }
    }

    /* ---- MOBILE (In-Flow) ---- */
    @media (max-width: 767px) {
      .back-link {
        position: relative;
        /* Takes up physical space */
        margin: 20px 0 0 20px;
        /* Add margin to position it nicely */
        display: inline-flex;
        /* This ensures the content below gets pushed down */
      }

      /* Optional: Adjust the stage padding so there isn't a double gap */
      .stage {
        padding-top: 10px !important;
        min-height: auto !important;
        /* Prevents double scrollbars on mobile */
        height: auto !important;
        padding-bottom: 40px;
      }
    }

    /* ---- Map Specifics ---- */
    #map {
      height: 300px;
      border-radius: var(--radius-btn);
      border: 1px solid var(--border);
      z-index: 1;
    }

    .leaflet-container {
      font-family: 'Inter', sans-serif;
    }

    /* Loading Overlay */
    #loadingSpinner {
      backdrop-filter: blur(5px);
    }

    /* Custom Checkbox */
    .form-check-input {
      background-color: rgba(11, 18, 32, 0.6);
      border-color: var(--border);
    }

    .form-check-input:checked {
      background-color: var(--brand-600);
      border-color: var(--brand-600);
    }
  </style>
</head>

<body>

  <a href="{{ url('/register') }}" class="back-link">
    <i class="bi bi-arrow-left"></i>
    <span>Change Role</span>
  </a>

  <div id="loadingSpinner" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(11, 18, 32, 0.8); z-index: 2000; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
    <h5 class="text-white fw-light">Registering Technician...</h5>
  </div>

  <div class="container stage">
    <form method="POST" action="{{ route('register-technician-create') }}">
      @csrf

      <div class="text-center mb-5">
        <span style="height: 50px; width: 50px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(16, 185, 129, 0.2); margin-bottom: 1rem;">
          <i class="bi bi-tools fs-4"></i>
        </span>
        <h2 class="fw-bold heading-grad mb-1">Technician Registration</h2>
        <p class="text-muted small">Join our network of professionals</p>
      </div>

      @if (session('error'))
      <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4">
        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
      </div>
      @endif

      <div class="row g-4">

        <div class="col-lg-6">
          <div class="auth-card p-4">
            <div class="mb-4 pb-2 border-bottom border-secondary border-opacity-25">
              <h5 class="text-white mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Personal Details</h5>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-md-4">
                <label for="first-name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first-name" name="first_name" required autofocus value="{{ old('first_name') }}">
              </div>
              <div class="col-md-4">
                <label for="middle-name" class="form-label">Middle</label>
                <input type="text" class="form-control" id="middle-name" name="middle_name" value="{{ old('middle_name') }}">
              </div>
              <div class="col-md-4">
                <label for="last-name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last-name" name="last_name" required value="{{ old('last_name') }}">
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="phone_number" name="phone_number" required placeholder="0917xxxxxxx" pattern="[0-9]{11}" value="{{old('phone_number')}}">
            </div>

            <div class="row g-2 mb-3">
              <div class="col-12">
                <div id="passwordAlert" class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-none py-2 small">
                  <i class="bi bi-exclamation-triangle me-1"></i> Passwords do not match
                </div>
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control border-end-0" id="password" name="password" required placeholder="••••••••" minlength="8">
                  <button class="btn btn-eye" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm</label>
                <div class="input-group">
                  <input type="password" class="form-control border-end-0" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                  <button class="btn btn-eye" type="button" id="toggleConfirmPassword"><i class="bi bi-eye"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="auth-card p-4">
            <div class="mb-4 pb-2 border-bottom border-secondary border-opacity-25">
              <h5 class="text-white mb-0"><i class="bi bi-geo-alt me-2 text-primary"></i>Service Details</h5>
            </div>

            {{-- ================= START NEW EXPERTISE SECTION ================= --}}
            <div class="mb-4">
              <label class="form-label mb-2">My Expertise <span class="text-danger">*</span></label>
              <div class="bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary border-opacity-25" style="max-height: 200px; overflow-y: auto;">

                @if(isset($expertiseCategories) && count($expertiseCategories) > 0)
                <div class="row g-2">
                  @foreach($expertiseCategories as $category)
                  <div class="col-6">
                    <div class="form-check">
                      <input class="form-check-input"
                        type="checkbox"
                        name="expertise_ids[]"
                        value="{{ $category->id }}"
                        id="exp_{{ $category->id }}"
                        {{ (is_array(old('expertise_ids')) && in_array($category->id, old('expertise_ids'))) ? 'checked' : '' }}>

                      <label class="form-check-label small text-light" for="exp_{{ $category->id }}">
                        {{ $category->name }}
                      </label>
                    </div>
                  </div>
                  @endforeach
                </div>
                @else
                <p class="text-muted small mb-0 text-center">No categories available.</p>
                @endif

              </div>
              @error('expertise_ids')
              <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</div>
              @enderror
            </div>
            {{-- ================= END NEW EXPERTISE SECTION ================= --}}

            <div class="mb-3">
              <label for="address" class="form-label">Base/Work Address</label>
              <input type="text" class="form-control" id="address" name="address" required value="{{ old('address') }}" placeholder="Street, Barangay, City">
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="home_service" name="home_service" {{ old('home_service') ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="home_service">I cater to Home Services</label>
              </div>
            </div>

            <div class="bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary border-opacity-25 mb-3">
              <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" id="tesda-verified-checkbox" name="tesda_verified" {{ old('tesda_verified') ? 'checked' : '' }}>
                <label class="form-check-label fw-bold text-secondary" for="tesda-verified-checkbox">I am TESDA Verified</label>
              </div>

              <div id="tesda-inputs" style="display: none;" class="mt-3 ps-2 border-start border-primary">
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label small text-muted">First 4 Digits</label>
                    <input type="text" class="form-control form-control-sm" id="tesda-first-four" name="tesda_first_four" pattern="\d{4}" maxlength="4" placeholder="XXXX" value="{{ old('tesda_first_four') }}">
                  </div>
                  <div class="col-6">
                    <label class="form-label small text-muted">Last 4 Digits</label>
                    <input type="text" class="form-control form-control-sm" id="tesda-last-four" name="tesda_last_four" pattern="\d{4}" maxlength="4" placeholder="XXXX" value="{{ old('tesda_last_four') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label d-flex justify-content-between">
                <span>Pin Location</span>
                <span id="location-status" class="badge bg-danger bg-opacity-25 text-danger fw-normal border border-danger border-opacity-25">Not Set</span>
              </label>

              <input type="hidden" id="longitude" name="longitude" required value="{{ old('longitude') }}">
              <input type="hidden" id="latitude" name="latitude" required value="{{ old('latitude') }}">

              <div id="map"></div>

              <button type="button" class="btn btn-outline-light btn-sm mt-2 w-100" onclick="getLocation()">
                <i class="bi bi-crosshair me-1"></i> Use My Current Location
              </button>

              <div id="location-warning" class="text-danger small mt-2" style="display:none;">
                <i class="bi bi-exclamation-circle"></i> Please pin your location on the map.
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-6 mx-auto">
          <div class="form-check mb-3 text-center">
            <input class="form-check-input float-none" type="checkbox" id="termsCheck" required style="display: inline-block; vertical-align: middle;">
            <label class="form-check-label text-muted small ms-1" for="termsCheck">
              I agree to the <a href="/terms" class="text-primary text-decoration-none">Terms of Service</a> & <a href="/privacy" class="text-primary text-decoration-none">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="btn btn-brand btn-lg w-100 shadow-lg">Register as Technician</button>

          <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">Already have an account? <span class="text-primary fw-bold">Login</span></a>
          </div>
        </div>
      </div>

    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      // --- 1. Password Toggle & Match ---
      document.querySelectorAll('#togglePassword, #toggleConfirmPassword').forEach(btn => {
        btn.addEventListener('click', () => {
          const input = btn.previousElementSibling;
          const icon = btn.querySelector('i');
          input.type = input.type === 'password' ? 'text' : 'password';
          icon.classList.toggle('bi-eye');
          icon.classList.toggle('bi-eye-slash');
        });
      });

      const p1 = document.getElementById("password");
      const p2 = document.getElementById("password_confirmation");
      const alertBox = document.getElementById("passwordAlert");

      function checkMatch() {
        if (p2.value && p1.value !== p2.value) {
          alertBox.classList.remove("d-none");
        } else {
          alertBox.classList.add("d-none");
        }
      }
      p1.addEventListener("input", checkMatch);
      p2.addEventListener("input", checkMatch);


      // --- 2. TESDA Logic ---
      const tesdaCheck = document.getElementById('tesda-verified-checkbox');
      const tesdaArea = document.getElementById('tesda-inputs');
      const t1 = document.getElementById('tesda-first-four');
      const t2 = document.getElementById('tesda-last-four');

      function toggleTesda() {
        if (tesdaCheck.checked) {
          tesdaArea.style.display = 'block';
          t1.required = true;
          t2.required = true;
        } else {
          tesdaArea.style.display = 'none';
          t1.required = false;
          t2.required = false;
          t1.value = '';
          t2.value = '';
        }
      }

      // Initialize
      toggleTesda();

      tesdaCheck.addEventListener('change', toggleTesda);

      // Enforce numbers only
      [t1, t2].forEach(input => {
        input.addEventListener('input', function() {
          this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });
      });


      // --- 3. Map Logic ---
      const defaultView = [7.1907, 125.4553]; // Davao City

      // Handle old values if validation failed
      const oldLat = document.getElementById('latitude').value;
      const oldLng = document.getElementById('longitude').value;
      const initialView = (oldLat && oldLng) ? [oldLat, oldLng] : defaultView;

      const map = L.map('map').setView(initialView, 12);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      let marker = null;
      const statusBadge = document.getElementById('location-status');

      window.updateCoords = function(lat, lng) {
        if (!marker) {
          marker = L.marker([lat, lng], {
            draggable: true
          }).addTo(map);
          marker.on('dragend', function() {
            const pos = marker.getLatLng();
            setHiddenInputs(pos.lat, pos.lng);
          });
        } else {
          marker.setLatLng([lat, lng]);
        }
        setHiddenInputs(lat, lng);
        map.setView([lat, lng], 15);
      };

      function setHiddenInputs(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        document.getElementById('location-warning').style.display = 'none';

        statusBadge.className = "badge bg-success bg-opacity-25 text-success fw-normal border border-success border-opacity-25";
        statusBadge.innerHTML = "<i class='bi bi-check-circle me-1'></i>Pinned";
      }

      // If old input existed, set marker
      if (oldLat && oldLng) {
        updateCoords(parseFloat(oldLat), parseFloat(oldLng));
      }

      map.on('click', function(e) {
        updateCoords(e.latlng.lat, e.latlng.lng);
      });

      window.getLocation = function() {
        if (navigator.geolocation) {
          // Visual feedback on button
          const btn = document.querySelector('button[onclick="getLocation()"]');
          const originalText = btn.innerHTML;
          btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Locating...';
          btn.disabled = true;

          navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            updateCoords(lat, lng);

            // Reset button
            btn.innerHTML = originalText;
            btn.disabled = false;
          }, function() {
            alert("Geolocation failed or permission denied.");
            btn.innerHTML = originalText;
            btn.disabled = false;
          });
        } else {
          alert("Geolocation is not supported by this browser.");
        }
      };


      // --- 4. Final Submission Check ---
      document.querySelector('form').addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;

        if (!lat || !lng) {
          e.preventDefault();
          document.getElementById('location-warning').style.display = 'block';
          document.getElementById('map').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
        } else {
          // Show full screen loader
          document.getElementById('loadingSpinner').classList.remove('d-none');
        }
      });
    });
  </script>
</body>

</html>