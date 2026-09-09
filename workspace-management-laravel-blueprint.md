# Workspace Management System — Laravel Backend Specification

**Document Status:** Initial Technical Blueprint  
**Version:** 0.1  
**Date:** 2026-09-04  
**Backend:** Laravel 13 / PHP 8.4+  
**Database:** MySQL 8+  
**Architecture:** Modular Laravel Monolith + REST API

---

## 1. Purpose

This document defines the initial technical foundation for a Workspace Management System used to manage:

- Shared and Private workspace usage
- Walk-in customer sessions
- Advance bookings
- Customers and guests
- Deals / live sessions
- Orders
- Drinks and snacks
- Payments
- Shifts and shift closing
- Inventory and stock counting
- Buffet purchases
- General expenses
- Receivables and payables
- Courses
- Daily tasks
- Reports
- Customer mobile application integration
- HubSpot integration
- Audit logs

The system must be designed so that future features can be added without rebuilding the core architecture.

---

# 2. Important Business Facts Confirmed So Far

These are the business rules explicitly confirmed during requirements discussion.

## Workspace

The business operates from an apartment/workspace containing rooms and areas.

There are two main usage modes:

- Shared
- Private

A room may be used as Shared at one time and Private at another time.

## Private

When a customer uses a room as Private:

- The customer gets the entire room.
- Another customer must not use the room privately during the same period.
- Private and Shared have different prices.

## Shared

When a customer uses a room as Shared:

- Multiple customers may sit in the same place at the same time.
- Shared and Private have different prices.

The exact Shared capacity rules are still to be finalized.

## Customer

Each customer has at minimum:

- Full name
- Mobile number

A customer can visit multiple times.

A customer may use the workspace without ordering drinks or snacks.

## Deal / Session

The current operational process is based on a live Deal:

1. Customer arrives.
2. Reception creates a Deal manually.
3. The system records the start time automatically.
4. Reception selects Shared or Private.
5. The customer uses the workspace.
6. Reception can add drinks and snacks to the Deal.
7. Customer leaves.
8. Reception closes the Deal.
9. The system records the end time automatically.
10. The system calculates the bill.
11. Customer pays.
12. The Deal / Order is closed.

Reception must be able to adjust recorded times when necessary.

## Duration Pricing

Current pricing uses duration levels such as:

- 30 minutes
- 1 hour
- Other configurable levels

Confirmed rule:

- 1:05 is charged as 1 hour.
- At 1:10, the customer moves to the next pricing level.

The exact behavior for every duration level must be implemented through a centralized pricing service, not hard-coded in controllers.

## Prices

Shared and Private have different prices.

Prices may change periodically.

Historical Deals must preserve the price that was actually applied at the time of the Deal.

Changing a current price must not change old Orders.

## Products

The workspace sells:

- Drinks
- Snacks
- Biscuits
- Chips
- Other configurable products

Products can change over time.

Reception can add products to an active Deal / Order.

## Payments

Current payment methods include:

- Cash
- InstaPay
- Wallet

The system must keep payment methods separate.

Example:

```text
Cash:     1,500 EGP
InstaPay:   800 EGP
Wallet:     300 EGP
----------------------
Total:    2,600 EGP
```

Cash must be included in Shift Handover / Shift Closing.

InstaPay and Wallet must also appear in shift reporting, but separately from physical cash.

## Advance Booking

Advance booking exists.

Customers will be able to make bookings from a mobile application.

A mobile booking does not necessarily mean that the customer has actually arrived.

Reception should be able to manually start the Deal.

Automatic starting can also be supported as a configurable/future behavior.

The Reception must be able to adjust the actual start time when necessary.

---

# 3. Architecture Decision

Use a **Modular Monolith**.

Do not start with microservices.

Recommended structure:

```text
Laravel Application
│
├── Authentication & Authorization
├── Customers
├── Workspace / Rooms
├── Bookings
├── Deals / Sessions
├── Orders
├── Products
├── Inventory
├── Payments
├── Shifts
├── Expenses
├── Receivables
├── Payables
├── Courses
├── Tasks
├── Reports
├── Notifications
├── Settings
├── Audit Logs
└── Integrations
    └── HubSpot
```

Benefits:

- Simple deployment
- Easier development
- Strong database transactions
- Centralized reporting
- Easy API integration
- Easy future mobile application
- Lower operational complexity

---

# 4. Technology Stack

Recommended:

```text
PHP 8.4+
Laravel 13
MySQL 8+
Redis
Laravel Queue
Laravel Scheduler
Laravel Sanctum
REST API
Policies / Gates
Events / Listeners
Notifications
```

The backend should be API-first.

Frontend applications should communicate with Laravel through APIs.

Target clients:

```text
Reception Dashboard
Admin Dashboard
Customer Mobile App
Future Mobile Staff App
Future Integrations
```

---

# 5. API Versioning

Start with:

```text
/api/v1/
```

Examples:

```text
/api/v1/customers
/api/v1/bookings
/api/v1/deals
/api/v1/orders
/api/v1/payments
```

Future versions can use:

```text
/api/v2/
```

without breaking existing mobile applications.

---

# 6. Core Domain Model

The following entities must remain separate:

```text
Customer
Booking
Deal
Order
Payment
```

Relationship:

```text
Customer
   │
   ├── Booking
   │
   └── Deal
          │
          └── Order
                 │
                 └── Payments
```

A Booking represents a planned reservation.

A Deal represents actual workspace usage.

An Order represents the bill.

A Payment represents money received.

---

# 7. Customers

## Table: `customers`

Recommended fields:

```text
id
full_name
phone
email nullable
customer_type nullable
source nullable
status
notes nullable
created_at
updated_at
```

Potential future data:

- WhatsApp
- Date of birth
- Job
- Company
- Preferences
- Marketing consent

These must only be added after business requirements are confirmed.

## Customer History

A customer should be able to access:

```text
Bookings
Deals
Orders
Payments
Course Bookings
```

---

# 8. Guests

If Guests are required, do not duplicate the Customer model.

Recommended approach:

```text
customers.customer_type
```

or a dedicated customer status/type.

Potential values:

```text
registered
guest
```

A Guest may later become a registered Customer without losing historical activity.

Exact Guest behavior remains to be confirmed.

---

# 9. Rooms

## Table: `rooms`

Recommended fields:

```text
id
name
code nullable
capacity nullable
status
color nullable
description nullable
created_at
updated_at
```

Possible statuses:

```text
active
maintenance
inactive
```

Room colors are intended for Calendar visualization.

---

# 10. Workspace Types

Do not hard-code Shared and Private throughout the application.

## Table: `workspace_types`

Fields:

```text
id
name
code
pricing_mode
active
created_at
updated_at
```

Initial records:

```text
Shared
Private
```

Future possibilities:

```text
Meeting Room
Training Room
Day Pass
Office
```

This design keeps the system extensible.

---

# 11. Pricing

Pricing must be historical.

Do not rely on a single mutable price field for session billing.

## Table: `pricing_rules`

Fields:

```text
id
workspace_type_id
duration_minutes
price
effective_from
effective_until nullable
active
created_at
updated_at
```

Example:

```text
Shared
30 minutes = 30 EGP
60 minutes = 50 EGP

Private
30 minutes = 80 EGP
60 minutes = 150 EGP
```

Later prices can change without changing historical Deals.

---

# 12. Pricing Service

Create a dedicated service:

```text
DealPricingService
```

Responsibilities:

- Calculate actual duration
- Apply configured pricing levels
- Apply the 10-minute threshold rule
- Select the correct historical pricing rule
- Return the billable duration
- Return the calculated session price

Do not implement pricing logic directly inside Controllers.

Example:

```text
Actual Duration
      ↓
Pricing Engine
      ↓
Billable Duration
      ↓
Applicable Historical Price
      ↓
Session Price
```

---

# 13. Deals / Sessions

The Deal is the core operational entity.

## Table: `deals`

Fields:

```text
id
deal_number
customer_id
booking_id nullable
room_id nullable
workspace_type_id
started_at
ended_at nullable
duration_minutes nullable
status
notes nullable
created_by
closed_by nullable
created_at
updated_at
```

Initial statuses:

```text
open
closed
cancelled
```

Potential future statuses:

```text
pending
no_show
```

---

# 14. Starting a Deal

When Reception clicks:

```text
Start Deal
```

Backend:

```text
started_at = now()
status = open
```

The system must record the actual timestamp automatically.

Reception can adjust the recorded time if authorized.

---

# 15. Closing a Deal

When Reception clicks:

```text
Close Deal
```

Backend:

```text
ended_at = now()
```

Then:

```text
Actual Duration
      ↓
Pricing Engine
      ↓
Session Charge
      ↓
Order Total
      ↓
Payment
```

The system must preserve both:

- Original automatically recorded time
- Final adjusted time, if adjustment is allowed

This can be handled through audit logs and controlled editing.

---

# 16. Shared Deal Logic

Shared Deals allow multiple customers to use the same space simultaneously.

Example:

```text
Room A

Ahmed → Shared → 2 hours
Mohamed → Shared → 3 hours
Ali → Shared → 1 hour
```

The system must not treat Shared as exclusive.

Capacity rules are still to be finalized.

---

# 17. Private Deal Logic

Private means the room is exclusively occupied by one customer/deal during the applicable period.

The backend must prevent conflicting Private bookings/usage for the same room and time range.

Example:

```text
Room A
17:00 → 19:00
Private
Ahmed
```

Another Private reservation cannot overlap the same room/time.

Whether a Shared user may use the same room while a Private Deal is active is a business rule that must be explicitly configured and enforced.

---

# 18. Bookings

## Table: `bookings`

Fields:

```text
id
booking_number
customer_id
room_id nullable
workspace_type_id
start_at
end_at
status
source
notes nullable
created_by nullable
created_at
updated_at
```

Sources may include:

```text
mobile
reception
admin
```

## Booking Principle

A Booking is a planned reservation.

It does not automatically prove that the customer arrived.

Actual usage begins when a Deal is started.

---

# 19. Booking → Deal

Recommended flow:

```text
Mobile Booking
      ↓
Customer Arrives
      ↓
Reception Check-in
      ↓
Start Deal
      ↓
Actual Start Time
      ↓
Customer Uses Workspace
      ↓
Close Deal
```

Future automatic start can be added through configuration.

---

# 20. Booking Conflict Rules

For Private:

```text
Same Room
+
Overlapping Time
+
Private
=
Conflict
```

The backend must reject conflicting reservations.

For Shared:

Multiple customers may use the same room subject to the configured capacity/business rules.

---

# 21. Calendar

The backend must support:

```text
Week
Month
```

Calendar navigation:

```text
Previous
Next
Today
```

Calendar event data should include:

```text
Booking ID
Customer
Room
Workspace Type
Start Time
End Time
Status
Color
```

Each room can have a configurable display color.

---

# 22. Orders

## Table: `orders`

Fields:

```text
id
order_number
customer_id
deal_id nullable
booking_id nullable
subtotal
discount
tax
total
paid_amount
remaining_amount
status
created_by
closed_by nullable
closed_at nullable
created_at
updated_at
```

Initial statuses:

```text
open
partially_paid
paid
cancelled
```

---

# 23. Order Items

## Table: `order_items`

Fields:

```text
id
order_id
product_id nullable
item_type
name
quantity
unit_price
total
metadata nullable
created_at
updated_at
```

Important rule:

The Order Item stores the actual price used for that transaction.

Changing the current Product price must not modify old Orders.

---

# 24. Products

## Table: `products`

Fields:

```text
id
category_id nullable
name
sku nullable
barcode nullable
unit
selling_price
purchase_price nullable
minimum_stock nullable
track_inventory
active
created_at
updated_at
```

Products may include:

```text
Drinks
Coffee
Water
Soft Drinks
Snacks
Chips
Biscuits
```

Categories must be configurable.

---

# 25. Product Categories

## Table: `product_categories`

Fields:

```text
id
name
code
active
created_at
updated_at
```

Categories should be manageable through the Admin Settings interface.

---

# 26. Inventory

Do not rely only on:

```text
products.stock
```

Use an inventory ledger.

## Table: `inventory_transactions`

Fields:

```text
id
product_id
type
quantity
reference_type nullable
reference_id nullable
location_id
notes nullable
created_by
created_at
```

Transaction types:

```text
purchase
sale
display_transfer
inventory_adjustment
damage
loss
return
```

This creates a complete stock history.

---

# 27. Inventory Locations

## Table: `inventory_locations`

Initial locations:

```text
Warehouse
Display
```

Fields:

```text
id
name
code
type
active
created_at
updated_at
```

This allows:

```text
Water
Warehouse = 50
Display = 5
```

instead of treating all inventory as one number.

---

# 28. Display Stock

Current business process includes keeping a target number of products on display.

Example:

```text
Required Display = 5
Actual Display = 5
Status = OK
```

If:

```text
Required = 5
Actual = 3
```

the system should show a shortage.

The exact required quantity must be configurable per product or through a default setting.

---

# 29. Display Transfer

When products move:

```text
Warehouse
    ↓
Display
```

the system creates a stock transaction.

Example:

```text
Water
Warehouse -5
Display +5
```

The movement must record:

- Product
- Quantity
- Source
- Destination
- User
- Timestamp

---

# 30. Opening Display Count

The opening count checks display quantities at the beginning of the day.

The system should support:

```text
Expected Quantity
Actual Quantity
Difference
Status
```

Possible result:

```text
Everything OK
```

or:

```text
Shortage
```

The system should retain the count history.

---

# 31. Weekly Warehouse Count

Weekly inventory counting applies to warehouse stock.

The system should compare:

```text
System Quantity
Actual Quantity
Difference
```

The final adjustment must be recorded as an inventory transaction rather than silently changing stock.

Reasons for differences should be configurable if required.

---

# 32. Inventory Consumption

Monthly consumption reports should be based on inventory movements.

Potential components:

```text
Sales
Damage
Loss
Adjustments
Other
```

The exact definition of "monthly consumption" must be finalized before reporting logic is locked.

---

# 33. Purchases

## Table: `purchases`

Fields:

```text
id
purchase_number
supplier_id nullable
purchase_date
subtotal
discount
total
paid_amount
remaining_amount
status
notes nullable
created_by
created_at
updated_at
```

## Table: `purchase_items`

Fields:

```text
id
purchase_id
product_id
quantity
unit_cost
total
created_at
updated_at
```

Confirmed purchase behavior:

```text
Purchase
   ↓
Inventory Transaction
   ↓
Warehouse Stock +
```

---

# 34. Suppliers

A Supplier entity is recommended if purchases and payables are managed in the system.

Potential table:

```text
suppliers
```

Fields:

```text
id
name
phone nullable
email nullable
address nullable
notes nullable
status
created_at
updated_at
```

Exact supplier requirements remain to be finalized.

---

# 35. Payments

## Table: `payments`

Fields:

```text
id
payment_number
order_id nullable
customer_id nullable
type
method
amount
reference nullable
paid_at
received_by
notes nullable
created_at
updated_at
```

Initial payment methods:

```text
cash
instapay
wallet
```

Additional methods can be added later.

---

# 36. Payment Rules

The system should support multiple payments for one Order.

Example:

```text
Order Total = 2,600

Cash = 1,500
InstaPay = 800
Wallet = 300

Total Paid = 2,600
Remaining = 0
```

This enables accurate shift reconciliation.

Partial payments can be supported at the architecture level, even if the current operational workflow normally requires full payment.

---

# 37. Shift Management

## Table: `shifts`

Fields:

```text
id
user_id
opened_at
closed_at nullable
opening_cash
expected_cash nullable
actual_cash nullable
cash_difference nullable
status
closing_notes nullable
created_at
updated_at
```

Statuses:

```text
open
closed
```

---

# 38. Shift Closing

Shift closing should calculate payment totals by method.

Example:

```text
Opening Cash       1,000 EGP

Cash Sales         2,500 EGP
InstaPay           1,200 EGP
Wallet               600 EGP

Cash Expenses        300 EGP

Expected Cash      3,200 EGP
Actual Cash        3,200 EGP

Difference             0 EGP
```

Important:

Only physical cash should be included in the expected physical cash calculation.

InstaPay and Wallet should appear separately in the shift report.

---

# 39. Expenses

## Table: `expenses`

Fields:

```text
id
category_id
amount
payment_method
expense_date
description
reference nullable
created_by
created_at
updated_at
```

Potential categories:

```text
Cleaning
Maintenance
Bills
Supplies
Other
```

Categories should be configurable.

---

# 40. General Purchases vs Buffet Purchases

The system should distinguish between:

### Buffet Purchases

Products that enter inventory.

Example:

```text
Water
Chips
Coffee
```

### General Expenses

Operating expenses.

Example:

```text
Cleaning supplies
Maintenance
Bills
```

A general expense should not automatically modify product inventory unless explicitly classified as an inventory purchase.

---

# 41. Receivables — Money Owed To The Business

## Table: `receivables`

Fields:

```text
id
customer_id nullable
entity_name nullable
amount
description
due_date nullable
status
created_by
created_at
updated_at
```

## Table: `receivable_payments`

Fields:

```text
id
receivable_id
amount
payment_method
paid_at
received_by
notes nullable
created_at
updated_at
```

Partial payments should be supported.

---

# 42. Payables — Money Owed By The Business

## Table: `payables`

Fields:

```text
id
supplier_id nullable
entity_name nullable
amount
description
due_date nullable
status
created_by
created_at
updated_at
```

## Table: `payable_payments`

Fields:

```text
id
payable_id
amount
payment_method
paid_at
paid_by
notes nullable
created_at
updated_at
```

Partial payments should be supported.

---

# 43. Tasks

## Table: `tasks`

Fields:

```text
id
title
description nullable
deadline nullable
assigned_to nullable
priority nullable
status
completed_at nullable
completed_by nullable
created_by
created_at
updated_at
```

Initial statuses:

```text
pending
completed
cancelled
```

The system should retain:

- Who completed the task
- When it was completed

---

# 44. Courses

Courses should be an independent module.

Potential tables:

```text
courses
course_bookings
```

Potential course attributes:

```text
name
trainer
room
capacity
start_at
end_at
price
```

Exact course workflow is not finalized and must be confirmed before implementation.

---

# 45. Customer Mobile Application

The Laravel backend should expose customer APIs.

Potential endpoints:

```text
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout

GET /api/v1/profile

GET /api/v1/rooms
GET /api/v1/availability

POST /api/v1/bookings
GET /api/v1/bookings
GET /api/v1/bookings/{booking}

POST /api/v1/bookings/{booking}/cancel
```

Future endpoints:

```text
GET /api/v1/orders
GET /api/v1/payments
GET /api/v1/history
GET /api/v1/customer/qr
```

The exact mobile feature set must be finalized later.

---

# 46. Authentication

Recommended:

```text
Laravel Sanctum
```

Separate authentication contexts can exist for:

```text
Staff
Customers
```

Staff access should use roles and permissions.

Customer access should only expose customer-owned resources.

---

# 47. Roles and Permissions

Recommended initial roles:

```text
Owner
Manager
Reception
Inventory
Accountant
```

These are proposed roles and should be confirmed.

Permissions should be granular.

Examples:

```text
customers.view
customers.create
customers.edit

bookings.view
bookings.create
bookings.edit
bookings.cancel

deals.view
deals.create
deals.edit
deals.close

orders.view
orders.create
orders.edit
orders.close

payments.create
payments.refund

inventory.view
inventory.adjust
inventory.count

purchases.create

shifts.open
shifts.close

reports.view

settings.manage
```

---

# 48. Authorization

Use:

- Policies
- Gates
- Permission middleware

Avoid scattered checks such as:

```text
if ($user->is_admin)
```

throughout the application.

Business permissions should be centralized.

---

# 49. Audit Logs

Financial and operational actions should be auditable.

## Table: `audit_logs`

Fields:

```text
id
user_id
action
auditable_type
auditable_id
old_values
new_values
ip_address nullable
user_agent nullable
created_at
```

Example:

```text
User: Ahmed
Action: Update Order Item Price

Old Price: 100
New Price: 80

Date: 2026-09-04 18:32
```

Audit logs should not normally be deletable by normal users.

---

# 50. Events

Use Laravel Events for important domain actions.

Examples:

```text
DealStarted
DealClosed

OrderCreated
OrderClosed

PaymentCreated

InventoryTransactionCreated

ShiftOpened
ShiftClosed

BookingCreated
BookingCancelled
```

Example flow:

```text
DealClosed
    ↓
Calculate Pricing
    ↓
Update Order
    ↓
Prepare Payment
```

Another:

```text
PaymentCreated
    ↓
Update Order Balance
    ↓
Update Shift Totals
```

---

# 51. Services

Business logic should live in dedicated services.

Recommended:

```text
CustomerService
BookingService
BookingAvailabilityService

DealService
DealPricingService

OrderService
PaymentService

ProductService
InventoryService
InventoryCountService
PurchaseService

ShiftService
ExpenseService

ReceivableService
PayableService

CourseService
TaskService

ReportService

HubSpotService
```

Controllers should remain thin.

---

# 52. Database Transactions

Use database transactions for critical operations.

Examples:

### Closing an Order

```text
Payment
Order Status
Order Balance
Inventory
Shift
Audit Log
```

must not leave the system in a half-completed state.

### Purchase

```text
Purchase
Purchase Items
Inventory Transaction
Payable
```

should be handled atomically where applicable.

---

# 53. Recommended Folder Structure

```text
app/
├── Actions/
├── DTOs/
├── Enums/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Notifications/
├── Policies/
├── Services/
│   ├── Booking/
│   ├── Deal/
│   ├── Order/
│   ├── Payment/
│   ├── Inventory/
│   ├── Shift/
│   ├── Customer/
│   ├── Finance/
│   └── Integration/
└── Support/
```

---

# 54. Suggested Database Tables

Initial database map:

```text
users

roles
permissions
role_user
permission_role

customers
customer_sources
customer_categories

rooms
workspace_types
pricing_rules

bookings

deals

orders
order_items
payments

product_categories
products

inventory_locations
inventory_transactions
inventory_counts
inventory_count_items

suppliers
purchases
purchase_items

expense_categories
expenses

receivables
receivable_payments

payables
payable_payments

shifts

tasks

courses
course_bookings

audit_logs

notifications

settings
```

Some tables should only be created after confirming the related business requirements.

---

# 55. Main Operational Workflow

```text
CUSTOMER
   │
   ├───────────────┐
   │               │
BOOKING          WALK-IN
   │               │
   └───────┬───────┘
           ↓
       START DEAL
           ↓
     Start Time = NOW
           ↓
   Shared / Private
           ↓
     Add Products
           ↓
      CLOSE DEAL
           ↓
      End Time = NOW
           ↓
    Pricing Engine
           ↓
         ORDER
           ↓
       PAYMENT
      ┌────┼────┐
      ↓    ↓    ↓
    CASH INSTA WALLET
      │    │    │
      └────┼────┘
           ↓
      CLOSE ORDER
           ↓
      SHIFT REPORT
```

---

# 56. Booking Workflow

```text
Customer Mobile
      ↓
Select Workspace
      ↓
Select Date
      ↓
Select Time
      ↓
Select Duration
      ↓
Create Booking
      ↓
Booking Confirmed
      ↓
Customer Arrives
      ↓
Reception Check-in
      ↓
Start Deal
      ↓
Actual Usage
```

Private bookings must pass availability validation before confirmation.

---

# 57. Inventory Workflow

```text
Purchase
   ↓
Warehouse +
   ↓
Transfer
   ↓
Display +
   ↓
Product Sale
   ↓
Stock -
   ↓
Opening Count
   ↓
Weekly Count
   ↓
Variance
   ↓
Inventory Adjustment
```

Every stock-changing operation should generate an inventory transaction.

---

# 58. Shift Workflow

```text
Open Shift
    ↓
Opening Cash
    ↓
Daily Operations
    ├── Cash Sales
    ├── InstaPay Sales
    ├── Wallet Sales
    ├── Cash Expenses
    └── Other Transactions
    ↓
Close Shift
    ↓
Calculate Expected Cash
    ↓
Enter Actual Cash
    ↓
Calculate Difference
    ↓
Save Shift Closing
```

---

# 59. Reports

The architecture should support:

## Sales

- Daily sales
- Weekly sales
- Monthly sales
- Custom date range

## Payments

- Cash
- InstaPay
- Wallet
- Total

## Bookings

- Number of bookings
- Room utilization
- Shared usage
- Private usage

## Customers

- New customers
- Returning customers
- Customer history

## Inventory

- Current stock
- Low stock
- Out of stock
- Monthly consumption
- Inventory variance
- Purchase history

## Finance

- Expenses
- Receivables
- Payables
- Payments
- Revenue

## Shifts

- Shift totals
- Cash reconciliation
- Payment-method breakdown

Exact report definitions should be finalized before implementation.

---

# 60. Dashboard

Potential dashboard widgets:

```text
Today's Sales
Today's Bookings
Active Deals
Open Orders
Today's Payments
Cash Sales
InstaPay Sales
Wallet Sales
Low Stock Products
Pending Tasks
Upcoming Bookings
```

Dashboard visibility should be permission-based.

---

# 61. Notifications

Future notification system can support:

```text
Low Stock
Upcoming Booking
Booking Reminder
Overdue Payment
Task Deadline
Shift Reminder
```

Delivery channels can later include:

```text
In-App
Email
WhatsApp
SMS
Push Notifications
```

Do not integrate all channels in the first implementation unless required.

---

# 62. HubSpot Integration

The current requirement includes customer identification through QR and HubSpot.

Potential flow:

```text
Customer QR
      ↓
Reception Scan
      ↓
Find HubSpot Contact
      ↓
Find/Create Laravel Customer
      ↓
Create Deal
```

A future bidirectional synchronization can be supported:

```text
Laravel
   ↕
HubSpot
```

However, the system must first decide which platform is the source of truth.

Do not implement destructive synchronization before this rule is confirmed.

---

# 63. QR Architecture

The QR system should not tightly couple the core application to HubSpot.

Recommended abstraction:

```text
CustomerIdentifierService
```

Possible sources:

```text
Customer QR
HubSpot QR
Internal QR
Future Membership QR
```

This allows the QR implementation to change without rewriting Reception workflows.

---

# 64. Settings

Configurable settings may include:

```text
Workspace Types
Rooms
Room Colors

Pricing Rules
Duration Levels
Rounding Rules

Products
Product Categories
Payment Methods

Customer Sources
Customer Categories

Inventory Rules
Display Quantities
Low Stock Threshold

Expense Categories

Booking Rules
```

Core entities should remain database tables rather than arbitrary settings.

---

# 65. Security Requirements

Minimum security foundation:

- Laravel Sanctum
- CSRF protection where applicable
- Password hashing
- Rate limiting
- Authorization Policies
- Role/Permission checks
- Input validation
- Form Requests
- Database transactions
- Audit logs
- Secure file uploads
- Environment secrets
- HTTPS
- Queue isolation
- Secure API authentication
- Login throttling
- Session expiration
- Database backups

Admin/Owner 2FA can be added as a high-priority enhancement.

---

# 66. Data Integrity Rules

Important rules:

1. Historical Orders must never change because a Product price changed.
2. Historical Deals must preserve their effective price.
3. Stock must change through inventory transactions.
4. Payments must be immutable or strictly audited after posting.
5. Closed Orders should not be casually edited.
6. Cancelled transactions should remain in history.
7. Financial records should not be hard-deleted.
8. Private booking conflicts must be enforced server-side.
9. Customer-owned API resources must be protected from IDOR.
10. Every important financial action must have an audit trail.

---

# 67. Testing Strategy

Testing should include:

## Unit Tests

- Pricing calculation
- Duration rounding
- Inventory calculations
- Payment totals
- Shift cash calculations

## Feature Tests

- Create customer
- Create Deal
- Close Deal
- Add product
- Create booking
- Detect private conflict
- Create payment
- Close shift
- Inventory count

## Authorization Tests

Verify that unauthorized roles cannot:

- Modify prices
- Adjust inventory
- Close/reopen sensitive records
- View restricted reports
- Access other customers

---

# 68. Development Phases

## Phase 1 — Core Operations

```text
Authentication
Users
Roles
Customers
Rooms
Workspace Types
Pricing
Deals
Orders
Order Items
Payments
```

Goal:

Run the normal daily Reception workflow.

---

## Phase 2 — Booking

```text
Bookings
Availability
Calendar
Private Conflict Detection
Shared Capacity
Mobile Booking API
```

---

## Phase 3 — Inventory

```text
Products
Categories
Warehouse
Display
Purchases
Transfers
Opening Count
Weekly Count
Low Stock
Consumption
```

---

## Phase 4 — Finance

```text
Shifts
Expenses
Receivables
Payables
Payment Reconciliation
Financial Reports
```

---

## Phase 5 — Operations

```text
Tasks
Courses
Notifications
Advanced Reports
```

---

## Phase 6 — Integrations

```text
HubSpot
QR
WhatsApp
Push Notifications
Payment Integrations
```

---

# 69. Recommended Implementation Order

The actual Laravel implementation should follow:

```text
1. Project setup
2. Environment configuration
3. Authentication
4. Users / Roles / Permissions
5. Database migrations
6. Models / Relationships
7. Form Requests
8. API Resources
9. Services
10. Policies
11. Controllers
12. Events / Listeners
13. Audit Logs
14. Unit Tests
15. Feature Tests
16. API documentation
17. Admin/Reception frontend integration
18. Mobile API integration
```

---

# 70. Important: Do Not Implement Yet

The following areas are architecturally prepared but still require final business confirmation:

- Exact Shared capacity
- Exact Private/Shared booking rules
- Exact pricing levels
- Exact duration rounding behavior for every level
- Automatic booking start behavior
- Partial payment policy
- Refund policy
- Guest behavior
- Course workflow
- HubSpot source of truth
- QR format
- Exact shift reconciliation rules
- Exact inventory adjustment rules
- Supplier workflow
- Exact financial definitions for "Money Owed To Us" and "Money We Owe"
- Customer mobile feature list
- Roles and exact permissions

These should not be guessed during implementation.

---

# 71. Definition of Done for the Backend Foundation

The backend foundation is considered ready when:

- Database schema is normalized
- Relationships are defined
- Core business rules are documented
- Authentication works
- Authorization works
- API versioning exists
- Core services exist
- Pricing is centralized
- Inventory uses a ledger
- Payments are separated by method
- Shift reconciliation works
- Audit logging works
- Critical operations use transactions
- Tests cover core business rules
- API documentation exists
- Mobile clients can consume the API

---

# 72. Final Architecture Principle

The system must be built around business entities and transactions, not around individual screens.

Core model:

```text
Customer
   ↓
Booking
   ↓
Deal / Session
   ↓
Order
   ↓
Payment
   ↓
Shift
```

Inventory model:

```text
Purchase
   ↓
Inventory Transaction
   ↓
Warehouse
   ↓
Display
   ↓
Sale
   ↓
Inventory Transaction
```

Financial model:

```text
Revenue
Expenses
Receivables
Payables
Payments
Shifts
```

This architecture allows the system to evolve from a Reception management application into a complete Workspace Management Platform without replacing the core backend.
