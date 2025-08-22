
# Prototype FF Academy
This is the backend of the E-Learning platform on investing. It is built with Laravel
and provides a RESTful API for managing courses, lessons, users, and lessonprogress. The backend handles authentication, validation, database operations, and API responses for the frontend application.

## Getting Started

1. **Clone the Repository:**

2. **Technologies Used:**
    - Laravel PHP8.1+
    - MySQL
    - Laravel Sanctum
    - Laravel Form Request

3. **Database Setup:**
    - Create a new MySQL database for the application.
    - Copy the `.env.example` file to `.env` and configure the database connection settings.

4. **Run Migrations and Seed Database:**
    ```bash
    php artisan migrate --seed
    ```

5. **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

6. **Serve the Application:**
    ```bash
    php artisan serve
    ```

Visit [http://localhost:8000](http://localhost:8000) in your browser to access the Site.


