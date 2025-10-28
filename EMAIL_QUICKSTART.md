# 📧 Email Notifications - Quick Start (5 Minutes)

## ✅ What's Already Done

Your email system is **95% complete**! All code is in place:
- ✅ Mail classes created
- ✅ Beautiful email templates designed
- ✅ Controller integration complete
- ✅ Error handling added

---

## 🚀 Quick Setup (Copy & Paste)

### **Option 1: Test with Mailtrap (Recommended for Development)**

1. **Sign up at Mailtrap** (FREE)
   - Go to: https://mailtrap.io
   - Create account (takes 30 seconds)
   - Go to "Inboxes" → Copy credentials

2. **Update your `.env` file**

Open `.env` and add/update these lines:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_your_mailtrap_username_here
MAIL_PASSWORD=paste_your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@afiaorbit.com
MAIL_FROM_NAME="AFIA ORBIT"
```

3. **Clear cache**

```bash
php artisan config:clear
```

4. **Test it!**
   - Register a test contractor
   - Login as admin
   - Approve the contractor
   - Check Mailtrap inbox to see the email!

---

### **Option 2: Use Gmail (For Real Emails)**

1. **Get Gmail App Password**
   - Go to: https://myaccount.google.com/security
   - Enable "2-Step Verification"
   - Search "App passwords"
   - Generate password for "Mail"
   - Copy the 16-character code

2. **Update `.env` file**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-char-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="AFIA ORBIT"
```

3. **Clear cache**

```bash
php artisan config:clear
```

4. **Test with real email!**

---

## 🎯 That's It!

When admin approves/rejects a contractor:
- ✉️ Email automatically sent
- 📧 Professional AFIA ORBIT branding
- 🎨 Beautiful HTML design
- 📱 Mobile responsive

---

## 🧪 Quick Test

```bash
# 1. Register test contractor
http://localhost:8000/register/contractor

# 2. Login as admin
http://localhost:8000/admin/login

# 3. Go to verification
http://localhost:8000/admin/verification

# 4. Click "Approve" on test contractor

# 5. Check email inbox!
```

---

## 📞 Need Help?

Check the detailed guide: `EMAIL_NOTIFICATIONS_SETUP.md`

Or contact: support@afiaorbit.com
