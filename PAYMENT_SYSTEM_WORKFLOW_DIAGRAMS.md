# Payment System - Complete Workflow Diagrams

## Client Payment Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│                    CLIENT INVOICE VIEW                          │
│                    (client.invoices)                            │
│  Shows list of invoices with status badges                      │
│  ✨ NEW: "Pay Now" button for unpaid invoices                   │
└─────────────────────────────┬───────────────────────────────────┘
                              │ Click "Pay Now"
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│              PAYMENT METHODS SELECTION                           │
│          (client.payment-methods)                               │
│  Displays all available payment methods for contractor:         │
│  • Vodacom M-Pesa                                               │
│  • Airtel Money                                                 │
│  • Halopesa                                                     │
│  • Mixx by Yas (Tigo Pesa)                                      │
│  • CRDB Bank                                                    │
│  • NMB Bank                                                     │
│  • NBC Bank                                                     │
│                                                                 │
│  Each shows: Method Name, Merchant Lipa No, Contractor Name     │
└─────────────────────────────┬───────────────────────────────────┘
                              │ Select Payment Method
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│          PAYMENT SUBMISSION FORM                                │
│      (client.payment-submission-form)                           │
│                                                                 │
│  Displays:                                                      │
│  • Invoice summary (total, paid, balance)                       │
│  • Merchant details (name, Lipa No)                             │
│  • Form fields:                                                 │
│    - Payer Name (account holder name)                           │
│    - Amount to Pay (up to balance due)                          │
│  • Amount preview (shows remaining after payment)               │
│  • Important instructions                                       │
│                                                                 │
│  Client enters:                                                 │
│  - Name on payment account                                      │
│  - Amount to send                                               │
└─────────────────────────────┬───────────────────────────────────┘
                              │ Submit Form
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│         PAYMENT SUBMISSION PROCESSING                           │
│      (POST /payment-submission)                                 │
│                                                                 │
│  System validates:                                              │
│  ✓ Amount > 0                                                   │
│  ✓ Amount <= balance due                                        │
│  ✓ Payment method exists                                        │
│  ✓ Contractor has Lipa No configured                            │
│                                                                 │
│  Creates: PaymentSubmission record                              │
│  - status: 'pending'                                            │
│  - submitted_at: NOW()                                          │
│  - payer_name, amount_submitted, payment_method                 │
└─────────────────────────────┬───────────────────────────────────┘
                              │
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│         PAYMENT SUBMITTED CONFIRMATION                          │
│       (client.payment-submitted)                                │
│                                                                 │
│  Displays:                                                      │
│  ✅ "Payment Submission Received!"                              │
│  ⏳ "Please wait up to 5 hours for verification"                │
│  • Submission summary (amount, method, payer name)              │
│  • Invoice status after payment                                 │
│  • What happens next (step-by-step)                             │
│  • Important reminders                                          │
└─────────────────────────────┬───────────────────────────────────┘
                              │
                    ⏳ AWAITING CONTRACTOR REVIEW
                         (up to 5 hours)
                              │
                              ↓
```

## Contractor Payment Verification Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│    CONTRACTOR DASHBOARD - PENDING APPROVALS                     │
│  (contractor.pending-payment-approvals)                         │
│                                                                 │
│  Quick Stats:                                                   │
│  • Total Pending: N submissions                                 │
│  • Total Amount: TZS X,XXX,XXX.XX                               │
│  • Average: TZS X,XXX.XX                                        │
│                                                                 │
│  For each pending submission shows:                             │
│  • Client Name                                                  │
│  • Payer Name (from payment account)                            │
│  • Payment Method                                               │
│  • Amount Submitted                                             │
│  • Submission Time                                              │
│  • Invoice Details (number, total, balance)                     │
│  • Status after approval preview                                │
│                                                                 │
│  Search/Filter capability by client or payer name               │
└─────────────────────────────┬───────────────────────────────────┘
                              │
                  Contractor reviews submission
                 and verifies payment received
                              │
                ┌─────────────┴──────────────┐
                │                            │
                ↓                            ↓
        ┌──────────────┐            ┌──────────────┐
        │   APPROVE    │            │   REJECT     │
        └────────┬─────┘            └────────┬─────┘
                 │                           │
                 ↓                           ↓
    ┌────────────────────┐      ┌──────────────────────┐
    │ GENERATE RECEIPT   │      │ MARK AS REJECTED     │
    │ - PDF created      │      │ - Store reason       │
    │ - Receipt # gen    │      │ - rejected_at set    │
    │ - Stored in /tmp   │      │ - Client notified    │
    │ - receipt_path     │      │   (future: SMS)      │
    │   saved in DB      │      │                      │
    └────────┬───────────┘      └────────────────────┘
             │
             ↓
    ┌────────────────────┐
    │ UPDATE INVOICE     │
    │ - amount_paid += X │
    │ - remaining_bal =  │
    │   total - paid     │
    │ - Status update:   │
    │   If paid >= total │
    │     → "paid"       │
    │   Else             │
    │     → "partially_  │
    │       paid"        │
    │ - paid_at = NOW()  │
    └────────┬───────────┘
             │
             ↓
    ┌────────────────────┐
    │ UPDATE SUBMISSION  │
    │ - status: approve  │
    │ - verified_at: NOW │
    │ - receipt_number   │
    │ - receipt_path     │
    │ - receipt_issued_  │
    │   at: NOW()        │
    └────────┬───────────┘
             │
             ↓
    ┌────────────────────────────┐
    │ RECEIPT DOWNLOADABLE       │
    │ • Both contractor & client │
    │   can download PDF         │
    │ • Contains all details     │
    │ • Professional format      │
    └────────────────────────────┘
```

## Invoice Status Lifecycle with Partial Payments

```
SIMPLE INVOICE (No Partial Payments):

    [DRAFT] → [SENT] → [PAID] ✓
              ↓
            [OVERDUE]


INVOICE WITH PARTIAL PAYMENTS:

    [DRAFT] → [SENT]
              ↓
            [OVERDUE]
              ↓
        Client submits $500 (total $1000)
              ↓
        [PARTIALLY_PAID]
        • amount_paid: $500
        • remaining_balance: $500
        • status: 'partially_paid'
              ↓
        Client submits $500 (remaining)
              ↓
        [PAID] ✓
        • amount_paid: $1000
        • remaining_balance: $0
        • status: 'paid'


STATUS VALUES:
├─ draft: Initial state, not sent
├─ sent: Invoice sent to client
├─ paid: Fully paid (amount_paid >= total_amount)
├─ partially_paid: Partial payment received (amount_paid > 0 && amount_paid < total)
├─ overdue: Past due_date, not fully paid
└─ cancelled: Cancelled
```

## Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                   DATABASE TABLES                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  CONTRACTORS                        CLIENTS                     │
│  ├─ id                              ├─ id                       │
│  ├─ name                            ├─ name                     │
│  ├─ vodacom_mpesa_lipa_no  ←─────┐  ├─ contractor_id           │
│  ├─ airtel_money_lipa_no           │  └─ registration_number    │
│  ├─ halopesa_lipa_no               │                            │
│  ├─ mixx_by_yas_lipa_no            │  INVOICES                  │
│  ├─ crdb_bank_lipa_no              ├─ id                        │
│  ├─ nmb_bank_lipa_no               ├─ contractor_id             │
│  └─ nbc_bank_lipa_no               ├─ client_id                 │
│                                    ├─ total_amount              │
│                                    ├─ amount_paid               │
│                                    ├─ remaining_balance ✨ NEW   │
│                                    ├─ status  (now includes     │
│                                    │   'partially_paid') ✨     │
│                                    └─ paid_at                   │
│                                            ↑                    │
│                                            │                    │
│                       PAYMENT_SUBMISSIONS ✨ NEW               │
│                       ├─ id                                     │
│                       ├─ invoice_id ─────────┘                 │
│                       ├─ client_id ──────────┐                 │
│                       ├─ contractor_id ──────┐                 │
│                       ├─ payer_name          │                 │
│                       ├─ amount_submitted    │                 │
│                       ├─ payment_method      │                 │
│                       ├─ status              │                 │
│                       ├─ submitted_at        │                 │
│                       ├─ verified_at         │                 │
│                       ├─ receipt_number      │                 │
│                       ├─ receipt_path        │                 │
│                       ├─ receipt_issued_at   │                 │
│                       └─ rejection_reason    │                 │
│                                              │                 │
│                                              ↓                 │
│                          STORAGE/RECEIPTS    │                 │
│                          └─ RCP*.pdf ────────┘                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## User Authentication & Authorization

```
┌──────────────────────────────┐
│   UNAUTHENTICATED USER       │
└──────────────┬───────────────┘
               │ Login
               ↓
    ┌──────────────────────┐
    │  AUTHENTICATED USER  │
    └──────────┬───────────┘
               │
        ┌──────┴──────┐
        ↓             ↓
   ┌─────────┐   ┌──────────┐
   │ CLIENT  │   │CONTRACTOR│
   └────┬────┘   └─────┬────┘
        │              │
        │ Can access:  │ Can access:
        │ • View       │ • View pending
        │   invoices   │   approvals
        │ • Select     │ • Approve
        │   payment    │   payments
        │   method     │ • Reject
        │ • Submit     │   payments
        │   payment    │ • Generate
        │ • View       │   receipts
        │   submission │ • Download
        │   confirm    │   receipts
```

## API Endpoint Flows

```
CLIENT FLOW:

GET  /dashboard/client/invoices/{invoice}/payment-methods
     ↓ Returns available methods with Lipa Nos
    
GET  /dashboard/client/invoices/{invoice}/payment-submission/{method}
     ↓ Shows form for specific method
    
POST /dashboard/client/invoices/{invoice}/payment-submission
     ├─ Validate: payer_name, amount, method
     ├─ Create: PaymentSubmission record
     └─ Redirect to confirmation
    
GET  /dashboard/client/invoices/{invoice}/payment-submitted
     ↓ Show confirmation page


CONTRACTOR FLOW:

GET  /dashboard/contractor/pending-payment-approvals
     ↓ List all pending submissions
    
GET  /dashboard/contractor/payment-approvals/stats
     ↓ JSON: pending_count, approved_today, total_pending_amount
    
POST /payment-submissions/{id}/approve
     ├─ Generate receipt PDF
     ├─ Update invoice
     ├─ Update submission status
     └─ Return: success, receipt_url, receipt_number
    
POST /payment-submissions/{id}/reject
     ├─ Store rejection reason
     ├─ Update submission status
     └─ Return: success, message
    
GET  /payment-submissions/{id}/receipt/download
     └─ Return: PDF file download
```

## Example Scenario

```
SCENARIO: Partial Payment Workflow

Invoice Created:
├─ Invoice #INV-202406-001
├─ Total: TZS 100,000
├─ Status: sent
└─ amount_paid: 0

Client First Payment:
├─ Amount submitted: TZS 60,000
├─ Contractor approves
├─ Invoice updated:
│  ├─ amount_paid: 60,000
│  ├─ remaining_balance: 40,000
│  ├─ status: partially_paid
│  └─ Receipt #1 generated
└─ Receipt URL: /storage/receipts/RCP20260603*.pdf

One Week Later - Client Second Payment:
├─ Amount submitted: TZS 40,000
├─ Contractor approves
├─ Invoice updated:
│  ├─ amount_paid: 100,000
│  ├─ remaining_balance: 0
│  ├─ status: paid ✓
│  └─ paid_at: 2026-06-10 14:30:00
│  └─ Receipt #2 generated
└─ Client receives final receipt

Payment Timeline:
├─ 2026-06-01: Invoice created
├─ 2026-06-03: First payment submitted (pending)
├─ 2026-06-03: First payment approved
├─ 2026-06-03: Invoice status → partially_paid
├─ 2026-06-10: Second payment submitted (pending)
├─ 2026-06-10: Second payment approved
└─ 2026-06-10: Invoice status → paid ✓
```
