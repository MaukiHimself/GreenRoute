# 🚀 Start Here: Email Setup in 10 Minutes

Follow these steps exactly to set up email invitations with Gmail or iCloud.

---

## ✅ Step 1: Get Your App Password (5 minutes)

### For Gmail Users:

1. **Open:** https://myaccount.google.com/security
2. **Enable 2-Step Verification** (if not already enabled):
   - Click "2-Step Verification"
   - Follow the setup wizard
3. **Generate App Password:**
   - Go back to Security page
   - Click "App passwords" (under "Signing in to Google")
   - Select app: **Mail**
   - Select device: **Other (Custom name)**
   - Type: `AFIA-ORBIT`
   - Click **Generate**
   - **COPY THE 16-LETTER PASSWORD** (example: `abcd efgh ijkl mnop`)
   - **REMOVE ALL SPACES** → becomes: `abcdefghijklmnop`

### For iCloud Users:

1. **Open:** https://appleid.apple.com/
2. **Sign in** with your Apple ID
3. **In Security section**, click **Generate Password** under App-Specific Passwords
4. **Label:** `AFIA-ORBIT`
5. **Click Create**
6. **COPY THE PASSWORD** (format: `xxxx-xxxx-xxxx-xxxx`)
7. **KEEP THE DASHES**

---

## ✅ Step 2: Update Your .env File (2 minutes)

1. **Open your project's `.env` file** (in the root directory)

2. **Find these lines** (around line 50):
```env
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

3. **Replace with Gmail configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="AFIA ORBIT"
```

**OR for iCloud:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.me.com
MAIL_PORT=587
MAIL_USERNAME=your-email@icloud.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@icloud.com
MAIL_FROM_NAME="AFIA ORBIT"
```

4. **Replace:**
   - `your-email@gmail.com` → Your actual Gmail address
   - `abcdefghijklmnop` → Your app password (NO SPACES)

5. **Save the file**

---

## ✅ Step 3: Clear Cache (30 seconds)

Open your terminal in the project directory and run:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## ✅ Step 4: Test Email Works (2 minutes)

```bash
php artisan tinker
```

Then paste this command (replace `YOUR-EMAIL@gmail.com` with your personal email):

```php
Mail::raw('Test email from AFIA ORBIT!', function ($message) {
    $message->to('YOUR-EMAIL@gmail.com')->subject('AFIA ORBIT Test');
});
echo "Email sent! Check your inbox.\n";
exit;
```

**Expected:** You should receive the email within 1-2 minutes.

✅ **If you got the email → SUCCESS! Continue to Step 5**  
❌ **If no email → Check TROUBLESHOOTING section below**

---

## ✅ Step 5: Test Client Invitation Email (1 minute)

```bash
php artisan tinker
```

```php
$contractor = App\Models\Contractor::first();
$client = App\Models\Client::first();

// Send to your personal email
\Notification::route('mail', 'YOUR-EMAIL@gmail.com')
    ->notify(new App\Notifications\ClientInvitation($client, $contractor, 'TempPass123'));

echo "Invitation email sent!\n";
exit;
```

**Expected:** You receive a professional welcome email with client portal information.

---

## ✅ Step 6: Use in Production

Now you can create clients with automatic email invitations!

### Via API:

```bash
curl -X POST http://localhost:8000/api/clients/create-with-invitation \
  -H "Content-Type: application/json" \
  -d '{
    "contractor_registration_number": "CT440039",
    "name": "Test Client Company",
    "email": "client@company.com",
    "phone": "555-1234",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001",
    "send_invitation": true
  }'
```

### In Your Code:

```php
use App\Services\ClientInvitationService;

$result = app(ClientInvitationService::class)->createClientWithInvitation(
    $clientData,
    $contractor,
    true  // Create user account
);

// Email sent automatically!
```

---

## 🐛 TROUBLESHOOTING

### No Email Received?

**1. Check Spam Folder**
- Gmail: Check "Spam" and "Promotions" tabs
- Mark as "Not Spam" if found

**2. Verify Configuration**
```bash
php artisan tinker
config('mail');
```

Should show:
```
"mailer" => "smtp"
"host" => "smtp.gmail.com"
"username" => "your-email@gmail.com"
```

**3. Check Laravel Logs**
```bash
# Windows
type storage\logs\laravel.log

# Or open in notepad
notepad storage\logs\laravel.log
```

Look for error messages at the bottom.

**4. Common Fixes**

❌ **"Failed to authenticate"**
- Use app password, NOT regular password
- Remove all spaces from app password
- Verify 2FA is enabled

❌ **"Connection refused"**
```bash
php artisan config:clear
php artisan cache:clear
```

❌ **"Could not open stream"**
- Check your internet connection
- Verify port 587 is not blocked
- Try port 465 with `MAIL_ENCRYPTION=ssl`

---

## 📞 Quick Test Commands

```bash
# Clear everything
php artisan config:clear && php artisan cache:clear && php artisan config:cache

# Test simple email
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@email.com')->subject('Test'));

# View mail config
php artisan tinker
dd(config('mail'));
```

---

## ✨ You're Done!

Once Step 4 works, your email invitations are ready!

**What happens when contractor creates a client:**
1. ✅ Client record created with registration number
2. ✅ User account created with temporary password
3. ✅ Email sent automatically with login details
4. ✅ Client receives professional invitation
5. ✅ Client clicks link and logs in
6. ✅ Client sees all invoices and schedules immediately

---

## 📚 More Information

- **GMAIL_ICLOUD_EMAIL_SETUP.md** - Detailed configuration guide
- **EMAIL_INVITATION_SETUP.md** - Complete email system documentation
- **HOW_CLIENT_RECEIVES_INVITATION.md** - User workflow overview

---

## 🎯 Next Steps After Email Works

1. ✅ Test creating a real client via API
2. ✅ Verify client receives the email
3. ✅ Test client can log in with temporary password
4. ✅ Set up email queue for production (optional)
5. ✅ Customize email template (optional)

**Need help?** Check the troubleshooting section or the detailed guides!
