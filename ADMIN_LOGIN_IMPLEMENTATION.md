# Admin Login Implementation Guide

## Recommended Approach: Separate Admin Login Route

To allow admins to login without accessing the main landing page, I recommend creating a separate admin login route at `/admin/login`.

### Benefits:
1. **Security**: Admin login is hidden from regular users
2. **Convenience**: Direct access for administrators
3. **Professional**: Separate admin portal entrance
4. **Access Control**: Can add IP restrictions or additional security

## Implementation Steps

### 1. Create Admin Login Route

Add to `routes/web.php`:

```php
// Admin-specific routes (public)
Route::prefix('admin')->group(function () {
    // Admin login page
    Route::get('/login', function () {
        return view('auth.admin-login');
    })->name('admin.login')->middleware('guest');
    
    // Admin login submission
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.submit');
});
```

### 2. Create Admin Login View

Create file: `resources/views/auth/admin-login.blade.php`

This will be a dedicated login page styled for administrators with:
- Your primary colors (teal #055c5c and red #640404)
- "Administrator Login" heading
- Email and password fields
- "Remember Me" option
- Clean, professional design
- AFIA ORBIT branding

### 3. Modify Authentication Controller (Optional)

You can add admin-specific login logic in `AuthenticatedSessionController` if needed:

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    
    $user = Auth::user();
    
    // Check if admin is trying to login via admin route
    if ($request->routeIs('admin.login.submit') && $user->user_type !== 'admin') {
        Auth::logout();
        throw ValidationException::withMessages([
            'email' => 'This account does not have administrator access.',
        ]);
    }
    
    // Redirect based on user type
    return match($user->user_type) {
        'admin' => redirect()->route('dashboard.admin'),
        'contractor' => redirect()->route('dashboard.contractor'),
        'client' => redirect()->route('client.dashboard'),
        default => redirect()->route('dashboard'),
    };
}
```

### 4. Admin Dashboard Routes

Create routes for all admin sections referenced in the dashboard:

```php
// Admin routes (protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.admin');
    Route::get('/verification', [AdminController::class, 'verification'])->name('admin.verification');
    Route::get('/clients', [AdminController::class, 'clients'])->name('admin.clients');
    Route::get('/billing', [AdminController::class, 'billing'])->name('admin.billing');
    Route::get('/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
});
```

### 5. Create Admin Middleware

Create `app/Http/Middleware/AdminMiddleware.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->user_type !== 'admin') {
            return redirect()->route('admin.login')
                ->with('error', 'You must be an administrator to access this page.');
        }
        
        return $next($request);
    }
}
```

Register middleware in `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ... other middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

## Access URLs

### For Administrators:
- **Login**: `https://yoursite.com/admin/login`
- **Dashboard**: `https://yoursite.com/admin/dashboard`

### For Regular Users:
- **Login**: `https://yoursite.com/login` (from landing page)
- **Contractor Dashboard**: `https://yoursite.com/contractor/dashboard`
- **Client Dashboard**: `https://yoursite.com/client/dashboard`

## Security Considerations

### 1. IP Whitelist (Optional but Recommended)
Add IP restriction to admin routes:

```php
Route::middleware(['auth', 'admin', 'ip.whitelist'])->prefix('admin')->group(function () {
    // Admin routes
});
```

### 2. Two-Factor Authentication (Future Enhancement)
Consider adding 2FA for admin accounts for extra security.

### 3. Rate Limiting
Add rate limiting to admin login route:

```php
Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:5,1') // 5 attempts per minute
    ->name('admin.login.submit');
```

### 4. Login Audit Log
Log all admin login attempts in database for security monitoring.

## Alternative Approaches

### Option 2: Subdomain
Create a completely separate subdomain:
- Admin: `admin.yoursite.com`
- Main: `www.yoursite.com`

### Option 3: Hidden Parameter
Add a hidden parameter to regular login:
- Regular: `/login`
- Admin: `/login?admin=true`

**Recommendation**: Option 1 (Separate /admin/login route) is the best balance of security, usability, and simplicity.

## Implementation Checklist

- [ ] Create `/admin/login` route
- [ ] Create admin login view with your brand colors
- [ ] Create AdminController
- [ ] Create Admin middleware
- [ ] Register admin routes
- [ ] Test admin login flow
- [ ] Test non-admin user cannot access admin routes
- [ ] Add admin dashboard controller logic
- [ ] Populate dashboard with real data
- [ ] Test logout from admin dashboard

## Current Dashboard Features

The admin dashboard I've created includes:

### Layout:
- Fixed left sidebar with logo
- Menu items: Dashboard, Verification, Clients Info, Billing & Payments, Schedules, Users
- Top header with breadcrumb navigation
- Notification bell with count badge
- User profile dropdown with logout

### Content:
- System Parameters section with stat cards:
  - Contractors count
  - Clients count
  - Active Routes count
- Pending Tasks section showing:
  - Task icon and title
  - Task description
  - Count badge
  - "View" button to take action

### Styling:
- Primary teal color (#055c5c) for headers, buttons, highlights
- Primary red color (#640404) for accents, notifications, badges
- Clean white background
- Professional hover effects
- Responsive grid layout
- Bootstrap 5 integration

## Next Steps

1. **Create admin login page** - Styled with your colors
2. **Create AdminController** - Handle all admin routes
3. **Add admin middleware** - Protect admin routes
4. **Populate dashboard data** - Connect to database
5. **Build sub-pages** - Verification, Clients, Billing, etc.

Would you like me to implement the admin login page and controller next?
