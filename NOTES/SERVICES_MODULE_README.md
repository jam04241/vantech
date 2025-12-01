# Services/Job Order Module - Implementation Guide

## 📋 Overview

The Services/Job Order module is a comprehensive system for managing customer service requests and job orders. It features a left-right layout with service card listings and an interactive form for creating and editing services.

## 🎨 Design Features

### Color Scheme
- **Primary Color**: `#151F28` (Dark Blue-Gray)
- **Secondary Color**: White backgrounds
- **Accent**: Blue gradient (`#4a9eff` to `#2196F3`)
- **Status Badges**: Multi-color based on status

### Layout Structure
- **Left Side (CardServices)**: Scrollable service card grid
- **Right Side (FormServices)**: Sticky service form
- **Responsive**: Single column (mobile) → 2 columns (tablet) → 3 columns (desktop)

## 📁 File Structure

```
resources/views/
├── ServicesOrder/
│   ├── Services.blade.php          # Main container & logic
│   └── partials/
│       ├── CardServices.blade.php  # Service cards display
│       └── FormServices.blade.php  # Service form

app/Http/Controllers/
├── ServicesController.php          # API endpoints & business logic

app/Models/
├── Service.php                     # Service model with relationships

database/
├── migrations/
│   └── 2024_01_17_create_services_table.php
└── sql_server_scripts/
    └── services_audit_triggers.sql # Audit triggers & procedures

routes/
├── api.php                         # API routes
└── web.php                         # Web routes
```

## 🚀 Installation Steps

### 1. Database Migration
Run the migration to create the services table:
```bash
php artisan migrate
```

This will create a `services` table with the following fields:
- `id` (Primary Key)
- `customer_name` (string, required)
- `service_type` (enum: Hardware Repair, Software Support, Network Setup, Data Recovery, Maintenance, Installation, Troubleshooting)
- `description` (longText, required)
- `status` (enum: Pending, In Progress, Completed, On Hold)
- `priority` (enum: Low, Medium, High, Urgent)
- `user_id` (nullable, foreign key reference)
- `created_at` & `updated_at` (timestamps)

### 2. SQL Server Audit Setup
Execute the SQL Server script to set up audit triggers:
```
database/sql_server_scripts/services_audit_triggers.sql
```

This creates:
- `services_audit_log` table
- Triggers for INSERT, UPDATE, DELETE operations
- Stored procedures for audit log retrieval and maintenance

### 3. Access the Module
Navigate to: `http://yourdomain.com/services`

## 📡 API Endpoints

All endpoints use the `/api/services` prefix and return JSON responses.

### GET `/api/services`
Fetch all services with full details.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "customer_name": "John Doe",
      "service_type": "Hardware Repair",
      "description": "Laptop motherboard inspection",
      "status": "Pending",
      "priority": "High",
      "user_id": 1,
      "created_at": "2024-01-17T10:30:00Z",
      "updated_at": "2024-01-17T10:30:00Z"
    }
  ]
}
```

### POST `/api/services`
Create a new service.

**Request Body:**
```json
{
  "customer_name": "John Doe",
  "service_type": "Hardware Repair",
  "description": "Laptop not turning on",
  "status": "Pending",
  "priority": "High"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Service created successfully",
  "data": { ... }
}
```

### PUT `/api/services/{id}`
Update an existing service.

**Request Body:**
```json
{
  "status": "In Progress",
  "priority": "Medium"
}
```

### DELETE `/api/services/{id}`
Delete a service.

**Response:**
```json
{
  "success": true,
  "message": "Service deleted successfully"
}
```

### GET `/api/services/list`
Get filtered services with query parameters.

**Query Parameters:**
- `search` (string) - Search in customer name, description, service type
- `status` (string) - Filter by status
- `type` (string) - Filter by service type

**Example:**
```
GET /api/services/list?search=john&status=Pending&type=Hardware%20Repair
```

### GET `/api/services/stats`
Get service statistics.

**Response:**
```json
{
  "success": true,
  "data": {
    "total": 25,
    "pending": 5,
    "in_progress": 8,
    "completed": 12
  }
}
```

## 🎯 Features

### Service Card Display
Each service card shows:
- **Customer Name** (bold, large text)
- **Service Type** (badge with primary color)
- **Status** (color-coded badge)
- **Priority** (if set)
- **Description** (truncated to 2 lines)
- **Date Added** (formatted as MMM DD, YYYY)
- **Service ID** (reference number)

### Service Form
The form includes:
- **Customer Name** (text input, required)
- **Service Type** (dropdown, required)
- **Description** (textarea, required)
- **Status** (dropdown: Pending, In Progress, Completed, On Hold)
- **Priority** (dropdown: Low, Medium, High, Urgent)
- **Action Buttons**:
  - Save Service (Blue gradient button)
  - Clear Form (Gray button)
  - Delete Service (Red button, only shows when editing)

### Search & Filter
- Search by customer name, service type, or description
- Filter by service type
- Filter by status
- Refresh button to reload services

## 🔍 Search & Filtering

The module includes real-time search and filtering:

1. **Search Bar**: Search across customer name, service type, and description
2. **Service Type Filter**: Filter by specific service types
3. **Status Filter**: Filter by service status
4. **Refresh Button**: Reload all services from the server

All filters work together and update the card display in real-time.

## 🛡️ Audit Logging

The SQL Server audit system automatically logs:
- **All INSERT operations** - New services created
- **All UPDATE operations** - Service modifications with before/after values
- **All DELETE operations** - Deleted services

### Accessing Audit Logs

**In SQL Server Management Studio:**

```sql
-- Get all audit logs for a service
EXEC sp_get_services_audit_log @service_id = 1;

-- Get all updates from last 7 days
EXEC sp_get_services_audit_log 
  @action = 'UPDATE',
  @start_date = DATEADD(DAY, -7, GETDATE());

-- Get audit summary
EXEC sp_get_audit_summary;
```

### Audit Log Fields
- `audit_id` - Unique identifier
- `service_id` - Service being audited
- `action` - INSERT, UPDATE, or DELETE
- `old_values` - Previous values (JSON format)
- `new_values` - New values (JSON format)
- `changed_by` - User who made the change
- `changed_at` - Timestamp of change
- `affected_columns` - Columns that were changed

## 🎨 Styling Details

### Colors & Shadows
- **Card Border**: Left 4px border in primary color
- **Header Background**: Gradient from primary to darker shade
- **Hover Effects**: Enhanced shadow on card hover
- **Focus States**: Blue ring on form inputs

### Responsive Classes
- Mobile: Single column layout
- Tablet (lg): 2-column layout
- Desktop (xl): 3-column layout

## 🔒 Security Features

1. **CSRF Protection**: All forms include CSRF token verification
2. **Input Validation**: Server-side validation for all inputs
3. **Status Verification**: Restricted to allowed values only
4. **Audit Trail**: Complete history of all changes
5. **Error Handling**: Comprehensive error messages

## 📱 User Experience

### Creating a Service
1. Fill in customer name, service type, and description
2. (Optional) Set status and priority
3. Click "Save Service" button
4. Success notification appears
5. Service appears in card list

### Editing a Service
1. Click on a service card
2. Card highlights with border
3. Form populates with service details
4. Modify any field
5. Click "Save Service"
6. Delete button appears for removal option

### Deleting a Service
1. Click on service card to select it
2. Click "Delete Service" button
3. Confirm deletion in dialog
4. Service removed from list

## 🚨 Status Badges

| Status | Color | Meaning |
|--------|-------|---------|
| Pending | Yellow | Waiting to start |
| In Progress | Blue | Currently being worked on |
| Completed | Green | Finished successfully |
| On Hold | Red | Temporarily paused |

## 🔧 Maintenance

### Database Optimization
The services table includes indexes on:
- `customer_name`
- `service_type`
- `status`
- `priority`
- `created_at`

This ensures fast queries even with large datasets.

### Purging Old Audit Logs
To maintain database performance, purge old audit logs:

```sql
-- Delete logs older than 365 days
EXEC sp_purge_old_audit_logs @days_to_keep = 365;
```

## 📊 Statistics API

Get quick overview of service operations:

```javascript
fetch('/api/services/stats')
  .then(res => res.json())
  .then(data => {
    console.log(`Total Services: ${data.data.total}`);
    console.log(`Pending: ${data.data.pending}`);
    console.log(`In Progress: ${data.data.in_progress}`);
    console.log(`Completed: ${data.data.completed}`);
  });
```

## 🐛 Troubleshooting

### Services Not Loading
1. Check browser console for errors
2. Verify API endpoint is accessible: `/api/services`
3. Ensure database migrations were run
4. Check user authentication status

### Form Not Saving
1. Verify CSRF token is present in HTML
2. Check network tab for request/response
3. Ensure all required fields are filled
4. Review server error logs

### Filters Not Working
1. Clear browser cache
2. Check JavaScript console for errors
3. Verify service data structure matches expectations
4. Try refreshing the page

## 📞 Support

For issues or questions:
1. Check the audit logs for data inconsistencies
2. Review server error logs
3. Verify database integrity
4. Contact system administrator

## 🎓 Advanced Usage

### Custom Queries

Get services with specific criteria:

```javascript
// Get only high priority pending services
fetch('/api/services/list?status=Pending&type=Hardware%20Repair')
  .then(res => res.json())
  .then(data => console.log(data));
```

### Real-time Updates

The form dynamically updates when cards are selected without page reload.

### Data Export

Services can be extracted via API for reporting:

```javascript
fetch('/api/services')
  .then(res => res.json())
  .then(data => {
    // Convert to CSV or Excel
    // Send to reporting system
  });
```

---

**Last Updated**: January 17, 2024  
**Version**: 1.0.0
