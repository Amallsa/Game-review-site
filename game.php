<?php
// Include database connection
include 'db_connect.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: browse.php");
    exit();
}

$gameId = intval($_GET['id']);

// Get game details
$sql = "SELECT g.*, c.category_name
        FROM games g
        JOIN categories c ON g.category_id = c.category_id
        WHERE g.game_id = $gameId";
$result = $conn->query($sql);

// Check if game exists
if ($result->num_rows == 0) {
    header("Location: browse.php");
    exit();
}

$game = $result->fetch_assoc();

// Include header
include 'header.php';
?>

<div class="container">
    <div class="game-detail">
        <div class="game-detail-container">
            <div class="game-detail-image">
                <?php
                // Check if image exists, otherwise use placeholder
                if (!empty($game["image_url"]) && file_exists($game["image_url"])) {
                    echo '<img src="' . $game["image_url"] . '" alt="' . $game["title"] . '">';
                } else {
                    echo '<img src="images/placeholder.jpg" alt="Game Cover Placeholder">';
                }
                ?>
            </div>

            <div class="game-detail-content">
                <h2><?php echo $game["title"]; ?></h2>
                <p class="developer">Developed by <?php echo $game["developer"]; ?></p>

                <div class="game-meta">
                    <div>
                        <span>Genre</span>
                        <?php echo $game["category_name"]; ?>
                    </div>
                    <div>
                        <span>Rating</span>
                        <strong><?php echo $game["rating"]; ?>/10</strong>
                    </div>
                    <div>
                        <span>Playtime</span>
                        <?php echo $game["playtime"]; ?> hours
                    </div>
                    <div>
                        <span>Release Year</span>
                        <?php echo $game["release_year"]; ?>
                    </div>
                </div>

                <div class="game-section">
                    <h3>Review</h3>
                    <p><?php echo nl2br($game["description"]); ?></p>
                </div>

                <div class="game-actions">
                    <a href="edit_game.php?id=<?php echo $gameId; ?>" class="btn">Edit Review</a>
                    <a href="delete_game.php?id=<?php echo $gameId; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this game review?')">Delete Review</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'footer.php';

// Close connection
$conn->close();
?>
