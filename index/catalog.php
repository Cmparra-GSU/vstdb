<?php
include('../admin-panel/functions.php');
include('../admin-panel/plugin-functions.php');
session_start();

$conn = connect();

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$selectedCategories = isset($_GET['category']) ? $_GET['category'] : [];
$sortingCriterion = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sortingOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc'; 


$query = "SELECT p.*, d.DevName, t.TypeName 
          FROM plugin p
          LEFT JOIN developer d ON p.DevName = d.DevName
          LEFT JOIN plugintype t ON p.TypeID = t.TypeID
          LEFT JOIN hastype h ON p.PID = h.PluginID
          LEFT JOIN category c ON h.CategoryID = c.CategoryID
          WHERE p.visible = 1 ";


if (!empty($searchQuery)) {
    $likeQuery = '%' . mysqli_real_escape_string($conn, $searchQuery) . '%';
    $query .= "AND (p.Name LIKE '$likeQuery' OR d.DevName LIKE '$likeQuery' 
                   OR t.TypeName LIKE '$likeQuery' OR c.CategoryName LIKE '$likeQuery') ";
}


if (!empty($selectedCategories)) {
    $categoryFilter = implode(',', array_map('intval', $selectedCategories));
    $query .= " AND c.CategoryID IN ($categoryFilter) ";
}


$query .= " GROUP BY p.PID ";
if ($sortingCriterion === 'name') {
    $query .= "ORDER BY p.Name $sortingOrder ";
} elseif ($sortingCriterion === 'price') {
    $query .= "ORDER BY p.Price $sortingOrder ";
} elseif ($sortingCriterion === 'release_date') {
    $query .= "ORDER BY p.ReleaseDate $sortingOrder ";
}


$result = mysqli_query($conn, $query);
if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

$plugins = mysqli_fetch_all($result, MYSQLI_ASSOC);

$isSearchActive = !empty($searchQuery);
$isFilterActive = !empty($selectedCategories);
$isAnyFilterActive = $isSearchActive || $isFilterActive;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plugin Catalog</title>

    <script src="index.js"></script>
    
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="index.css">
</head>
<body>
<?php include ('header.php');?>
    
<div class = main-content>
                                  


                
        <div class = "filter-sort-container">

            <?php if ($isAnyFilterActive): ?>
                <a href="catalog.php<?= $isAnyFilterActive ? '?filter_active=1' : '' ?>" class="clear-search-button">❌</a>
            <?php endif; ?>

            <button onclick="toggleFilterSortSection()" class="toggle-button">▼</button>

            <div class="filter-sort-section" id="filterSortSection" style="display: none;">
                <form action="catalog.php" method="GET">
                    <div class="filter-section">
                        <h3>Filter by Category:</h3>
                        <div id="filterOptions" class = filter-options>
                            <?php
                            $categories = getPluginCategories();
                            foreach ($categories as $category) {
                                echo '<label><input type="checkbox" name="category[]" value="' . $category['CategoryID'] . '"> ' . $category['CategoryName'] . '</label><br>';
                            }
                            ?>
                        </div>
                    </div>


                    <div class="sort-section">
                        <h3>Sort by:</h3>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <select name="sort">
                            <option value="name" <?= $sortingCriterion == 'name' ? 'selected' : '' ?>>Name</option>
                            <option value="price" <?= $sortingCriterion == 'price' ? 'selected' : '' ?>>Price</option>
                            <option value="release_date" <?= $sortingCriterion == 'release_date' ? 'selected' : '' ?>>Release Date</option>
                        </select>

                        <select name="sort_order">
                            <option value="asc" <?= $sortingOrder == 'asc' ? 'selected' : '' ?>>Ascending</option>
                            <option value="desc" <?= $sortingOrder == 'desc' ? 'selected' : '' ?>>Descending</option>
                        </select>

                    </div>
                    <button type="submit" class="apply-button">Apply</button>
                    <button type="button" onclick="closeFilterSortSection()" class="collapse-button">Close</button>

                </form>
            </div>
        </div>

        <div class="plugins-container">

            <div class="row">
                <?php
                foreach ($plugins as $plugin) {
                    echo '<div class="row">
                            <div class="card">
                                <img src="' . $plugin['CatalogImageURL'] . '" alt="' . $plugin['Name'] . '" class="img-fluid">
                                <div class="card-body">
                                    <h4>' . $plugin['Name'] . '</h4>
                                    <p class="card-text">' . $plugin['ShortDescription'] . '</p>
                                    <div class = "detailsB">
                                        <a href="details.php?pluginID=' . $plugin['PID'] . '" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
                ?>
            </div>

        </div>
        

    </div>

    <script src="jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
