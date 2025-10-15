# Gmail & iCloud Email Setup Guide

## 📧 Gmail Setup (Recommended for Production)

### Step 1: Enable 2-Factor Authentication

1. Go to your Google Account: https://myaccount.google.com/
2. Click on **Security** (left sidebar)
3. Under "Signing in to Google", click **2-Step Verification**
4. Follow the prompts to enable it (if not already enabled)

### Step 2: Generate App Password

1. After 2FA is enabled, go back to **Security**
2. Under "Signing in to Google", click **App passwords**
3. You may need to sign in again
4. Under "Select app", choose **Mail**
5. Under "Select device", choose **Other (Custom name)**
6. Enter a name like "AFIA-ORBIT Laravel App"
7. Click **Generate**
8. **COPY THE 16-CHARACTER PASSWORD** (looks like: `abcd efgh ijkl mnop`)
9. Save it somewhere safe - you won't see it again!

### Step 3: Configure Laravel .env

Open your `.env` file and add/update these lines:

```env
# Gmail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcdefghijklmnop  # ← Paste your 16-char app password (no spaces)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Optional: Reply-to address
MAIL_REPLY_TO_ADDRESS=support@yourcompany.com
MAIL_REPLY_TO_NAME="AFIA ORBIT Support"
```

**Important:**
- Remove ALL spaces from the app password
- Use the app password, NOT your regular Gmail password
- `MAIL_USERNAME` is your full Gmail address

---

## 🍎 iCloud Mail Setup (Alternative)

### Step 1: Generate App-Specific Password

1. Go to https://appleid.apple.com/
2. Sign in with your Apple ID
3. In the **Security** section, under **App-Specific Passwords**, click **Generate Password**
4. Enter a label like "AFIA-ORBIT App"
5. Click **Create**
6. **COPY THE PASSWORD** (format: `xxxx-xxxx-xxxx-xxxx`)
7. Click **Done**

### Step 2: Configure Laravel .env

```env
# iCloud Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.me.com
MAIL_PORT=587
MAIL_USERNAME=your-email@icloud.com  # or @me.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx    # ← Your app-specific password (keep dashes)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@icloud.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:**
- Keep the dashes in the iCloud password
- Use your full iCloud email address
- Works with @icloud.com, @me.com, or @mac.com addresses

---

## 🔧 Complete .env Configuration

Here's a complete example with all settings:

```env
# Application Settings
APP_NAME="AFIA ORBIT"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourwebsite.com

# Database (your existing settings)
DB_CONNECTION=sqlite
# ... keep your database settings

# ===================================
# EMAIL CONFIGURATION (Choose one)
# ===================================

# Option 1: Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=notifications@yourdomain.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notifications@yourdomain.com
MAIL_FROM_NAME="AFIA ORBIT Notifications"

# Option 2: iCloud (uncomment to use)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mail.me.com
# MAIL_PORT=587
# MAIL_USERNAME=yourname@icloud.com
# MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=yourname@icloud.com
# MAIL_FROM_NAME="AFIA ORBIT Notifications"

# Queue Configuration (recommended for production)
QUEUE_CONNECTION=database
```

---

## ✅ Testing Your Email Configuration

### Test 1: Clear Cache

```bash
cd c:\Users\junio\AFIA-ORBIT
php artisan config:clear
php artisan cache:clear
```

### Test 2: Send Test Email via Tinker

```bash
php artisan tinker
```

Then paste this:

```php
// Test with your actual email
Mail::raw('This is a test email from AFIA ORBIT!', function ($message) {
    $message->to('juniormeela5@gmail.com')  // ← Change this!
            ->subject('AFIA ORBIT - Test Email');
});

echo "Email sent! Check your inbox (and spam folder).\n";
exit;
```

**Expected Result:** You should receive the test email within 1-2 minutes.

### Test 3: Test Client Invitation Email

```bash
php artisan tinker
```

```php
// Get test data
$contractor = App\Models\Contractor::first();
$client = App\Models\Client::first();


// Update client email to yours for testing
$client->email = 'YOUR-PERSONAL-EMAIL@gmail.com';  // ← Change this!
$client->save();

// Send invitation
\Notification::route('mail', $client->email)
    ->notify(new App\Notifications\ClientInvitation($client, $contractor, 'TestPassword123'));

echo "Invitation sent to: " . $client->email . "\n";
exit;
```

**Expected Result:** You receive a professional welcome email with login details.

---

## 🚀 Using Email Invitations in Your App

### Method 1: Via API Endpoint

Create a client with automatic email invitation:

```bash
curl -X POST http://localhost:8000/api/clients/create-with-invitation \
  -H "Content-Type: application/json" \
  -d '{
    "contractor_registration_number": "CT440039",
    "name": "XYZ Corporation",
    "email": "client@company.com",
    "phone": "555-1234",
    "address": "123 Main Street",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001",
    "send_invitation": true,
    "create_user_account": true
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Client created and invitation email sent successfully",
  "data": {
    "client": {
      "id": 2,
      "registration_number": "CL123456",
      "name": "XYZ Corporation",
      "email": "client@company.com"
    },
    "contractor": {
      "registration_number": "CT440039",
      "company_name": "ABC Waste Services"
    },
    "user_account_created": true,
    "temporary_password": "aB3dE5fG7h",
    "invitation_sent": true
  }
}
```

### Method 2: Via Service Class (In Your Controllers)

```php
<?php

namespace App\Http\Controllers;

use App\Services\ClientInvitationService;
use App\Models\Contractor;
use Illuminate\Http\Request;

class ContractorClientController extends Controller
{
    public function createClient(Request $request, ClientInvitationService $invitationService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
        ]);

        // Get logged-in contractor
        $contractor = Contractor::where('user_id', auth()->id())->firstOrFail();

        // Create client and send invitation
        $result = $invitationService->createClientWithInvitation(
            $validated,
            $contractor,
            true  // Create user account with login credentials
        );

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created! Invitation email sent to ' . $result['client']->email);
    }
}
```

### Method 3: Resend Invitation (If Client Didn't Receive It)

```bash
# Via API
POST http://localhost:8000/api/clients/CL123456/resend-invitation
```

Or in your controller:

```php
use App\Services\ClientInvitationService;

public function resendInvitation($clientId, ClientInvitationService $service)
{
    $client = Client::findOrFail($clientId);
    $contractor = auth()->user()->contractor;
    
    $sent = $service->resendInvitation($client, $contractor);
    
    if ($sent) {
        return back()->with('success', 'Invitation resent to ' . $client->email);
    }
    
    return back()->with('error', 'Failed to send invitation');
}
```

---

## 🎨 Customizing the Email Template

The email uses Laravel's default notification template. To customize it:

### Option 1: Edit the Notification Class

Open `app/Notifications/ClientInvitation.php` and modify the `toMail()` method:

```php
public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('🎉 Welcome to AFIA ORBIT!')  // Custom subject
        ->greeting('Hello ' . $this->client->name . '! 👋')  // Custom greeting
        ->line('Exciting news! You have been added by ' . $this->contractor->company_name)
        // ... add more customization
        ->action('Login to Portal', url('/login'))
        ->salutation('Best regards, The AFIA ORBIT Team');
}
```

### Option 2: Create Custom Email View

```bash
# Create custom markdown template
php artisan make:mail ClientWelcomeMail --markdown=emails.client-welcome
```

Then use it in your notification.

---

## 🐛 Troubleshooting

### Issue 1: "Failed to authenticate on SMTP server"

**Solution:**
- ✅ Verify 2FA is enabled on your Google account
- ✅ Use app password, not regular password
- ✅ Remove all spaces from app password
- ✅ Check username is full email address

### Issue 2: "Connection could not be established"

**Solution:**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Issue 3: Email not received

**Checklist:**
- ✅ Check spam/junk folder
- ✅ Verify recipient email is correct
- ✅ Check Laravel logs: `storage/logs/laravel.log`
- ✅ Test with simple `Mail::raw()` first
- ✅ Verify firewall allows port 587

### Issue 4: Gmail blocks sign-in

If you see "Sign-in attempt blocked":
1. Go to https://myaccount.google.com/lesssecureapps
2. This shouldn't happen with app passwords, but if it does:
3. Use the app password method (not regular password)
4. Consider using a dedicated Gmail account for the app

### Issue 5: iCloud password not working

**Solution:**
- ✅ Keep dashes in password: `xxxx-xxxx-xxxx-xxxx`
- ✅ Verify you're using app-specific password
- ✅ Try generating a new app-specific password

### View Email Logs

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# In tinker, check mail config
php artisan tinker
config('mail');
```

---

## 📊 Production Best Practices

### 1. Use Dedicated Email Account

Create a dedicated email like:
- `notifications@yourcompany.com`
- `noreply@yourcompany.com`
- `system@yourcompany.com`

### 2. Set Up Email Queue

For better performance, use queues:

```bash
# Create queue table
php artisan queue:table
php artisan migrate

# Update .env
QUEUE_CONNECTION=database

# Run queue worker
php artisan queue:work
```

The `ClientInvitation` notification already implements `ShouldQueue`, so it will automatically use queues.

### 3. Monitor Email Sending

Log all email sends:

```php
// In app/Services/ClientInvitationService.php
\Log::info('Client invitation sent', [
    'client_email' => $client->email,
    'client_reg' => $client->registration_number,
    'contractor_reg' => $contractor->registration_number
]);
```

### 4. Rate Limiting

Prevent spam by limiting invitation sends:

```php
// In your controller
if (Cache::has('invitation_sent_' . $client->id)) {
    return back()->with('error', 'Please wait before resending invitation');
}

// Send invitation...

Cache::put('invitation_sent_' . $client->id, true, now()->addMinutes(5));
```

### 5. Handle Bounces

Consider using a dedicated email service for production:
- **Amazon SES** (cheap, reliable)
- **SendGrid** (good free tier)
- **Mailgun** (developer friendly)

These provide bounce handling and better deliverability.

---

## 🔒 Security Considerations

### Protect Your .env File

```bash
# Make sure .env is in .gitignore
echo ".env" >> .gitignore

# Never commit .env to version control
git rm --cached .env  # If already committed
```

### Use Environment Variables

For production servers, set environment variables directly instead of .env:

```bash
# On your server
export MAIL_USERNAME=your-email@gmail.com
export MAIL_PASSWORD=your-app-password
```

### Rotate Passwords Regularly

Change your app passwords every 90 days for security.

---

## 📞 Quick Reference

### Gmail Settings
```
Host: smtp.gmail.com
Port: 587
Encryption: TLS
Username: full-email@gmail.com
Password: 16-char app password (no spaces)
```

### iCloud Settings
```
Host: smtp.mail.me.com
Port: 587
Encryption: TLS
Username: full-email@icloud.com
Password: xxxx-xxxx-xxxx-xxxx (keep dashes)
```

### Test Commands
```bash
# Clear cache
php artisan config:clear && php artisan cache:clear

# Test email
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@email.com')->subject('Test'));

# Check logs
tail -f storage/logs/laravel.log
```

---

## ✅ Setup Checklist

Before going live:

- [ ] Gmail/iCloud 2FA enabled
- [ ] App password generated and saved
- [ ] .env file configured correctly
- [ ] Cache cleared (`php artisan config:clear`)
- [ ] Test email sent successfully
- [ ] Client invitation email tested
- [ ] Email appears professional (not in spam)
- [ ] Temporary passwords work
- [ ] Client can log in with credentials
- [ ] Queue configured (optional but recommended)
- [ ] Logs monitoring set up
- [ ] .env file secured (not in git)

---

**Next Steps:** Follow the testing section below to verify everything works!
