# Prayer App

A modern, full-featured prayer request management application built with Laravel, Vue.js, and Inertia.js. This application allows organizations and individuals to create, manage, and track prayer requests with real-time updates and notifications.

## Features

### Core Functionality
- **Prayer Request Management**: Create, view, edit, and delete prayer requests
- **Organization Support**: Multi-tenant architecture supporting multiple organizations
- **Prayer Tracking**: Count and track how many times each request has been prayed for
- **Request Updates**: Add updates to prayer requests to share progress and answers
- **Prayer Wall**: Public view of all prayer requests for community prayer
- **User Following**: Follow specific prayer requests to receive notifications
- **Admin Panel**: Comprehensive admin interface for managing users and requests

### User Management
- **User Authentication**: Secure login and registration system
- **Profile Management**: Users can update their profiles
- **Organization Assignment**: Users belong to specific organizations
- **Admin Controls**: Designated admin users with elevated permissions

### Notifications
- **Real-time Updates**: Email notifications for new prayer requests
- **Update Notifications**: Notify followers when requests are updated
- **Prayer Count Tracking**: Track engagement with prayer requests

## Technology Stack

- **Backend**: Laravel 11.x with PHP 8.2+
- **Frontend**: Vue.js 3 with TypeScript
- **UI Framework**: Tailwind CSS with Inertia.js
- **Database**: SQLite (configurable for other databases)
- **Authentication**: Laravel Sanctum
- **Testing**: Pest PHP
- **Build Tools**: Vite

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite (or MySQL/PostgreSQL)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd prayer-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   
   # Or configure MySQL/PostgreSQL in .env
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

### Development Setup

For development with hot reloading:

```bash
# Run all development services
composer run dev

# Or run individually:
php artisan serve          # Laravel server
php artisan queue:listen   # Queue worker
php artisan pail          # Log viewer
npm run dev               # Vite dev server
```

## Usage

### Getting Started

1. **Register an Account**: Create a new user account
2. **Join an Organization**: Get assigned to an organization by an admin
3. **Create Prayer Requests**: Submit prayer requests for your needs
4. **Pray for Others**: Visit the prayer wall to pray for community requests
5. **Follow Requests**: Follow specific requests to receive updates
6. **Add Updates**: Share progress and answers to prayer

### Admin Features

Admin users have access to:
- User management (view, edit, delete users)
- Prayer request moderation
- Organization management
- System-wide analytics

### API Endpoints

The application provides RESTful API endpoints for:
- Prayer request CRUD operations
- User management
- Prayer counting
- Update management
- Admin operations

## Database Schema

### Key Models
- **Users**: User accounts with organization membership
- **Organizations**: Multi-tenant organization structure
- **PrayerRequests**: Core prayer request data
- **PrayerCounts**: Tracking prayer engagement
- **PrayerRequestUpdates**: Progress updates on requests
- **PrayerRequestFollowers**: User following relationships

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Testing

Run the test suite:

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html).

### What this means:
- ✅ **Free to use** for personal, educational, and non-commercial purposes
- ✅ **Free to modify** and distribute under the same license
- ✅ **Free to use** in open source projects
- ❌ **Cannot be used** in proprietary commercial products without open-sourcing the entire application
- ❌ **Cannot be used** in closed-source commercial services

This license ensures the prayer app remains freely available to the community while preventing commercial exploitation without contributing back to the open source ecosystem.
