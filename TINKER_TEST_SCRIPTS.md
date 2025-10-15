# Ready-to-Paste Tinker Test Scripts

Copy and paste these scripts into `php artisan tinker` to test your email setup.

---

## 🧪 Test 1: Simple Email (SMTP Connection Test)

**What it does:** Sends a basic email to verify SMTP works.

```php
Mail::raw('This is a test email from AFIA ORBIT. If you see this, your SMTP configuration is working correctly!', function ($message) {
    $message->to('YOUR-EMAIL@gmail.com')  // ← CHANGE THIS
            ->subject('AFIA ORBIT - SMTP Test');
});
echo "✓ Email queued for sending!\n";
echo "Check your inbox at YOUR-EMAIL@gmail.com\n";
echo "Also check SPAM folder!\n";
```

**Replace:** `YOUR-EMAIL@gmail.com` with your actual email

**Expected:** Email arrives within 1-2 minutes

---

## 📧 Test 2: Client Invitation Notification

**What it does:** Sends the actual ClientInvitation notification.

```php
$contractor = App\Models\Contractor::first();
$client = App\Models\Client::first();

if (!$contractor || !$client) {
    echo "❌ No test data found. Run the data creation script first.\n";
} else {
    \Notification::route('mail', 'YOUR-EMAIL@gmail.com')  // ← CHANGE THIS
        ->notify(new App\Notifications\ClientInvitation($client, $contractor, 'TempPassword123'));
    
    echo "✓ Invitation sent!\n";
    echo "Client: " . $client->name . "\n";
    echo "Contractor: " . $contractor->company_name . "\n";
    echo "Password: TempPassword123\n";
    echo "Check YOUR-EMAIL@gmail.com\n";
}
```

**Replace:** `YOUR-EMAIL@gmail.com` with your actual email

**Expected:** Professional invitation email with login details

---

## 🔍 Test 3: Verify Configuration

**What it does:** Shows your current mail configuration.

```php
$config = config('mail');
echo "Mailer: " . $config['default'] . "\n";
echo "Host: " . $config['mailers']['smtp']['host'] . "\n";
echo "Port: " . $config['mailers']['smtp']['port'] . "\n";
echo "Username: " . $config['mailers']['smtp']['username'] . "\n";
echo "Password: " . (strlen($config['mailers']['smtp']['password']) . " characters") . "\n";
echo "From: " . $config['from']['address'] . "\n";
```

**Expected Output:**
```
Mailer: smtp
Host: smtp.gmail.com
Port: 587
Username: juniormeela5@gmail.com
Password: 16 characters
From: juniormeela5@gmail.com
```

**If wrong:** Run `php artisan config:clear` and try again

---

## 🧹 Test 4: Check Environment Variables

**What it does:** Checks if .env variables are loaded.

```php
echo "MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? 'SET (' . strlen(env('MAIL_PASSWORD')) . ' chars)' : 'NOT SET') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
```

**Expected:**
```
MAIL_MAILER: smtp
MAIL_HOST: smtp.gmail.com
MAIL_PORT: 587
MAIL_USERNAME: juniormeela5@gmail.com
MAIL_PASSWORD: SET (16 chars)
MAIL_ENCRYPTION: tls
```

---

## 💾 Test 5: Create Test Data (If Missing)

**What it does:** Creates sample contractor and client for testing.

```php
// Create test user
$user = App\Models\User::create([
    'name' => 'Test Contractor',
    'email' => 'contractor-test@example.com',
    'password' => bcrypt('password'),
    'user_type' => 'contractor'
]);

// Create contractor
$contractor = App\Models\Contractor::create([
    'user_id' => $user->id,
    'company_name' => 'Test Waste Services',
    'name' => 'John Test',
    'email' => 'john@testwaste.com',
    'phone' => '555-TEST',
    'address' => '123 Test Ave'
]);

// Create client
$client = App\Models\Client::create([
    'contractor_id' => $user->id,
    'name' => 'Test Corporation',
    'email' => 'test@testcorp.com',
    'phone' => '555-CLIENT',
    'address' => '456 Client St',
    'city' => 'Test City',
    'state' => 'TC',
    'zip_code' => '12345',
    'status' => 'active'
]);

// Link them
$contractor->client_registration_number = $client->registration_number;
$contractor->save();

echo "✓ Test data created!\n";
echo "Contractor: " . $contractor->registration_number . " - " . $contractor->company_name . "\n";
echo "Client: " . $client->registration_number . " - " . $client->name . "\n";
echo "Linked: YES\n";
```

---

## 🔄 Test 6: Full Integration Test

**What it does:** Creates client and sends invitation in one go (like production).

```php
use App\Services\ClientInvitationService;

$contractor = App\Models\Contractor::first();

if (!$contractor) {
    echo "❌ No contractor found. Create one first.\n";
} else {
    $service = new ClientInvitationService();
    
    $result = $service->createClientWithInvitation([
        'name' => 'Integration Test Client',
        'email' => 'YOUR-EMAIL@gmail.com',  // ← CHANGE THIS
        'phone' => '555-9999',
        'address' => '789 Integration Blvd',
        'city' => 'Test City',
        'state' => 'TC',
        'zip_code' => '99999',
        'status' => 'active'
    ], $contractor, true);
    
    echo "✓ Client created and invitation sent!\n";
    echo "Client: " . $result['client']->name . " (" . $result['client']->registration_number . ")\n";
    echo "Email: " . $result['client']->email . "\n";
    echo "Temp Password: " . $result['password'] . "\n";
    echo "User Account: " . ($result['user'] ? 'Created' : 'Not created') . "\n";
}
```

**Replace:** `YOUR-EMAIL@gmail.com` with your actual email

---

## 🚨 Test 7: Error Logging Test

**What it does:** Tests email and logs any errors.

```php
try {
    Mail::raw('Error logging test', function ($message) {
        $message->to('YOUR-EMAIL@gmail.com')->subject('Test');
    });
    echo "✓ Email sent successfully!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Details:\n";
    echo $e->getTraceAsString() . "\n";
}
```

---

## 📊 Test 8: Queue Check

**What it does:** Checks if emails are being queued (and stuck).

```php
use Illuminate\Support\Facades\DB;

$jobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();

echo "Queued jobs: " . $jobs . "\n";
echo "Failed jobs: " . $failedJobs . "\n";

if ($jobs > 0) {
    echo "\n⚠️  WARNING: You have " . $jobs . " queued jobs!\n";
    echo "Emails are being queued but not sent.\n";
    echo "Solution: Remove ShouldQueue from ClientInvitation class.\n";
}

if ($failedJobs > 0) {
    echo "\n❌ You have " . $failedJobs . " failed jobs!\n";
    echo "Check them with: DB::table('failed_jobs')->get();\n";
}
```

---

## 🧪 How to Use These Scripts

1. **Open Terminal:**
   ```bash
   cd c:\Users\junio\AFIA-ORBIT
   php artisan tinker
   ```

2. **Copy & Paste** any script above

3. **Replace** `YOUR-EMAIL@gmail.com` with your actual email

4. **Press Enter**

5. **Check your inbox** (and spam folder)

---

## ✅ Success Indicators

**Email Sending Works If:**
- ✓ No errors in tinker
- ✓ Email arrives within 1-2 minutes
- ✓ Email has proper formatting
- ✓ All content is visible (not HTML code)

**Email NOT Working If:**
- ❌ Tinker shows errors
- ❌ No email after 5 minutes
- ❌ Email shows HTML code instead of formatted text
- ❌ "Authentication failed" errors

---

## 🐛 Quick Fixes

**If Test 1 Fails:**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Verify .env
cat .env | grep MAIL
```

**If Config Shows Wrong Values:**
```bash
# Delete cached config
rm bootstrap/cache/config.php
php artisan config:clear
```

**If Password Length Wrong:**
- Gmail app passwords are exactly 16 characters
- Remove ALL spaces: `abcd efgh ijkl mnop` → `abcdefghijklmnop`

---

## 📞 Support

If emails still don't work after these tests:

1. Check `storage/logs/laravel.log` for errors
2. Verify 2FA is enabled on Gmail
3. Generate a fresh app password
4. Try port 465 with SSL encryption
5. Check firewall isn't blocking port 587

---

**Pro Tip:** Run Test 1 first. If it works, everything else will work!
