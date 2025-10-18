# 📧 Enable Real Email Sending on Render

## Current Status
Emails are being **logged** instead of sent. This is why users don't receive emails.

---

## Quick Fix - Use Gmail SMTP

### **Step 1: Get Gmail App Password**

1. Go to: https://myaccount.google.com/security
2. Enable **2-Step Verification** (if not already enabled)
3. Go to: https://myaccount.google.com/apppasswords
4. Create app password for "Mail"
5. Copy the 16-character password (example: `abcd efgh ijkl mnop`)

---

### **Step 2: Update Render Environment Variables**

1. Go to: https://dashboard.render.com
2. Click on your **afia-orbit** service
3. Go to **Environment** tab
4. Click **Add Environment Variable**

**Add/Update these variables**:

| Key | Value |
|-----|-------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `your-email@gmail.com` |
| `MAIL_PASSWORD` | `your-16-char-app-password` |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS` | `your-email@gmail.com` |
| `MAIL_FROM_NAME` | `AFIA ORBIT` |

5. Click **Save Changes**
6. Render will automatically redeploy (~5 minutes)

---

### **Step 3: Test**

After redeployment:
1. Register a new test user
2. Check their email inbox
3. Should receive verification email ✅

---

## Alternative - Better Email Services

### **Option 1: SendGrid (Recommended for Production)**

**Why**: Free tier (100 emails/day), reliable, professional

**Setup**:
1. Sign up: https://sendgrid.com
2. Create API Key
3. Update Render environment:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.sendgrid.net
   MAIL_PORT=587
   MAIL_USERNAME=apikey
   MAIL_PASSWORD=your-sendgrid-api-key
   MAIL_ENCRYPTION=tls
   ```

---

### **Option 2: Mailgun**

**Why**: Free tier (100 emails/day), easy setup

**Setup**:
1. Sign up: https://mailgun.com
2. Get SMTP credentials
3. Update Render environment with Mailgun settings

---

### **Option 3: AWS SES**

**Why**: Very cheap, scalable, professional

**Setup**:
1. AWS account needed
2. Verify domain or email
3. Get SMTP credentials
4. Update Render environment

---

## Testing Emails Locally

To test emails without sending them:

1. Use **Mailtrap** (https://mailtrap.io)
2. Free testing inbox
3. See exactly what emails look like
4. No emails sent to real users

---

## What Emails Are Sent

Your application sends emails for:

1. **Email Verification** - When user registers
2. **Password Reset** - When user forgets password
3. **Notifications** - Various system notifications

---

## Current Logs Location

To see "sent" emails in logs:
1. Render Dashboard → Logs
2. Search for "mail" or "email"
3. You'll see email content that was logged

---

## Status

**Current**: Emails logged only (not sent)  
**After Gmail Setup**: Emails sent via Gmail SMTP  
**Production Ready**: Use SendGrid/Mailgun/AWS SES
