@php
    $statusMeta = [
        'open'      => ['label' => 'Open',       'class' => 'bg-warning text-dark'],
        'responded' => ['label' => 'Responded',  'class' => 'bg-info text-dark'],
        'resolved'  => ['label' => 'Resolved',   'class' => 'bg-success'],
    ];
@endphp

<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0" style="color:#047857"><i class="bi bi-life-preserver me-2"></i>Report a problem or share feedback</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Use this form to tell the GreenRoute team about anything related to <strong>the system itself</strong> —
                        a bug, something confusing, a feature you'd like, or a payment/schedule that isn't behaving as expected.
                        An administrator will review your message and reply here. You'll also get a notification in your bell
                        when we respond.
                    </p>
                    <form method="POST" action="{{ $submitRoute }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="">Select a category (optional)</option>
                                <option value="Bug / Error" {{ old('category') === 'Bug / Error' ? 'selected' : '' }}>Bug / Error</option>
                                <option value="Payments" {{ old('category') === 'Payments' ? 'selected' : '' }}>Payments</option>
                                <option value="Schedules" {{ old('category') === 'Schedules' ? 'selected' : '' }}>Schedules</option>
                                <option value="Account / Login" {{ old('category') === 'Account / Login' ? 'selected' : '' }}>Account / Login</option>
                                <option value="Feature request" {{ old('category') === 'Feature request' ? 'selected' : '' }}>Feature request</option>
                                <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" placeholder="Briefly describe the issue" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Provide details so we can help you faster — steps to reproduce, what you expected, and what happened" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn text-white" style="background:#047857">
                                <i class="bi bi-send me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Your submissions</h6>
                </div>
                <div class="card-body">
                    @forelse($tickets as $ticket)
                        @php $meta = $statusMeta[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-secondary']; @endphp
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-semibold">{{ $ticket->subject }}</span>
                                <span class="badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                            </div>
                            @if($ticket->category)
                                <div class="mb-1"><span class="badge bg-light text-muted border">{{ $ticket->category }}</span></div>
                            @endif
                            <p class="text-muted small mb-2">{{ $ticket->message }}</p>
                            <div class="small text-muted">Submitted {{ $ticket->created_at->diffForHumans() }}</div>

                            @if($ticket->admin_response)
                                <div class="mt-2 p-2 rounded" style="background:#ecfdf5">
                                    <div class="small fw-semibold" style="color:#047857"><i class="bi bi-reply me-1"></i>Support reply</div>
                                    <div class="small">{{ $ticket->admin_response }}</div>
                                    @if($ticket->responded_at)
                                        <div class="small text-muted mt-1">{{ $ticket->responded_at->diffForHumans() }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted small mb-0">You haven't submitted any feedback yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0"><i class="bi bi-telephone me-2" style="color:#047857"></i>Contact</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1 text-muted">Email: support@greenroute.co.tz</p>
                    <p class="mb-1 text-muted">Phone: +255 000 000 000</p>
                    <p class="mb-0 text-muted">Hours: Mon–Fri, 9:00–17:00</p>
                </div>
            </div>
        </div>
    </div>
</div>
