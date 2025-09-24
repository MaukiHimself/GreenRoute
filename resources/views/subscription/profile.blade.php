<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark mb-2">Complete Your Subscription</h2>
        <p class="text-muted">Please provide the required documents to activate your account</p>
    </div>

    <form method="POST" action="{{ route('subscription.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="business_license" class="form-label">Business License <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('business_license') is-invalid @enderror" 
                   id="business_license" name="business_license" accept=".pdf,.jpg,.jpeg,.png" required>
            @error('business_license')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Upload your business license (PDF, JPG, PNG - Max 5MB)</small>
        </div>

        <div class="mb-3">
            <label for="certificate_incorporation" class="form-label">Certificate of Incorporation <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('certificate_incorporation') is-invalid @enderror" 
                   id="certificate_incorporation" name="certificate_incorporation" accept=".pdf,.jpg,.jpeg,.png" required>
            @error('certificate_incorporation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Upload your certificate of incorporation (PDF, JPG, PNG - Max 5MB)</small>
        </div>

        <div class="mb-3">
            <label for="contract_document" class="form-label">Valid Contract <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('contract_document') is-invalid @enderror" 
                   id="contract_document" name="contract_document" accept=".pdf,.jpg,.jpeg,.png" required>
            @error('contract_document')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Upload a valid contract document (PDF, JPG, PNG - Max 5MB)</small>
        </div>

        <div class="mb-4">
            <label for="initial_payment" class="form-label">Initial Payment Amount (USD) <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('initial_payment') is-invalid @enderror" 
                   id="initial_payment" name="initial_payment" step="0.01" min="0" required>
            @error('initial_payment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Enter the initial payment amount for your subscription</small>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle me-2"></i>Complete Subscription
            </button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">
            By completing this subscription, you agree to our 
            <a href="#" class="text-success">Terms of Service</a> and 
            <a href="#" class="text-success">Privacy Policy</a>
        </p>
    </div>
</x-guest-layout>