<?php
session_start();
include('../admin-panel/functions.php'); 
include('../admin-panel/plugin-functions.php'); 
$conn = connect();

$query = "SELECT * FROM article WHERE visible = 1 ORDER BY PublicationDate DESC";

$result = mysqli_query($conn, $query);

$articles = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Articles - VST Database</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>
</head>
<body>
<?php include ('header.php');?>

<div class="main-content">
    <div class="all-articles">
        <div class="all-container">
            <?php
            foreach ($articles as $article) {
                if ($article['visible'] == 1) {
                    echo '<div class="article">';
                    echo '<a href="article.php?article_id=' . $article['ArticleID'] . '">'; 
                    echo '<img src="' . htmlspecialchars($article['CatalogImageURL']) . '" class="article-img" alt="Article Image">';
                    echo '<h3>' . htmlspecialchars($article['Title']) . '</h3>';
                    echo '<div class="article-text">';
                    echo '<p>' . htmlspecialchars($article['Preview']) . '</p>';
                    echo '</div>';
                    echo '</a>'; 
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
