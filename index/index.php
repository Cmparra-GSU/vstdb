<?php
session_start();
include('../admin-panel/functions.php'); 
include('../admin-panel/plugin-functions.php'); 
$conn = connect();

$query = "SELECT * FROM article WHERE visible = 1 ORDER BY PublicationDate DESC LIMIT 5";

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
    <title>VST Database</title>
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="https:


  <link rel="stylesheet" href="index.css">
  <script src="index.js"></script>

</head>
<body>
<?php include ('header.php');?>
    <!--
    <script>
        window.onload = function() {
            alert("This site is still under construction! Some functions may not work.");
        };
    </script>-->
    


    <div class = notification></div>

  
    <div class="main">
        
    <div class="recent-articles">
    <div class="article-button-header">
        <button class="view-all" id="view-all-articles">∀</button>
        Recent articles
    </div>
    <div class="recent-container">
        <?php
        $recentArticles = array(); 
        $article62 = null; 

        foreach ($articles as $article) {
            if ($article['visible'] == 1) {
                if ($article['ArticleID'] == 62) {
                    
                    $article62 = $article;
                } else {
                    
                    $recentArticles[] = $article;
                }
            }
        }

        
        if ($article62) {
            displayArticle($article62); 
        }

        
        usort($recentArticles, function ($a, $b) {
            return strtotime($b['PublicationDate']) - strtotime($a['PublicationDate']);
        });

        
        
        for ($i = min(count($recentArticles), 3) - 1; $i >= 0; $i--) {
            displayArticle($recentArticles[$i]);
        }


        function displayArticle($article) {
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
        ?>
    </div>
</div>




        <div class = recent-articles>
            <div class = "recent-container">
                
                <div class="heading-and-button">
                    <button class="view-all" id="view-all-plugins">∀</button>
                    <h2 class="random-heading">Random Plugins</h2>
                    <button id="shuffle-btn">⧢</button>
                </div>

                <div class="row">
                    <?php
                    $randomPlugins = getRandomPlugins(4);

                    foreach ($randomPlugins as $plugin) {
                        echo '<div class="col-md-4">'; 
                        echo '<div class="card">';
                        echo '<img src="' . $plugin['CatalogImageURL'] . '" alt="' . $plugin['Name'] . '" class="img-fluid">';
                        echo '<div class="card-body">';
                        echo '<h4>' . htmlspecialchars($plugin['Name']) . '</h4>';
                        echo '<p class="card-text">' . htmlspecialchars($plugin['ShortDescription']) . '</p>';
                        echo '<div class="detailsB">';
                        echo '<a href="details.php?pluginID=' . $plugin['PID'] . '" class="btn btn-primary">View Details</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
    <footer>
        <div id="hidden-footer">
            <div id="special-area" class="special-area">
                <br><br><br>
                <p>this is it 1</p>
            </div>

        </div>
    </footer>


    <script src = reveal-footer.js></script>
    <script src="shuffle-plugins.js"></script>
</body>
</html>
