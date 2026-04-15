# Clinic Management System (CodeIgniter 3)

Initial module delivered:
- Login page (session-based authentication)
- Admin-only User Management (CRUD for users and role/status control)

## Setup
1. Create a MySQL database (example: `clinicms`).
2. Import `database/clinicms.sql`.
3. Update DB credentials in `application/config/database.php`.
4. Serve the app and open `/index.php/login`.

## Default Admin
- Email: `admin@clinicms.local`
- Password: `Admin@123`

## Roles
- admin
- reception
- doctor
- lab
- nurse
