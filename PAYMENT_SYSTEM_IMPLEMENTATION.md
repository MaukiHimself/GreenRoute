# Payment System Implementation Guide

## Overview
This guide covers the complete implementation of the GreenRoute payment system, including merchant payment configurations, client checkout, payment submissions, debt calculations, and contractor payment verification.

## 1. Database Setup

### Run Migrations
The following migrations have been created to support the payment system:

```bash
php artisan migrate
```

**Migrations Created:**
- `2026_06_03_000001_add_payment_methods_to_contractors_table.php` - Adds Lipa No fields for all payment methods
- `2026_06_03_000002_create_payment_submissions_table.php` - Creates payment submissions tracking
- `2026_06_03_000003_update_invoices_for_partial_payments.php` - Adds partial payment support

### Migration Details

#### Contractor Payment Methods Fields
```sql
- vodacom_mpesa_lipa_no
- airtel_money_lipa_no
- halopesa_lipa_no
- mixx_by_yas_lipa_no
- crdb_bank_lipa_no
- nmb_bank_lipa_no
- nbc_bank_lipa_no
```

#### Payment Submissions Table
Tracks all client payment submissions with:
- `payer_name` - Name on payment account
- `amount_submitted` - Payment amount
- `payment_method` - Selected payment method
- `status` - pending/approved/rejected
- `receipt_number` - Generated receipt ID
- `receipt_path` - PDF receipt location

#### Invoice Updates
- `remaining_balance` - Tracks outstanding amount
- `status` - Now includes 'partially_paid' state

## 2. Model Updates

### Contractor Model
New methods added:
- `getLipaNo(string $paymentMethod)` - Get specific payment method number
- `getPaymentMethods()` - Get all configured payment methods
- `paymentSubmissions()` - Relationship to payment submissions
- `pendingPaymentSubmissions()` - Get pending submissions

### PaymentSubmission Model (New)
Manages payment submission lifecycle:
- `approve()` - Approve payment and update invoice
- `reject()` - Reject with reason
- `generateReceiptNumber()` - Generate unique receipt number

### Invoice Model
New relationships and methods:
- `paymentSubmissions()` - All submissions for invoice
- `pendingPaymentSubmissions()` - Pending submissions
- `approvedPaymentSubmissions()` - Approved submissions
- Updated `calculateTotals()` - Calculates remaining balance

## 3. Controllers

### PaymentSubmissionController
Handles client payment flow:
- `showPaymentMethods()` - Display available payment methods
- `showSubmissionForm()` - Show form for specific method
- `store()` - Process payment submission
- `showSubmissionConfirmation()` - Confirmation after submission

### ContractorPaymentApprovalController
Handles contractor verification:
- `showPendingApprovals()` - Contractor dashboard section
- `approve()` - Approve and generate receipt
- `reject()` - Reject with reason
- `downloadReceipt()` - Download PDF receipt

## 4. Routes

### Client Payment Routes
```php
Route::get('invoices/{invoice}/payment-methods', ...) // Select payment method
Route::get('invoices/{invoice}/payment-submission/{paymentMethod}', ...) // Form
Route::post('invoices/{invoice}/payment-submission', ...) // Submit
Route::get('invoices/{invoice}/payment-submitted', ...) // Confirmation
```

### Contractor Payment Routes
```php
Route::get('dashboard/contractor/pending-payment-approvals', ...) // Dashboard
Route::get('payment-approvals/stats', ...) // Stats API
Route::post('payment-submissions/{submission}/approve', ...) // Approve
Route::post('payment-submissions/{submission}/reject', ...) // Reject
Route::get('payment-submissions/{submission}/receipt/download', ...) // Download
```

## 5. Views

### Client Portal Views
- `client-portal/payment-methods.blade.php` - Payment method selection
- `client-portal/payment-submission-form.blade.php` - Payment form
- `client-portal/payment-submitted.blade.php` - Confirmation page

### Contractor Views
- `contractor/pending-payment-approvals.blade.php` - Approval dashboard

### Receipt View
- `receipts/payment-receipt.blade.php` - PDF receipt template

## 6. Setup Instructions

### Initial Setup

#### Step 1: Run Database Migrations
```bash
php artisan migrate
```

#### Step 2: Seed Payment Methods (Staging/Testing)
```bash
# Interactive setup for each contractor
php artisan payment:setup-methods

# Or reset all to placeholder
php artisan payment:setup-methods --reset

# Or for specific contractor
php artisan payment:setup-methods --contractor-id=1
```

#### Step 3: Run Seeder
```bash
php artisan db:seed --class=PaymentMethodSeeder
```

This seeds all existing contractors with placeholder number: `1234565`

## 7. Workflow

### Client Payment Flow
1. Client navigates to Invoices
2. Clicks "Pay Now" button on unpaid invoice
3. Selects preferred payment method
4. Views merchant Lipa No and contractor name
5. Fills form with:
   - Payer Name (account holder name)
   - Amount to Pay (up to balance due)
6. Submits payment proof
7. Gets success confirmation

### Contractor Approval Flow
1. Contractor accesses "Pending Payment Approvals" section
2. Reviews client payment submissions:
   - Client Name
   - Payer Name
   - Amount Submitted
   - Payment Method
   - Invoice Details
3. Verifies payment was received
4. Either:
   - **Approve**: Generates receipt, updates invoice, marks as paid/partially paid
   - **Reject**: Provides rejection reason

### Invoice Status Transitions
```
Initial State: draft/sent/overdue
    ↓
Client submits payment < Total Amount
    ↓
Status: pending (awaiting contractor review)
    ↓
Contractor approves
    ↓
Status: partially_paid (if remaining balance > 0)
Status: paid (if fully covered)
```

## 8. Key Features

### Merchant Payment Configurations
- Each contractor can configure unique Lipa Nos per payment method
- 7 payment methods supported:
  - Vodacom M-Pesa
  - Airtel Money
  - Halopesa
  - Mixx by Yas (Tigo Pesa)
  - CRDB Bank
  - NMB Bank
  - NBC Bank

### Dynamic Payment Display
- Client sees contractor's specific Lipa No for selected method
- Merchant name always displayed for verification
- Payment method clearly identified

### Partial Payment Support
- Clients can submit less than full balance
- Remaining amount tracked as outstanding debt
- Multiple submissions per invoice supported
- Invoice status updates accordingly

### Receipt Generation
- Automatic PDF generation upon approval
- Unique receipt number per payment
- Includes all transaction details
- Downloadable by both client and contractor

### Debt Tracking
- `remaining_balance` field tracks outstanding amount
- Partial payment calculations automatic
- Invoice status updates to 'partially_paid'
- No manual calculation needed

## 9. Testing

### Test Data Setup
1. Create contractors with payment methods:
```bash
php artisan payment:setup-methods --reset
```

2. Create test invoices via contractor dashboard

3. Create test client account

4. Client submits payment:
- Navigate to invoice
- Click "Pay Now"
- Select payment method
- Submit form

5. Contractor approves/rejects

### Verify Implementation
- Check payment_submissions table for submissions
- Verify invoices table has remaining_balance values
- Check receipt files in storage/receipts/
- Review receipt_issued_at timestamp

## 10. Configuration

### Payment Method Names (Customizable)
Edit in `PaymentSubmissionController.php` and `Contractor.php` models

### Receipt Storage Location
Default: `storage/receipts/`

### Receipt Template
Edit: `resources/views/receipts/payment-receipt.blade.php`

## 11. Common Operations

### Add Payment Method for Contractor
```bash
php artisan payment:setup-methods --contractor-id=1
```

### Reset All Methods to Placeholder
```bash
php artisan payment:setup-methods --reset
```

### Database Queries

#### View Pending Payments
```php
PaymentSubmission::where('status', 'pending')->get();
```

#### View Approved Payments by Date
```php
PaymentSubmission::where('status', 'approved')
    ->whereDate('verified_at', today())
    ->get();
```

#### View Invoice with Partial Payments
```php
Invoice::where('status', 'partially_paid')->get();
```

## 12. Troubleshooting

### Issue: Payment methods not showing for client
- **Solution**: Verify contractor has payment methods configured
- Check: `SELECT vodacom_mpesa_lipa_no FROM contractors WHERE id = X`

### Issue: Receipt not generating
- **Solution**: Ensure storage/receipts directory is writable
- Run: `chmod -R 755 storage/receipts`

### Issue: Partial payment not updating correctly
- **Solution**: Verify `remaining_balance` calculation in PaymentSubmission.approve()

## 13. Future Enhancements

Potential improvements for future versions:
- Automatic payment verification integration with mobile money APIs
- SMS notifications for clients and contractors
- Payment transaction history/timeline view
- Late payment reminders
- Payment analytics dashboard
- Multi-currency support
- Bulk payment processing
- Recurring payment templates

## 14. API Documentation

### Payment Submission Endpoints

#### Submit Payment (POST)
```
POST /dashboard/client/invoices/{invoice}/payment-submission
Content-Type: application/json

{
  "payer_name": "John Doe",
  "amount_submitted": 50000,
  "payment_method": "vodacom_mpesa"
}

Response:
{
  "success": true,
  "message": "Payment submission received..."
}
```

#### Approve Payment (POST)
```
POST /payment-submissions/{submission}/approve
X-CSRF-TOKEN: token

Response:
{
  "success": true,
  "message": "Payment approved and receipt generated",
  "receipt_url": "/path/to/receipt",
  "receipt_number": "RCP2026060312345678"
}
```

#### Reject Payment (POST)
```
POST /payment-submissions/{submission}/reject
Content-Type: application/json

{
  "reason": "Amount doesn't match"
}

Response:
{
  "success": true,
  "message": "Payment submission rejected"
}
```

## Support and Maintenance

For issues or questions:
1. Check database schema matches migrations
2. Verify controller logic in app/Http/Controllers/
3. Review model relationships in app/Models/
4. Check view rendering in resources/views/
5. Test routes with: `php artisan route:list | grep payment`
