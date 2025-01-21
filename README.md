# Telkomsigma | Laravel + Vue

<div align="center">
  <img src="https://i.imgur.com/9aPcbIw.jpeg" alt="Image Description" style="width: auto; height: 500px;">
</div>

## Installation Guide

### Prerequisites

Ensure the following tools are installed on your system:

- **PHP >= 8.0** with required extensions
- **Composer**
- **Node.js >= 14** and **npm**
- **MySQL** or any compatible database

### Steps

1. **Clone the Repository**
   Clone this repository to your local machine using the following command:

    ```sh
    git clone https://github.com/mtharreqq/laravel-vue-telkomsigma.git
    ```

2. **Navigate to the Project Directory**
   Move into the project folder:

    ```sh
    cd laravel-vue-telkomsigma
    ```

3. **Install PHP Dependencies**
   Use Composer to install all required PHP dependencies:

    ```sh
    composer install
    ```

4. **Install Node.js Dependencies**
   Install the required Node.js packages:

    ```sh
    npm install
    ```

5. **Set Up Environment Variables**
   Create a `.env` file by copying the example file:

    ```sh
    cp .env.example .env
    ```

6. **Run Database Migrations**
   Apply the database migrations to set up the database schema:

    ```sh
    php artisan migrate
    ```

7. **Seed the Database**
   Populate the database with initial data using the seeder:
    ```sh
    php artisan db:seed --class=PersonsSeeder
    ```

## How to Run the Application

1. **Start the Development Server**
   Use the following command to start the server:

    ```sh
    composer run dev
    ```

2. **Access the Application**
   Open your browser and navigate to:
    ```
    http://127.0.0.1:8000
    ```

## License

This project is licensed under the [MIT License](LICENSE).

For further inquiries or contributions, feel free to submit an issue or a pull request.
