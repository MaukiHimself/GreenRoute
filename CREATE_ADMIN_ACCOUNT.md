# 👤 Create Admin Account on Render

## Quick Method - Using Render Shell

### **Step 1: Access Render Shell**

1. Go to: https://dashboard.render.com
2. Click on your **afia-orbit** service
3. Click on **Shell** tab (top right)
4. Wait for shell to connect

---

### **Step 2: Create Admin Account**

**Copy and paste this command**:

```bash
php artisan tinker
```

**Then copy and paste these commands one by one**:

```php
$admin = new App\Models\User();
$admin->name = 'Administrator';
$admin->email = 'admin@afiaorbit.com';
$admin->username = 'admin';
$admin->password = bcrypt('Admin@2025!');
$admin->user_type = 'admin';
$admin->status = 'approved';
$admin->email_verified_at = now();
$admin->save();
```

**Press Enter after the last line, then type**:
```php
exit
```

---

### **Step 3: Login**

**Admin Login URL**: https://afia-orbit.onrender.com/admin/login

**Credentials**:
- **Email**: `admin@afiaorbit.com`
- **Password**: `Admin@2025!`

✅ **You're now logged in as admin!**

---

## Change Default Password (Recommended)

After first login:
1. Go to admin profile settings
2. Change password to something more secure
3. Save changes

---

## Create Additional Admins

To create more admin accounts, repeat the process with different details:

```php
$admin2 = new App\Models\User();
$admin2->name = 'John Doe';
$admin2->email = 'john@afiaorbit.com';
$admin2->username = 'johndoe';
$admin2->password = bcrypt('YourSecurePassword123!');
$admin2->user_type = 'admin';
$admin2->status = 'approved';
$admin2->email_verified_at = now();
$admin2->save();
```

---

## Verify Admin Creation

To check if admin was created:

```bash
php artisan tinker
```

```php
App\Models\User::where('user_type', 'admin')->get();
```

Should show your admin account details.

---

## Troubleshooting

### **If "Class not found" error**:
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

Then try creating admin again.

---

### **If "tinker" command not found**:
```bash
composer require laravel/tinker
```

Then try again.

---

## Alternative Method - Registration Form

You can also:
1. Create admin via registration form
2. Then manually update database to set user_type to 'admin'

But the tinker method above is easier!

---

## Admin Account Details Template

**For your records**:

```
Admin Login URL: https://afia-orbit.onrender.com/admin/login
Email: admin@afiaorbit.com
Username: admin
Password: Admin@2025!
Created: [Today's Date]
```

**⚠️ Change the password after first login!**

---

## Status

**Admin Account**: Not created yet  
**Action Required**: Follow steps above  
**Time Needed**: 2 minutes  
**Access**: Immediate after creation
