<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../admin-panel/functions.php');
include('../admin-panel/plugin-functions.php');
include('../admin-panel/article-functions.php');
$conn = connect();

if (isset($_GET['article_id'])) {
    $articleID = $_GET['article_id'];
    
} else {
    
    echo "Article not found.";
}



$article = getArticleData($articleID, $conn); 
$author = getAuthorUsername($article['AuthorUID'], $conn); 
$images = getArticleImages($articleID, $conn); 
$videos = getArticleVideos($articleID, $conn); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VST Database</title>
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>
</head>
<body>
<?php include ('header.php');?>

<div class="main-content">
    <div class="article-view">
        <h1><?php echo $article['Title']; ?></h1>

        <div class="article-info">
            <p>Author: <?php echo $author;?> </p>
            <p>  Publication Date: <?php echo $article['PublicationDate']; ?></p>
        </div>

        <div class="carousel-container article-carousel">
        <?php
            $hasVideos = false;
            foreach ($videos as $video) {
                if (!empty($video['VideoURL'])) {
                    $hasVideos = true;
                    break;
                }
            }
            
            $hasImages = count($images) > 0;
            
            if ($hasVideos) {
                echo '<div class="video-container">';
                
                foreach ($videos as $video) {
                    if (!empty($video['VideoURL'])) {
                        echo '<div class="article-slide">';
                        echo '<iframe src="' . $video['VideoURL'] . '" frameborder="0" allowfullscreen></iframe>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
            
            if ($hasImages) {
                echo '<div class="image-container">';
                
                foreach ($images as $image) {
                    echo '<div class="article-slide">';
                    echo '<img src="' . $image['ImageURL'] . '" alt="Article Image">';
                    echo '</div>';
                }
                echo '</div>';
            }
       

        ?>

            <div class="carousel-controls">
                <button class="carousel-btn prev-btn">&#10094;</button>
                <button class="carousel-btn next-btn">&#10095;</button>
                <div class="carousel-dots">
                    <?php
                    $totalSlides = 0;
                    if ($hasVideos) {
                        $totalSlides += count($videos);
                    }
                    if ($hasImages) {
                        $totalSlides += count($images);
                    }
                    for ($i = 0; $i < $totalSlides; $i++) {
                        echo '<span class="dot" onclick="currentSlide(' . ($i + 1) . ')"></span>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="article-content">
            <?php $formattedContent = str_replace("\\r\\n", "\n", $article['Content']);
                echo nl2br($formattedContent);
            ?>
        </div>


        <div class="related-plugins">
            <h3>Related Plugins</h3>
            <div class="row">
                <?php

                $relatedPlugins = getRelatedPlugins($article['ArticleID'], $conn);

                foreach ($relatedPlugins as $plugin) {
                    echo '<div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <img src="' . $plugin['CatalogImageURL'] . '" alt="' . $plugin['Name'] . '" class="img-fluid">
                                <div class="card-body">
                                    <h4>' . $plugin['Name'] . '</h4>
                                    <p class="card-text">' . $plugin['ShortDescription'] . '</p>
                                    <a href="details.php?pluginID=' . $plugin['PID'] . '" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="slideshow-articles.js"></script>
</body>
</html>
