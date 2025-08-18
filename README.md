# Laravel Multi-Tenant Z360 SaaS Assessment

This project is a comprehensive demonstration of a multi-tenant Software-as-a-Service (SaaS) application built with Laravel 12. It showcases a robust architecture designed to handle multiple isolated tenant environments, a central landlord administration panel, and a global public-facing onboarding system.

The core of the application is built upon the `spatie/laravel-multitenancy` package, configured for domain-based tenant resolution and complete database isolation. The frontend is powered by Inertia.js, React, and TypeScript, offering a modern, single-page application experience.

---

## 🏛️ Core Architectural Concepts

The application is architecturally divided into three distinct, isolated environments, each serving a specific purpose.

### 1. Root Environment (Public Onboarding)
The public-facing entry point of the application, responsible for attracting and signing up new organizations (tenants).

*   **Domain:** `http://myapp.test`
*   **Purpose:** Handles the global, multi-step onboarding flow for new tenants. This environment operates completely outside of any tenant context.
*   **Database:** Interacts exclusively with the central `landlord` database.
*   **Key Features:**
    *   A secure, 5-step sign-up process with signed URLs to prevent step-skipping.
    *   Global email uniqueness validation to prevent duplicate onboarding sessions.
    *   Uses a queued job (`ProvisionTenantJob`) to handle tenant creation in the background, providing a seamless user experience.

### 2. Landlord Environment (Admin Panel)
The administrative backend for the SaaS owner to manage the entire application and all its tenants.

*   **Domain:** `http://landlord.myapp.test`
*   **Purpose:** Provides a centralized dashboard to view and manage all tenants and their statuses.
*   **Database:** Interacts exclusively with the central `landlord` database.
*   **Key Features:**
    *   Secure, session-based authentication (`LandlordAuth` middleware).
    *   A dashboard listing all tenants, their domains, and current status (e.g., `provisioning`, `active`).
    *   Ability to view details of individual tenants and delete them.
    *   Functionality to view and manage incomplete onboarding sessions.

### 3. Tenant Environment (Customer Workspace)
The isolated, private workspace for each individual tenant organization. This is the core product that customers use.

*   **Domain:** `http://{tenant}.myapp.test` (e.g., `http://xyz.myapp.test`, `http://status.myapp.test`)
*   **Purpose:** Provides a dedicated application instance for each tenant with complete data isolation.
*   **Database:** Each tenant has their own dedicated database (e.g., `tenant_xyz_db`), which is created automatically during onboarding.
*   **Key Features:**
    *   **Complete Data Segregation:** Tenant data is stored in separate databases, ensuring privacy and security.
    *   **Subdomain-Based Routing:** The `EnsureTenantContext` middleware identifies the tenant from the subdomain, finds their database, and switches the application's database connection on the fly.
    *   **Tenant-Specific Authentication:** Users log into their specific workspace, and their credentials are validated only against their own tenant database.

---

## ✨ Key Features Implemented

*   **Modern Frontend Stack:** The application uses a modern, reactive frontend built with **Inertia.js**, **React**, and **TypeScript**, providing a seamless, single-page application experience.
*   **Dynamic Tenant Provisioning:** New tenants and their databases are created automatically upon successful sign-up.
*   **Domain-Based Tenant Resolution:** Uses the powerful `DomainTenantFinder` from the Spatie package to identify tenants.
*   **Queued Jobs for Performance:** Tenant creation is offloaded to a background queue, preventing UI delays.
*   **Secure Onboarding Flow:** A secure, multi-step sign-up process with signed URLs prevents users from skipping steps.
*   **Secure Landlord Authentication:** The landlord admin panel is protected, and admin credentials are now managed securely via database seeders, removing hard-coded passwords.
*   **Automated Session Cleanup:** A scheduled command automatically cleans up abandoned onboarding sessions to maintain database hygiene.
*   **Comprehensive Test Coverage:** The application includes a suite of feature tests to ensure the reliability and correctness of the onboarding flow and other critical features.
*   **Clean & Professional Code Structure:** Logic is properly abstracted into Controllers and Middleware, keeping route files clean, readable, and declarative.

---

## � Local Development Setup (Docker)

This project is configured to run in a local development environment using Docker.

### Prerequisites
*   Docker Desktop

### Step 1: Clone & Configure Environment

```bash
# Clone the repository
git clone https://github.com/hammasshamsi/z360_assessment.git myassessment
cd myassessment

# Copy the environment file
cp .env.example .env
```

### Step 2: Configure `.env` for Docker

Open the `.env` file and ensure the following variables are set correctly for the Docker environment:

```dotenv
APP_NAME="Laravel Multi-Tenant Assessment"
APP_URL=http://myapp.test
APP_DOMAIN=myapp.test

# Landlord Database Connection
DB_CONNECTION=landlord
DB_HOST=db
DB_PORT=3306
DB_DATABASE=landlord
DB_USERNAME=root
DB_PASSWORD= # Your desired root password for the DB container

# Session Configuration for Subdomain Sharing
SESSION_DOMAIN=.myapp.test
```

### Step 3: Configure Local Host Environment

For the application's domain and subdomains to work on your local machine, you need to edit your `hosts` file.

1.  Open your `hosts` file with administrator/sudo privileges.
    *   **Windows:** `C:\Windows\System32\drivers\etc\hosts`
    *   **macOS/Linux:** `/etc/hosts`
2.  Add the following lines. You must add a new line for every tenant you create during testing.

```
127.0.0.1    myapp.test
127.0.0.1    landlord.myapp.test
# Add tenants you create here for testing
# 127.0.0.1    xyz.myapp.test
```

### Step 4: Build and Run the Application

Use `docker-compose` to build the images and run the containers in the background.

```bash
docker-compose up -d --build
```

### Step 5: Final Application Setup

Once the containers are running, execute the following commands to finalize the setup inside the `app` container.

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Generate the application key
docker-compose exec app php artisan key:generate

# Run landlord migrations to set up the central database
docker-compose exec app php artisan migrate --database=landlord

# Seed the database with the landlord admin user
docker-compose exec app php artisan db:seed

# Install NPM dependencies and build frontend assets
docker-compose exec app npm install
docker-compose exec app npm run dev
```

### Step 6: Run the Queue Worker

The queue worker is **essential** for processing new tenant sign-ups. Run it in a separate terminal:

```bash
docker-compose exec app php artisan queue:work --queue=provisioning
```

---

## 🧪 Running Tests

To run the application's test suite, use the following command:

```bash
docker-compose exec app php artisan test
```

---

## 🚀 Using the Application

You are now ready to use the application!

1.  **Onboard a New Tenant:**
    *   Navigate to `http://localhost:8000` (which maps to `http://myapp.test` inside the container) and follow the sign-up flow.
    *   **Remember:** After creating a tenant (e.g., with subdomain `xyz`), you must add `127.0.0.1 xyz.myapp.test` to your `hosts` file.

2.  **Access the Tenant Workspace:**
    *   Navigate to your tenant's URL: `http://xyz.myapp.test:8000/login`
    *   Log in with the credentials you created during onboarding.

3.  **Access the Landlord Panel:**
    *   Navigate to `http://landlord.myapp.test:8000`
    *   Log in with the seeded admin credentials. You will be able to see the new tenant you created.
