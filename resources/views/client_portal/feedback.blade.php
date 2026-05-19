<x-dashboard-layout title="Feedback & Comments">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.profile') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.equipment') }}">
                    <i class="bi bi-tools me-2"></i>Equipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.contractor.info') }}">
                    <i class="bi bi-building me-2"></i>Contractor Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.payments') }}">
                    <i class="bi bi-credit-card me-2"></i>Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.feedback') }}">
                    <i class="bi bi-chat-dots me-2"></i>Feedback
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Feedback</li>
    </x-slot>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Submit Feedback</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('client.feedback.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject" required>
                                <option value="">Select feedback type</option>
                                <option value="Service Quality">Service Quality</option>
                                <option value="Pickup Schedule">Pickup Schedule</option>
                                <option value="Billing Inquiry">Billing Inquiry</option>
                                <option value="Equipment Request">Equipment Request</option>
                                <option value="Complaint">Complaint</option>
                                <option value="Suggestion">Suggestion</option>
                                <option value="Compliment">Compliment</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="6" placeholder="Please provide detailed feedback or comments..." required></textarea>
                            <div class="form-text">Maximum 5000 characters</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent">
                                <label class="form-check-label" for="urgent">
                                    This is urgent and requires immediate attention
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Submit Feedback
                        </button>
                        <button type="reset" class="btn btn-secondary ms-2">Clear Form</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Emergency Contact</h6>
                        <p class="mb-1"><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</p>
                        <small class="text-muted">Available 24/7 for emergencies</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Customer Support</h6>
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i>support@afiaorbit.com</p>
                        <p class="mb-1"><i class="bi bi-telephone me-2"></i>+1 (555) 987-6543</p>
                        <small class="text-muted">Mon-Fri: 8:00 AM - 6:00 PM</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle me-2"></i>
                            We typically respond to feedback within 24 hours during business days.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Your Feedback History</h5>
                </div>
                <div class="card-body">
                    @if($feedbacks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                            <td>{{ $feedback->subject }}</td>
                                            <td>
                                                @switch($feedback->status)
                                                    @case('open')
                                                        <span class="badge bg-warning">Open</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge bg-info">In Progress</span>
                                                        @break
                                                    @case('resolved')
                                                        <span class="badge bg-success">Resolved</span>
                                                        @break
                                                    @case('closed')
                                                        <span class="badge bg-secondary">Closed</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ ucfirst($feedback->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewFeedback({{ $feedback->id }})">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $feedbacks->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots display-4 text-muted"></i>
                            <h6 class="mt-3 text-muted">No feedback submitted yet</h6>
                            <p class="text-muted">Your feedback history will appear here once you submit feedback.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Details Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Feedback Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="feedbackContent">
                    <!-- Feedback details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewFeedback(feedbackId) {
            const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            
            // Find the feedback data from the table
            const feedbacks = @json($feedbacks->items());
            const feedback = feedbacks.find(f => f.id === feedbackId);
            
            if (feedback) {
                document.getElementById('feedbackContent').innerHTML = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong> ${feedback.subject}
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong> ${new Date(feedback.created_at).toLocaleDateString()}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong> <span class="badge bg-info">${feedback.status}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            ${feedback.message}
                        </div>
                    </div>
                `;
            }
            
            modal.show();
        }
    </script>
</x-dashboard-layout>