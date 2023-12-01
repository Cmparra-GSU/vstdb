<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();
$conn = connect();

if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}

if (isset($_SESSION['UserID'])) {
    $authorUID = $_SESSION['UserID'];
    
} else {
    echo "Session variable 'UserID' is not set.";
    
}

$authorUsername = "";

if (isset($_SESSION['UserName'])) {
    $authorUsername = $_SESSION['UserName'];
} else {
    echo "Session variable 'username' is not set.";
}


$allPlugins = getFilteredPlugins();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Article</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Create Article</div>
                <div class="button-group">
                    <a href="admin.php" class="button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="create-content">
            <h1>Create New Article</h1>
            <form method="POST" action="submit-article.php" multiple>
                <input type="hidden" name="authorUID" value="<?php echo $authorUID; ?>">
                <input type="hidden" name="timestamp" value="<?php echo date('Y-m-d H:i:s'); ?>">
                <div class = "formSection">
                <p>Author: <?php echo $authorUsername; ?></p><br>
                </div>
                <div class="formSection">
                    <label for="articleTitle">Article Title:</label><br>
                    <input type="text" name="articleTitle" id="articleTitle" required><br>
                </div>
                <div class="formSection">
                <label for="articlePreview">Article Preview:</label><br>
                    <textarea name="articlePreview" id="articlePreview" rows="4" cols="40" placeholder="Short Summary for front page" required></textarea><br>
                </div>


                <div class="formSection">
                    <div id="selectedPluginIDsContainer">
                        <label for="pluginIDs">Associated Plugin ID's</label><br>
                        <button type="button" id="addPluginID" class = "addrem">+</button>
                        <button type="button" id="removePluginID" class = "addrem">-</button>
                        <button id="openPluginSelectorButton">Open Plugin ID Table</button>

                        <input type="text" id="pluginIDs" name="pluginIDs[]" placeholder="Enter Plugin ID" required><br>
                    </div>
                </div>

                <div class="formSection">
                    <label for="articleContent">Article Content:</label><br>
                    <textarea name="articleContent" id="articleContent" rows="10" cols="80" placeholder="You should probably write this in a text editor and paste it here" required></textarea><br>
                </div>

                <div class="formSection">
                    <label for="catalogImageURL">Catalog Image URL:</label><br>
                    <input type="text" name="catalogImageURL" id="catalogImageURL" placeholder="Enter catalog image URL" required><br>
                </div>

                <div class="formSection">
                    <div id="articleImageURLsContainer">
                        <label for="articleImages">Article Image URLs:</label><br>
                        <button type="button" id="addArticleImageURL" class="addrem">+</button>
                        <button type="button" id="removeArticleImageURL" class="addrem">-</button><br>
                        <input type="text" id="articleImageURLs" name="articleImages[]" placeholder="Enter image URL" required><br>
                    </div>
                </div>

                <!-- Add a section for video URLs -->
                <div class="formSection">
                    <div id="articleVideoURLsContainer">
                        <label for="articleVideos">Article Video URLs:</label><br>
                        <button type="button" id="addArticleVideoURL" class="addrem">+</button>
                        <button type="button" id="removeArticleVideoURL" class="addrem">-</button><br>
                        <input type="text" id="articleVideoURLs" name="articleVideos[]" placeholder="Enter video URL"><br>
                    </div>
                </div>

                <input type="submit" name="preview" value="Preview Article">
            </form>
        </div>
    </div>


    <script>
    var allPlugins = <?php echo json_encode($allPlugins); ?>;
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const articleImageURLsContainer = document.getElementById('articleImageURLsContainer');
            const addArticleImageURLButton = document.getElementById('addArticleImageURL');
            const removeArticleImageURLButton = document.getElementById('removeArticleImageURL');
            const pluginIDsContainer = document.getElementById('selectedPluginIDsContainer');
            const addPluginIDButton = document.getElementById('addPluginID');
            const removePluginIDButton = document.getElementById('removePluginID');
            const pluginIDsInput = document.getElementById('pluginIDs');
        
            console.log('DOMContentLoaded event fired');


            addArticleImageURLButton.addEventListener('click', function () {
                const articleImageURLInput = document.createElement('input');
                articleImageURLInput.type = 'text';
                articleImageURLInput.name = 'articleImages[]';
                articleImageURLInput.placeholder = 'Enter image URL';
                articleImageURLsContainer.appendChild(articleImageURLInput);
            });

            removeArticleImageURLButton.addEventListener('click', function () {
                const articleImageURLInputs = articleImageURLsContainer.querySelectorAll('input[name="articleImages[]"]');
                if (articleImageURLInputs.length > 1) {
                    const lastArticleImageURLInput = articleImageURLInputs[articleImageURLInputs.length - 1];
                    lastArticleImageURLInput.remove();
                }
            });

            const articleVideoURLsContainer = document.getElementById('articleVideoURLsContainer');
            const addArticleVideoURLButton = document.getElementById('addArticleVideoURL');
            const removeArticleVideoURLButton = document.getElementById('removeArticleVideoURL');

            addArticleVideoURLButton.addEventListener('click', function () {
                const articleVideoURLInput = document.createElement('input');
                articleVideoURLInput.type = 'text';
                articleVideoURLInput.name = 'articleVideos[]';
                articleVideoURLInput.placeholder = 'Enter video URL';
                articleVideoURLsContainer.appendChild(articleVideoURLInput);
            });

            removeArticleVideoURLButton.addEventListener('click', function () {
                const articleVideoURLInputs = articleVideoURLsContainer.querySelectorAll('input[name="articleVideos[]"]');
                if (articleVideoURLInputs.length > 1) {
                    const lastArticleVideoURLInput = articleVideoURLInputs[articleVideoURLInputs.length - 1];
                    lastArticleVideoURLInput.remove();
                }
            });

            addPluginIDButton.addEventListener('click', function () {
                const pluginIDInput = document.createElement('input');
                pluginIDInput.type = 'text';
                pluginIDInput.name = 'pluginIDs[]';
                pluginIDInput.placeholder = 'Enter Plugin ID';
                pluginIDsContainer.appendChild(pluginIDInput);
            });

            removePluginIDButton.addEventListener('click', function () {
                const pluginIDInputs = pluginIDsContainer.querySelectorAll('input[name="pluginIDs[]"]');
                if (pluginIDInputs.length > 1) {
                    const lastPluginIDInput = pluginIDInputs[pluginIDInputs.length - 1];
                    lastPluginIDInput.remove();
                }
            });
           
            const openPluginSelectorButton = document.getElementById('openPluginSelectorButton');

            openPluginSelectorButton.addEventListener('click', function() {
                
            const pluginSelectorWindow = window.open('plugin-selector.php', '_blank', 'width=800,height=600');
            });

    });

        </script>
    </body>
</html>