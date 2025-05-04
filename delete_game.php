<?php
// Include database connection
include 'db_connect.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: browse.php");
    exit();
}

$gameId = intval($_GET['id']);

// Get game details to check if it exists and to get image path
$sql = "SELECT * FROM games WHERE game_id = $gameId";
$result = $conn->query($sql);

// Check if game exists
if ($result->num_rows == 0) {
    header("Location: browse.php");
    exit();
}

$game = $result->fetch_assoc();

// Delete game from database
$deleteSql = "DELETE FROM games WHERE game_id = $gameId";

if ($conn->query($deleteSql) === TRUE) {
    // Delete image file if it exists
    if (!empty($game['image_url']) && file_exists($game['image_url'])) {
        unlink($game['image_url']);
    }
    
    // Redirect to browse page with success message
    header("Location: browse.php?deleted=1");
    exit();
} else {
    // Include header
    include 'header.php';
    ?>
    
    <div class="container">
        <div class="error-message show">
            <h3>Error</h3>
            <p>Failed to delete game review: <?php echo $conn->error; ?></p>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="game.php?id=<?php echo $gameId; ?>" class="btn">Back to Game</a>
            <a href="browse.php" class="btn">Browse Games</a>
        </div>
    </div>
    
    <?php
    // Include footer
    include 'footer.php';
}

// Close connection
$conn->close();
?>
