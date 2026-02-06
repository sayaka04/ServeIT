<br>
<br>
<p align="center">
<a href="https://github.com/sayaka04/laravel-ai-image-sorter"><img src="https://img.shields.io/badge/ServelT:- Tech Repair Redefined-blue?style=flat-pill" alt="Build Status" style="height:70px"></a>
</p>


<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel"></a>
  <a href="https://laravel.com/docs/11.x/reverb"><img src="https://img.shields.io/badge/Laravel_Reverb-Websocket-42b883?style=for-the-badge&logo=laravel" alt="Reverb"></a>
  <a href="https://getbootstrap.com/"><img src="https://img.shields.io/badge/Bootstrap-5.3-7952b3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap"></a>
</p>
<p align="center">
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-8.0.28-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="https://leafletjs.com"><img src="https://img.shields.io/badge/Leaflet.js-Proximity_Map-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet"></a>
  <a href="#"><img src="https://img.shields.io/badge/Security-AES--256--CBC-005C94?style=for-the-badge" alt="AES-256"></a>
</p>

**ServelT** is a web-based platform designed to bridge the gap between gadget owners and verified repair technicians. It streamlines the repair process through geo-location services, real-time status tracking, and a secure vetting system for service providers.

---

## 🛠️ Key Technical Features

* **Weighted Matching (WSA):** Implements a **Weighted Scoring Algorithm** in MySQL to rank technicians based on location, rating, certification, and success record, ensuring optimal client-technician matching.
* **Proximity Search:** Uses **Leaflet.js** and the **Haversine formula** combined with boundary approximation for location-based searching, allowing accurate matching without compromising user GPS privacy.
* **Real-Time & Secure Communication:** Integrates **Laravel Reverb WebSocket** for real-time updates and includes a secure chat feature utilizing **AES-256-CBC encryption** to protect user privacy.
* **Inclusive Credibility System:** Features automated **TESDA verification** for certified technicians, while granting legitimacy to non-certified technicians through digital portfolios and user feedback.
* **Full-Stack Architecture:** Built using the **PHP Laravel framework** (backend) and standard frontend technologies (HTML, CSS, JavaScript, Bootstrap), hosted on a **VPS**.

---

## 🚀 Key Features

### 🌍 For Clients (The Service Seekers)
* **Geo-Location Search:** Find nearby technicians using an interactive map with adjustable search radius.
* **Verified Experts:** Identify trusted professionals via the "Verified Badge" (TESDA Certificate verification) and "Success Rate" indicators.
* **Filtering:** Filter technicians by specific skills (e.g., Micro-soldering, Desktop Hardware), home service availability, and ratings.
* **Progress Tracking:** Monitor the status of your device (In Progress, Completed, Unclaimed) directly from the dashboard and the repairs page for more details.
* **Secure Messaging:** Chat with technicians before hiring, with support for attachments to diagnose and discuss issues remotely.
* **Digital Receipts:** Automatic logging of repair history for warranty and record-keeping.

### 🛠️ For Technicians (The Service Providers)
* **Digital Job Ledger:** A centralized system to create repair orders, track active jobs, and manage walk-in customers alongside app users.
* **Profile Management:** customizable public profile with a "Service Area Map," expertise tags, and portfolio uploads to attract clients.
* **Business Analytics:** Dashboard charts visualizing "Total Repairs per Month" and "Repair Type Breakdown" to track business growth.
* **Calendar Management:** A built-in deadline tracker to ensure timely repair completions.
* **Client Management:** Manage leads and communicate with clients using the integrated messaging system.

### 🛡️ For Admins (The Oversight)
* **Dispute Resolution Center:** A dedicated module to investigate reports, view evidence (screenshots/PDFs), and resolve conflicts between users.
* **User Management:** Tools to monitor, warn, suspend, or ban users based on behavior patterns.
* **Safety Monitoring:** Track "Reporter History" vs. "Reported History" to identify serial complainers or scammers.
* **Category Control:** Add or archive repair skill categories as technology evolves.

---

## 📖 User Workflows

### 1. The Client Journey
1.  **Search:** Client sets a location pin and filters for a specific repair skill.
2.  **Vet:** Client views Technician profiles, checking TESDA credentials and reviews.
3.  **Inquire:** Client sends a message (with photos of the broken device) to discuss pricing.
4.  **Track:** Once the device is handed over, the Client tracks the "Repair Status" via their dashboard until completion.

### 2. The Technician Journey
1.  **Setup:** Technician pins their shop location and uploads verification documents.
2.  **Intake:** Technician uses the **"Create Repair"** button to log a new device (capturing Serial # and Condition).
3.  **Repair:** Technician updates the job status on the Ledger as work progresses.
4.  **Complete:** Status is flipped to "Completed/Unclaimed," notifying the client for pickup.

---

## 🔧 Getting Started

### Prerequisites
## Prerequisites


To avoid compatibility issues with WebSockets and modern Laravel features, ensure you have the following installed (latest versions recommended):

- **PHP:** `^8.2.4`  
  Required for Laravel 11 and Reverb

- **Composer:** `^2.7.3`  
  PHP dependency management

- **Node.js & NPM:** `^20.12.2` or `^10.8.3`  
  LTS recommended for Vite and asset bundling

- **MySQL:** `^8.010.4.28-MariaDB`  
  Required for optimized Weighted Scoring Algorithm support

- **Web Server:** Apache or Nginx  
  Alternatively, use `php artisan serve` for development

<br>

### Installation & Setup
## Installation & Setup

Follow these steps in order to set up your environment:

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/servelt.git
cd servelt
````

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install and build frontend dependencies

```bash
npm install
npm run build
```


### 4. Configure environment variables

Copy the example environment file:

```bash
cp .env.example .env
````

Generate an application key:

```bash
php artisan key:generate
```

Open the `.env` file and **modify the configuration as needed for your local environment**, including:

* Your MySQL database credentials which is already added.
* Laravel Reverb settings
* **SMTP / Mail configuration** (use your own mail service credentials — SMTP is not preconfigured for security reasons)

> ⚠️ Mail credentials are intentionally not provided.
> You must set up your own SMTP provider (e.g. Gmail, Mailtrap, Mailgun, etc.) to enable email functionality.

### 5. Initialize the database

Run migrations and seed the database with initial categories and data:

```bash
php artisan migrate --seed
```

### 6. Start the application

In one terminal, start the WebSocket server (for real-time messaging):

```bash
php artisan reverb:start
```

In another terminal, start the local development server:

```bash
php artisan serve
```


---

## 📂 Project Structure & Modules

The application is organized into specialized directories that handle the distinct workflows of Clients, Technicians, and Admins:

### 📊 `/dashboard` - User Analytics
The central hub for all users. It displays personalized metrics, including:
* **Clients:** Ongoing vs. Unclaimed repair counts.
* **Technicians:** Performance analytics (Monthly repairs, success rates).
* **Scheduling:** A unified Repair Deadlines Calendar to track service dates.

### 🛠️ `/technicians` - Provider Management
Dedicated to the Technician’s business presence:
* **Professional Profile:** Management of expertise tags (e.g., iOS, Micro-soldering).
* **Public Listing:** Configuration of the shop/base location and service area mapping.
* **Credentials:** Upload and verification of TESDA certificates and portfolios.

### 👥 `/users` - Client Services
Handles private account settings and client-specific data:
* **Personal Profile:** Management of contact info and default service locations.
* **Identity:** Secure authentication and account verification logs.

### 🔍 `/search` - Discovery & Mapping
The core engine where Clients find Technicians. This module contains:
* **Proximity Mapping:** Interactive geo-location pins showing nearby shops.
* **Filtering Algorithm:** Advanced search by skill category, rating, and service type (Home Service vs. Shop-in).
* **Vetting:** Direct access to technician ratings and verified credentials.

### 📝 `/repairs` - The Repair Lifecycle
The most critical module where the "Business Logic" happens. It manages the entire transaction flow:
* **Intake & Creation:** Technician-led creation of Repair Orders (Digital Ledger).
* **Legal & Agreement:** Handling of digital signing, terms agreement, and reception notes (device condition).
* **Progress Tracking:** Real-time updates where Technicians upload progress and Clients monitor status.
* **Financials:** Detailed cost breakdown of parts vs. labor.
* **Resolution:** Management of device claiming, cancellations, and final service history logs.

### 💬 `/conversations` - Communications
A secure environment for pre-repair and active-repair inquiries:
* **Direct Messaging:** Real-time chat between Clients and Technicians.
* **Media Handling:** A queued system for sending photos/videos of damaged devices.
* **Inquiry Tracking:** Notification badges for unread leads and messages.

---

## 🚀 Key Workflows

### 1. The Search & Hire Flow (Client-Side)
* Use the **`/search`** module to filter technicians by distance and expertise.
* Review credentials in the **`/technicians`** profile view.
* Initiate a chat via **`/conversations`** to discuss the issue.

### 2. The Repair Lifecycle (Technician-Side)
* Use the **`/repairs`** module to "Create Repair."
* Select a Registered Client or log a "Walk-in" customer.
* Document device model, serial number, and physical condition upon reception.
* Update progress until the job moves to "Completed" for client pickup.

### 3. Oversight & Safety (Admin-Side)
* Monitor system-wide reports and disputes.
* Investigate "Reporter vs. Reported" histories to ensure platform integrity.
* Manage repair categories and user restrictions.

