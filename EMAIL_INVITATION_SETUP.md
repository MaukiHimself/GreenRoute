# Email Invitation System Setup Guide

## 📧 Overview

The email invitation system automatically sends welcome emails to clients when they are created by contractors. This includes their registration number, login credentials, and portal access instructions.

---

## ✅ What I've Created

1. **`app/Notifications/ClientInvitation.php`** - Email notification class
2. **`app/Services/ClientInvitationService.php`** - Service to handle client creation with emails
3. This setup guide

---

## 🔧 Setup Instructions

### Step 1: Configure Email Settings

> ⚠️ Note: Laravel defaults to the `log` mailer when `MAIL_MAILER` is not set. That means emails will be written to application logs instead of being delivered.

Edit your `.env` file with email configuration:

```env
# For Gmail (Development/Testing)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# For Mailtrap (Testing)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@afia-orbit.com
MAIL_FROM_NAME="${APP_NAME}"

# For Production (e.g., AWS SES, SendGrid, Mailgun)
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="AFIA ORBIT"
```

### Step 2: Clear Configuration Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Test Email Configuration

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email', function ($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

---

## 💻 Usage in Your Controllers

### Option 1: Using the Service (Recommended)

```php
<?php

namespace App\Http\Controllers;

use App\Services\ClientInvitationService;
use App\Models\Contractor;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $invitationService;

    public function __construct(ClientInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Store a new client and send invitation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'status' => 'nullable|string',
        ]);

        // Get authenticated contractor
        $contractor = Contractor::where('user_id', auth()->id())->firstOrFail();

        // Create client with invitation
        $result = $this->invitationService->createClientWithInvitation(
            $validated,
            $contractor,
            true // Create user account and send login credentials
        );

        return response()->json([
            'success' => true,
            'message' => 'Client created and invitation sent successfully',
            'data' => [
                'client' => $result['client'],
                'temporary_password' => $result['password'], // Show to contractor (optional)
            ]
        ], 201);
    }

    /**
     * Resend invitation to existing client
     */
    public function resendInvitation($clientId)
    {
        $client = Client::findOrFail($clientId);
        $contractor = Contractor::where('user_id', auth()->id())->firstOrFail();

        $sent = $this->invitationService->resendInvitation($client, $contractor);

        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation email resent successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send invitation email'
        ], 500);
    }
}
```

### Option 2: Direct Notification (Simple)

```php
use App\Models\Client;
use App\Models\Contractor;
use App\Notifications\ClientInvitation;

// After creating a client
$client = Client::create([
    'name' => 'XYZ Corporation',
    'email' => 'contact@xyzcorp.com',
    // ... other fields
]);

$contractor = Contractor::find($contractorId);

// Send invitation
if ($client->email) {
    \Notification::route('mail', $client->email)
        ->notify(new ClientInvitation($client, $contractor));
}
```

---

## 🎯 What the Email Contains

When a client receives the invitation, they see:

**Subject:** Welcome to AFIA ORBIT - Client Portal Access

**Content:**
- Welcome greeting with client name
- Contractor company name and registration number
- Client registration number
- Login email
- Temporary password (if user account created)
- Link to portal login page
- List of portal features
- Contact information

**Example:**
```
Hello XYZ Corporation!

You have been added as a client by ABC Waste Services.

Your account details:
- Registration Number: CL012230
- Email: contact@xyzcorp.com
- Contractor: ABC Waste Services (CT440039)
- Temporary Password: aB3dE5fG7hJ9

[Access Client Portal Button]

Through the portal, you can:
• View all invoices from your contractor
• Check scheduled pickups and services
• Download invoice PDFs
• Track your service history
```

---

## 🔄 Integration Points

### When Creating Client via Web Form

```php
// In ClientController@store
public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    $contractor = auth()->user()->contractor;
    
    // Use the service
    $result = app(ClientInvitationService::class)->createClientWithInvitation(
        $validated,
        $contractor,
        true
    );
    
    return redirect()->route('clients.index')
        ->with('success', 'Client created and invitation sent!');
}
```

### When Creating Client via API

```php
// In API Controller
use App\Services\ClientInvitationService;

public function store(Request $request, ClientInvitationService $service)
{
    $contractor = Contractor::where('registration_number', $request->contractor_reg)->first();
    
    $result = $service->createClientWithInvitation(
        $request->validated(),
        $contractor,
        true
    );
    
    return response()->json([
        'success' => true,
        'data' => $result
    ], 201);
}
```

### Manual Trigger (Admin Panel)

```php
// Resend invitation button in admin panel
Route::post('/admin/clients/{client}/resend-invitation', function (Client $client) {
    $contractor = $client->contractor; // Assuming relationship exists
    
    app(ClientInvitationService::class)->resendInvitation($client, $contractor);
    
    return back()->with('success', 'Invitation resent!');
});
```

---

## 🧪 Testing the System

### Test in Tinker

```bash
php artisan tinker
```

```php
// Get test data
$contractor = App\Models\Contractor::first();
$client = App\Models\Client::first();

// Send test invitation
$client->email = 'your-test-email@example.com';
$client->save();

// Send notification
\Notification::route('mail', $client->email)
    ->notify(new App\Notifications\ClientInvitation($client, $contractor, 'TestPassword123'));

echo "Email sent to: " . $client->email;
```

### Test via API

```bash
# Create client with invitation
curl -X POST http://localhost:8000/api/clients \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Client",
    "email": "testclient@example.com",
    "phone": "555-1234",
    "address": "123 Test St",
    "city": "Test City",
    "state": "TS",
    "zip_code": "12345",
    "contractor_registration_number": "CT440039"
  }'
```

---

## 📋 Email Templates Customization

The email template can be customized by publishing Laravel's notification views:

```bash
php artisan vendor:publish --tag=laravel-notifications
```

Then edit: `resources/views/vendor/notifications/email.blade.php`

Or create a custom Markdown template:

```bash
php artisan make:notification ClientInvitation --markdown=emails.client-invitation
```

---

## 🔐 Security Considerations

### Temporary Passwords
- Auto-generated with 12 random characters
- Client should change on first login
- Consider adding password reset requirement

### Email Verification
- User account created with `email_verified_at = null`
- Add email verification middleware if needed

### Prevent Spam
- Rate limit invitation resends
- Log all invitation sends
- Add unsubscribe link for compliance

---

## 🚀 Production Checklist

Before going live with email invitations:

- [ ] Configure production email service (AWS SES, SendGrid, etc.)
- [ ] Set proper `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME`
- [ ] Test email deliverability
- [ ] Set up email queue for better performance
- [ ] Add email logging
- [ ] Configure bounce and complaint handling
- [ ] Add unsubscribe functionality
- [ ] Test spam score of emails
- [ ] Verify SPF and DKIM records
- [ ] Add rate limiting

---

## 📊 Queue Setup (Optional but Recommended)

For better performance, queue the email sending:

**1. Configure Queue in .env:**
```env
QUEUE_CONNECTION=database
```

**2. Create queue table:**
```bash
php artisan queue:table
php artisan migrate
```

**3. Run queue worker:**
```bash
php artisan queue:work
```

**Note:** The `ClientInvitation` notification already implements `ShouldQueue`, so it will automatically use queues when configured.

---

## 🛠️ Troubleshooting

### Email Not Sending

**Check mail configuration:**
```bash
php artisan tinker
config('mail');
```

**Test connection:**
```php
try {
    Mail::raw('Test', function($msg) {
        $msg->to('test@example.com')->subject('Test');
    });
    echo "Email sent successfully!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Gmail App Password

If using Gmail, you need an app-specific password:
1. Go to Google Account settings
2. Security → 2-Step Verification
3. App passwords → Generate new password
4. Use that password in `MAIL_PASSWORD`

### Check Logs

```bash
tail -f storage/logs/laravel.log
```

---

## 📝 Alternative: Manual Invitation

If you don't want automatic emails, you can:

1. **Generate invitation link manually:**
```php
$inviteUrl = url('/register/client?token=' . $client->invite_token . '&email=' . $client->email);
```

2. **Display to contractor in UI:**
```php
return view('clients.created', [
    'client' => $client,
    'inviteUrl' => $inviteUrl,
    'temporaryPassword' => $password
]);
```

3. **Contractor copies and sends manually** via their preferred method

---

## 📞 Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify email configuration: `php artisan config:cache`
3. Test with Mailtrap first before production
4. Check queue status: `php artisan queue:failed`

---

**Created:** October 14, 2025  
**Version:** 1.0  
**For:** AFIA-ORBIT Email Invitation System
