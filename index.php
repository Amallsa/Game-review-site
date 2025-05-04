<?php
// Include database connection
include 'db_connect.php';

// Get top rated games (highest ratings)
$topRatedSql = "SELECT g.*, c.category_name
        FROM games g
        JOIN categories c ON g.category_id = c.category_id
        ORDER BY g.rating DESC
        LIMIT 3";
$topRatedResult = $conn->query($topRatedSql);

// Get latest games (most recent)
$latestSql = "SELECT g.*, c.category_name
        FROM games g
        JOIN categories c ON g.category_id = c.category_id
        ORDER BY g.created_at DESC
        LIMIT 4";
$latestResult = $conn->query($latestSql);

// Get game categories for featured genres section
$categoriesSql = "SELECT c.*, COUNT(g.game_id) as game_count
                 FROM categories c
                 LEFT JOIN games g ON c.category_id = g.category_id
                 GROUP BY c.category_id
                 ORDER BY game_count DESC
                 LIMIT 4";
$categoriesResult = $conn->query($categoriesSql);

// Include header
include 'header.php';
?>

<!-- Hero Section with Animated Background -->
<div class="hero">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">GameCritic</h1>
            <h2>Discover & Review the Best Video Games</h2>
            <p>Find detailed reviews of the latest games or share your own gaming experiences with our community.</p>
            <div class="hero-buttons">
                <a href="browse.php" class="btn btn-primary">Browse Games</a>
                <a href="add_game.php" class="btn btn-secondary">Add a Review</a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Counter Section -->
<div class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">🎮</div>
                <div class="stat-counter"><?php echo $conn->query("SELECT COUNT(*) as count FROM games")->fetch_assoc()['count']; ?></div>
                <div class="stat-label">Games Reviewed</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">🏆</div>
                <div class="stat-counter"><?php echo $conn->query("SELECT COUNT(DISTINCT category_id) as count FROM games")->fetch_assoc()['count']; ?></div>
                <div class="stat-label">Game Genres</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">⭐</div>
                <div class="stat-counter"><?php echo $conn->query("SELECT ROUND(AVG(rating), 1) as avg FROM games")->fetch_assoc()['avg']; ?></div>
                <div class="stat-label">Avg Rating</div>
            </div>
        </div>
    </div>
</div>

<!-- Top Rated Games Section -->
<div class="container section">
    <div class="section-title">
        <h2>Top Rated Games</h2>
        <p class="section-subtitle">The highest-rated games according to our community</p>
    </div>

    <div class="game-grid">
        <?php
        if ($topRatedResult->num_rows > 0) {
            while($row = $topRatedResult->fetch_assoc()) {
                echo '<div class="game-card">';

                // Check if image exists, otherwise use placeholder
                if (!empty($row["image_url"]) && file_exists($row["image_url"])) {
                    echo '<img src="' . $row["image_url"] . '" alt="' . $row["title"] . '">';
                } else {
                    echo '<img src="images/placeholder.svg" alt="Game Cover Placeholder">';
                }

                echo '<div class="game-card-content">';
                echo '<span class="category">' . $row["category_name"] . '</span>';
                echo '<h3>' . $row["title"] . '</h3>';
                echo '<p class="developer">By ' . $row["developer"] . '</p>';

                // Display rating and release year
                echo '<div class="rating-container">';
                echo '<span class="rating">' . $row["rating"] . '/10</span>';
                echo '<span class="year">' . $row["release_year"] . '</span>';
                echo '</div>';

                echo '<a href="game.php?id=' . $row["game_id"] . '" class="btn">Read Review</a>';
                echo '</div></div>';
            }
        } else {
            echo '<div class="no-results"><p>No games found. Be the first to <a href="add_game.php">add a game review</a>!</p></div>';
        }
        ?>
    </div>

    <div class="view-all-container">
        <a href="browse.php?sort=rating" class="btn btn-outline">View All Top Rated Games</a>
    </div>
</div>

<!-- Featured Genres Section -->
<div class="featured-genres-section">
    <div class="container section">
        <div class="section-title">
            <h2>Featured Genres</h2>
            <p class="section-subtitle">Explore games by your favorite genres</p>
        </div>

        <div class="genres-grid">
            <?php
            if ($categoriesResult->num_rows > 0) {
                while($category = $categoriesResult->fetch_assoc()) {
                    echo '<a href="browse.php?category=' . $category["category_id"] . '" class="genre-card">';
                    echo '<div class="genre-icon">🎮</div>';
                    echo '<h3>' . $category["category_name"] . '</h3>';
                    echo '<p>' . $category["game_count"] . ' games</p>';
                    echo '</a>';
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Latest Reviews Section -->
<div class="container section">
    <div class="section-title">
        <h2>Latest Reviews</h2>
        <p class="section-subtitle">Fresh reviews from our community</p>
    </div>

    <div class="latest-reviews-grid">
        <?php
        if ($latestResult->num_rows > 0) {
            while($row = $latestResult->fetch_assoc()) {
                echo '<div class="latest-review-card">';

                // Image container
                echo '<div class="review-image">';
                if (!empty($row["image_url"]) && file_exists($row["image_url"])) {
                    echo '<img src="' . $row["image_url"] . '" alt="' . $row["title"] . '">';
                } else {
                    echo '<img src="images/placeholder.svg" alt="Game Cover Placeholder">';
                }
                echo '</div>';

                // Content container
                echo '<div class="review-content">';
                echo '<div class="review-header">';
                echo '<span class="category-badge">' . $row["category_name"] . '</span>';
                echo '<span class="rating-badge">' . $row["rating"] . '/10</span>';
                echo '</div>';
                echo '<h3>' . $row["title"] . '</h3>';
                echo '<p class="developer">By ' . $row["developer"] . ' (' . $row["release_year"] . ')</p>';

                // Short description
                $shortDesc = substr($row["description"], 0, 120) . (strlen($row["description"]) > 120 ? '...' : '');
                echo '<p class="description">' . $shortDesc . '</p>';

                echo '<a href="game.php?id=' . $row["game_id"] . '" class="btn btn-sm">Read Full Review</a>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <div class="view-all-container">
        <a href="browse.php" class="btn btn-outline">View All Games</a>
    </div>
</div>

<!-- Call to Action Section -->
<div class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Share Your Gaming Experience</h2>
            <p>Join our community and help other gamers by sharing your honest reviews.</p>
            <a href="add_game.php" class="btn btn-cta">Add Your Review</a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'footer.php';

// Close connection
$conn->close();
?>
