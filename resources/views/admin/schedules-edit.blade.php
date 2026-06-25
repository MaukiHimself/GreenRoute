<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            margin-bottom: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            font-size: 1.2rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-group label .required {
            color: #ef4444;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn-submit {
            background: var(--primary-teal);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 1rem;
        }
        
        .btn-submit:hover {
            background: #044a4a;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
            color: white;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
        }
        
        .checkbox-group label {
            margin: 0;
            font-weight: normal;
        }
        
        .info-box {
            background: #e6f2f2;
            border-left: 4px solid var(--primary-teal);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.schedules') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Schedules
            </a>
        </div>
        
        <h1 class="page-title">Edit Collection Schedule</h1>
        <p class="page-description">Update schedule information and organic waste integration</p>

        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i>
                Modify collection schedules and integrate organic waste collections with regular schedules.
            </p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <h3><i class="bi bi-calendar3 me-2"></i>Schedule Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Contractor <span class="required">*</span></label>
                            <select name="contractor_id" class="form-control" required>
                                <option value="">Select Contractor</option>
                                @foreach($contractors as $contractor)
                                    <option value="{{ $contractor->id }}" {{ old('contractor_id', $schedule->contractor_id) == $contractor->id ? 'selected' : '' }}>
                                        {{ $contractor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contractor_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Client <span class="required">*</span></label>
                            <select name="client_id" class="form-control" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $schedule->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Scheduled Date <span class="required">*</span></label>
                            <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', $schedule->scheduled_date ? $schedule->scheduled_date->format('Y-m-d') : '') }}" required>
                            @error('scheduled_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Scheduled Time <span class="required">*</span></label>
                            <input type="time" name="scheduled_time" class="form-control" value="{{ old('scheduled_time', $schedule->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '') }}" required>
                            @error('scheduled_time')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Service Type <span class="required">*</span></label>
                            <input type="text" name="service_type" class="form-control" value="{{ old('service_type', $schedule->service_type) }}" placeholder="e.g., Standard Collection" required>
                            @error('service_type')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Frequency</label>
                            <select name="frequency" class="form-control">
                                <option value="">Not Set</option>
                                <option value="daily" {{ old('frequency', $schedule->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency', $schedule->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="bi-weekly" {{ old('frequency', $schedule->frequency) == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                <option value="monthly" {{ old('frequency', $schedule->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('frequency')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ old('status', $schedule->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ old('status', $schedule->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Organic Waste Integration -->
                <div class="form-section">
                    <h3><i class="bi bi-recycle me-2"></i>Organic Waste Collection</h3>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="includes_organic_waste" id="includes_organic_waste" value="1" {{ old('includes_organic_waste', $schedule->includes_organic_waste) ? 'checked' : '' }}>
                            <label for="includes_organic_waste">
                                <strong>Include Organic Waste Collection</strong>
                                <span style="display: block; font-size: 0.875rem; color: #666;">
                                    Integrate organic waste collection with this regular collection schedule
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Organic Waste Notes</label>
                        <textarea name="organic_waste_notes" class="form-control" rows="3" placeholder="Special instructions for organic waste collection">{{ old('organic_waste_notes', $schedule->organic_waste_notes) }}</textarea>
                        <div class="help-text">Add any specific instructions for organic waste handling</div>
                        @error('organic_waste_notes')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="form-section">
                    <h3><i class="bi bi-journal-text me-2"></i>Additional Notes</h3>
                    
                    <div class="form-group">
                        <label>General Notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Any additional information about this schedule">{{ old('notes', $schedule->notes) }}</textarea>
                        @error('notes')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-section">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Update Schedule
                    </button>
                    <a href="{{ route('admin.schedules') }}" class="btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
