# User Workflow Guide - Client-Contractor System

## 🎯 Complete Registration & Linking Process

---

## PART 1: CONTRACTOR REGISTRATION & SETUP

### Step 1: Contractor Registration
**Location:** `/register` (Contractor option)

**What Happens:**
1. Contractor fills registration form:
   - Name
   - Email
   - Password
   - Company Name
   - Phone
   - Address
   - License Number
   - Vehicle details (optional)

2. **System Auto-Actions:**
   - ✅ Creates User account (user_type = 'contractor')
   - ✅ Creates Contractor profile
   - ✅ **AUTO-GENERATES** registration number (e.g., `CT440039`)
   - ✅ Sends email verification (if enabled)

**Result:** 
```
Contractor Created:
- User ID: 1
- Registration Number: CT440039 (AUTO-GENERATED)
- Status: Active
- Linked to Client: NONE (not yet)
```

### Step 2: Contractor Login
**Location:** `/login`

**Credentials:**
- Email: contractor@test.com
- Password: (their password)

**After Login, Contractor Sees:**
- Dashboard with their registration number displayed
- "Not assigned to any client" message
- Limited functionality until linked

---

## PART 2: CLIENT REGISTRATION

### Step 1: Client Registration
**Two Scenarios:**

#### Scenario A: Contractor Creates Client (Recommended)
**Location:** Contractor Dashboard → "Add Client"

**Process:**
1. Contractor logs in
2. Goes to "Clients" → "Add New Client"
3. Fills client form:
   - Client Name
   - Email
   - Phone
   - Address (City, State, ZIP)
   - Category (optional)
   - Status

4. **System Auto-Actions:**
   - ✅ Creates Client record
   - ✅ **AUTO-GENERATES** client registration number (e.g., `CL012230`)
   - ✅ **AUTO-LINKS** to contractor (`contractor_id` = contractor's user_id)
   - ✅ Optionally creates User account for client (if email provided)
   - ✅ Sends invitation email to client

**Result:**
```
Client Created:
- Registration Number: CL012230 (AUTO-GENERATED)
- Linked to Contractor: CT440039 (AUTOMATIC)
- Status: Active
- Can access portal: YES (if user account created)
```

#### Scenario B: Client Self-Registration (Alternative)
**Location:** `/register/client`

**Process:**
1. Client fills registration form
2. Provides contractor's registration number OR email
3. **System Auto-Actions:**
   - ✅ Creates Client record
   - ✅ **AUTO-GENERATES** registration number
   - ⚠️ **REQUIRES MANUAL LINKING** (see Part 3)

---

## PART 3: LINKING CONTRACTOR TO CLIENT

### Method 1: Automatic (When Contractor Creates Client)
✅ **RECOMMENDED** - No manual action needed!

When contractor creates a client through their dashboard:
- Client is automatically linked
- No additional steps required
- Client can immediately see contractor's invoices/schedules

### Method 2: Manual Admin Linking
**Location:** Admin Panel → "Link Contractor to Client"

**When Needed:**
- Client self-registered
- Contractor was added by admin
- Reassigning clients between contractors

**Process:**
1. Admin logs in
2. Goes to "Contractor Management"
3. Finds contractor (CT440039)
4. Clicks "Assign to Client"
5. Enters or selects client registration number (CL012230)
6. Clicks "Link"

**System Action:**
```php
$contractor->client_registration_number = 'CL012230';
$contractor->save();
```

### Method 3: API Linking (For Integrations)
**Endpoint:** `POST /api/contractors/assign`

**Request:**
```json
{
  "contractor_registration_number": "CT440039",
  "client_registration_number": "CL012230"
}
```

**When to Use:**
- Automated workflows
- Third-party integrations
- Bulk linking operations

---

## PART 4: CLIENT LOGIN & PORTAL ACCESS

### Step 1: Client Receives Invitation
**Email Contains:**
- Client registration number (CL012230)
- Login credentials (if account created)
- Portal link
- Welcome message from contractor

### Step 2: Client First Login
**Location:** `/login/client` or `/login`

**Credentials:**
- Email: (provided during registration)
- Password: (set by them or in invitation)

### Step 3: Client Dashboard View
**What Client Sees:**
- Their registration number (CL012230) displayed
- Linked contractor information (ABC Waste Services - CT440039)
- **All invoices** created by their contractor (automatic)
- **All schedules** created by their contractor (automatic)
- Real-time updates

**Example:**
```
Welcome, XYZ Corporation (CL012230)
Your Contractor: ABC Waste Services (CT440039)

📄 Invoices (1)
- INV-202510-0001: $550.00 (Due: Nov 13, 2025)

📅 Schedules (1)
- Oct 17, 2025 at 9:00 AM - Main Office
```

---

## PART 5: DAILY OPERATIONS WORKFLOW

### Contractor Creates Invoice

**Step 1:** Contractor logs in
**Step 2:** Goes to "Invoices" → "Create New"
**Step 3:** Fills invoice form:
- Select client (XYZ Corporation - CL012230)
- Invoice date
- Due date
- Service type
- Amount
- Tax rate

**Step 4:** System Auto-Actions:
```php
✅ AUTO-POPULATES contractor_registration_number: CT440039
✅ AUTO-POPULATES client_registration_number: CL012230
✅ Generates invoice number: INV-202510-0002
✅ Calculates totals
✅ Saves to database
```

**Step 5:** Result:
- Invoice appears **INSTANTLY** in client's portal
- No manual notification needed
- Client sees it next time they log in

### Contractor Creates Schedule

**Step 1:** Contractor logs in
**Step 2:** Goes to "Schedules" → "Create New"
**Step 3:** Fills schedule form:
- Select client (XYZ Corporation - CL012230)
- Pickup date & time
- Location
- Service type
- Notes

**Step 4:** System Auto-Actions:
```php
✅ AUTO-POPULATES contractor_registration_number: CT440039
✅ AUTO-POPULATES client_registration_number: CL012230
✅ Saves to database
```

**Step 5:** Result:
- Schedule appears **INSTANTLY** in client's portal
- Client can view pickup details
- Real-time visibility

---

## PART 6: WHAT TO DO MANUALLY vs AUTOMATIC

### ✅ AUTOMATIC (No Manual Action Needed)

1. **Registration Number Generation**
   - Client: `CL######` auto-generated on creation
   - Contractor: `CT######` auto-generated on creation
   - ❌ DO NOT manually set these

2. **Linking (When Contractor Creates Client)**
   - Automatic `contractor_id` assignment
   - ❌ DO NOT manually link if using contractor's "Add Client" feature

3. **Invoice/Schedule Visibility**
   - Client automatically sees contractor's items
   - Filtered by registration numbers
   - ❌ DO NOT manually notify clients

4. **Invoice Number Generation**
   - Auto-format: `INV-YYYYMM-####`
   - ❌ DO NOT manually set invoice numbers

5. **Total Calculations**
   - Subtotal + Tax = Total
   - Auto-calculated on save
   - ❌ DO NOT manually calculate

### ⚠️ MANUAL ACTIONS REQUIRED

1. **Initial Contractor-Client Linking (If Client Self-Registered)**
   - Use Admin panel or API
   - Set contractor's `client_registration_number`
   - ✅ DO THIS once per contractor-client pair

2. **User Account Creation**
   - If client needs portal access
   - Create User with user_type = 'client'
   - Link to Client record via `user_id`

3. **Email Invitations**
   - Send welcome emails to clients
   - Include registration number and login details

4. **Reassigning Clients**
   - If client switches contractors
   - Update `client_registration_number` on new contractor
   - Update existing invoices/schedules (optional)

### ❌ DO NOT DO MANUALLY

1. **Never manually set registration numbers**
   - System generates unique numbers
   - Manual entry can cause duplicates

2. **Never manually set invoice numbers**
   - System ensures sequential numbering
   - Manual entry breaks sequence

3. **Never manually copy invoices/schedules to client**
   - System automatically filters by registration number
   - Manual copying creates duplicates

4. **Never update both contractor_id AND registration numbers**
   - Use registration numbers for new workflow
   - Keep contractor_id/client_id for legacy support

---

## PART 7: TROUBLESHOOTING COMMON ISSUES

### Issue 1: Client Can't See Contractor's Invoices

**Diagnosis:**
```php
// Check in tinker
$client = App\Models\Client::where('registration_number', 'CL012230')->first();
$contractor = App\Models\Contractor::where('registration_number', 'CT440039')->first();

// Check if linked
echo $contractor->client_registration_number; // Should show CL012230
```

**Fix:**
```php
// Link them
$contractor->client_registration_number = $client->registration_number;
$contractor->save();
```

### Issue 2: Registration Number Not Generated

**Diagnosis:**
```php
$contractor = App\Models\Contractor::find(1);
echo $contractor->registration_number; // Should show CT######
```

**Fix:**
```php
// Trigger regeneration
$contractor->save();
echo $contractor->registration_number;
```

### Issue 3: Invoice Created But Not Visible to Client

**Diagnosis:**
```php
$invoice = App\Models\Invoice::find(1);
echo "Contractor Reg: " . $invoice->contractor_registration_number . "\n";
echo "Client Reg: " . $invoice->client_registration_number . "\n";
```

**Fix:**
```php
// Update invoice with correct registration numbers
$invoice->contractor_registration_number = 'CT440039';
$invoice->client_registration_number = 'CL012230';
$invoice->save();
```

---

## PART 8: UI BEST PRACTICES

### For Contractor Dashboard

**DO:**
- ✅ Display contractor's registration number prominently
- ✅ Show linked client(s) clearly
- ✅ Auto-populate registration numbers in forms
- ✅ Provide "Add Client" button that auto-links
- ✅ Show client's registration number in dropdown/select

**DON'T:**
- ❌ Allow manual entry of registration numbers
- ❌ Show raw IDs to users
- ❌ Let contractor change their own registration number

### For Client Portal

**DO:**
- ✅ Display client's registration number in header
- ✅ Show contractor information clearly
- ✅ Auto-refresh invoice/schedule lists
- ✅ Filter by client_registration_number automatically
- ✅ Show real-time counts (X invoices, Y schedules)

**DON'T:**
- ❌ Allow client to see other clients' data
- ❌ Show registration numbers as input fields
- ❌ Expose API endpoints without authentication

### For Admin Panel

**DO:**
- ✅ Provide "Link Contractor to Client" interface
- ✅ Show both registration numbers when linking
- ✅ Allow searching by registration number
- ✅ Display linking history/audit log
- ✅ Provide "Unlink" functionality

**DON'T:**
- ❌ Allow changing registration numbers after creation
- ❌ Bulk operations without confirmation
- ❌ Delete linked records without cascade handling

---

## PART 9: RECOMMENDED UI WORKFLOW

### Contractor's First-Time Setup

```
1. Register → Auto-assigned CT440039
2. Login → See dashboard
3. Click "Add Client" button
4. Fill client form
5. Submit → Client auto-linked
6. Client receives invitation email
7. Client logs in → Sees contractor's data immediately
```

### Daily Contractor Operations

```
1. Login
2. Create Invoice:
   - Select client from dropdown (shows: "XYZ Corp - CL012230")
   - System auto-fills registration numbers
   - Fill amounts
   - Submit
   - Invoice appears in client's portal INSTANTLY
   
3. Create Schedule:
   - Select client from dropdown
   - System auto-fills registration numbers
   - Set date/time
   - Submit
   - Schedule appears in client's portal INSTANTLY
```

### Client's Experience

```
1. Receive invitation email with credentials
2. Login to portal
3. Dashboard shows:
   - "Your Registration: CL012230"
   - "Contractor: ABC Waste Services (CT440039)"
   - Invoice list (auto-populated)
   - Schedule list (auto-populated)
4. View/download invoices
5. See upcoming schedules
6. No manual refresh needed - data always current
```

---

## PART 10: SECURITY CONSIDERATIONS

### DO in UI:

1. **Client Portal Access**
   - ✅ Only show data matching client's registration number
   - ✅ Filter all queries by `client_registration_number`
   - ✅ Validate user owns the registration number

2. **Contractor Dashboard**
   - ✅ Only allow creating items for linked clients
   - ✅ Validate contractor owns the registration number
   - ✅ Check contractor is assigned to client before creating items

3. **API Endpoints**
   - ✅ Add authentication middleware
   - ✅ Validate registration numbers exist
   - ✅ Check user permissions before operations

### DON'T in UI:

1. ❌ Expose registration numbers in URLs (use IDs instead)
2. ❌ Allow client to change their registration number
3. ❌ Show other clients' registration numbers
4. ❌ Allow contractor to link to clients without verification
5. ❌ Trust client-side filtering - always filter server-side

---

## SUMMARY: Manual vs Automatic Actions

| Action | Automatic | Manual | Notes |
|--------|-----------|--------|-------|
| Generate Contractor Reg # | ✅ | ❌ | Auto on creation |
| Generate Client Reg # | ✅ | ❌ | Auto on creation |
| Link when contractor creates client | ✅ | ❌ | Fully automatic |
| Link when client self-registers | ❌ | ✅ | Admin must link |
| Invoice visibility to client | ✅ | ❌ | Instant via reg numbers |
| Schedule visibility to client | ✅ | ❌ | Instant via reg numbers |
| Generate invoice number | ✅ | ❌ | Auto-sequential |
| Calculate invoice totals | ✅ | ❌ | Auto on save |
| Send email invitations | ❌ | ✅ | Admin/Contractor action |
| User account creation | ⚠️ | ⚠️ | Can be either |

**Legend:**
- ✅ Automatic - System handles it
- ❌ Manual - Requires human action
- ⚠️ Either - Depends on workflow

---

## Quick Reference Commands

### Check Linking Status
```php
php artisan tinker
$contractor = App\Models\Contractor::where('registration_number', 'CT440039')->first();
echo "Assigned to: " . ($contractor->client_registration_number ?? 'NONE');
```

### Link Contractor to Client
```php
$contractor = App\Models\Contractor::where('registration_number', 'CT440039')->first();
$contractor->client_registration_number = 'CL012230';
$contractor->save();
```

### Verify Client Can See Invoices
```php
$invoices = App\Models\Invoice::forClient('CL012230')->get();
echo "Client has " . $invoices->count() . " invoices";
```

### Test API Endpoint
```bash
curl http://localhost:8000/api/clients/CL012230/invoices
```

---

**Created:** October 14, 2025  
**System Version:** 1.0  
**For:** AFIA-ORBIT Client-Contractor Linking System
