# Laravel Blog API

This is a simple RESTful API for a blog post system using Laravel. It provides CRUD operations for blog posts. The API uses Laravel Sanctum for authentication.

## Requirements

- PHP 8.1+
- Composer
- MySQL/MariaDB or any other database supported by Laravel

## Setup

1. Clone the repository:
   ```
   git clone https://github.com/rodinmehr/3_sided_cube_api.git
   cd 3_sided_cube_api
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Copy the `.env.example` file to `.env` and configure the database settings:
   ```
   cp .env.example .env
   ```

4. Generate an application key:
   ```
   php artisan key:generate
   ```

5. Run migrations:
   ```
   php artisan migrate
   ```
6. Seed the database with sample data:
   ```
   php artisan db:seed
   ```

7. Generate OpenAPI (Swagger) documentation:
   ```
   php artisan l5-swagger:generate
   ```

## Running the Application

To start the development server, run:

```
php artisan serve
```

The API will be available at `http://localhost:8000/api`.

## API Endpoints

- POST `/api/login`: Log in and receive an access token
- POST `/api/logout`: Log out (requires authentication)
- GET `/api/posts`: Show all posts (Public)
- POST `/api/posts`: Create a new post (Requires Authentication)
- GET `/api/posts/{id}`: Show a specific post (Public)
- PUT `/api/posts/{id}`: Update a specific post (Requires Authentication)
- DELETE `/api/posts/{id}`: Delete a specific post (Requires Authentication)

You can use Postman collection that is provided in `3_sided_cube_api.postman_collection.json` file to test the endpoints.

## API Documentation

API documentation is available here: `http://localhost:8000/api/documentation` where detailed information about the API endpoints is displayed.

## Authentication

This API uses Laravel Sanctum for authentication. To authenticate:

1. Make a POST request to `/api/login` with the email and password to receive an access token.
2. Include the access token in the `Authorization` header of the requests:

```
Authorization: Bearer {received_access_token}
```

## Running Tests

To run the tests, it's better to set up a new database to preserve the original one. Copy the `.env.example` file to `.env.testing` and configure the test database settings:
```
cp .env.example .env.testing
```

Run migrations:

```
php artisan config:cache --env=.env.testing
php artisan migrate --env=.env.testing
```

To run the feature tests, use the following command:

```
php artisan test
```

## License

This open-source project is licensed under the MIT License - see the LICENSE file for details.
