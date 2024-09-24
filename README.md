# Web App with OAuth, Permissions, and API Use

This web application showcases secure web development techniques by implementing user authentication (with secure password storage), OAuth integration, API usage for features like profanity filtering, and role-based access controls. The app also includes a visual dashboard for admins to manage users and permissions.

## Features
- **User Authentication**: Secure login and registration using password hashing with salt.
- **OAuth Integration**: Allows users to link their Discord account for additional features.
- **Permissions Management**: Admins can manage roles (admin, moderator, basic) and grant or revoke access to specific areas of the application.
- **API Integration**:
  - IP to Location API
  - Profanity Cleaner API
  - Synonym Finder API (based on the selected word in registration)
- **Access Logging**: Tracks user activity, including access attempts to restricted pages.
- **Encryption**: Sensitive user data is encrypted and stored securely in the database.

## Tech Stack
- **Backend**: PHP, MySQL, OAuth (via Guzzle), API usage
- **Frontend**: HTML, CSS (with basic styling), and PHP templating
- **Server**: Azure VM running Ubuntu 20.04
- **Database**: MySQL with user roles, access logs, and encrypted user data

## Getting Started

### Prerequisites
Ensure you have the following software installed on your machine:
- **Azure VM**: You can use VM
- **Ubuntu 20.04** (running on your Azure VM)
- **PHP 7.x** or later
- **MySQL 8.x** or later
- **Composer** (for managing Guzzle and other PHP dependencies)
- **phpMyAdmin** (optional for managing the MySQL database via UI)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/DipPandey/Web-App-with-OAuth-and-Permissions-and-API-use.git
   cd Web-App-with-OAuth-and-Permissions-and-API-use

   # Web Application with OAuth, Permissions, and API Use

This application showcases secure web development techniques, including secure authentication, OAuth, encryption of user data, API usage for features, and permission restrictions between different users. It also includes a visual dashboard for the application.

## Setup Instructions

### 1. Set Up the Database:

1. Create a MySQL database for the app:
   ```sql
   CREATE DATABASE secure_app;
   USE secure_app;
Run the SQL scripts provided in the /database/ folder to create the necessary tables (users, roles, access_logs).

Modify the /config/db.php file to add your database credentials.

**2. Install Dependencies:**
Install the required PHP packages using Composer:


composer install
**3. Configure the Environment:**
Rename the .env.example file to .env and add your environment variables (such as MySQL credentials and API keys for the IP, Profanity, and Words API).
Ensure that your Azure VM has proper SSL configuration for secure HTTPS access.
**4. Set Up the Server:**
Deploy the application on the Azure VM using Apache or Nginx.
Ensure that the virtual host is configured correctly with SSL (HTTPS).
**5. Set Up OAuth:**
Register your application with Discord to obtain an OAuth client ID and secret.
Add these credentials to the .env file.
Usage Instructions
**Registration:**
Visit the registration page at http://your-vm-ip/viewsregister.php.
Provide the required details: username, password, and additional custom data.
After registration, you'll be able to log in.
**Login:**
Navigate to the login page at http://your-vm-ip/viewslogin.php to log in using your credentials.

**Secure Pages:**
Once logged in, access the secure dashboard where you can:

View the custom data provided at registration.
See the API-based features (e.g., IP to location, profanity filtering, synonyms).
Admins can manage users and their roles on the permissions page.
Admin Access:
Only users with the "admin" role can view and manage the access logs and user roles. This can be done from the Permissions page and Access Log page.

**API Documentation**
This project uses several APIs for its features. Below is a brief description of each:

IP to Location API: Converts an IP address to a human-readable location. API Documentation
Profanity Cleaner API: Censors profanity from user input. API Documentation
Words API: Retrieves synonyms for a given word. API Documentation
Security Considerations
All user passwords are hashed with salt for secure storage.
User sessions are secured with PHP session management techniques.
Sensitive user data is encrypted before storage and decrypted on retrieval.


**Troubleshooting**
If you encounter errors with database connections, ensure that your MySQL user has proper permissions and the database is correctly set up.
For OAuth issues, check that your client ID and secret are correct and your callback URL is configured properly in Discord.

**License**
This project is licensed under the MIT License.

   
