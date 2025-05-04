<?php
// Include database connection
include 'db_connect.php';

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
    $imageUrl = '';
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
                $imageUrl = $targetFilePath;
            }
        }
    }

    // Insert game into database
    $imageUrl = $conn->real_escape_string($imageUrl);
    $sql = "INSERT INTO games (title, developer, description, rating, playtime, release_year, category_id, image_url)
            VALUES ('$title', '$developer', '$description', $rating, $playtime, $releaseYear, $categoryId, '$imageUrl')";

    if ($conn->query($sql) === TRUE) {
        $gameId = $conn->insert_id;
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
    <h2>Add New Game Review</h2>

    <?php if (isset($error)) { ?>
        <div class="error-message show">
            <p><?php echo $error; ?></p>
        </div>
    <?php } ?>

    <div class="form-container">
        <form id="game-form" action="add_game.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Game Title *</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="developer">Developer/Studio *</label>
                <input type="text" id="developer" name="developer" required>
            </div>

            <div class="form-group">
                <label for="category_id">Genre *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a genre</option>
                    <?php
                    if ($categoryResult->num_rows > 0) {
                        while($cat = $categoryResult->fetch_assoc()) {
                            echo '<option value="' . $cat["category_id"] . '">' . $cat["category_name"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Review/Description *</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label for="rating">Rating (0-10) *</label>
                    <input type="number" id="rating" name="rating" min="0" max="10" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="playtime">Average Playtime (hours)</label>
                    <input type="number" id="playtime" name="playtime" min="1">
                </div>

                <div class="form-group">
                    <label for="release_year">Release Year</label>
                    <input type="number" id="release_year" name="release_year" min="1970" max="<?php echo date('Y'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="image">Game Cover Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p style="font-size: 0.9rem; color: #666;">Accepted formats: JPG, JPEG, PNG, GIF</p>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">Add Game Review</button>
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
