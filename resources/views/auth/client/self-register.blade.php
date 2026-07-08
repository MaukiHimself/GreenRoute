<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — GreenRoute Client Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <style>
        :root { --teal: #047857; --red: #c0392b; }
        body { background: linear-gradient(135deg, #f0f9f9 0%, #e2e8f0 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 30px rgba(5,92,92,.12); }
        .card-header { background: linear-gradient(135deg, var(--teal), #059669); color: white; border-radius: 16px 16px 0 0 !important; padding: 2rem; }
        .form-control, .form-select { border: 2px solid #e2e8f0; border-radius: 10px; padding: .75rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(5,92,92,.1); }
        .form-select:disabled { background-color: #f8fafc; color: #94a3b8; cursor: not-allowed; }
        .btn-teal { background: var(--teal); color: white; border: none; border-radius: 10px; padding: .85rem 2rem; font-weight: 600; }
        .btn-teal:hover { background: #065f46; color: white; }
        .section-label { color: var(--teal); font-weight: 700; border-bottom: 2px solid #e2e8f0; padding-bottom: .5rem; margin-bottom: 1.25rem; }
        .loading-select { position: relative; }
        .select-spinner { position: absolute; right: 2.5rem; top: 50%; transform: translateY(-50%); display: none; }
        .route-card { border: 2px solid #e2e8f0; border-radius: 10px; padding: 1rem; cursor: pointer; transition: all .2s; }
        .route-card:hover { border-color: var(--teal); background: #f0fdf9; }
        .route-card.selected { border-color: var(--teal); background: #ecfdf5; }
        .route-card input[type="radio"] { accent-color: var(--teal); }
        #pinMap { height: 260px; border-radius: 10px; border: 2px solid #e2e8f0; z-index: 0; }
        .pin-hint { font-size: .85rem; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Back to home / login --}}
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Back to Home
                </a>
                <a href="{{ route('client.login') }}" class="text-decoration-none text-muted small">
                    Already have an account? Login
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-recycle fs-3 me-3"></i>
                        <div>
                            <h2 class="mb-0 fw-bold">GreenRoute</h2>
                            <small style="opacity:.8; font-size:.85rem;">Waste Collection Services</small>
                        </div>
                    </div>
                    <p class="mb-0 mt-2" style="opacity:.9;">Register below and you'll be automatically matched to a waste contractor serving your area. Your account will be activated once your contractor approves your registration.</p>
                </div>

                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client.self-register.store') }}" id="signupForm">
                        @csrf

                        {{-- Personal / Business Info --}}
                        <p class="section-label"><i class="bi bi-person-badge me-2"></i>Your Information</p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Business / Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. John's Residence or ABC Shop" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Person Name <span class="text-muted small">(optional)</span></label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" placeholder="Defaults to the name above">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. 0712345678" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Category <span class="text-muted small">(optional)</span></label>
                                <select name="category" class="form-select">
                                    <option value="">Select your category</option>
                                    <option value="Residential (Unplanned)"            {{ old('category')=='Residential (Unplanned)' ? 'selected' : '' }}>Residential (Unplanned)</option>
                                    <option value="Residential (Planned/Modern)"       {{ old('category')=='Residential (Planned/Modern)' ? 'selected' : '' }}>Residential (Planned/Modern)</option>
                                    <option value="Commercial Residential (Apartment)" {{ old('category')=='Commercial Residential (Apartment)' ? 'selected' : '' }}>Commercial Residential (Apartment)</option>
                                    <option value="Restaurant"                         {{ old('category')=='Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                    <option value="Retail shops (food and other items)"{{ old('category')=='Retail shops (food and other items)' ? 'selected' : '' }}>Retail Shop</option>
                                    <option value="Offices"                            {{ old('category')=='Offices' ? 'selected' : '' }}>Offices</option>
                                    <option value="Hotel"                              {{ old('category')=='Hotels' ? 'selected' : '' }}>Hotel</option>
                                    <option value="Hospital (Domestic waste)"          {{ old('category')=='Hospital (Domestic waste)' ? 'selected' : '' }}>Hospital / Health Centre</option>
                                    <option value="Markets"                            {{ old('category')=='Markets' ? 'selected' : '' }}>Market</option>
                                    <option value="Industries (Light waste)"           {{ old('category')=='Industries (Light waste)' ? 'selected' : '' }}>Industry</option>
                                    <option value="Other"                              {{ old('category')=='Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        {{-- Location --}}
                        <p class="section-label mt-4"><i class="bi bi-geo-alt me-2"></i>Your Location</p>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Physical Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Street address or landmark" required>
                            </div>

                            {{-- Region --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
                                <div class="loading-select">
                                    <select name="region" id="regionSelect" class="form-select" required>
                                        <option value="">Select Region</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                                        @endforeach
                                    </select>
                                    <div class="select-spinner" id="regionSpinner">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- District --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">District</label>
                                <div class="loading-select">
                                    <select name="district" id="districtSelect" class="form-select" disabled>
                                        <option value="">— select region first —</option>
                                    </select>
                                    <div class="select-spinner" id="districtSpinner">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ward --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ward <span class="text-danger">*</span></label>
                                <div class="loading-select">
                                    <select name="ward" id="wardSelect" class="form-select" required disabled>
                                        <option value="">— select district first —</option>
                                    </select>
                                    <div class="select-spinner" id="wardSpinner">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                </div>
                                <div class="pin-hint text-muted mt-1">We use your ward to connect you with a contractor serving your area.</div>
                            </div>
                        </div>

                        {{-- Pin location (optional, improves matching) --}}
                        <p class="section-label mt-4"><i class="bi bi-geo-alt-fill me-2"></i>Pin Your Location <span class="text-muted small fw-normal">(optional)</span></p>
                        <div class="mb-3">
                            <p class="text-muted pin-hint mb-2">
                                <i class="bi bi-info-circle me-1"></i>Tap on the map or drag the marker to your exact location. This helps your contractor find you. You can skip this.
                            </p>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="locateBtn">
                                    <i class="bi bi-crosshair me-1"></i>Use my current location
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearPinBtn" style="display:none;">
                                    <i class="bi bi-x-circle me-1"></i>Clear pin
                                </button>
                            </div>
                            <div id="pinMap"></div>
                            <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            <div class="pin-hint text-muted mt-1" id="pinStatus"></div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions or information for your contractor…">{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-shield-check me-2"></i>
                            We'll automatically match you to a waste contractor serving your area. They will review your registration and you'll receive an email with your login credentials once approved. <strong>You cannot log in until approved.</strong>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-teal">
                                <i class="bi bi-send me-2"></i>Submit Registration
                            </button>
                            <a href="{{ route('client.login') }}" class="btn btn-outline-secondary">Already have an account? Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    // ── API base URLs (public, no auth required) ──────────────────────────
    const API = {
        districts: '/location/public/districts',
        wards:     '/location/public/wards',
    };

    // ── Element refs ──────────────────────────────────────────────────────
    const regionSel   = document.getElementById('regionSelect');
    const districtSel = document.getElementById('districtSelect');
    const wardSel     = document.getElementById('wardSelect');

    // Restore old() values after a validation failure
    const oldRegion   = @json(old('region'));
    const oldDistrict = @json(old('district'));
    const oldWard     = @json(old('ward'));

    // ── Generic helpers ───────────────────────────────────────────────────
    function showSpinner(id)  { document.getElementById(id).style.display = 'block'; }
    function hideSpinner(id)  { document.getElementById(id).style.display = 'none';  }

    function resetSelect(sel, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        sel.disabled = true;
    }

    function populateSelect(sel, items, oldValue) {
        sel.innerHTML = '<option value="">— select —</option>';
        items.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item;
            opt.textContent = item;
            if (item === oldValue) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = items.length === 0;
    }

    async function fetchJson(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    }

    // ── Districts ─────────────────────────────────────────────────────────
    async function loadDistricts(region, restoreValue) {
        resetSelect(districtSel, '— loading… —');
        resetSelect(wardSel,     '— select district first —');
        showSpinner('districtSpinner');
        try {
            const data = await fetchJson(API.districts + '?region=' + encodeURIComponent(region));
            populateSelect(districtSel, data.data || [], restoreValue || null);
        } catch (e) {
            resetSelect(districtSel, '— unable to load —');
        } finally {
            hideSpinner('districtSpinner');
        }
    }

    // ── Wards ─────────────────────────────────────────────────────────────
    async function loadWards(region, district, restoreValue) {
        resetSelect(wardSel, '— loading… —');
        showSpinner('wardSpinner');
        try {
            const data = await fetchJson(
                API.wards + '?region=' + encodeURIComponent(region) +
                            '&district=' + encodeURIComponent(district)
            );
            populateSelect(wardSel, data.data || [], restoreValue || null);
        } catch (e) {
            resetSelect(wardSel, '— unable to load —');
        } finally {
            hideSpinner('wardSpinner');
        }
    }

    // ── Dependent dropdown listeners ──────────────────────────────────────
    regionSel.addEventListener('change', function () {
        const region = this.value;
        resetSelect(districtSel, '— select region first —');
        resetSelect(wardSel,     '— select district first —');
        if (!region) return;
        loadDistricts(region, null);
    });

    districtSel.addEventListener('change', function () {
        const region   = regionSel.value;
        const district = this.value;
        resetSelect(wardSel, '— select district first —');
        if (!district) return;
        loadWards(region, district, null);
    });

    // Restore old() values on validation failure
    if (oldRegion) {
        loadDistricts(oldRegion, oldDistrict).then(function () {
            if (oldDistrict) return loadWards(oldRegion, oldDistrict, oldWard);
        });
    }

    // ── Leaflet location pin (optional, auto-locate + confirm) ────────────
    const latInput   = document.getElementById('latitude');
    const lngInput   = document.getElementById('longitude');
    const pinStatus  = document.getElementById('pinStatus');
    const locateBtn  = document.getElementById('locateBtn');
    const clearBtn   = document.getElementById('clearPinBtn');

    // Default view: Dar es Salaam.
    const DEFAULT_CENTER = [-6.8235, 39.2695];
    const map = L.map('pinMap').setView(DEFAULT_CENTER, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    let marker = null;

    function setPin(lat, lng, zoom) {
        latInput.value = Number(lat).toFixed(7);
        lngInput.value = Number(lng).toFixed(7);
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function () {
                const p = marker.getLatLng();
                setPin(p.lat, p.lng);
            });
        }
        map.setView([lat, lng], zoom || map.getZoom());
        pinStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Location pinned.';
        clearBtn.style.display = 'inline-block';
    }

    function clearPin() {
        latInput.value = '';
        lngInput.value = '';
        if (marker) { map.removeLayer(marker); marker = null; }
        pinStatus.textContent = '';
        clearBtn.style.display = 'none';
    }

    // Tap map to drop/move the pin.
    map.on('click', function (e) { setPin(e.latlng.lat, e.latlng.lng); });

    clearBtn.addEventListener('click', clearPin);

    locateBtn.addEventListener('click', function () {
        if (!navigator.geolocation) {
            pinStatus.innerHTML = '<i class="bi bi-exclamation-circle text-warning me-1"></i>Geolocation not supported — tap the map instead.';
            return;
        }
        pinStatus.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Locating…';
        navigator.geolocation.getCurrentPosition(
            function (pos) { setPin(pos.coords.latitude, pos.coords.longitude, 16); },
            function ()    { pinStatus.innerHTML = '<i class="bi bi-exclamation-circle text-warning me-1"></i>Couldn\'t get your location — tap the map to set it manually.'; },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    });

    // Restore an old pin after validation failure, otherwise auto-locate once.
    if (latInput.value && lngInput.value) {
        setPin(parseFloat(latInput.value), parseFloat(lngInput.value), 16);
    } else if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (pos) { setPin(pos.coords.latitude, pos.coords.longitude, 16); },
            function () { /* silent: user can click the button or tap the map */ },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    // Leaflet needs a size recalculation once laid out.
    setTimeout(function () { map.invalidateSize(); }, 200);
})();
</script>
</body>
</html>
