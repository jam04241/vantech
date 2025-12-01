# Services Module Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    SERVICES MODULE (Frontend)                     │
│                                                                   │
│  ┌──────────────────┐                ┌──────────────────────┐   │
│  │   CardServices   │                │  FormServices        │   │
│  │  (Left Panel)    │                │  (Right Panel)       │   │
│  │                  │                │                      │   │
│  │ • Service Cards  │    Selection   │ • Customer Name      │   │
│  │ • Scrollable     │◄──────────────►│ • Service Type       │   │
│  │ • Clickable      │                │ • Description        │   │
│  │ • Filterable     │                │ • Status             │   │
│  │                  │                │ • Priority           │   │
│  │ Search & Filter  │                │ • Action Buttons     │   │
│  │ • Search Bar     │                │                      │   │
│  │ • Status Filter  │                │ Form Handling        │   │
│  │ • Type Filter    │                │ • Submit             │   │
│  │ • Refresh Button │                │ • Reset              │   │
│  │                  │                │ • Delete             │   │
│  └──────────────────┘                └──────────────────────┘   │
│          │                                        │              │
└──────────┼────────────────────────────────────────┼──────────────┘
           │ JavaScript Fetch                       │
           │ JSON Communication                     │
           │                                        │
           ▼                                        ▼
┌─────────────────────────────────────────────────────────────────┐
│              LARAVEL API ENDPOINTS (Backend)                      │
│                                                                   │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ ServicesController                                          │  │
│  │                                                             │  │
│  │ • index()          → GET all services                      │  │
│  │ • store()          → POST create service                   │  │
│  │ • show()           → GET specific service                  │  │
│  │ • update()         → PUT update service                    │  │
│  │ • destroy()        → DELETE service                        │  │
│  │ • getServicesList()→ GET with filters                      │  │
│  │ • getStatistics()  → GET service stats                     │  │
│  │                                                             │  │
│  │ Routes:                                                     │  │
│  │ /api/services          (GET/POST)                          │  │
│  │ /api/services/{id}     (GET/PUT/DELETE)                    │  │
│  │ /api/services/list     (GET with filters)                  │  │
│  │ /api/services/stats    (GET statistics)                    │  │
│  └────────────────────────────────────────────────────────────┘  │
│                           │                                      │
└───────────────────────────┼──────────────────────────────────────┘
                            │ Eloquent ORM
                            │ Business Logic
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                  DATA LAYER (Models)                              │
│                                                                   │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Service Model                                               │  │
│  │                                                             │  │
│  │ • Fillable: customer_name, service_type, description,      │  │
│  │   status, priority, user_id                                │  │
│  │ • Relationships: belongsTo(User)                           │  │
│  │ • Scopes: active(), completed()                            │  │
│  │ • Attributes: statusBadge, priorityColor                   │  │
│  │                                                             │  │
│  │ Validation:                                                │  │
│  │ • customer_name: required|string|max:255                   │  │
│  │ • service_type: enum values                                │  │
│  │ • description: required|string                             │  │
│  │ • status: enum values                                      │  │
│  │ • priority: enum values                                    │  │
│  └────────────────────────────────────────────────────────────┘  │
│                           │                                      │
└───────────────────────────┼──────────────────────────────────────┘
                            │ Query Builder
                            │ Migrations
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│              DATABASE LAYER (SQL Server)                          │
│                                                                   │
│  ┌──────────────────┐           ┌─────────────────────────────┐  │
│  │ services table   │           │ services_audit_log table    │  │
│  │                  │           │                             │  │
│  │ • id (PK)        │           │ • audit_id (PK)            │  │
│  │ • customer_name  │           │ • service_id (FK)          │  │
│  │ • service_type   │           │ • action (INSERT/UPDATE/   │  │
│  │ • description    │─Audit────►│   DELETE)                  │  │
│  │ • status         │  Triggers │ • old_values (JSON)        │  │
│  │ • priority       │           │ • new_values (JSON)        │  │
│  │ • user_id (FK)   │           │ • changed_by               │  │
│  │ • created_at     │           │ • changed_at               │  │
│  │ • updated_at     │           │ • affected_columns         │  │
│  │                  │           │                             │  │
│  │ Indexes:         │           │ Indexes:                    │  │
│  │ • customer_name  │           │ • service_id               │  │
│  │ • service_type   │           │ • action                   │  │
│  │ • status         │           │ • changed_at               │  │
│  │ • priority       │           └─────────────────────────────┘  │
│  │ • created_at     │                                            │
│  └──────────────────┘                                            │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────────┐  │
│  │ Audit Triggers & Stored Procedures                          │  │
│  │                                                             │  │
│  │ • tr_services_insert    → Logs new services               │  │
│  │ • tr_services_update    → Logs service changes            │  │
│  │ • tr_services_delete    → Logs deleted services           │  │
│  │                                                             │  │
│  │ • sp_get_services_audit_log()    → Retrieve audit logs    │  │
│  │ • sp_get_audit_summary()         → Summary statistics     │  │
│  │ • sp_purge_old_audit_logs()      → Maintenance routine    │  │
│  └─────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow Diagram

### Create Service Flow
```
User Form Input
      │
      ▼
Form Validation (Client-side)
      │
      ▼
Fetch POST /api/services {data}
      │
      ▼
ServicesController::store()
      │
      ├─ Validate Input (Server-side)
      ├─ Create Service Model
      ├─ Save to Database
      │  └─ SQL Server INSERT Trigger
      │     └─ Log to services_audit_log
      │
      ▼
Return JSON Response
      │
      ▼
Frontend Success Handler
      │
      ├─ Show Success Notification (SweetAlert)
      ├─ Reload Services List
      ├─ Reset Form
      │
      ▼
User Sees New Card
```

### Update Service Flow
```
User Clicks Service Card
      │
      ▼
Select Service Handler
      │
      ├─ Highlight Card
      ├─ Populate Form with Data
      ├─ Show Delete Button
      │
User Modifies Form & Clicks Save
      │
      ▼
Form Validation (Client-side)
      │
      ▼
Fetch PUT /api/services/{id} {data}
      │
      ▼
ServicesController::update()
      │
      ├─ Validate Input (Server-side)
      ├─ Update Service Model
      ├─ Save to Database
      │  └─ SQL Server UPDATE Trigger
      │     └─ Log old_values & new_values to services_audit_log
      │
      ▼
Return JSON Response
      │
      ▼
Frontend Success Handler
      │
      ├─ Show Success Notification
      ├─ Reload Services List
      │
      ▼
User Sees Updated Card
```

### Delete Service Flow
```
User Clicks Delete Button
      │
      ▼
Confirmation Dialog (SweetAlert)
      │
User Confirms
      │
      ▼
Fetch DELETE /api/services/{id}
      │
      ▼
ServicesController::destroy()
      │
      ├─ Find Service
      ├─ Delete Service
      │  └─ SQL Server DELETE Trigger
      │     └─ Log to services_audit_log
      │
      ▼
Return JSON Response
      │
      ▼
Frontend Success Handler
      │
      ├─ Show Success Notification
      ├─ Reload Services List
      ├─ Reset Form
      ├─ Hide Delete Button
      │
      ▼
User Sees Service Removed
```

### Search & Filter Flow
```
User Types Search Term
      │
      ▼
Input Event Triggered
      │
      ▼
JavaScript Filter Logic
      │
      ├─ Get Search Term
      ├─ Get Selected Filters
      ├─ Iterate Through All Cards
      ├─ Check Against Criteria
      │
      ▼
Update Card Display
      │
      ├─ Hide Non-matching Cards
      ├─ Show Matching Cards
      │
      ▼
User Sees Filtered Results
```

## Component Relationship Diagram

```
┌─────────────────────────────────────────────────────────┐
│         Services.blade.php (Main Container)             │
│                                                         │
│ ┌──────────────────────┐      ┌──────────────────────┐  │
│ │ CardServices Partial │      │FormServices Partial  │  │
│ │                      │      │                      │  │
│ │ • Service Cards      │      │ • Input Fields       │  │
│ │ • Search & Filter UI │      │ • Form Controls      │  │
│ │ • Container          │      │ • Action Buttons     │  │
│ └──────────────────────┘      └──────────────────────┘  │
│         ▲                              ▲                 │
│         │                              │                 │
│         └──────────┬────────────────────┘                 │
│                    │                                     │
│         JavaScript Event Handlers                        │
│         • loadServices()                                │
│         • filterServices()                              │
│         • selectService()                               │
│         • submitService()                               │
│         • deleteService()                               │
│         • displayServices()                             │
│                    │                                     │
└────────────────────┼─────────────────────────────────────┘
                     │
         API Calls (fetch)
                     │
          ┌──────────┴──────────┐
          │                     │
          ▼                     ▼
    GET /api/services   POST/PUT/DELETE /api/services/{id}
          │                     │
          └──────────┬──────────┘
                     │
          ServicesController
```

## Database Schema Relationships

```
┌─────────────────┐              ┌─────────────────┐
│  users table    │              │ services table  │
│                 │              │                 │
│ • id (PK)   ────┼──────────────┼► user_id (FK)   │
│ • name      │   │              │ • id (PK)       │
│ • email     │   │              │ • customer_name │
│ • ...       │   │              │ • service_type  │
│             │   │              │ • description   │
└─────────────┘   │              │ • status        │
                  │              │ • priority      │
                  │              │ • created_at    │
                  │              │ • updated_at    │
                  │              └─────────────────┘
                  │                    │
                  │                    │ (1 service creates many audit logs)
                  │                    │
                  │              ┌─────────────────────┐
                  │              │services_audit_log   │
                  │              │                     │
                  │              │ • audit_id (PK)     │
                  │              │ • service_id (FK)   │
                  │              │ • action            │
                  │              │ • old_values        │
                  │              │ • new_values        │
                  │              │ • changed_by        │
                  │              │ • changed_at        │
                  │              │ • affected_columns  │
                  │              └─────────────────────┘
                  │
```

## API Response Structure

```json
{
  "success": true|false,
  "message": "Operation message",
  "data": {
    "id": 1,
    "customer_name": "John Doe",
    "service_type": "Hardware Repair",
    "description": "Laptop repair needed",
    "status": "Pending",
    "priority": "High",
    "user_id": 1,
    "created_at": "2024-01-17T10:30:00Z",
    "updated_at": "2024-01-17T10:30:00Z"
  },
  "errors": {} // If validation fails
}
```

## Security Flow

```
User Request
    │
    ▼
Middleware Check
    │
    ├─ CSRF Token Validation
    ├─ Authentication Check
    ├─ Authorization Check
    │
User NOT Authenticated/Authorized
    │
    ▼
    Return 401/403 Error
    
User IS Authenticated/Authorized
    │
    ▼
Input Validation
    │
    ├─ Required Fields Check
    ├─ Enum Value Check
    ├─ Data Type Check
    ├─ Max Length Check
    │
    Invalid Data
    │
    ▼
    Return 422 Validation Error
    
    Valid Data
    │
    ▼
Database Operation
    │
    ├─ Execute Query
    ├─ Trigger Audit Log
    ├─ Return Result
    │
    ▼
Audit Trail Created
```

---

## Performance Optimization

### Database Indexes
- `customer_name` - Fast search queries
- `service_type` - Efficient filtering
- `status` - Quick status-based queries
- `priority` - Priority sorting
- `created_at` - Date range filtering

### Frontend Optimization
- Lazy loading of service cards
- Pagination ready (can be added)
- Real-time filtering without server calls
- Efficient DOM updates

### Backend Optimization
- Eloquent query optimization
- Index-based filtering
- Audit log index pruning capability
- Connection pooling

---

This architecture ensures scalability, maintainability, and full auditability of all service operations.
