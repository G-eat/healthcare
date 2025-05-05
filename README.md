
# Laravel Project Setup

## Prerequisites

Before you begin, make sure you have the following installed:

- PHP (>= 8.2)
- Composer
- SQLite or another database (depending on your project requirements)

## Installation Steps

### 1. Clone the Repository

Clone the Laravel project from GitHub to your local machine.

```bash
git clone https://github.com/G-eat/healthcare.git
```

### 2. Install Composer Dependencies

Navigate to the project directory and install the Composer dependencies:

```bash
cd healthcare
composer install
```

### 3. Set Up the `.env` File

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

### 4. Generate the Application Key

Run the following Artisan command to generate the application key:

```bash
php artisan key:generate
```

### 5. Set Up Database Configuration

Edit the `.env` file to match your local database credentials:

```plaintext
DB_CONNECTION=sqlite
```

### 6. Run Database Migrations

To set up the database schema, run the migrations:

```bash
php artisan migrate
```

### 7. Serve the Application

Now you can serve your Laravel application:

```bash
php artisan serve
```

Visit your application at `http://localhost:8000`.

## Notes

- Make sure you have the necessary permissions to run the server and access the database.
- If you make any changes to the `.env` file or environment variables, remember to clear the cache using the following command:

```bash
php artisan config:clear
```

---

If you need further help, feel free to reach out!