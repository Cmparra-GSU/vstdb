<?php
print_r($_POST);
include('functions.php');
include('article-functions.php'); 
session_start();
isAdmin();
$conn = connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $authorUID = $_POST['authorUID'];
    $timestamp = $_POST['timestamp'];
    $articleTitle = $_POST['articleTitle'];
    $articleContent = $_POST['articleContent'];
    $catalogImageURL = $_POST['catalogImageURL'];
    $articleImages = $_POST['articleImages'];
    $articleVideos = $_POST['articleVideos'];
    $articlePreview = $_POST['articlePreview'];

    if (isset($_POST['pluginIDs']) && is_array($_POST['pluginIDs'])) {
        $selectedPlugins = $_POST['pluginIDs'];
    } else {
        $selectedPlugins = [];
    }

    $articleID = submitArticle($authorUID, $timestamp, $articleTitle, $articleContent, $articlePreview, $catalogImageURL, $articleImages, $articleVideos, $selectedPlugins);

    if (is_numeric($articleID)) {

        echo "Article data submitted successfully!";
        header("refresh:5;url=preview-article.php?articleID=" . $articleID); 
    } else {
        echo "Error: " . $articleID;
    }
}
?>
