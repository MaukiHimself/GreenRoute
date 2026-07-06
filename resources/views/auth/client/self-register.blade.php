<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — GreenRoute Client Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
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
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Back to login --}}
            <div class="mb-3">
                <a href="{{ route('client.login') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Already have an account? Login
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
                                <label class="form-label fw-semibold">Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" placeholder="Your full name" required>
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
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
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
                                <label class="form-label fw-semibold">Ward</label>
                                <div class="loading-select">
                                    <select name="ward" id="wardSelect" class="form-select" disabled>
                                        <option value="">— select district first —</option>
                                    </select>
                                    <div class="select-spinner" id="wardSpinner">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Street --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Street</label>
                                <div class="loading-select">
                                    <select name="street" id="streetSelect" class="form-select" disabled>
                                        <option value="">— select ward first —</option>
                                    </select>
                                    <div class="select-spinner" id="streetSpinner">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Collection Route --}}
                        <p class="section-label mt-4"><i class="bi bi-signpost-split me-2"></i>Select Your Collection Route</p>
                        <div class="mb-3">
                            <div id="routeLoadingSpinner" class="text-center py-2" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                <span class="ms-2 text-muted small">Loading routes…</span>
                            </div>
                            <div id="routeWrapper">
                                <p class="text-muted small"><i class="bi bi-info-circle me-1"></i>Select your region above to see available collection routes in your area.</p>
                            </div>
                            <input type="hidden" name="route_id" id="route_id" value="{{ old('route_id') }}">
                            @error('route_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions or information for your contractor…">{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-shield-check me-2"></i>
                            After submitting, your contractor will review your registration. You will receive an email with your login credentials once approved. <strong>You cannot log in until approved.</strong>
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
<script>
(function () {
    // ── API base URLs (public, no auth required) ──────────────────────────
    const API = {
        districts: '/location/public/districts',
        wards:     '/location/public/wards',
        streets:   '/location/public/streets',
        routes:    '{{ route("client.registration.routes") }}',
    };

    // ── Element refs ──────────────────────────────────────────────────────
    const regionSel   = document.getElementById('regionSelect');
    const districtSel = document.getElementById('districtSelect');
    const wardSel     = document.getElementById('wardSelect');
    const streetSel   = document.getElementById('streetSelect');
    const routeInput  = document.getElementById('route_id');
    const routeWrapper = document.getElementById('routeWrapper');
    const routeSpinner = document.getElementById('routeLoadingSpinner');

    // Restore old() values after a validation failure
    const oldRegion   = @json(old('region'));
    const oldDistrict = @json(old('district'));
    const oldWard     = @json(old('ward'));
    const oldStreet   = @json(old('street'));
    const oldRouteId  = @json(old('route_id'));

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
        resetSelect(streetSel,   '— select ward first —');
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
        resetSelect(wardSel,   '— loading… —');
        resetSelect(streetSel, '— select ward first —');
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

    // ── Streets ───────────────────────────────────────────────────────────
    async function loadStreets(region, district, ward, restoreValue) {
        resetSelect(streetSel, '— loading… —');
        showSpinner('streetSpinner');
        try {
            const data = await fetchJson(
                API.streets + '?region='   + encodeURIComponent(region) +
                              '&district=' + encodeURIComponent(district) +
                              '&ward='     + encodeURIComponent(ward)
            );
            populateSelect(streetSel, data.data || [], restoreValue || null);
        } catch (e) {
            resetSelect(streetSel, '— unable to load —');
        } finally {
            hideSpinner('streetSpinner');
        }
    }

    // ── Routes ────────────────────────────────────────────────────────────
    async function loadRoutes(region) {
        routeWrapper.innerHTML = '';
        routeInput.value = '';
        routeSpinner.style.display = 'block';
        try {
            const data = await fetchJson(API.routes + '?region=' + encodeURIComponent(region));
            routeSpinner.style.display = 'none';
            const routes = data.data || [];
            if (routes.length === 0) {
                routeWrapper.innerHTML =
                    '<div class="alert alert-warning small py-2"><i class="bi bi-exclamation-circle me-2"></i>No collection routes are currently available in <strong>' +
                    escHtml(region) + '</strong>. Please try a different region or contact support.</div>';
                return;
            }
            let html = '<div class="row g-2">';
            routes.forEach(function (r) {
                const isChecked = String(r.id) === String(oldRouteId) ? 'checked' : '';
                html += `
                    <div class="col-12">
                        <label class="route-card d-flex align-items-start gap-3 w-100 ${isChecked ? 'selected' : ''}">
                            <input type="radio" name="_route_radio" value="${r.id}" class="mt-1 flex-shrink-0" ${isChecked} required>
                            <div>
                                <div class="fw-semibold">${escHtml(r.route_name)}</div>
                                <div class="text-muted small">
                                    ${r.contractor && r.contractor.name ? escHtml(r.contractor.name) : ''}
                                    ${r.district ? ' &bull; ' + escHtml(r.district) : ''}
                                    ${r.ward     ? ' &bull; ' + escHtml(r.ward) : ''}
                                </div>
                            </div>
                        </label>
                    </div>`;
                if (isChecked) routeInput.value = r.id;
            });
            html += '</div>';
            routeWrapper.innerHTML = html;

            // Wire up radio buttons → hidden input
            routeWrapper.querySelectorAll('input[name="_route_radio"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    routeInput.value = this.value;
                    routeWrapper.querySelectorAll('.route-card').forEach(function (c) { c.classList.remove('selected'); });
                    this.closest('.route-card').classList.add('selected');
                });
            });
        } catch (e) {
            routeSpinner.style.display = 'none';
            routeWrapper.innerHTML =
                '<div class="alert alert-danger small py-2"><i class="bi bi-x-circle me-2"></i>Unable to load routes. Please try again.</div>';
        }
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Event listeners ───────────────────────────────────────────────────
    regionSel.addEventListener('change', function () {
        const region = this.value;
        resetSelect(districtSel, '— select region first —');
        resetSelect(wardSel,     '— select district first —');
        resetSelect(streetSel,   '— select ward first —');
        routeWrapper.innerHTML = '<p class="text-muted small"><i class="bi bi-info-circle me-1"></i>Select your region above to see available collection routes.</p>';
        routeInput.value = '';
        if (!region) return;
        loadDistricts(region, null);
        loadRoutes(region);
    });

    districtSel.addEventListener('change', function () {
        const region   = regionSel.value;
        const district = this.value;
        resetSelect(wardSel,   '— select district first —');
        resetSelect(streetSel, '— select ward first —');
        if (!district) return;
        loadWards(region, district, null);
    });

    wardSel.addEventListener('change', function () {
        const region   = regionSel.value;
        const district = districtSel.value;
        const ward     = this.value;
        resetSelect(streetSel, '— select ward first —');
        if (!ward) return;
        loadStreets(region, district, ward, null);
    });

    // ── Restore old() values on validation failure ────────────────────────
    if (oldRegion) {
        loadDistricts(oldRegion, oldDistrict).then(function () {
            if (oldDistrict) {
                return loadWards(oldRegion, oldDistrict, oldWard).then(function () {
                    if (oldWard) {
                        return loadStreets(oldRegion, oldDistrict, oldWard, oldStreet);
                    }
                });
            }
        });
        loadRoutes(oldRegion);
    }
})();
</script>
</body>
</html>
