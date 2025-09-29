cat > docs/ERD.md << 'EOF'
# BabiBlaze API – ERD (v1)

## Entities

### users
- id (PK)
- name (string)
- email (string, unique)
- phone (string, nullable)
- password (hashed)
- role (enum: customer|barber|admin, default: customer)
- email_verified_at (datetime, nullable)
- timestamps

### barbers
- id (PK)
- user_id (FK → users.id, unique)
- bio (text, nullable)
- rating_avg (decimal(3,2), default 0.00)
- timestamps

### services
- id (PK)
- name (string)
- description (text, nullable)
- duration_minutes (smallint)
- price_cents (int)   // store prices in cents to avoid float issues
- is_active (boolean, default true)
- timestamps

### appointments
- id (PK)
- customer_id (FK → users.id)
- barber_id (FK → barbers.id)
- service_id (FK → services.id)
- starts_at (timestamp)
- ends_at (timestamp)
- status (enum: pending|confirmed|completed|cancelled, default pending)
- notes (text, nullable)
- timestamps

### reviews
- id (PK)
- appointment_id (FK → appointments.id)
- rating (tinyint 1..5)
- comment (text, nullable)
- timestamps

## Relationships
- User (role=barber) 1 — 1 Barber
- User (role=customer) 1 — * Appointments (as customer)
- Barber 1 — * Appointments
- Service 1 — * Appointments
- Appointment 1 — 1 Review

## ASCII Diagram

users (customer)      users (barber)
     | 1                      | 1
     | *                      | 1
appointments * ------------- 1 barbers
      | 1
      | *
   reviews

services 1 ---- * appointments

## Notes & Constraints
- Keep price as integer cents for accuracy.
- Add indexes on foreign keys and on appointments(starts_at, barber_id).
- Later extension: working_hours, schedules, payment_intents, availability checks.
