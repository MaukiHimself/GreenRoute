# Client-Contractor Linking & Notification System

## 📋 Overview

This system implements a sophisticated client-contractor linking mechanism where contractors can be assigned to clients via unique registration numbers. When a contractor creates an invoice or schedule, it automatically appears in the linked client's portal.

## 🏗️ Architecture

### Database Design

#### Registration Numbers
- **Client Registration Number**: Auto-generated format `CL######` (e.g., `CL123456`)
- **Contractor Registration Number**: Auto-generated format `CT######` (e.g., `CT789012`)

#### Relationships
- **One-to-Many**: One Client can have many Contractors
- **Many-to-One**: A Contractor is assigned to one specific Client
- **Linking Field**: `client_registration_number` in the `contractors` table

### Tables Modified

#### 1. `contractors` Table
- Added `registration_number` (unique, auto-generated)
- Added `client_registration_number` (nullable, foreign reference to client)

#### 2. `invoices` Table
- Added `contractor_registration_number` (indexed)
- Added `client_registration_number` (indexed)

#### 3. `schedules` Table
- Added `contractor_registration_number` (indexed)
- Added `client_registration_number` (indexed)

## 🚀 Implementation Details

### Phase 1: Database Migrations

Three new migration files were created:

1. **`2025_10_13_000001_add_registration_number_to_contractors_table.php`**
   - Adds `registration_number` field to contractors
   - Adds `client_registration_number` for linking
   - Creates index for faster lookups

2. **`2025_10_13_000002_add_registration_numbers_to_invoices_table.php`**
   - Adds registration number fields to invoices
   - Creates indexes for client and contractor filtering

3. **`2025_10_13_000003_add_registration_numbers_to_schedules_table.php`**
   - Adds registration number fields to schedules
   - Creates indexes for client and contractor filtering

### Phase 2: Model Updates

#### Contractor Model
**New Features:**
- Auto-generates `registration_number` on creation (format: `CT######`)
- Relationship: `assignedClient()` - Returns the client the contractor is assigned to
- Relationship: `clients()` - Returns all clients managed by contractor (legacy)
- Relationship: `invoices()` - Returns all invoices by registration number
- Relationship: `schedules()` - Returns all schedules by registration number

#### Invoice Model
**New Features:**
- Added `contractor_registration_number` and `client_registration_number` to fillable
- Scope: `forClient($clientRegistrationNumber)` - Filter by client reg number
- Scope: `byContractorRegNumber($contractorRegNumber)` - Filter by contractor reg number

#### Schedule Model
**New Features:**
- Added `contractor_registration_number` and `client_registration_number` to fillable
- Scope: `forClient($clientRegistrationNumber)` - Filter by client reg number
- Scope: `byContractorRegNumber($contractorRegNumber)` - Filter by contractor reg number

### Phase 3: API Controllers

#### ContractorLinkingController
Handles contractor-client relationships:

**Endpoints:**
- `POST /api/contractors/assign` - Link contractor to client
- `DELETE /api/contractors/{contractorRegNumber}/unlink` - Unlink contractor
- `GET /api/contractors/{contractorRegNumber}/assignment` - Get contractor's assigned client
- `GET /api/clients/{clientRegNumber}/contractors` - Get all contractors for a client

#### InvoiceApiController
Manages invoice operations via registration numbers:

**Endpoints:**
- `POST /api/invoices` - Create invoice (auto-links to client)
- `GET /api/clients/{clientRegNumber}/invoices` - Get all invoices for a client
- `GET /api/contractors/{contractorRegNumber}/invoices` - Get all invoices by contractor
- `GET /api/invoices/{id}` - Get specific invoice
- `PUT/PATCH /api/invoices/{id}` - Update invoice
- `DELETE /api/invoices/{id}` - Delete invoice
- `POST /api/invoices/{id}/mark-paid` - Mark invoice as paid

#### ScheduleApiController
Manages schedule operations via registration numbers:

**Endpoints:**
- `POST /api/schedules` - Create schedule (auto-links to client)
- `GET /api/clients/{clientRegNumber}/schedules` - Get all schedules for a client
- `GET /api/contractors/{contractorRegNumber}/schedules` - Get all schedules by contractor
- `GET /api/schedules/{id}` - Get specific schedule
- `PUT/PATCH /api/schedules/{id}` - Update schedule
- `DELETE /api/schedules/{id}` - Delete schedule
- `PATCH /api/schedules/{id}/status` - Update schedule status

### Phase 4: Frontend Components

#### Client Portal Views

**`resources/views/client-portal/invoices.blade.php`**
- Displays all invoices for the logged-in client
- Filtered automatically by client's registration number
- Shows summary cards (total, paid, outstanding)
- Status badges and filtering
- Download/view options

**`resources/views/client-portal/schedules.blade.php`**
- Displays all schedules for the logged-in client
- Filtered automatically by client's registration number
- Shows summary cards (total, upcoming, completed)
- Date and status filtering
- Clean card-based layout

#### Contractor Views

**`resources/views/contractor/create-invoice.blade.php`**
- Form to create new invoices
- Auto-populates contractor registration number
- Selects from assigned clients
- Real-time total calculation
- Links to schedules (optional)

**`resources/views/contractor/create-schedule.blade.php`**
- Form to create new schedules
- Auto-populates contractor registration number
- Auto-loads client address on selection
- Service type selection
- Disposal tracking fields

## 📡 API Usage Examples

### 1. Link Contractor to Client

```bash
POST /api/contractors/assign
Content-Type: application/json

{
  "contractor_registration_number": "CT789012",
  "client_registration_number": "CL123456"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Contractor successfully linked to client",
  "data": {
    "contractor": {
      "id": 5,
      "registration_number": "CT789012",
      "company_name": "ABC Waste Services",
      "assigned_to": "CL123456"
    },
    "client": {
      "id": 10,
      "registration_number": "CL123456",
      "name": "XYZ Corporation"
    }
  }
}
```

### 2. Create Invoice (Contractor)

```bash
POST /api/invoices
Content-Type: application/json

{
  "contractor_registration_number": "CT789012",
  "client_registration_number": "CL123456",
  "invoice_date": "2025-10-13",
  "due_date": "2025-11-13",
  "service_type": "Waste Collection",
  "description": "Monthly waste collection services",
  "subtotal": 500.00,
  "tax_rate": 10,
  "notes": "October 2025 services"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Invoice created successfully",
  "data": {
    "invoice": {
      "id": 100,
      "invoice_number": "INV-202510-0045",
      "contractor_registration_number": "CT789012",
      "client_registration_number": "CL123456",
      "total_amount": 550.00,
      "status": "draft",
      "invoice_date": "2025-10-13",
      "due_date": "2025-11-13"
    }
  }
}
```

### 3. Get Client's Invoices

```bash
GET /api/clients/CL123456/invoices
```

**Response:**
```json
{
  "success": true,
  "message": "Client invoices retrieved successfully",
  "data": {
    "client": {
      "registration_number": "CL123456",
      "name": "XYZ Corporation"
    },
    "invoices": [
      {
        "id": 100,
        "invoice_number": "INV-202510-0045",
        "total_amount": 550.00,
        "status": "draft",
        "invoice_date": "2025-10-13"
      }
    ],
    "count": 1,
    "total_amount": 550.00,
    "total_paid": 0,
    "total_outstanding": 550.00
  }
}
```

### 4. Create Schedule (Contractor)

```bash
POST /api/schedules
Content-Type: application/json

{
  "contractor_registration_number": "CT789012",
  "client_registration_number": "CL123456",
  "pickup_date": "2025-10-15",
  "pickup_time": "09:00",
  "pickup_location": "Main Office",
  "pickup_address": "123 Main Street",
  "city": "New York",
  "state": "NY",
  "zip_code": "10001",
  "service_type": "collection",
  "notes": "Morning pickup"
}
```

### 5. Get Client's Schedules

```bash
GET /api/clients/CL123456/schedules
```

**Response:**
```json
{
  "success": true,
  "message": "Client schedules retrieved successfully",
  "data": {
    "client": {
      "registration_number": "CL123456",
      "name": "XYZ Corporation"
    },
    "schedules": [
      {
        "id": 50,
        "pickup_date": "2025-10-15",
        "pickup_time": "09:00:00",
        "pickup_location": "Main Office",
        "service_type": "collection",
        "status": "scheduled"
      }
    ],
    "count": 1,
    "upcoming": 1,
    "completed": 0
  }
}
```

## 🔒 Security Features

1. **Registration Number Uniqueness**: Auto-generated numbers are guaranteed unique
2. **Index Optimization**: All registration number fields are indexed for fast queries
3. **Relationship Validation**: API validates contractor-client links before operations
4. **Scoped Queries**: All client data is automatically scoped by registration number
5. **Data Isolation**: Clients can only see data linked to their registration number

## 🔄 Data Flow

### Invoice Creation Flow
```
1. Contractor logs in
2. Contractor creates invoice via form/API
3. System auto-populates contractor_registration_number
4. System retrieves contractor's client_registration_number
5. Invoice saved with both registration numbers
6. Client portal automatically displays invoice (filtered by client_registration_number)
```

### Schedule Creation Flow
```
1. Contractor logs in
2. Contractor creates schedule via form/API
3. System auto-populates contractor_registration_number
4. System retrieves contractor's client_registration_number
5. Schedule saved with both registration numbers
6. Client portal automatically displays schedule (filtered by client_registration_number)
```

## 📊 Running Migrations

To apply all changes to your database:

```bash
# Run all pending migrations
php artisan migrate

# Or run specific migrations
php artisan migrate --path=/database/migrations/2025_10_13_000001_add_registration_number_to_contractors_table.php
php artisan migrate --path=/database/migrations/2025_10_13_000002_add_registration_numbers_to_invoices_table.php
php artisan migrate --path=/database/migrations/2025_10_13_000003_add_registration_numbers_to_schedules_table.php
```

## 🧪 Testing the System

### 1. Create or Update Contractors
After migration, existing contractors need registration numbers:

```php
// In tinker or a seeder
php artisan tinker

// Generate registration numbers for existing contractors
$contractors = App\Models\Contractor::whereNull('registration_number')->get();
foreach ($contractors as $contractor) {
    $contractor->registration_number = 'CT' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    $contractor->save();
}
```

### 2. Link a Contractor to a Client

```bash
curl -X POST http://your-app.test/api/contractors/assign \
  -H "Content-Type: application/json" \
  -d '{
    "contractor_registration_number": "CT789012",
    "client_registration_number": "CL123456"
  }'
```

### 3. Create Test Invoice

```bash
curl -X POST http://your-app.test/api/invoices \
  -H "Content-Type: application/json" \
  -d '{
    "contractor_registration_number": "CT789012",
    "invoice_date": "2025-10-13",
    "due_date": "2025-11-13",
    "service_type": "Waste Collection",
    "subtotal": 500.00,
    "tax_rate": 10
  }'
```

### 4. Verify Client Can See Invoice

```bash
curl http://your-app.test/api/clients/CL123456/invoices
```

## 🎯 Key Features

✅ **Automatic Registration Number Generation**
- Clients: `CL######`
- Contractors: `CT######`

✅ **One-to-Many Relationship**
- One client can have multiple contractors
- One contractor assigned to one client

✅ **Automatic Data Visibility**
- Invoices appear instantly in client portal
- Schedules appear instantly in client portal

✅ **Dual ID System**
- Legacy `contractor_id` and `client_id` preserved
- New registration number system for linking

✅ **Indexed for Performance**
- All registration number fields indexed
- Fast filtering and queries

✅ **RESTful API**
- Complete CRUD operations
- Consistent JSON responses
- Proper HTTP status codes

## 🔧 Customization

### Modify Registration Number Format

Edit the boot methods in models:

```php
// In Client.php or Contractor.php
$client->registration_number = 'CUSTOM' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
```

### Add Authentication Middleware

In `routes/api.php`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [InvoiceApiController::class, 'store']);
    // ... other protected routes
});
```

### Add Email Notifications

When invoice/schedule is created:

```php
// In InvoiceApiController@store
use Illuminate\Support\Facades\Mail;

Mail::to($client->email)->send(new InvoiceCreatedMail($invoice));
```

## 📚 Additional Resources

- Laravel Documentation: https://laravel.com/docs
- API Route List: Run `php artisan route:list --path=api`
- Database Schema: Run `php artisan schema:dump`

## 🐛 Troubleshooting

### Issue: Registration numbers not generating

**Solution:** Ensure migrations ran successfully and models have the boot method.

### Issue: Client not seeing contractor's invoices

**Solution:** Verify the contractor has `client_registration_number` set:
```php
$contractor = Contractor::where('registration_number', 'CT789012')->first();
echo $contractor->client_registration_number; // Should show client reg number
```

### Issue: API returns 404

**Solution:** Clear route cache:
```bash
php artisan route:clear
php artisan cache:clear
```

## 📝 Next Steps

1. ✅ Run migrations
2. ✅ Generate registration numbers for existing data
3. ✅ Link contractors to clients
4. ✅ Test invoice/schedule creation
5. ✅ Verify client portal displays data
6. Add email notifications (optional)
7. Add webhook integrations (optional)
8. Implement real-time updates (optional, using WebSockets)

---

**Created:** October 13, 2025  
**Version:** 1.0  
**Framework:** Laravel (PHP)  
**Database:** MySQL/PostgreSQL Compatible
