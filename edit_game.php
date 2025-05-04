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
$sql = "SELECT * FROM games WHERE game_id = $gameId";
$result = $conn->query($sql);

// Check if game exists
if ($result->num_rows == 0) {
    header("Location: browse.php");
    exit();
}

$game = $result->fetch_assoc();

// Get categories for dropdown
$categorySql = "SELECT * FROM categories ORDER BY category_name";
$categoryResult = $conn->query($categorySql);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $conn->real_escape_string($_POST['title']);
    $developer = $conn->real_escape_string($_POST['developer']);
    $description = $conn->real_escape_string($_POST['description']);
    $rating = !empty($_POST['rating']) ? floatval($_POST['rating']) : 0;
    $playtime = !empty($_POST['playtime']) ? intval($_POST['playtime']) : 0;
    $releaseYear = !empty($_POST['release_year']) ? intval($_POST['release_year']) : 0;
    $categoryId = intval($_POST['category_id']);

    // Handle image upload
    $imageUrl = $game['image_url']; // Keep existing image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Create images directory if it doesn't exist
        if (!file_exists('images')) {
            mkdir('images', 0777, true);
        }

        $targetDir = "images/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . '_' . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Delete old image if it exists
                if (!empty($game['image_url']) && file_exists($game['image_url'])) {
                    unlink($game['image_url']);
                }
                $imageUrl = $targetFilePath;
            }
        }
    }

    // Update game in database
    $imageUrl = $conn->real_escape_string($imageUrl);
    $sql = "UPDATE games SET
            title = '$title',
            developer = '$developer',
            description = '$description',
            rating = $rating,
            playtime = $playtime,
            release_year = $releaseYear,
            category_id = $categoryId,
            image_url = '$imageUrl'
            WHERE game_id = $gameId";

    if ($conn->query($sql) === TRUE) {
        header("Location: game.php?id=$gameId");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Include header
include 'header.php';
?>

<div class="container">
    <h2>Edit Game Review</h2>

    <?php if (isset($error)) { ?>
        <div class="error-message show">
            <p><?php echo $error; ?></p>
        </div>
    <?php } ?>

    <div class="form-container">
        <form id="game-form" action="edit_game.php?id=<?php echo $gameId; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Game Title *</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($game['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="developer">Developer/Studio *</label>
                <input type="text" id="developer" name="developer" value="<?php echo htmlspecialchars($game['developer']); ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">Genre *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a genre</option>
                    <?php
                    if ($categoryResult->num_rows > 0) {
                        while($cat = $categoryResult->fetch_assoc()) {
                            $selected = ($cat["category_id"] == $game["category_id"]) ? "selected" : "";
                            echo '<option value="' . $cat["category_id"] . '" ' . $selected . '>' . $cat["category_name"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Review/Description *</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($game['description']); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label for="rating">Rating (0-10) *</label>
                    <input type="number" id="rating" name="rating" min="0" max="10" step="0.1" value="<?php echo $game['rating']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="playtime">Average Playtime (hours)</label>
                    <input type="number" id="playtime" name="playtime" min="1" value="<?php echo $game['playtime']; ?>">
                </div>

                <div class="form-group">
                    <label for="release_year">Release Year</label>
                    <input type="number" id="release_year" name="release_year" min="1970" max="<?php echo date('Y'); ?>" value="<?php echo $game['release_year']; ?>">
                </div>
            </div>

            <div class="form-group">
                <?php if (!empty($game['image_url']) && file_exists($game['image_url'])) { ?>
                    <div style="margin-bottom: 1rem;">
                        <p>Current Image:</p>
                        <img src="<?php echo $game['image_url']; ?>" alt="Game Cover" style="max-width: 200px; max-height: 200px;">
                    </div>
                <?php } ?>

                <label for="image">Game Cover Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p style="font-size: 0.9rem; color: #666;">Accepted formats: JPG, JPEG, PNG, GIF</p>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">Update Review</button>
                <a href="game.php?id=<?php echo $gameId; ?>" class="btn" style="background-color: #999;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';

// Close connection
$conn->close();
?>
