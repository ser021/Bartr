# Bartr
Bartr is a platform that allows users to post listings, browse items, view user profiles, and message other users to negotiate trades.

## Features
- User Registration and Login
- Create and Delete Listings
- Browse Listings (Search Listings not fully implemented)
- View User Profiles
- Send and Receive Messages

## Installation
1. Clone repository
2. Set up the database
  - Import the SQL dump into MySQL (provided by SQL file)
  - To pull up MySQL:
      1. Download and install XAMPP
      2. Set up XAMPP with login info
      3. Open XAMPP Control Panel
      4. Start Apache and MySQL
      5. Go to `http://localhost/phpmyadmin/`
      6. Select to import
      7. Choose file and enter the SQL file
      8. Scroll to bottom and select "Import"
3. Configure database connection settings in `config.php`
4. Run the application using XAMPP or a local web server

## Usage
- Open `http://localhost/bartr` in your browser
- Register a new user or log in with an existing account
- Start managing your listings, profile, and messages

## Folder Structure
/bartr
  /css
  /images
  /php
  /db

## Technologies Used
- PHP
- XAMPP
- MySQL
- HTML/CSS

## Known Issues
- No admin implemented in this version
- Images limited to JPG and PNG
- Search Listing written but not fully implemented

## Contributors
- Sophia Arwen Razon (externally contributed to PHP backend scripts from Github)
- Renee Lopez (contributed to frontend integration and uploaded files)
- Jade Odom (externally contributed to PHP backend scripts from Github)
