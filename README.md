# GameCritic - Video Games Review Website

Welcome to **GameCritic**, a website where you can discover and review your favorite video games.

## Description
This project allows users to:
- Browse and read reviews of video games.
- Submit their own reviews and rate games.
- Explore games by genre and view the top-rated ones.

## Features
- **Top Rated Games**: Display the highest-rated games.
- **Latest Reviews**: Show the most recent game reviews.
- **Game Categories**: Browse games based on their genres.
- **Add a Review**: Submit your own reviews for games.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL

## Installation Instructions

### 1. Clone the repository
To get a copy of this project on your local machine, run:
```bash
git clone https://github.com/Amallsa/Game-review-site.git
2. Set up the database
Open phpMyAdmin or MySQL Workbench.

Create a new database (e.g., game_reviews).

Import the file database_schema.sql into that database to create the necessary tables (games, reviews, categories).

3. Configure the database connection
Open the file db_connect.php.

Update the following variables to match your local database credentials:

php
Copy code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "game_reviews";
4. Set up PHP and MySQL locally
Make sure PHP and MySQL are installed (you can use XAMPP).

Place the project folder inside the htdocs directory in XAMPP.

5. Access the website
Start Apache and MySQL from the XAMPP control panel.

Open your browser and go to:

bash
Copy code
http://localhost/Game-review-site/index.php
Contributing
Feel free to fork this repository, make your changes, and submit a pull request. Contributions are always welcome!

License
This project is open-source and available under the MIT License
