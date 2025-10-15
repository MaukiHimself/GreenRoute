# API Reference - Client-Contractor Linking System

## Base URL
```
http://your-domain.com/api
```

## Authentication
Currently, the API endpoints are unprotected. For production, add authentication middleware.

---

## 📌 Contractor-Client Linking

### Assign Contractor to Client
Link a contractor to a client using their registration numbers.

**Endpoint:** `POST /contractors/assign`

**Request Body:**
```json
{
  "contractor_registration_number": "CT789012",
  "client_registration_number": "CL123456"
}
```

**Success Response (200):**
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

---

### Unlink Contractor
Remove the contractor-client link.

**Endpoint:** `DELETE /contractors/{contractorRegistrationNumber}/unlink`

**Example:** `DELETE /contractors/CT789012/unlink`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Contractor successfully unlinked from client",
  "data": {
    "contractor_registration_number": "CT789012"
  }
}
```

---

### Get Contractor Assignment
Retrieve information about which client a contractor is assigned to.

**Endpoint:** `GET /contractors/{contractorRegistrationNumber}/assignment`

**Example:** `GET /contractors/CT789012/assignment`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Contractor assignment retrieved successfully",
  "data": {
    "contractor": {
      "registration_number": "CT789012",
      "company_name": "ABC Waste Services"
    },
    "assigned_client": {
      "registration_number": "CL123456",
      "name": "XYZ Corporation",
      "email": "contact@xyzcorp.com",
      "phone": "555-1234",
      "address": "123 Main St, New York, NY 10001"
    }
  }
}
```

---

### Get Client's Contractors
Get all contractors linked to a specific client.

**Endpoint:** `GET /clients/{clientRegistrationNumber}/contractors`

**Example:** `GET /clients/CL123456/contractors`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Client contractors retrieved successfully",
  "data": {
    "client": {
      "registration_number": "CL123456",
      "name": "XYZ Corporation"
    },
    "contractors": [
      {
        "id": 5,
        "registration_number": "CT789012",
        "company_name": "ABC Waste Services",
        "email": "abc@waste.com",
        "phone": "555-5678"
      }
    ],
    "count": 1
  }
}
```

---

## 📄 Invoice Management

### Create Invoice
Create a new invoice that will automatically appear in the client's portal.

**Endpoint:** `POST /invoices`

**Request Body:**
```json
{
  "contractor_registration_number": "CT789012",
  "client_registration_number": "CL123456",
  "invoice_date": "2025-10-13",
  "due_date": "2025-11-13",
  "service_type": "Waste Collection",
  "description": "Monthly waste collection services for October 2025",
  "subtotal": 500.00,
  "tax_rate": 10,
  "notes": "Payment due within 30 days"
}
```

**Fields:**
- `contractor_registration_number` (required): Contractor's registration number
- `client_registration_number` (optional): If not provided, uses contractor's assigned client
- `schedule_id` (optional): Link to an existing schedule
- `invoice_date` (required): Invoice issue date
- `due_date` (required): Payment due date
- `service_type` (required): Type of service
- `description` (optional): Detailed description
- `subtotal` (required): Amount before tax
- `tax_rate` (required): Tax percentage (0-100)
- `notes` (optional): Additional notes

**Success Response (201):**
```json
{
  "success": true,
  "message": "Invoice created successfully",
  "data": {
    "invoice": {
      "id": 100,
      "invoice_number": "INV-202510-0045",
      "contractor_id": 3,
      "client_id": 10,
      "contractor_registration_number": "CT789012",
      "client_registration_number": "CL123456",
      "invoice_date": "2025-10-13",
      "due_date": "2025-11-13",
      "status": "draft",
      "subtotal": "500.00",
      "tax_rate": "10.00",
      "tax_amount": "50.00",
      "total_amount": "550.00",
      "service_type": "Waste Collection",
      "description": "Monthly waste collection services for October 2025"
    }
  }
}
```

---

### Get Client's Invoices
Retrieve all invoices for a specific client.

**Endpoint:** `GET /clients/{clientRegistrationNumber}/invoices`

**Example:** `GET /clients/CL123456/invoices`

**Success Response (200):**
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
        "invoice_date": "2025-10-13",
        "due_date": "2025-11-13",
        "status": "draft",
        "total_amount": "550.00",
        "contractor": {
          "id": 3,
          "name": "John Doe",
          "email": "john@abcwaste.com"
        }
      }
    ],
    "count": 1,
    "total_amount": "550.00",
    "total_paid": "0.00",
    "total_outstanding": "550.00"
  }
}
```

---

### Get Contractor's Invoices
Retrieve all invoices created by a specific contractor.

**Endpoint:** `GET /contractors/{contractorRegistrationNumber}/invoices`

**Example:** `GET /contractors/CT789012/invoices`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Contractor invoices retrieved successfully",
  "data": {
    "contractor": {
      "registration_number": "CT789012",
      "company_name": "ABC Waste Services"
    },
    "invoices": [...],
    "count": 5,
    "total_amount": "2750.00",
    "total_paid": "1500.00",
    "total_outstanding": "1250.00"
  }
}
```

---

### Get Single Invoice
Retrieve details of a specific invoice.

**Endpoint:** `GET /invoices/{id}`

**Example:** `GET /invoices/100`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Invoice retrieved successfully",
  "data": {
    "invoice": {
      "id": 100,
      "invoice_number": "INV-202510-0045",
      "contractor_registration_number": "CT789012",
      "client_registration_number": "CL123456",
      "invoice_date": "2025-10-13",
      "due_date": "2025-11-13",
      "status": "draft",
      "subtotal": "500.00",
      "tax_rate": "10.00",
      "tax_amount": "50.00",
      "total_amount": "550.00",
      "amount_paid": "0.00",
      "client": {
        "id": 10,
        "name": "XYZ Corporation"
      },
      "contractor": {
        "id": 3,
        "name": "John Doe"
      }
    }
  }
}
```

---

### Update Invoice
Update an existing invoice.

**Endpoint:** `PUT /invoices/{id}` or `PATCH /invoices/{id}`

**Request Body (partial update allowed):**
```json
{
  "due_date": "2025-12-13",
  "subtotal": 600.00,
  "status": "sent",
  "notes": "Extended payment terms"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Invoice updated successfully",
  "data": {
    "invoice": {
      "id": 100,
      "due_date": "2025-12-13",
      "subtotal": "600.00",
      "total_amount": "660.00",
      "status": "sent"
    }
  }
}
```

---

### Mark Invoice as Paid
Mark an invoice as paid.

**Endpoint:** `POST /invoices/{id}/mark-paid`

**Request Body:**
```json
{
  "payment_method": "Bank Transfer"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Invoice marked as paid successfully",
  "data": {
    "invoice": {
      "id": 100,
      "status": "paid",
      "amount_paid": "550.00",
      "paid_at": "2025-10-13T14:30:00.000000Z",
      "payment_method": "Bank Transfer"
    }
  }
}
```

---

### Delete Invoice
Delete an invoice.

**Endpoint:** `DELETE /invoices/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Invoice deleted successfully"
}
```

---

## 📅 Schedule Management

### Create Schedule
Create a new schedule that will automatically appear in the client's portal.

**Endpoint:** `POST /schedules`

**Request Body:**
```json
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
  "status": "scheduled",
  "notes": "Morning pickup, use back entrance",
  "estimated_duration": 2.5,
  "total_volume": 15.5,
  "disposal_site": "Central Landfill",
  "disposal_type": "Municipal Waste",
  "disposal_notes": "Regular waste disposal"
}
```

**Fields:**
- `contractor_registration_number` (required): Contractor's registration number
- `client_registration_number` (optional): If not provided, uses contractor's assigned client
- `pickup_date` (required): Date of pickup
- `pickup_time` (required): Time of pickup (HH:MM format)
- `pickup_location` (required): Location name
- `pickup_address` (required): Street address
- `city` (required): City
- `state` (required): State
- `zip_code` (required): ZIP code
- `service_type` (required): "collection", "disposal", or "both"
- `status` (optional): "scheduled", "in_progress", "completed", "cancelled"
- `notes` (optional): Additional notes
- `estimated_duration` (optional): Duration in hours
- `total_volume` (optional): Volume in cubic yards
- `disposal_site` (optional): Disposal site name
- `disposal_type` (optional): Type of disposal
- `disposal_notes` (optional): Disposal notes

**Success Response (201):**
```json
{
  "success": true,
  "message": "Schedule created successfully",
  "data": {
    "schedule": {
      "id": 50,
      "contractor_id": 3,
      "client_id": 10,
      "contractor_registration_number": "CT789012",
      "client_registration_number": "CL123456",
      "pickup_date": "2025-10-15",
      "pickup_time": "09:00:00",
      "pickup_location": "Main Office",
      "pickup_address": "123 Main Street",
      "city": "New York",
      "state": "NY",
      "zip_code": "10001",
      "service_type": "collection",
      "status": "scheduled"
    }
  }
}
```

---

### Get Client's Schedules
Retrieve all schedules for a specific client.

**Endpoint:** `GET /clients/{clientRegistrationNumber}/schedules`

**Example:** `GET /clients/CL123456/schedules`

**Success Response (200):**
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

---

### Get Contractor's Schedules
Retrieve all schedules created by a specific contractor.

**Endpoint:** `GET /contractors/{contractorRegistrationNumber}/schedules`

**Example:** `GET /contractors/CT789012/schedules`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Contractor schedules retrieved successfully",
  "data": {
    "contractor": {
      "registration_number": "CT789012",
      "company_name": "ABC Waste Services"
    },
    "schedules": [...],
    "count": 8,
    "upcoming": 5,
    "completed": 3
  }
}
```

---

### Get Single Schedule
Retrieve details of a specific schedule.

**Endpoint:** `GET /schedules/{id}`

**Example:** `GET /schedules/50`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Schedule retrieved successfully",
  "data": {
    "schedule": {
      "id": 50,
      "pickup_date": "2025-10-15",
      "pickup_time": "09:00:00",
      "pickup_location": "Main Office",
      "pickup_address": "123 Main Street",
      "city": "New York",
      "state": "NY",
      "zip_code": "10001",
      "service_type": "collection",
      "status": "scheduled",
      "client": {...},
      "contractor": {...},
      "invoices": []
    }
  }
}
```

---

### Update Schedule
Update an existing schedule.

**Endpoint:** `PUT /schedules/{id}` or `PATCH /schedules/{id}`

**Request Body (partial update allowed):**
```json
{
  "pickup_date": "2025-10-16",
  "pickup_time": "10:00",
  "notes": "Rescheduled to next day"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Schedule updated successfully",
  "data": {
    "schedule": {
      "id": 50,
      "pickup_date": "2025-10-16",
      "pickup_time": "10:00:00"
    }
  }
}
```

---

### Update Schedule Status
Update only the status of a schedule.

**Endpoint:** `PATCH /schedules/{id}/status`

**Request Body:**
```json
{
  "status": "completed"
}
```

**Allowed Values:** "scheduled", "in_progress", "completed", "cancelled"

**Success Response (200):**
```json
{
  "success": true,
  "message": "Schedule status updated successfully",
  "data": {
    "schedule": {
      "id": 50,
      "status": "completed"
    }
  }
}
```

---

### Delete Schedule
Delete a schedule.

**Endpoint:** `DELETE /schedules/{id}`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Schedule deleted successfully"
}
```

---

## ⚠️ Error Responses

All endpoints follow a consistent error format:

**Validation Error (400):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "contractor_registration_number": [
      "The contractor registration number field is required."
    ]
  }
}
```

**Not Found (404):**
```json
{
  "success": false,
  "message": "Invoice not found",
  "error": "No query results for model [App\\Models\\Invoice] 999"
}
```

**Server Error (500):**
```json
{
  "success": false,
  "message": "Failed to create invoice",
  "error": "Database connection failed"
}
```

---

## 📊 Status Values

### Invoice Status
- `draft` - Invoice created but not sent
- `sent` - Invoice sent to client
- `paid` - Invoice fully paid
- `overdue` - Invoice past due date
- `cancelled` - Invoice cancelled

### Schedule Status
- `scheduled` - Schedule created and confirmed
- `in_progress` - Service currently being performed
- `completed` - Service completed
- `cancelled` - Schedule cancelled

---

## 🔧 Integration Examples

### JavaScript/Fetch
```javascript
// Create invoice
async function createInvoice(data) {
  const response = await fetch('/api/invoices', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
  });
  return await response.json();
}

// Get client invoices
async function getClientInvoices(clientRegNumber) {
  const response = await fetch(`/api/clients/${clientRegNumber}/invoices`);
  return await response.json();
}
```

### cURL
```bash
# Create invoice
curl -X POST http://your-domain.com/api/invoices \
  -H "Content-Type: application/json" \
  -d '{
    "contractor_registration_number": "CT789012",
    "invoice_date": "2025-10-13",
    "due_date": "2025-11-13",
    "service_type": "Waste Collection",
    "subtotal": 500.00,
    "tax_rate": 10
  }'

# Get client invoices
curl http://your-domain.com/api/clients/CL123456/invoices
```

### PHP/Laravel
```php
use Illuminate\Support\Facades\Http;

// Create invoice
$response = Http::post('http://your-domain.com/api/invoices', [
    'contractor_registration_number' => 'CT789012',
    'invoice_date' => '2025-10-13',
    'due_date' => '2025-11-13',
    'service_type' => 'Waste Collection',
    'subtotal' => 500.00,
    'tax_rate' => 10
]);

$data = $response->json();
```

---

**Last Updated:** October 13, 2025  
**API Version:** 1.0
