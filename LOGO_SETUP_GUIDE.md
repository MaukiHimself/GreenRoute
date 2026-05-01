# 🎨 How to Change the Logo

## 📁 Logo Storage Location

Your logos are stored in:
```
public/your-logo.png
public/your-logo2.png
```

These files already exist but are not currently used in the views. You can replace them with your own logo.

---

## 🔄 How to Add Your Logo

### Step 1: Replace Logo Files
1. Go to: `/public/` folder
2. Replace `your-logo.png` or `your-logo2.png` with your own logo file
3. Keep the filename the same (or update references in step 2)

**Recommended:**
- Logo format: PNG with transparent background
- Size: 150-200px wide × 50-80px tall
- Aspect ratio: Wide and short (landscape)

---

### Step 2: Add Logo to Views

Choose one of the options below:

#### **Option A: Simple Text Logo (Current Setup)**
Currently, the app shows text logos:
- Guest/Login pages: **"GreenRoute"** text
- Admin panel: **"GreenRoute"** text
- Contractor dashboard: **"GreenRoute"** with recycling icon

To add an image logo to these areas, follow Option B below.

#### **Option B: Add Image Logo to Guest Pages**

Edit `/resources/views/layouts/guest.blade.php` (around line 138):

**Current:**
```blade
<div class="logo-section">
    <a href="/" class="logo text-decoration-none">
        GreenRoute
    </a>
</div>
```

**Replace with:**
```blade
<div class="logo-section">
    <a href="/" class="logo text-decoration-none">
        <img src="{{ asset('your-logo.png') }}" alt="GreenRoute Logo" style="max-height: 60px; width: auto;">
    </a>
</div>
```

#### **Option C: Add Image Logo to Admin Panel**

Edit `/resources/views/admin/contractor-details.blade.php` (around line 196):

**Current:**
```blade
<div class="logo-section">
    <h4 style="color: var(--primary-teal);">GreenRoute</h4>
    <small>Admin Panel</small>
</div>
```

**Replace with:**
```blade
<div class="logo-section">
    <img src="{{ asset('your-logo.png') }}" alt="GreenRoute Logo" style="max-height: 60px; width: auto; margin-bottom: 10px;">
    <small>Admin Panel</small>
</div>
```

#### **Option D: Add Image Logo to Contractor Dashboard**

Edit `/resources/views/layouts/contractor-app.blade.php` (around line 242):

**Current:**
```blade
<a href="{{ route('dashboard.contractor') }}" class="sidebar-logo">
    <i class="bi bi-recycle me-2"></i>GreenRoute
</a>
```

**Replace with:**
```blade
<a href="{{ route('dashboard.contractor') }}" class="sidebar-logo">
    <img src="{{ asset('your-logo.png') }}" alt="GreenRoute Logo" style="max-height: 50px; width: auto;">
</a>
```

---

## 📋 Quick Steps Summary

1. **Prepare your logo:**
   - Format: PNG (with transparent background recommended)
   - Size: 150-200px wide
   - Place in `/public/` folder

2. **Update views** (pick which pages need logos):
   - Guest pages: `resources/views/layouts/guest.blade.php`
   - Admin panel: `resources/views/admin/contractor-details.blade.php`
   - Contractor dashboard: `resources/views/layouts/contractor-app.blade.php`

3. **Replace text with image tag:**
   ```blade
   <img src="{{ asset('your-logo.png') }}" alt="GreenRoute Logo" style="max-height: 60px; width: auto;">
   ```

4. **Test** in your browser to see the logo appear

---

## 🎯 Logo File Names to Use

You can use any of these:
- `your-logo.png` (already exists in public folder)
- `your-logo2.png` (already exists in public folder)
- `logo.png` (custom name)
- `greenroute-logo.png` (descriptive name)

Just make sure the filename matches in the `asset()` helper:
```blade
<img src="{{ asset('your-custom-logo.png') }}" ...>
```

---

## 🔗 Useful Asset Paths

In Laravel views, use the `asset()` helper to reference files in the `/public` folder:

```blade
<!-- Correct way -->
{{ asset('your-logo.png') }}

<!-- This will generate URL like -->
http://localhost:8000/your-logo.png
```

---

## 💡 Tips

- **Favicon**: You can also change the favicon at `/public/favicon.ico`
- **Responsive**: Add `style="max-height: 60px; width: auto;"` to keep logos responsive
- **Alt text**: Always include `alt="Logo"` for accessibility
- **Test on all pages**: Check logo appears on login, admin, and contractor pages

---

## 🚀 Done!

Once you follow these steps, your custom logo will appear on all the pages you updated!
