# Caree Hotel Guest Management and Reservation System

A modern **web-based guest management and reservation system** built with **Laravel**, designed for Caree Hotel. This system streamlines hotel operations by providing an efficient and user-friendly platform for managing room reservations, guest information, and dynamic pricing.

It enhances the overall booking experience by allowing guests and administrators to interact with real-time data and simplified reservation workflows.

---

## Key Features

- 🏨 **Real-time Room Availability**
- 🛏️ **Detailed Room Information Viewing**
- 📅 **Direct Online Reservation System**
- 💳 **Guest Identification Management**
- 📊 **Micro Pricing Integration for Dynamic Room Rates**
- 🧾 **Structured Guest and Booking Management**

---

## System Objective

The main objective of this system is to enhance and integrate a web-based guest management and reservation platform for Caree Hotel with micro pricing capabilities.

Specifically, the system aims to:

1. Develop a web-based platform that enhances the guest booking experience by enabling:
    - Real-time availability checking
    - Viewing of detailed room specifications
    - Direct reservation capabilities
    - Guest identification management

2. Integrate a micro pricing system for dynamic and flexible room pricing based on demand and other factors.

---

## Getting Started

Follow these steps to set up the system:

###

1. **Clone the repository**

    ```sh
    git clone https://github.com/DJmistMAGO/chms.git
    cd chms
    ```

2. **Install PHP dependencies**

    ```sh
    composer install
    ```

3. **Install Node.js dependencies**

    ```sh
    npm install
    ```

4. **Copy the example environment file and set your configuration**

    ```sh
    cp .env.example .env
    ```

5. **Generate the application key**

    ```sh
    php artisan key:generate
    ```

6. **Run database migrations**

    ```sh
    php artisan migrate
    ```

7. **Start the development server**

    ```sh
    php artisan serve
    ```

8. **Compile frontend assets**

    ```sh
    npm run dev
    ```

9. **Link Storage**

    ```sh
    php artisan storage:link
    ```

Now you can access the application at `http://localhost:8000`.

---

### Or if using Laragon

If you are using Laragon, follow these steps:

1. Place the cloned `chms` folder inside your Laragon `www` directory (e.g., `C:\laragon\www\chms`).
2. Start Laragon and ensure Apache/Nginx and MySQL are running.
3. Open a terminal in the project directory and run:

    ```sh
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    npm run dev
    php artisan storage:link
    ```

4. Visit your project in the browser at `http://chms.test` (or the domain Laragon assigns).
