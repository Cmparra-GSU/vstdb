<?php
if (isset($_POST['editedPluginIDs'])) {
    echo "Edited Plugin IDs: ";
    print_r($_POST['editedPluginIDs']);
} else {
    echo "No editedPluginIDs found in POST data.";
}

include('functions.php');
include('article-functions.php');
session_start();
isAdmin();
$conn = connect();

$articleID = null;

if (isset($_POST['articleID'])) {
    $articleID = $_POST['articleID'];
} elseif (isset($_GET['articleID'])) {
    $articleID = $_GET['articleID'];
}

if ($articleID !== null) {
    
    $articleData = getArticleData($articleID, $conn);

    if (!$articleData) {
        echo "Article not found or you don't have permission to view it.";
        exit();
    }
} else {
    echo "Invalid article ID.";
    exit();
}

if (isset($_POST['updateArticle'])) {
    
    $editedTitle = $_POST['editedTitle'];
    $editedPreview = $_POST['editedPreview'];
    $editedCatalogImg = $_POST['editedCatalogImg'];
    $editedContent = $_POST['editedContent'];
    $editedArticleImages = $_POST['editedArticleImages'];
    $editedArticleVideos = $_POST['editedArticleVideos'];
    $editedPluginIDs = isset($_POST['editedPluginIDs']) ? $_POST['editedPluginIDs'] : [];

    clearArticleData($articleID, $conn, "pluginIDs");
    clearArticleData($articleID, $conn, "videoURL");
    clearArticleData($articleID, $conn, "imageURL");

    $updateResult = updateArticle(
        $articleID,
        $editedTitle,
        $editedPreview,
        $editedCatalogImg,
        $editedContent,
        $editedArticleImages,
        $editedArticleVideos,
        $editedPluginIDs, 
        $conn
    );

    if (is_numeric($updateResult)) {
        echo "Article updated successfully. Article ID: $updateResult";
        echo '<script>
                setTimeout(function() {
                    window.location.href = "preview-article.php?articleID=' . $articleID . '";
                }, 5000);
              </script>';
    } else {
        echo "Error updating the article: $updateResult";
    }
}
?>
