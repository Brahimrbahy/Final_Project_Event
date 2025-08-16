#!/bin/bash

# Event Management System Setup Script
echo "🎉 Setting up Event Management System..."

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Copy environment file
if [ ! -f .env ]; then
    echo "⚙️ Creating environment file..."
    cp .env.example .env
else
    echo "⚠️ .env file already exists, skipping..."
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "🗄️ Creating SQLite database..."
    touch database/database.sqlite
else
    echo "⚠️ Database file already exists, skipping..."
fi

# Run migrations and seeders
echo "🗄️ Running migrations and seeders..."
php artisan migrate:fresh --seed

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "✅ Setup completed successfully!"
echo ""
echo "🚀 To start the application, run:"
echo "   php artisan serve"
echo ""
echo "📧 Default login credentials:"
echo "   Admin: admin@eventmanagement.com / password"
echo "   Organizer: john@eventorganizer.com / password"
echo "   Client: alice@example.com / password"
echo ""
echo "🌐 Visit http://localhost:8000 to access the application"
