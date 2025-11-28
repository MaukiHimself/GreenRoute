<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Subscription - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --light-teal: rgba(5, 92, 92, 0.1);
            --dark-teal: #044a4a;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .subscription-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .subscription-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .header-icon {
            width: 100px;
            height: 100px;
            background: var(--light-teal);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: var(--primary-teal);
            border: 3px solid var(--primary-teal);
        }
        
        .subscription-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .subscription-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 400;
        }
        
        .subscription-form-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(5, 92, 92, 0.1);
            border: 1px solid rgba(5, 92, 92, 0.1);
        }
        
        .form-section {
            margin-bottom: 2.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-teal);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .required-field::after {
            content: " *";
            color: var(--primary-red);
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.3rem rgba(5, 92, 92, 0.1);
        }
        
        .file-input-wrapper {
            position: relative;
        }
        
        .file-input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-teal);
            font-size: 1.25rem;
        }
        
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .btn-subscribe {
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            border: none;
            border-radius: 12px;
            padding: 1.25rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
        }
        
        .btn-subscribe:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
            color: white;
        }
        
        .terms-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
            text-align: center;
        }
        
        .terms-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        
        .terms-link {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .terms-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }
        
        .document-checklist {
            background: var(--light-teal);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .checklist-title {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 1rem;
        }
        
        .checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .checklist-icon {
            color: var(--primary-teal);
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .invalid-feedback {
            color: var(--primary-red);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .subscription-container {
                padding: 2rem 0;
            }
            
            .subscription-form-container {
                padding: 2rem;
            }
            
            .subscription-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="subscription-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row align-items-center">
                        <!-- Subscription Form Column -->
                        <div class="col-lg-8">
                            <div class="subscription-form-container">
                                <!-- Header -->
                                <div class="subscription-header">
                                    <div class="header-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <h1 class="subscription-title">Complete Your Subscription</h1>
                                    <p class="subscription-subtitle">Please provide the required documents to activate your account</p>
                                </div>

                                <!-- Document Checklist -->
                                <div class="document-checklist">
                                    <h5 class="checklist-title">Required Documents Checklist</h5>
                                    <div class="checklist-item">
                                        <i class="bi bi-check-circle-fill checklist-icon"></i>
                                        <span>Business License Document</span>
                                    </div>
                                    <div class="checklist-item">
                                        <i class="bi bi-check-circle-fill checklist-icon"></i>
                                        <span>Certificate of Incorporation</span>
                                    </div>
                                    <div class="checklist-item">
                                        <i class="bi bi-check-circle-fill checklist-icon"></i>
                                        <span>Valid Contract Document</span>
                                    </div>
                                    <div class="checklist-item">
                                        <i class="bi bi-check-circle-fill checklist-icon"></i>
                                        <span>Initial Payment Information</span>
                                    </div>
                                </div>

                                <!-- Subscription Form -->
                                <form method="POST" action="{{ route('subscription.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <!-- Business Documents Section -->
                                    <div class="form-section">
                                        <h4 class="section-title">Business Documents</h4>
                                        
                                        <div class="mb-4">
                                            <label for="business_license" class="form-label required-field">Business License</label>
                                            <div class="file-input-wrapper">
                                                <input type="file" class="form-control @error('business_license') is-invalid @enderror" 
                                                       id="business_license" name="business_license" accept=".pdf,.jpg,.jpeg,.png" required>
                                                <i class="bi bi-file-earmark-pdf file-input-icon"></i>
                                            </div>
                                            @error('business_license')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text">Upload your business license (PDF, JPG, PNG - Max 5MB)</small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="certificate_incorporation" class="form-label required-field">Certificate of Incorporation</label>
                                            <div class="file-input-wrapper">
                                                <input type="file" class="form-control @error('certificate_incorporation') is-invalid @enderror" 
                                                       id="certificate_incorporation" name="certificate_incorporation" accept=".pdf,.jpg,.jpeg,.png" required>
                                                <i class="bi bi-file-earmark-text file-input-icon"></i>
                                            </div>
                                            @error('certificate_incorporation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text">Upload your certificate of incorporation (PDF, JPG, PNG - Max 5MB)</small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="contract_document" class="form-label required-field">Valid Contract</label>
                                            <div class="file-input-wrapper">
                                                <input type="file" class="form-control @error('contract_document') is-invalid @enderror" 
                                                       id="contract_document" name="contract_document" accept=".pdf,.jpg,.jpeg,.png" required>
                                                <i class="bi bi-file-earmark-check file-input-icon"></i>
                                            </div>
                                            @error('contract_document')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text">Upload a valid contract document (PDF, JPG, PNG - Max 5MB)</small>
                                        </div>
                                    </div>

                                    <!-- Payment Information Section -->
                                    <div class="form-section">
                                        <h4 class="section-title">Payment Information</h4>
                                        
                                        <div class="mb-4">
                                            <label for="initial_payment" class="form-label required-field">Initial Payment Amount (TZS)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">TZS</span>
                                                <input type="number" class="form-control @error('initial_payment') is-invalid @enderror border-start-0" 
                                                       id="initial_payment" name="initial_payment" step="0.01" min="0" required placeholder="0.00">
                                            </div>
                                            @error('initial_payment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text">Enter the initial payment amount for your subscription</small>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-subscribe">
                                            <i class="bi bi-check-circle me-2"></i>Complete Subscription & Activate Account
                                        </button>
                                    </div>
                                </form>

                                <!-- Terms and Conditions -->
                                <div class="terms-section">
                                    <p class="terms-text">
                                        By completing this subscription, you agree to our 
                                        <a href="#" class="terms-link">Terms of Service</a> and 
                                        <a href="#" class="terms-link">Privacy Policy</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Benefits Sidebar -->
                        <div class="col-lg-4 d-none d-lg-block">
                            <div class="ps-4">
                                <h4 class="primary-dark mb-4">Subscription Benefits</h4>
                                
                                <div class="d-flex align-items-start mb-4">
                                    <i class="bi bi-building-check text-primary-teal me-3 mt-1 fs-5"></i>
                                    <div>
                                        <h6 class="primary-dark mb-1">Business Verification</h6>
                                        <p class="text-muted small mb-0">Get verified and build trust with clients</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start mb-4">
                                    <i class="bi bi-graph-up-arrow text-primary-teal me-3 mt-1 fs-5"></i>
                                    <div>
                                        <h6 class="primary-dark mb-1">Growth Opportunities</h6>
                                        <p class="text-muted small mb-0">Access premium features to scale your business</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start mb-4">
                                    <i class="bi bi-shield-check text-primary-teal me-3 mt-1 fs-5"></i>
                                    <div>
                                        <h6 class="primary-dark mb-1">Secure Platform</h6>
                                        <p class="text-muted small mb-0">Enterprise-grade security for your business data</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-headset text-primary-teal me-3 mt-1 fs-5"></i>
                                    <div>
                                        <h6 class="primary-dark mb-1">Priority Support</h6>
                                        <p class="text-muted small mb-0">Dedicated support for subscribed businesses</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>