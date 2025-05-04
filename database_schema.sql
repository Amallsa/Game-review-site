-- Create the database
CREATE DATABASE IF NOT EXISTS gamereviews_db;
USE gamereviews_db;

-- Create categories table (game genres)
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create games table
CREATE TABLE IF NOT EXISTS games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    developer VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    playtime INT,
    release_year INT,
    category_id INT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- Insert sample categories (game genres)
INSERT INTO categories (category_name, description) VALUES
('Action', 'Games focused on physical challenges, including hand-eye coordination and reaction time'),
('RPG', 'Role-playing games where players assume the roles of characters in a fictional setting'),
('Strategy', 'Games that focus on skillful thinking and planning to achieve victory'),
('Adventure', 'Games that emphasize exploration, puzzle-solving, and narrative'),
('Simulation', 'Games designed to simulate aspects of real or fictional reality');

-- Insert sample games
INSERT INTO games (title, developer, description, rating, playtime, release_year, category_id, image_url) VALUES
('The Legend of Zelda: Breath of the Wild', 'Nintendo', 'An open-world action-adventure game with a focus on exploration and discovery in a vast world.', 9.5, 120, 2017, 4, 'images/botw.jpg'),
('Elden Ring', 'FromSoftware', 'An action RPG set in a fantasy open world with challenging combat and deep lore created in collaboration with George R.R. Martin.', 9.3, 100, 2022, 2, 'images/elden_ring.jpg'),
('Civilization VI', 'Firaxis Games', 'A turn-based strategy game where players build and lead a civilization from the ancient era to the modern age.', 8.7, 80, 2016, 3, 'images/civ6.jpg'),
('Red Dead Redemption 2', 'Rockstar Games', 'An epic tale of life in America's unforgiving heartland with a vast and atmospheric world.', 9.7, 150, 2018, 4, 'images/rdr2.jpg'),
('Microsoft Flight Simulator', 'Asobo Studio', 'A highly realistic flight simulator that uses real-world data and maps to allow players to fly anywhere on Earth.', 9.0, 60, 2020, 5, 'images/flight_simulator.jpg');
