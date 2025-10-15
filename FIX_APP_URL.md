# 🔧 Fix APP_URL Issue - Email Links Not Working

## 🔍 Problem

Your invitation email shows:
```
Portal URL: http://localhost/client/login  ❌ No port number!
```

But your Laravel app runs on:
```
http://localhost:8000  ✅ With port 8000
```

**Result:** Link doesn't work because it's missing `:8000`

---

## ✅ Solution: Update APP_URL in .env

### Step 1: Open Your .env File

```bash
# In your project root
notepad c:\Users\junio\AFIA-ORBIT\.env
```

Or open it in your IDE/text editor.

### Step 2: Find and Update APP_URL

**Find this line:**
```env
APP_URL=http://localhost
```

**Change it to include the port:**
```env
APP_URL=http://localhost:8000
```

**Note:** If your app runs on a different port, use that instead:
- Port 8000: `http://localhost:8000` (most common)
- Port 8080: `http://localhost:8080`
- Port 80: `http://localhost` (no port needed)

### Step 3: Clear Configuration Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Step 4: Test the Fix

```bash
php artisan tinker
```

```php
// Check what URL is generated now
echo url('/client/login') . "\n";

// Should output: http://localhost:8000/client/login ✅
```

**If it shows the correct URL with port, you're fixed!** ✅

---

## 🔄 Resend Email to Client with Correct URL

Now that APP_URL is fixed, resend the invitation:

### Option 1: Use the Script Again

```bash
php send-invitation-to-recent-client.php
```

Choose option 1 (Resend invitation) or option 2 (Reset password)

### Option 2: Via Tinker

```bash
php artisan tinker
```

```php
$client = App\Models\Client::where('email', 'calvinjunior921@gmail.com')->first();
$contractor = App\Models\Contractor::where('registration_number', 'CT740293')->first();

// Reset password and send with correct URL
$newPassword = Str::random(12);
$client->user->password = Hash::make($newPassword);
$client->user->save();

$client->user->notify(new App\Notifications\ClientInvitation($client, $contractor, $newPassword));

echo "✓ Email sent with correct URL!\n";
echo "Email: calvinjunior921@gmail.com\n";
echo "Password: " . $newPassword . "\n";
echo "URL: " . url('/client/login') . "\n";
exit;
```

---

## 📧 What the Email Will Show Now

**Before (Wrong):**
```
Portal URL: http://localhost/client/login  ❌
```

**After (Correct):**
```
Portal URL: http://localhost:8000/client/login  ✅
```

---

## 🎯 For Production/Deployment

When you deploy to production, update APP_URL to your actual domain:

```env
# Development
APP_URL=http://localhost:8000

# Production
APP_URL=https://yourdomain.com
```

**Important:** Always run `php artisan config:clear` after changing APP_URL!

---

## 🧪 Verify Everything Works

### Test 1: Check URL Generation

```bash
php artisan tinker
```

```php
echo "Generated URL: " . url('/client/login') . "\n";
echo "Expected URL: http://localhost:8000/client/login\n";

// Should match!
```

### Test 2: Send Test Email

```php
Mail::raw('Test email with correct URL: ' . url('/client/login'), function ($m) {
    $m->to('calvinjunior921@gmail.com')->subject('Test');
});

echo "Check your email - URL should include :8000\n";
```

### Test 3: Full Invitation Test

```php
$client = App\Models\Client::where('email', 'calvinjunior921@gmail.com')->first();
$contractor = App\Models\Contractor::first();

$client->user->notify(new App\Notifications\ClientInvitation(
    $client, 
    $contractor, 
    'TestPass123'
));

echo "✓ Invitation sent!\n";
echo "Check email - click button should go to: " . url('/client/login') . "\n";
```

---

## 🚀 Quick Fix Steps Summary

1. ✅ Open `.env` file
2. ✅ Change `APP_URL=http://localhost` to `APP_URL=http://localhost:8000`
3. ✅ Run `php artisan config:clear`
4. ✅ Test with `php artisan tinker` and `echo url('/client/login');`
5. ✅ Resend invitation to client: `php send-invitation-to-recent-client.php`
6. ✅ Client receives email with correct URL
7. ✅ Client clicks button → Goes to `http://localhost:8000/client/login` ✅

---

## 🆘 If Still Not Working

### Issue: URL Still Wrong After Changing .env

**Solution:** Configuration might be cached

```bash
# Clear ALL caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild cache
php artisan config:cache
```

### Issue: Don't Know Which Port

**Find your dev server port:**

```bash
# If using php artisan serve
php artisan serve
# Output shows: Server running on [http://127.0.0.1:8000]

# Or check running processes
netstat -ano | findstr :8000
```

### Issue: Using Valet/Herd/Other

If using Laravel Valet, Herd, or similar:

```env
# Valet typically uses .test domains
APP_URL=http://afia-orbit.test

# Or custom domain
APP_URL=http://yourdomain.local
```

---

## 📋 Current Credentials for Client

**After you fix APP_URL and resend email:**

```
Name: Calvin Junior
Email: calvinjunior921@gmail.com
Registration: CL164069
Contractor: CITY-WASTE COMPANY (CT740293)

Portal URL (Correct): http://localhost:8000/client/login
Password: [Will be in the new email you send]
```

---

## ✅ Checklist

Before sending email to client:

- [ ] APP_URL updated in .env with correct port
- [ ] Configuration cache cleared (`php artisan config:clear`)
- [ ] Tested URL generation in tinker
- [ ] URL shows correct port (e.g., `:8000`)
- [ ] Resent invitation email to client
- [ ] Verified email contains correct URL

---

**Last Updated:** October 15, 2025  
**Issue:** Email URL missing port number  
**Fix:** Update APP_URL in .env to include port
