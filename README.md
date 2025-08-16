# Event Management System

A comprehensive Laravel-based event management platform with role-based access control, payment processing, and admin approval workflows.

## Features

### 🎯 **Core Functionality**
- **Multi-Role System**: Admin, Organizer, and Client roles with distinct permissions
- **Event Management**: Create, edit, and manage events with approval workflows
- **Payment Processing**: Stripe integration for paid events with automatic fee collection
- **Ticket Management**: QR code generation, booking management, and ticket validation
- **Admin Dashboard**: Comprehensive admin controls for user and event management

### 🎨 **Event Categories**
- 🎵 Concerts
- 🎪 Festivals
- 🎭 Theatre
- ⚽ Sports
- 🎬 Cinema
- 💼 Business
- 💻 Technology
- 🎨 Arts & Culture
- 📚 Education
- 🍽️ Food & Drink
- 🏃 Health & Wellness
- 📋 Other

### 👥 **User Roles**

#### **Admin**
- Approve/reject organizer registrations
- Approve/reject events before public visibility
- View comprehensive analytics and revenue reports
- Manage all users and events
- Collect 15% platform fee from paid events

#### **Organizer**
- Create and manage events (free/paid)
- Upload event images and set detailed information
- View booking analytics and revenue reports
- Manage client bookings and ticket sales
- Access to 85% of ticket revenue (after platform fee)

#### **Client**
- Browse and search approved events
- Purchase tickets for paid events
- Get free tickets for free events
- View personal ticket history
- Receive QR code tickets

## Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL
- Stripe Account (for payments)

### Setup Instructions

1. **Clone the repository**
```bash
git clone <repository-url>
cd Final_Project
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure environment variables**
Edit `.env` file:
```env
APP_NAME="Event Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key

MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@eventmanagement.com"
MAIL_FROM_NAME="${APP_NAME}"
```

5. **Database setup**
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

6. **Storage setup**
```bash
php artisan storage:link
```

7. **Build assets**
```bash
npm run build
```

8. **Start the application**
```bash
php artisan serve
```

## Default Users

After running the seeders, you can login with these accounts:

### Admin
- **Email**: admin@eventmanagement.com
- **Password**: password

### Approved Organizers
- **Email**: john@eventorganizer.com
- **Password**: password
- **Email**: sarah@musicevents.com
- **Password**: password
- **Email**: mike@techconferences.com
- **Password**: password

### Pending Organizer (for testing approval)
- **Email**: emma@newevents.com
- **Password**: password

### Clients
- **Email**: alice@example.com
- **Password**: password
- **Email**: bob@example.com
- **Password**: password

## Usage Guide

### For Admins
1. Login with admin credentials
2. Access admin dashboard at `/admin/dashboard`
3. Review pending organizer applications
4. Approve/reject events before they go public
5. Monitor revenue and platform analytics

### For Organizers
1. Register as an organizer
2. Wait for admin approval
3. Once approved, create events with:
   - Event details (title, description, category)
   - Date and time
   - Location information
   - Pricing (free or paid)
   - Ticket limits
   - Event images
4. Monitor bookings and revenue

### For Clients
1. Register as a client
2. Browse approved events
3. Filter by category, type, or search
4. Purchase tickets (paid events) or get free tickets
5. View ticket history and QR codes

## Technical Architecture

### Backend
- **Framework**: Laravel 11
- **Database**: SQLite (configurable)
- **Authentication**: Laravel Breeze
- **File Storage**: Local storage with symlinks
- **Queue System**: Database queues
- **Testing**: Pest PHP

### Frontend
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Alpine.js (via Breeze)
- **Build Tool**: Vite
- **Icons**: Heroicons

### Payment Processing
- **Provider**: Stripe
- **Fee Structure**: 15% platform fee on paid events
- **Security**: PCI compliant payment processing

## Security Features

- **Role-based Access Control**: Middleware protection for all routes
- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive validation on all inputs
- **File Upload Security**: Validated image uploads with size limits
- **Payment Security**: Stripe handles all sensitive payment data

## Testing

Run the test suite:
```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
# Final_Project_Event
