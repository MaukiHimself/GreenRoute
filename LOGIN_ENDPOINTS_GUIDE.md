# 🔐 Login Endpoints Guide

## 📍 All Login Pages

### 1. **Client Login** ⭐ (Updated)
**URL:** `http://localhost:8000/client/login`

**Fields:**
- Email Address
- Password
- Remember me (checkbox)

**Who Uses It:**
- Clients who received invitation emails
- Clients created by contractors

**Credentials:**
- Email from invitation
- Temporary password from invitation (or updated password)

**Example:**
```
Email: client@company.com
Password: aB3dE5fG7hJ9 (from email)
```

**Redirects To:** `/dashboard/client` (Client Dashboard)

---

### 2. **Contractor Login**
**URL:** `http://localhost:8000/login/contractor`

**Fields:**
- Email Address
- Password
- Remember me (checkbox)

**Who Uses It:**
- Contractors who registered via `/register/contractor`

**Credentials:**
- Email used during registration
- Password set during registration

**Example:**
```
Email: contractor@company.com
Password: [their password]
```

**Redirects To:** `/dashboard/contractor` (Contractor Dashboard)

---

### 3. **Admin Login**
**URL:** `http://localhost:8000/login/admin`

**Fields:**
- Email Address
- Password
- Remember me (checkbox)

**Who Uses It:**
- System administrators

**Credentials:**
- Admin email
- Admin password

**Example:**
```
Email: admin@greenroute-orbit.com
Password: [admin password]
```

**Redirects To:** `/dashboard/admin` (Admin Dashboard)

---

### 4. **Universal Login** (Main)
**URL:** `http://localhost:8000/login`

**Fields:**
- Email Address
- Password
- Remember me (checkbox)

**Who Uses It:**
- Anyone (auto-detects user type)

**How It Works:**
- Checks user_type field in database
- Redirects to appropriate dashboard

**Examples:**
```
# Client logs in here
→ Redirects to Client Dashboard

# Contractor logs in here
→ Redirects to Contractor Dashboard

# Admin logs in here
→ Redirects to Admin Dashboard
```

---

## 🔄 Registration Pages

### **Contractor Registration**
**URL:** `http://localhost:8000/register/contractor`

**Creates:**
- User account (user_type: 'contractor')
- Contractor record with registration number (CT######)

**After Registration:**
- Can log in at `/login/contractor`
- Can create clients
- Can create invoices/schedules

---

### **Client Registration** (Optional)
**URL:** `http://localhost:8000/client/register`

**Note:** Not typically used - contractors create clients instead

**When Used:**
- Client wants to self-register
- Requires contractor's registration number
- Still needs manual linking by admin

---

## 🎯 Recommended Flow

### For New System Setup:

```
1. CONTRACTOR REGISTERS
   └─ /register/contractor
   └─ Gets: CT440039

2. CONTRACTOR CREATES CLIENT
   └─ Via contractor dashboard
   └─ Client gets: CL012230
   └─ Email sent automatically

3. CLIENT RECEIVES EMAIL
   └─ Contains:
      - Email: client@company.com
      - Password: aB3dE5fG7hJ9
      - Registration: CL012230

4. CLIENT LOGS IN
   └─ /client/login
   └─ Enters email + password
   └─ Sees dashboard with invoices/schedules
```

---

## 🧪 Testing Each Login

### Test Client Login:
```bash
# Create test client
php artisan tinker
```

```php
$contractor = App\Models\Contractor::first();
$service = new App\Services\ClientInvitationService();
$result = $service->createClientWithInvitation([
    'name' => 'Test Client',
    'email' => 'test@client.com',
    'phone' => '555-1234',
    'address' => '123 St',
    'city' => 'City',
    'state' => 'ST',
    'zip_code' => '12345'
], $contractor, true);

echo "Email: test@client.com\n";
echo "Password: " . $result['password'] . "\n";
```

**Then:**
1. Go to: `/client/login`
2. Enter email: `test@client.com`
3. Enter password from above
4. Should see client dashboard

### Test Contractor Login:
```php
$contractor = App\Models\Contractor::first();
$user = $contractor->user;
echo "Email: " . $user->email . "\n";
```

**Then:**
1. Go to: `/login/contractor`
2. Enter contractor email
3. Enter contractor password
4. Should see contractor dashboard

---

## 🔒 Security Features

### All Login Pages Have:
- ✅ CSRF Protection
- ✅ Session regeneration after login
- ✅ User type validation
- ✅ Password hashing (bcrypt)
- ✅ Remember me tokens (30 days)
- ✅ Rate limiting (to prevent brute force)
- ✅ Proper error messages

### Additional Security:
```php
// User type enforcement
User::where('email', $email)
    ->where('user_type', 'client') // Only clients
    ->first();

// Client record verification
$client = Client::where('user_id', $user->id)->first();
if (!$client) {
    // Reject login
}
```

---

## 📊 Login Flow Diagram

```
┌──────────────────────────────────────┐
│         User Visits Login Page       │
│  /client/login                       │
│  /login/contractor                   │
│  /login/admin                        │
│  /login (auto-detect)                │
└──────────────────────────────────────┘
                 ↓
┌──────────────────────────────────────┐
│     User Enters Credentials          │
│  - Email                             │
│  - Password                          │
│  - Remember me (optional)            │
└──────────────────────────────────────┘
                 ↓
┌──────────────────────────────────────┐
│       Server Validation              │
│  1. Email exists?                    │
│  2. User type correct?               │
│  3. Password matches?                │
│  4. Associated record exists?        │
└──────────────────────────────────────┘
                 ↓
        ┌────────┴────────┐
        │                 │
    ✅ Success        ❌ Failed
        │                 │
        ↓                 ↓
┌──────────────┐  ┌──────────────┐
│ Create       │  │ Show Error   │
│ Session      │  │ Redirect     │
│              │  │ Back         │
│ Redirect To: │  └──────────────┘
│ - Client     │
│   Dashboard  │
│ - Contractor │
│   Dashboard  │
│ - Admin      │
│   Dashboard  │
└──────────────┘
```

---

## 🆘 Common Issues

### Issue: "No client account found"
**Fix:** Email is not registered as client  
**Check:** User type should be 'client'

### Issue: "Client account not properly set up"
**Fix:** User exists but not linked to client record  
**Solution:** Link user_id in clients table

### Issue: "The provided credentials do not match"
**Fix:** Wrong password  
**Try:** Use temporary password from email

### Issue: Wrong dashboard after login
**Fix:** User type not set correctly  
**Check:** user_type field in users table

---

## ✅ Quick Reference

| User Type | Login URL | Dashboard |
|-----------|-----------|-----------|
| Client | `/client/login` | `/dashboard/client` |
| Contractor | `/login/contractor` | `/dashboard/contractor` |
| Admin | `/login/admin` | `/dashboard/admin` |
| Any | `/login` | Auto-detected |

**All use:** Email + Password + Remember me (optional)

---

## 🎉 Summary

- **4 login endpoints** available
- **All use email + password** authentication
- **Client login updated** to work with invitation system
- **Registration numbers** still work behind scenes
- **Secure** Laravel authentication
- **Simple** user experience

**Recommended:** Use specific login pages (`/client/login`, `/login/contractor`) for better UX and security.

---

**Last Updated:** October 15, 2025  
**Version:** 2.0
