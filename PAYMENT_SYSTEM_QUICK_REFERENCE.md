# Payment System - Quick Reference Guide

## File Structure

```
app/
  ├── Console/Commands/
  │   └── SetupPaymentMethods.php          (CLI tool for payment method setup)
  ├── Http/Controllers/
  │   ├── PaymentSubmissionController.php   (Client payment flow)
  │   └── ContractorPaymentApprovalController.php  (Contractor approvals)
  └── Models/
      ├── Contractor.php                   (Updated with payment methods)
      ├── PaymentSubmission.php            (NEW - Payment tracking)
      └── Invoice.php                      (Updated for partial payments)

database/
  ├── migrations/
  │   ├── 2026_06_03_000001_*.php         (Payment methods table)
  │   ├── 2026_06_03_000002_*.php         (Payment submissions table)
  │   └── 2026_06_03_000003_*.php         (Invoice updates)
  └── seeders/
      └── PaymentMethodSeeder.php          (Seed placeholder data)

resources/views/
  ├── client-portal/
  │   ├── payment-methods.blade.php        (Method selection)
  │   ├── payment-submission-form.blade.php (Payment form)
  │   └── payment-submitted.blade.php      (Confirmation)
  ├── contractor/
  │   └── pending-payment-approvals.blade.php (Approval dashboard)
  └── receipts/
      └── payment-receipt.blade.php        (PDF receipt)

routes/
  └── web.php                              (Updated with payment routes)
```

## Database Tables

### contractors (UPDATED)
```
- vodacom_mpesa_lipa_no (VARCHAR)
- airtel_money_lipa_no (VARCHAR)
- halopesa_lipa_no (VARCHAR)
- mixx_by_yas_lipa_no (VARCHAR)
- crdb_bank_lipa_no (VARCHAR)
- nmb_bank_lipa_no (VARCHAR)
- nbc_bank_lipa_no (VARCHAR)
```

### payment_submissions (NEW)
```
- id (PRIMARY KEY)
- invoice_id (FOREIGN KEY → invoices)
- client_id (FOREIGN KEY → clients)
- contractor_id (FOREIGN KEY → contractors)
- payer_name (VARCHAR)
- amount_submitted (DECIMAL 15,2)
- payment_method (VARCHAR)
- status (ENUM: pending, approved, rejected)
- submitted_at (TIMESTAMP)
- verified_at (TIMESTAMP, nullable)
- rejected_at (TIMESTAMP, nullable)
- rejection_reason (TEXT, nullable)
- receipt_number (VARCHAR, nullable)
- receipt_path (VARCHAR, nullable)
- receipt_issued_at (TIMESTAMP, nullable)
- timestamps
```

### invoices (UPDATED)
```
- remaining_balance (DECIMAL 15,2)  - NEW
- status - UPDATED (now includes 'partially_paid')
```

## Key Routes

### Client Payment Routes
| Route | Method | Handler |
|-------|--------|---------|
| `/dashboard/client/invoices/{id}/payment-methods` | GET | showPaymentMethods |
| `/dashboard/client/invoices/{id}/payment-submission/{method}` | GET | showSubmissionForm |
| `/dashboard/client/invoices/{id}/payment-submission` | POST | store |
| `/dashboard/client/invoices/{id}/payment-submitted` | GET | showSubmissionConfirmation |

### Contractor Payment Routes
| Route | Method | Handler |
|-------|--------|---------|
| `/dashboard/contractor/pending-payment-approvals` | GET | showPendingApprovals |
| `/dashboard/contractor/payment-approvals/stats` | GET | getStats |
| `/payment-submissions/{id}/approve` | POST | approve |
| `/payment-submissions/{id}/reject` | POST | reject |
| `/payment-submissions/{id}/receipt/download` | GET | downloadReceipt |

## Payment Methods Supported

| Key | Display Name | Field Name |
|-----|--------------|------------|
| vodacom_mpesa | Vodacom M-Pesa | vodacom_mpesa_lipa_no |
| airtel_money | Airtel Money | airtel_money_lipa_no |
| halopesa | Halopesa | halopesa_lipa_no |
| mixx_by_yas | Mixx by Yas (Tigo Pesa) | mixx_by_yas_lipa_no |
| crdb_bank | CRDB Bank | crdb_bank_lipa_no |
| nmb_bank | NMB Bank | nmb_bank_lipa_no |
| nbc_bank | NBC Bank | nbc_bank_lipa_no |

## Setup Commands

### Initial Setup
```bash
# Run migrations
php artisan migrate

# Run seeder (sets all contractors to placeholder: 1234565)
php artisan db:seed --class=PaymentMethodSeeder
```

### Configure Payment Methods

```bash
# Interactive setup (prompts for each contractor)
php artisan payment:setup-methods

# Setup for specific contractor
php artisan payment:setup-methods --contractor-id=1

# Reset all to placeholder (1234565)
php artisan payment:setup-methods --reset
```

## Client Payment Workflow

```
Invoice View (client.invoices)
    ↓ "Pay Now" button
Payment Methods Selection (client.payment-methods)
    ↓ Click payment method (e.g., M-Pesa)
Payment Submission Form (client.payment-submission-form)
    ↓ Enter payer name and amount
Submit Payment (POST /payment-submission)
    ↓ Validation & Creation
Confirmation Page (client.payment-submitted)
    ↓
PaymentSubmission created with status='pending'
    ↓
Awaiting contractor verification (up to 5 hours)
```

## Contractor Approval Workflow

```
Pending Payments Dashboard (contractor.pending-payment-approvals)
    ↓
Review Client Submission
    ├─ Verify payment received
    ├─ Check payer name matches
    └─ Confirm amount correct
    ↓
Choose Action:
    ├─ Approve → Generate Receipt → Update Invoice → PaymentSubmission.status='approved'
    └─ Reject → PaymentSubmission.status='rejected' + rejection_reason
    ↓
If Approved:
    - Receipt PDF generated and stored
    - Invoice balance updated
    - Status set to 'paid' or 'partially_paid'
    - receipt_number & receipt_path saved
```

## Invoice Status Transitions

```
Status Values:
- draft: Initial state
- sent: Invoice sent to client
- paid: Fully paid
- partially_paid: Partial payment received
- overdue: Due date passed, not paid
- cancelled: Cancelled

Workflow with Partial Payments:
draft/sent/overdue
    ↓
Client submits payment (amount < total)
    ↓ Contractor approves
    ↓
Status: partially_paid
amount_paid: updated
remaining_balance: calculated
    ↓
Client submits another payment
    ↓ Contractor approves
    ↓
If (amount_paid >= total_amount):
    Status: paid
Else:
    Status: partially_paid
```

## Important Model Methods

### Contractor Model
```php
$contractor->getLipaNo($paymentMethod)
// Returns specific Lipa No or null

$contractor->getPaymentMethods()
// Returns array of all methods with Lipa Nos

$contractor->paymentSubmissions()
// All payment submissions for contractor

$contractor->pendingPaymentSubmissions()
// Only pending submissions
```

### PaymentSubmission Model
```php
$submission->approve()
// Approves payment, updates invoice, generates receipt

$submission->reject($reason)
// Rejects payment with reason

PaymentSubmission::generateReceiptNumber()
// Generates unique receipt number
```

### Invoice Model
```php
$invoice->paymentSubmissions()
// All submissions for invoice

$invoice->pendingPaymentSubmissions()
// Pending submissions

$invoice->approvedPaymentSubmissions()
// Approved submissions
```

## Testing Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Seed data: `php artisan db:seed --class=PaymentMethodSeeder`
- [ ] Verify contractors table has payment method columns
- [ ] Verify payment_submissions table created
- [ ] Verify invoices table has remaining_balance column
- [ ] Create test invoice as contractor
- [ ] Login as client
- [ ] Navigate to invoices
- [ ] Click "Pay Now"
- [ ] Select payment method
- [ ] See contractor Lipa No displayed
- [ ] Submit payment
- [ ] See confirmation page
- [ ] Verify payment_submissions record created
- [ ] Login as contractor
- [ ] View pending approvals
- [ ] See payment submission details
- [ ] Approve payment
- [ ] See receipt generated
- [ ] Verify invoice updated
- [ ] Check remaining_balance calculated correctly
- [ ] Download receipt PDF
- [ ] Verify receipt contains all details

## Receipt Download

Receipts stored in: `storage/receipts/RCP{timestamp}{random}.pdf`

Download URL: `/payment-submissions/{id}/receipt/download`

## Useful Queries

```php
// Pending payments for contractor
PaymentSubmission::where('contractor_id', $id)
    ->where('status', 'pending')
    ->with('invoice', 'client')
    ->get();

// Total pending amount
PaymentSubmission::where('contractor_id', $id)
    ->where('status', 'pending')
    ->sum('amount_submitted');

// Partially paid invoices
Invoice::where('status', 'partially_paid')->get();

// Client pending payments
PaymentSubmission::where('client_id', $id)
    ->where('status', 'pending')
    ->with('invoice')
    ->get();
```

## Troubleshooting

### Problem: "No payment methods available"
- Check contractor has payment methods configured
- Query: `SELECT * FROM contractors WHERE id = X`
- Run setup: `php artisan payment:setup-methods`

### Problem: Receipt not downloading
- Check file exists: `storage/receipts/`
- Check permissions: `chmod -R 755 storage/receipts`
- Verify receipt_path in database

### Problem: Remaining balance incorrect
- Check `calculateTotals()` in Invoice model
- Verify `approve()` in PaymentSubmission model
- Check amount_submitted vs total_amount

### Problem: Status not updating to partially_paid
- Check invoice status update logic in `PaymentSubmission.approve()`
- Verify remaining_balance calculation
- Check if amount >= total (should be 'paid', not 'partially_paid')

## Performance Considerations

- Payment submissions indexed on: `(invoice_id, status)`, `(contractor_id, status)`
- Consider pagination for large result sets
- Receipt generation async for bulk operations
- Cache contractor payment methods
