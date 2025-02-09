# Guitar Shop Web Application

## Overview
This is a simple Guitar Shop web application built using PHP with Object-Oriented Programming (OOP) principles and MySQL with PHP Data Objects (PDO) for secure database connectivity. The application allows users to browse guitars, manage their cart, place orders, and perform authentication (login, logout, and registration).

---

## Features
- User Authentication (Login, Logout, and Registration) with password hashing (bcrypt)
- Guitar Listing and Management (CRUD operations)
- Secure Database Connectivity using PDO
- Shopping Cart Functionality
- Order Processing with Inventory Update
- Navigation Bar dynamically updates based on authentication
- Access restriction for protected pages

---

## Technologies Used
- PHP (Object-Oriented Programming)
- MySQL (Database)
- PDO (Secure Database Connection)
- HTML/CSS (Basic UI Styling)

---

## Installation & Setup

### Step 1: Clone the Repository
```sh
 git clone https://github.com/your-repository/guitar-shop.git
 cd guitar-shop
```

### Step 2: Setup the Database
1. Create a new database in MySQL:
   ```sql
   CREATE DATABASE guitar_shop;
   ```
2. Import the provided **guitar_shop.sql** file into the database:
   ```sh
   mysql -u root -p guitar_shop < guitar_shop.sql
   ```

### Step 3: Configure Database Connection
1. Open **Database.php** and update the credentials:
   ```php
   private $host = "localhost";
   private $db_name = "guitar_shop";
   private $username = "root";
   private $password = "";
   ```

### Step 4: Run the Application
Start a local PHP server:
Open **http://localhost/guitar-shop** in your browser.

---

## File Structure
```
/guitar-shop
│── index.php             # Homepage with Guitars Listing
│── header.php            # Header
│── login.php             # User Login Page
│── registration.php      # User Registration Page
│── logout.php            # Logout Logic
│── add_guitar.php        # Add a New Guitar
│── edit_guitar.php       # Edit Guitar Details
│── delete_guitar.php     # Delete a Guitar
│── view_cart.php         # Shopping Cart
│── checkout.php          # Checkout Process
│── orders.php            # Order History
│── auth.php              # Authentication Helper
│── Database.php          # Database Connection
│── User.php              # User Model
│── Guitar.php            # Guitar Model
│── Order.php             # Order Model
│── OrderItem.php         # Order Item Model
│── guitar_shop.sql       # Database Schema with Dummy Data
```

---

## Authentication & User Access Control

### **Login Process**
1. Open **login.php**.
2. Enter your username and password.
3. Upon successful authentication, you are redirected to **index.php**.

### **Logout Process**
1. Click on **Logout** in the navigation bar.
2. The session is cleared, and you are redirected to the **login.php** page.

### **Access Restriction**
The following pages require login. If not logged in, users are redirected to **login.php**:
- **checkout.php**
- **view_cart.php**

---

## CRUD Operations

### **Guitar Management (Admin Only)**
- **Add a Guitar:** `add_guitar.php`
- **Edit a Guitar:** `edit_guitar.php`
- **Delete a Guitar:** `delete_guitar.php`

### **Shopping Cart & Orders**
- **Add to Cart** (from `guitars.php`)
- **View Cart** (`view_cart.php`)
- **Checkout** (`checkout.php`)

