# 🔧 Fix Contractor Dashboard Tabs

## 🔍 What's the Issue?

Your contractor dashboard tabs load content via **iframes**. Here's what happens:

### When you click a tab:
1. **Billing & Payments** tab → Loads `/billing` in an iframe
2. **Collection Schedules** tab → Loads `/schedules` in an iframe
3. **Disposal Schedules** tab → Loads `/disposal` in an iframe

### Possible Problems:

1. ❌ **Iframe not loading** - Content blocked by browser/server
2. ❌ **Content shows but buttons/forms don't work** - Iframe navigation issues
3. ❌ **Tab doesn't switch** - JavaScript error

---

## 🧪 Test Which Issue You Have

### Test 1: Can you access the pages directly?

Open these URLs in your browser:

```
http://localhost:8000/billing
http://localhost:8000/billing/create
http://localhost:8000/schedules
http://localhost:8000/schedules/create
```

✅ **If they work:** Iframe issue  
❌ **If they don't work:** Controller/route issue

### Test 2: Check browser console

1. Open contractor dashboard
2. Click a tab (e.g., "Billing & Payments")
3. Press `F12` → Console tab
4. Look for errors

Common errors:
- `Refused to display in a frame` - X-Frame-Options blocking
- `404 Not Found` - Route missing
- No error but blank - CSS/display issue

---

## ✅ Solution 1: Remove Iframes (Recommended)

Instead of iframes, navigate directly to the pages:

### Update the sidebar links:

**File:** `resources/views/contractor/mapping-dashboard.blade.php`

**Find (around line 298-305):**
```html
<a class="nav-link" href="#" data-tab="billing">
    <i class="bi bi-credit-card"></i>
    <span>Billing & Payments</span>
</a>
<a class="nav-link" href="#" data-tab="collection">
    <i class="bi bi-calendar3"></i>
    <span>Collection Schedules</span>
</a>
```

**Replace with:**
```html
<a class="nav-link" href="/billing">
    <i class="bi bi-credit-card"></i>
    <span>Billing & Payments</span>
</a>
<a class="nav-link" href="/schedules">
    <i class="bi bi-calendar3"></i>
    <span>Collection Schedules</span>
</a>
<a class="nav-link" href="/disposal">
    <i class="bi bi-trash"></i>
    <span>Disposal Schedules</span>
</a>
```

This way, clicking the tabs will navigate to the actual pages instead of trying to load them in iframes.

---

## ✅ Solution 2: Fix Iframes (If you want to keep them)

If you want to keep iframes, we need to ensure they load properly.

### Step 1: Check X-Frame-Options

Add this to your `.env`:
```env
SESSION_SAME_SITE=lax
```

### Step 2: Update iframe heights

**File:** `resources/views/contractor/mapping-dashboard.blade.php`

**Find (around line 547-556):**
```html
<div id="billing-tab" class="tab-content" style="display: none;">
    <iframe src="/billing" width="100%" height="600" frameborder="0"></iframe>
</div>

<div id="collection-tab" class="tab-content" style="display: none;">
    <iframe src="/schedules" width="100%" height="600" frameborder="0"></iframe>
</div>
```

**Replace with:**
```html
<div id="billing-tab" class="tab-content" style="display: none;">
    <iframe src="/billing" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
</div>

<div id="collection-tab" class="tab-content" style="display: none;">
    <iframe src="/schedules" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
</div>

<div id="disposal-tab" class="tab-content" style="display: none;">
    <iframe src="/disposal" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
</div>
```

### Step 3: Add iframe auto-resize

Add this JavaScript at the end of the file (before `</script>`):

```javascript
// Auto-resize iframes
document.querySelectorAll('iframe').forEach(iframe => {
    iframe.onload = function() {
        try {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const height = iframeDoc.body.scrollHeight;
            iframe.style.height = (height + 50) + 'px';
        } catch(e) {
            console.log('Cannot resize iframe:', e);
        }
    };
});
```

---

## ✅ Solution 3: Debug Tab Switching

If tabs aren't switching at all, there might be a JavaScript error.

### Test tab switching:

1. Open contractor dashboard
2. Press `F12` → Console
3. Run this command:

```javascript
document.querySelector('[data-tab="billing"]').click();
```

If it works → Tab switching is fine  
If it errors → JavaScript issue

---

## 🚀 Quick Fix Command

Run this to test if the pages work:

```bash
# Test billing page
curl http://localhost:8000/billing

# Test schedules page  
curl http://localhost:8000/schedules
```

If you see HTML output → Pages work, iframe issue  
If you see errors → Route/controller issue

---

## 📝 What I Recommend

**Best approach:**
1. ✅ Navigate directly to pages (Solution 1)
2. ✅ Remove iframe complexity
3. ✅ Faster, more reliable

**Why?**
- Iframes have navigation issues
- Forms inside iframes can be problematic
- Direct navigation is cleaner

---

## 🎯 Tell Me:

1. **When you click "Billing & Payments" tab, what happens?**
   - Nothing
   - Blank screen
   - Error message
   - Content loads but doesn't work

2. **Can you access `/billing` directly in browser?**
   - Yes, works fine
   - No, shows error

3. **What specifically doesn't work?**
   - Tab doesn't open
   - Tab opens but blank
   - Tab shows content but "Create Invoice" button doesn't work
   - Something else

Let me know and I'll provide a targeted fix!
