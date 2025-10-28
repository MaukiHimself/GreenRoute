# 📧 Email Notifications - Complete Setup Guide

## ✅ What You Already Have

Your system already includes:
- ✅ **Mail Classes**: `ContractorApproved.php` and `ContractorRejected.php`
- ✅ **Email Templates**: Beautiful HTML emails with AFIA ORBIT branding
- ✅ **Controller Integration**: Email code already in `AdminController.php`
- ✅ **Error Handling**: Graceful failure if email doesn't send

---

## 🚀 Quick Setup (3 Steps)

### **Step 1: Configure Email in `.env` File**

Choose ONE of these options:

#### **Option A: Gmail (Recommended for Production)**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-afiaorbit-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-afiaorbit-email@gmail.com
MAIL_FROM_NAME="AFIA ORBIT"
```

**How to Get Gmail App Password:**
1. Go to https://myaccount.google.com/security
2. Enable "2-Step Verification" (required)
3. Search for "App passwords" in the search bar
4. Select "Mail" and "Windows Computer"
5. Click "Generate"
6. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)
7. Paste it into `.env` as `MAIL_PASSWORD` (remove spaces)

#### **Option B: Mailtrap (Recommended for Testing)**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@afiaorbit.com
MAIL_FROM_NAME="AFIA ORBIT"
```

**How to Get Mailtrap Credentials:**
1. Sign up at https://mailtrap.io (FREE)
2. Go to "Email Testing" → "Inboxes"
3. Create a new inbox or use the default one
4. Copy the SMTP credentials from the inbox settings
5. Paste into your `.env` file

**Benefits of Mailtrap:**
- ✅ No real emails sent (perfect for testing)
- ✅ See exactly what emails look like
- ✅ Test without spamming users
- ✅ Check HTML rendering

#### **Option C: SendGrid (Recommended for High Volume)**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@afiaorbit.com
MAIL_FROM_NAME="AFIA ORBIT"
```

**How to Get SendGrid API Key:**
1. Sign up at https://sendgrid.com (FREE tier: 100 emails/day)
2. Go to Settings → API Keys
3. Create API Key with "Mail Send" permission
4. Copy the API key
5. Paste as `MAIL_PASSWORD` in `.env`

---

### **Step 2: Clear Configuration Cache**

After updating `.env`, run:

```bash
php artisan config:clear
php artisan cache:clear
```

This ensures Laravel loads your new email settings.

---

### **Step 3: Test the System**

#### **Test with Mailtrap (Safe):**
1. Configure `.env` with Mailtrap credentials
2. Go to Admin Dashboard → Verification
3. Approve or reject a contractor
4. Check your Mailtrap inbox to see the email

#### **Test with Gmail:**
1. Configure `.env` with Gmail credentials
2. Approve a contractor
3. Check the contractor's email inbox
4. They should receive a professional approval email

---

## 📧 What Emails Are Sent

### **1. Contractor Approved Email**

**Triggered when:** Admin clicks "Approve" button

**Subject:** "Your Contractor Account Has Been Approved - AFIA ORBIT"

**Content:**
- ✅ Congratulations message
- 📋 Account details (name, email, company)
- 🔗 Login button/link
- 📞 Support contact information
- 🎨 Professional AFIA ORBIT branding

**What contractor receives:**
```
🎉 Congratulations, [Contractor Name]!

Your contractor account has been approved and you can now access the AFIA ORBIT platform.

Your Account Details:
- Name: John Doe
- Email: john@example.com
- Company: Waste Management Ltd

You can now:
✓ Access your contractor dashboard
✓ Manage your clients
✓ Create pickup schedules
✓ Generate invoices
✓ Track routes

[Login to Dashboard Button]

Need help? Contact support@afiaorbit.com
```

---

### **2. Contractor Rejected Email**

**Triggered when:** Admin clicks "Reject" button

**Subject:** "Contractor Account Application Status - AFIA ORBIT"

**Content:**
- ℹ️ Professional rejection notice
- 📋 Application details
- 💬 Reason for rejection (if provided)
- 📞 Contact information to appeal
- 🤝 Invitation to reapply

**What contractor receives:**
```
Application Status Update

Dear [Contractor Name],

Thank you for your interest in becoming an AFIA ORBIT contractor.

After careful review, we are unable to approve your application at this time.

Reason: [Admin's reason if provided]

What's Next?
You can:
• Review our contractor requirements
• Contact support for more information
• Reapply after addressing the concerns

Contact Us:
Email: support@afiaorbit.com
Phone: +255 123 456 789

We appreciate your interest in AFIA ORBIT.
```

---

## 🔍 How It Works (Technical Details)

### **Flow Diagram:**

```
Admin clicks "Approve"
        ↓
Controller: AdminController::approveContractor()
        ↓
Update user status to 'approved' in database
        ↓
Send email: Mail::to($user->email)->send(new ContractorApproved($user))
        ↓
Mail class: App\Mail\ContractorApproved
        ↓
Email template: resources/views/emails/contractor-approved.blade.php
        ↓
SMTP server (Gmail/Mailtrap/SendGrid)
        ↓
Contractor's inbox ✉️
```

### **Code in AdminController.php:**

```php
public function approveContractor(User $user)
{
    // Update status
    $user->update(['status' => 'approved']);

    // Send email with error handling
    try {
        \Mail::to($user->email)->send(new \App\Mail\ContractorApproved($user));
    } catch (\Exception $e) {
        // Log error but don't fail the approval
        \Log::error('Failed to send approval email: ' . $e->getMessage());
    }

    return redirect()->route('admin.verification')
        ->with('success', "Contractor approved. Email sent.");
}
```

**Key Features:**
- ✅ Try-catch prevents approval failure if email fails
- ✅ Logs errors for debugging
- ✅ User experience not affected if email server is down
- ✅ Admin still sees success message

---

## 📂 File Structure

```
app/
├── Mail/
│   ├── ContractorApproved.php     ← Mail class for approval
│   └── ContractorRejected.php     ← Mail class for rejection
│
└── Http/Controllers/
    └── AdminController.php         ← Contains email sending logic

resources/views/emails/
├── contractor-approved.blade.php   ← HTML template for approval
└── contractor-rejected.blade.php   ← HTML template for rejection
```

---

## 🎨 Email Template Features

Both email templates include:
- ✅ **Responsive Design**: Works on mobile and desktop
- ✅ **AFIA ORBIT Branding**: Teal and red color scheme
- ✅ **Professional Layout**: Header, body, footer
- ✅ **Call-to-Action**: Login buttons with hover effects
- ✅ **Contact Information**: Support email and phone
- ✅ **Clear Messaging**: Easy to understand
- ✅ **Icons**: Visual indicators (✓, ✉, etc.)

---

## 🧪 Testing Checklist

- [ ] Configure `.env` with email credentials
- [ ] Clear configuration cache
- [ ] Register a test contractor
- [ ] Login as admin
- [ ] Navigate to Verification page
- [ ] Approve the test contractor
- [ ] Check contractor's email inbox
- [ ] Verify email received and looks correct
- [ ] Click login link in email
- [ ] Confirm contractor can access dashboard
- [ ] Test rejection email
- [ ] Verify rejection email received

---

## ❗ Troubleshooting

### **Problem: Emails not sending**

**Solution 1: Check `.env` configuration**
```bash
# Verify your settings
cat .env | grep MAIL
```

**Solution 2: Clear cache**
```bash
php artisan config:clear
php artisan cache:clear
```

**Solution 3: Check Laravel logs**
```bash
# Windows
type storage/logs/laravel.log

# Check for email errors
```

**Solution 4: Test SMTP connection**
```bash
php artisan tinker
>>> \Mail::raw('Test email', function($msg) { $msg->to('test@example.com'); });
```

### **Problem: Gmail "Less secure app" error**

**Solution:**
- ✅ Use App Password (not regular password)
- ✅ Enable 2-Step Verification first
- ✅ Generate app-specific password from Google Account settings

### **Problem: Emails go to spam**

**Solution:**
- ✅ Use professional email domain (not Gmail for production)
- ✅ Set up SPF records for your domain
- ✅ Use SendGrid or similar service
- ✅ Avoid spam trigger words in subject/body

---

## 🚀 Production Recommendations

### **Before Going Live:**

1. **Use Professional Email Service**
   - ✅ SendGrid, Mailgun, or AWS SES
   - ✅ NOT Gmail (rate limits)

2. **Set Up Email Domain**
   - ✅ Use `noreply@afiaorbit.com`
   - ✅ NOT personal Gmail address

3. **Configure SPF/DKIM Records**
   - ✅ Prevents emails from going to spam
   - ✅ Your email service will provide DNS records

4. **Monitor Email Delivery**
   - ✅ Check bounce rates
   - ✅ Monitor spam complaints
   - ✅ Track open rates

5. **Update Contact Information**
   - ✅ Use real support email
   - ✅ Use real phone number
   - ✅ Update in email templates

---

## 📊 Email Statistics (Optional Enhancement)

Want to track email opens and clicks? Add:

1. **SendGrid Analytics**: Built-in tracking
2. **Mailgun Events**: Email open/click webhooks
3. **Custom Tracking**: Add UTM parameters to links

---

## 🎯 Next Steps

1. ✅ **Choose email provider** (Mailtrap for testing, SendGrid for production)
2. ✅ **Configure `.env`** with credentials
3. ✅ **Clear cache** (`php artisan config:clear`)
4. ✅ **Test with real contractor** registration
5. ✅ **Verify email delivery** and appearance
6. ✅ **Update contact info** in templates for production
7. ✅ **Set up professional email domain** before launch

---

## 📞 Support

If you need help:
- Check Laravel logs: `storage/logs/laravel.log`
- Test email configuration: `php artisan tinker`
- Email provider docs:
  - Gmail: https://support.google.com/mail/answer/185833
  - Mailtrap: https://mailtrap.io/docs
  - SendGrid: https://docs.sendgrid.com

---

## ✨ Summary

Your email notification system is **ready to go**! Just:
1. Add email credentials to `.env`
2. Clear cache
3. Test it!

The system will automatically send professional emails when:
- ✅ Admin approves a contractor
- ✅ Admin rejects a contractor

**No additional coding required!** 🎉
