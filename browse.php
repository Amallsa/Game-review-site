<?php
// Include database connection
include 'db_connect.php';

// Get categories for filter
$categorySql = "SELECT * FROM categories ORDER BY category_name";
$categoryResult = $conn->query($categorySql);

// Initialize filter variables
$categoryFilter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build query based on filters
$sql = "SELECT g.*, c.category_name
        FROM games g
        JOIN categories c ON g.category_id = c.category_id
        WHERE 1=1";

if ($categoryFilter > 0) {
    $sql .= " AND g.category_id = $categoryFilter";
}

if (!empty($searchTerm)) {
    $sql .= " AND (g.title LIKE '%$searchTerm%' OR g.developer LIKE '%$searchTerm%' OR g.description LIKE '%$searchTerm%')";
}

$sql .= " ORDER BY g.rating DESC, g.created_at DESC";

$result = $conn->query($sql);

// Include header
include 'header.php';
?>

<div class="container">
    <div class="section-title">
        <h2>Browse Game Reviews</h2>
    </div>

    <div class="filter-container" style="margin-bottom: 3rem; padding: 2rem; background-color: #f9f9f9; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);">
        <form action="browse.php" method="GET" id="filter-form">
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="category" style="display: block; margin-bottom: 0.7rem; font-weight: 600; color: #2c3e50;">Filter by Genre:</label>
                    <select name="category" id="category" onchange="document.getElementById('filter-form').submit()" style="width: 100%; padding: 0.9rem 1rem; border: 1px solid #e0e0e0; border-radius: 6px; background-color: #fff; color: #333;">
                        <option value="0">All Genres</option>
                        <?php
                        if ($categoryResult->num_rows > 0) {
                            while($cat = $categoryResult->fetch_assoc()) {
                                $selected = ($categoryFilter == $cat["category_id"]) ? "selected" : "";
                                echo '<option value="' . $cat["category_id"] . '" ' . $selected . '>' . $cat["category_name"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div style="flex: 2; min-width: 300px;">
                    <label for="search" style="display: block; margin-bottom: 0.7rem; font-weight: 600; color: #2c3e50;">Search:</label>
                    <div style="display: flex;">
                        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by title, developer, or description" style="flex: 1; padding: 0.9rem 1rem; border: 1px solid #e0e0e0; border-radius: 6px 0 0 6px; background-color: #fff; color: #333;">
                        <button type="submit" class="btn" style="margin-left: 0; border-radius: 0 6px 6px 0; padding: 0.9rem 1.5rem;">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($searchTerm) || $categoryFilter > 0): ?>
    <div style="margin-bottom: 2rem; padding: 1rem; background-color: #f1f9fe; border-radius: 8px; border-left: 4px solid #3498db;">
        <p style="margin: 0; font-size: 0.95rem;">
            <strong>Search Results:</strong>
            <?php if (!empty($searchTerm)): ?>
                Searching for "<span style="color: #3498db;"><?php echo htmlspecialchars($searchTerm); ?></span>"
            <?php endif; ?>

            <?php if ($categoryFilter > 0):
                $catName = "";
                $categoryResult = $conn->query($categorySql);
                while($cat = $categoryResult->fetch_assoc()) {
                    if ($cat["category_id"] == $categoryFilter) {
                        $catName = $cat["category_name"];
                        break;
                    }
                }
            ?>
                <?php if (!empty($searchTerm)): ?> in <?php endif; ?>
                genre "<span style="color: #3498db;"><?php echo $catName; ?></span>"
            <?php endif; ?>

            <a href="browse.php" style="margin-left: 1rem; color: #e74c3c; text-decoration: none; font-size: 0.9rem;">Clear Filters</a>
        </p>
    </div>
    <?php endif; ?>

    <div class="game-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="game-card">';

                // Check if image exists, otherwise use placeholder
                if (!empty($row["image_url"]) && file_exists($row["image_url"])) {
                    echo '<img src="' . $row["image_url"] . '" alt="' . $row["title"] . '">';
                } else {
                    echo '<img src="images/placeholder.jpg" alt="Game Cover Placeholder">';
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
            echo '<div style="text-align: center; padding: 3rem 0; background-color: #f9f9f9; border-radius: 12px; grid-column: 1 / -1;">';
            echo '<img src="images/no-results.svg" alt="No Results" style="width: 100px; height: 100px; margin-bottom: 1.5rem; opacity: 0.5;" onerror="this.style.display=\'none\'">';
            echo '<h3 style="color: #8e44ad; margin-bottom: 1rem;">No Games Found</h3>';
            echo '<p style="color: #666; max-width: 500px; margin: 0 auto;">We couldn\'t find any games matching your criteria. Try adjusting your search or <a href="add_game.php" style="color: #3498db; text-decoration: none;">add a new game review</a>.</p>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php
// Include footer
include 'footer.php';

// Close connection
$conn->close();
?>
