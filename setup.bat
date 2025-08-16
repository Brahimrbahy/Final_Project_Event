@echo off
echo 🎉 Setting up Event Management System...

REM Check if composer is installed
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer first.
    pause
    exit /b 1
)

REM Check if npm is installed
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ NPM is not installed. Please install Node.js and NPM first.
    pause
    exit /b 1
)

REM Install PHP dependencies
echo 📦 Installing PHP dependencies...
composer install

REM Install Node.js dependencies
echo 📦 Installing Node.js dependencies...
npm install

REM Copy environment file
if not exist .env (
    echo ⚙️ Creating environment file...
    copy .env.example .env
) else (
    echo ⚠️ .env file already exists, skipping...
)

REM Generate application key
echo 🔑 Generating application key...
php artisan key:generate

REM Create SQLite database if it doesn't exist
if not exist database\database.sqlite (
    echo 🗄️ Creating SQLite database...
    type nul > database\database.sqlite
) else (
    echo ⚠️ Database file already exists, skipping...
)

REM Run migrations and seeders
echo 🗄️ Running migrations and seeders...
php artisan migrate:fresh --seed

REM Create storage link
echo 🔗 Creating storage link...
php artisan storage:link

REM Build frontend assets
echo 🎨 Building frontend assets...
npm run build

echo.
echo ✅ Setup completed successfully!
echo.
echo 🚀 To start the application, run:
echo    php artisan serve
echo.
echo 📧 Default login credentials:
echo    Admin: admin@eventmanagement.com / password
echo    Organizer: john@eventorganizer.com / password
echo    Client: alice@example.com / password
echo.
echo 🌐 Visit http://localhost:8000 to access the application
pause
