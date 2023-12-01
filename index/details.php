<?php
session_start();
include('../admin-panel/functions.php');
include('../admin-panel/plugin-functions.php');

$pluginID = $_GET['pluginID']; 

$plugin = getPluginInfo($pluginID); 

$images = getPluginImagePaths($pluginID); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $plugin['Name']; ?> - VST Database</title>

    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="https:
    <script src="index.js"></script>
    
</head>
<body>
<?php include ('header.php');?>

    <div class = notification></div>

    <div class="main-content">
    <div class="plugin-details">
    <h2><?php echo $plugin['Name']; ?></h2>
    <div class = "line">
            <p>
                <?php echo $plugin['DevName'] . " | "; ?>
                <a href="<?php echo $plugin['DLPage']; ?>" target="_blank"><?php echo "download"; ?></a>
                <?php echo " | $" . $plugin['Price']; ?>
            </p>
        </div>
        <div class="plugin-images">
            <div class="carousel-container details-carousel">
                <?php
                if (!empty($images)) {
                    foreach ($images as $key => $imageURL) { 
                        echo '<div class="carousel-slide ' . ($key === 0 ? 'active' : '') . '">';
                        echo '<img src="' . $imageURL . '" alt="Image description" class = "de-img">'; 
                        echo '</div>';
                    }
                }
                ?>
                <div class="carousel-controls">
                    <button class="carousel-btn prev-btn">&#10094;</button>
                    <button class="carousel-btn next-btn">&#10095;</button>
                    <div class="carousel-dots">
                        <?php
                        if (!empty($images)) {
                            foreach ($images as $key => $image) {
                                echo '<span class="dot" onclick="currentSlide(' . ($key + 1) . ')"></span>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="plugin-info">
            <p><?php echo nl2br($plugin['LongDescription']); ?></p>
        </div>

    </div>
</div>
<script src="slideshow.js"></script>
</body>
</html>
