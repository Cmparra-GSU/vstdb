<?php
include('functions.php'); 
include('article-functions.php'); 
session_start();
$conn = connect();

isAdmin(); 

if (isset($_GET['articleID'])) {
    $articleID = $_GET['articleID'];    
    $articleData = getArticleData($articleID, $conn); 

    if (!$articleData) {
        echo "Article not found or you don't have permission to view it.";
        exit();
    }
} else {
    echo "Invalid article ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Plugin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    
<header>
    <div class="container">
        <div class="navbar">
            <div class="logo">Article Preview</div>
            <div class="button-group">
                <a href = "edit.php" class = "button">Back to Search</a>
                <a href="admin.php" class="button">Admin Panel</a>
                <a href="../index/index.php" class="button">Index</a>
                <a href="../index/logout.php" class="button">Logout</a>
            </div>
        </div>
    </div>
</header>
    
<div class="main-content">
    <h4>At the moment, the preview shows mobile formatting, but if it looks good here, it'll look good on desktop.</h4>
    <div class="article-preview">
        <iframe src="../index/article.php?article_id=<?php echo $articleID; ?>" frameborder="0" width="1600px" height="1000px"></iframe>
    </div>

    <div class="edit-buttons">

        <form method="POST" action="article-edit.php">
            <input type="hidden" name="articleID" value="<?php echo $articleID; ?>">
            <button type="submit">Edit</button>
        </form>

        <button onclick="confirmExit()">OK</button>

        <script>
        function confirmExit() {
            if (confirm('Are you sure you want to exit edit mode? Please remember to change the visibility if you are finished.')) {
                
                window.location.href = 'edit-article.php?articleID=<?php echo $articleID; ?>';
            }
        }
        </script>

    </div>

</div>

</body>
</html>
