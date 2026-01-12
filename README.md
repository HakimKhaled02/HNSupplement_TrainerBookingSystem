# HN Supplement - Laravel Website

A Laravel-based website with Bootstrap styling and a black theme.

## Features

- ✅ Black theme design with green accent colors
- ✅ Reusable navbar component with logo
- ✅ Reusable footer component
- ✅ Bootstrap 5 integration
- ✅ Responsive design
- ✅ MySQL database ready (XAMPP)

## Project Structure

```
hnsupplement/
├── app/
│   └── Http/
│       └── Controllers/
│           └── HomeController.php
├── public/
│   └── images/
│       └── logo.jpg
├── resources/
│   └── views/
│       ├── components/
│       │   ├── navbar.blade.php    # Reusable navbar
│       │   └── footer.blade.php    # Reusable footer
│       ├── layouts/
│       │   └── app.blade.php       # Main layout
│       ├── home.blade.php          # Homepage
│       └── example.blade.php       # Example page
└── routes/
    └── web.php                     # Routes
```

## Setup Instructions

1. **Install Laravel dependencies** (if not already installed):
   ```bash
   composer install
   ```

2. **Configure your .env file**:
   - Set up your database connection for MySQL/XAMPP
   - Update `DB_CONNECTION=mysql`
   - Update `DB_HOST=127.0.0.1`
   - Update `DB_PORT=3306`
   - Update `DB_DATABASE=your_database_name`
   - Update `DB_USERNAME=root`
   - Update `DB_PASSWORD=` (usually empty for XAMPP)

3. **Start XAMPP**:
   - Start Apache and MySQL services

4. **Run the development server**:
   ```bash
   php artisan serve
   ```

5. **Access the website**:
   - Open your browser and go to `http://localhost:8000`

## Using Navbar and Footer in Other Pages

To use the navbar and footer in any page, simply extend the main layout:

```blade
@extends('layouts.app')

@section('title', 'Your Page Title')

@section('content')
    <!-- Your page content here -->
@endsection
```

The navbar and footer are automatically included in the `layouts/app.blade.php` file using:
- `@include('components.navbar')` for the navbar
- `@include('components.footer')` for the footer

## Customization

- **Logo**: Replace `public/images/logo.jpg` with your logo
- **Colors**: Modify CSS variables in `resources/views/layouts/app.blade.php`
- **Navbar links**: Edit `resources/views/components/navbar.blade.php`
- **Footer content**: Edit `resources/views/components/footer.blade.php`

## Technologies Used

- Laravel (PHP Framework)
- Bootstrap 5
- MySQL (via XAMPP)
- Blade Templating Engine

