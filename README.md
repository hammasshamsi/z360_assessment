# Laravel Multi-Tenant Z360 SaaS Assessment

This project is a comprehensive demonstration of a multi-tenant Software-as-a-Service (SaaS) application built with Laravel 12. It showcases a robust architecture designed to handle multiple isolated tenant environments, a central landlord administration panel, and a global public-facing onboarding system.

The core of the application is built upon the `spatie/laravel-multitenancy` package, configured for domain-based tenant resolution and complete database isolation.

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

*   **Dynamic Tenant Provisioning:** New tenants and their databases are created automatically upon successful sign-up.
*   **Domain-Based Tenant Resolution:** Uses the powerful `DomainTenantFinder` from the Spatie package to identify tenants.
*   **Queued Jobs for Performance:** Tenant creation is offloaded to a background queue, preventing UI delays.
*   **Custom Middleware:**
    *   `LandlordAuth`: A simple middleware to protect the landlord administration panel.
*   **Clean & Professional Code Structure:** Logic is properly abstracted into Controllers and Middleware, keeping route files clean, readable, and declarative.
*   **Secure Authentication Flow:** Implements CSRF protection and session management correctly across multiple subdomains.

---

## 💻 Local Development Setup

This project is configured to run in a local development environment using **XAMPP** (or a similar stack with Apache).

### Prerequisites
*   XAMPP (with Apache & MySQL)
*   PHP 8.2 or higher
*   Composer

### Step 1: Clone & Install Dependencies

```bash
# Clone the repository
git clone https://github.com/hammasshamsi/z360_assessment.git myassessment
cd myassessment

# Install PHP dependencies
composer install

# Copy the environment file
cp .env.example .env
```

### Step 2: Configure Environment (`.env`)

Open the `.env` file and configure the following variables:

```dotenv
APP_NAME="Laravel Multi-Tenant Assessment"
APP_URL=http://myapp.test
APP_DOMAIN=myapp.test

# Landlord Database Connection
DB_CONNECTION=landlord
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=landlord
DB_USERNAME=root
DB_PASSWORD=

# Session Configuration for Subdomain Sharing
SESSION_DOMAIN=.myapp.test
```

### Step 3: Database Setup

1.  Open phpMyAdmin (or your preferred MySQL client).
2.  Create a new, empty database named `myassessment_landlord`.
3.  Run the landlord migrations to create the necessary tables.

```bash
php artisan migrate --database=landlord
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Configure Local Host Environment

**A. Configure Windows `hosts` file:**

1.  Open **Notepad** as an **Administrator**.
2.  Open the file: `C:\Windows\System32\drivers\etc\hosts`
3.  Add the following lines to the end of the file. You must add a new line for every tenant you create during testing.

```
127.0.0.1    myapp.test
127.0.0.1    landlord.myapp.test
# Add tenants you create here for testing
# 127.0.0.1    xyz.myapp.test
# 127.0.0.1    status.myapp.test
```

**B. Configure Apache Virtual Host:**

1.  Open the Apache config file: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2.  Add the following Virtual Host configuration. This single block handles the root domain and all wildcard subdomains.

```apache
<VirtualHost *:80>
    # IMPORTANT: Update this path to your project's public folder
    DocumentRoot "C:/path/to/your/project/myassessment/public"
    ServerName myapp.test
    ServerAlias *.myapp.test
    
    <Directory "C:/path/to/your/project/myassessment/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>
</VirtualHost>
```
**C. Use DNS Server App:**
1. Click your new zone myapp.test.
*   **Click Add Record**: Name: *, Type: A, Data: 127.0.0.1


2. Ensure Root Domain Works Too, Add another A record:
*   **Click Add Record**: Name: @, Type: A, Data: 127.0.0.1


### Step 6: Start Services & Run Queue

1.  **Start Apache and MySQL** from the XAMPP Control Panel.
2.  **Run the Queue Worker:** Open a new terminal in your project directory and run the following command. This worker is **essential** for processing new tenant sign-ups.

```bash
php artisan queue:work --queue=provisioning
```

---

## 🚀 Using the Application

You are now ready to use the application!

1.  **Onboard a New Tenant:**
    *   Navigate to `http://myapp.test` and follow the sign-up flow.
    *   **Remember:** After creating a tenant (e.g., with subdomain `xyz`), you must add `127.0.0.1 xyz.myapp.test` to your `hosts` file if not using DNS Server App.

2.  **Access the Tenant Workspace:**
    *   Navigate to your tenant's URL: `http://xyz.myapp.test/login`
    *   Log in with the credentials you created during onboarding.

3.  **Access the Landlord Panel:**
    *   Navigate to `http://landlord.myapp.test`
    *   You will be able to see the new tenant you created.
