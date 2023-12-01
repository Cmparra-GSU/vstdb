<?php
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Edit</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Article Edit</div>
                <div class="button-group">
                    <a href = "edit-article.php" class = "button">Back to Search</a>
                    <a href="admin.php" class="button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="edit">
        <h1><?php echo htmlspecialchars($articleData['Title']); ?></h1>
        <p><strong>Author:</strong>
        <?php
        if (isset($articleData['AuthorUID'])) {
            $authorUsername = getAuthorUsername($articleData['AuthorUID'], $conn);
            echo htmlspecialchars($authorUsername);
        } else {
            echo 'Unknown';
        }
        ?>
        </p>

            
        <form method="POST" action="updateArticle.php">
            <div class="formSection">
                <label for="editedTitle">Title:</label><br>
                <input type="text" name="editedTitle" id="editedTitle" value="<?php echo htmlspecialchars($articleData['Title']); ?>" required><br>
            </div>

            <div class="formSection">
                <label for="editedPreview">Article Preview:</label><br>
                <textarea name="editedPreview" id="editedPreview" rows="5" cols="80" required><?php echo htmlspecialchars($articleData['Preview']); ?></textarea><br>
            </div>

            <div class="formSection">
                <label for="editedCatalogImg">Preview Image:</label><br>
                <input type="text" name="editedCatalogImg" id="editedCatalogImg" rows="10" cols="80" value = "<?php echo htmlspecialchars($articleData['CatalogImageURL']); ?>" required></input><br>
            </div>


            <div class="formSection">
                <label for="editedContent">Content:</label><br>
                <textarea name="editedContent" id="editedContent" rows="10" cols="80" required><?php echo htmlspecialchars($articleData['Content']); ?></textarea><br>
            </div>

            
            <div class="formSection">
                <div id="articleImageURLsContainer">
                    <label for="articleImages">Article Image URLs:</label><br>
                    <?php
                    $articleImages = explode(",", $articleData['Images']); 
                    foreach ($articleImages as $image) {
                        echo '<input type="text" name="editedArticleImages[]" value="' . htmlspecialchars($image) . '" placeholder="Enter image URL" required><br>';
                    }
                    ?>
                    <button type="button" id="addArticleImageURL" class="addrem">+</button>
                    <button type="button" id="removeArticleImageURL" class="addrem">-</button><br>
                </div>
            </div>
            
            <div class="formSection">
                <div id="selectedPluginIDsContainer">
                    <label for="pluginIDs">Associated Plugin ID's</label><button type="button" id="openPluginSelectorButton">Open Plugin ID Table</button><br>
                    <?php
                    $selectedPlugins = explode(",", $articleData['SelectedPlugins']); 
                    foreach ($selectedPlugins as $pluginID) {
                        echo '<div class="pluginInput">';
                        echo '<input type="text" name="editedPluginIDs[]" value="' . htmlspecialchars($pluginID) . '" placeholder="Enter Plugin ID" required>';
                        echo '</div>';
                    }
                    ?>
                    <button type="button" id="addPluginID" class="addrem">+</button>
                    <button type="button" id="removePluginID" class="addrem">-</button><br>
                    
                </div>
            </div>


            <div class="formSection">
                <div id="articleVideoURLsContainer">
                    <label for="articleVideos">Article Video URLs:</label><br>
                    <?php
                    $articleVideos = explode(",", $articleData['Videos']); 
                    foreach ($articleVideos as $videoURL) {
                        echo '<input type="text" name="editedArticleVideos[]" value="' . htmlspecialchars($videoURL) . '" placeholder="Enter video URL"><br>';
                    }
                    ?>
                    <button type="button" id="addArticleVideoURL" class="addrem">+</button>
                    <button type="button" id="removeArticleVideoURL" class="addrem">-</button><br>
                </div>
            </div>


            
            <input type="submit" name="updateArticle" value="Update Article">
            <input type="hidden" name="articleID" value="<?php echo $articleID; ?>">
        </form>

        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const articleImageURLsContainer = document.getElementById('articleImageURLsContainer');
    const addArticleImageURLButton = document.getElementById('addArticleImageURL');
    const removeArticleImageURLButton = document.getElementById('removeArticleImageURL');
    
    const pluginIDsContainer = document.getElementById('selectedPluginIDsContainer');
    const addPluginIDButton = document.getElementById('addPluginID');
    const removePluginIDButton = document.getElementById('removePluginID');

    const articleVideoURLsContainer = document.getElementById('articleVideoURLsContainer');
    const addArticleVideoURLButton = document.getElementById('addArticleVideoURL');
    const removeArticleVideoURLButton = document.getElementById('removeArticleVideoURL');
    const removedImages = [];

    console.log('DOMContentLoaded event fired');

    addArticleImageURLButton.addEventListener('click', function () {
        const articleImageURLInput = document.createElement('input');
        articleImageURLInput.type = 'text';
        articleImageURLInput.name = 'editedArticleImages[]';
        articleImageURLInput.placeholder = 'Enter image URL';
        articleImageURLsContainer.appendChild(articleImageURLInput);
    });

    removeArticleImageURLButton.addEventListener('click', function () {
        const articleImageURLInputs = articleImageURLsContainer.querySelectorAll('input[name="editedArticleImages[]"]');
        if (articleImageURLInputs.length > 1) {
            const lastArticleImageURLInput = articleImageURLInputs[articleImageURLInputs.length - 1];
            lastArticleImageURLInput.remove();
        }
    });

    addArticleVideoURLButton.addEventListener('click', function () {
        const articleVideoURLInput = document.createElement('input');
        articleVideoURLInput.type = 'text';
        articleVideoURLInput.name = 'editedArticleVideos[]';
        articleVideoURLInput.placeholder = 'Enter video URL';
        articleVideoURLsContainer.appendChild(articleVideoURLInput);
    });

    removeArticleVideoURLButton.addEventListener('click', function () {
        const articleVideoURLInputs = articleVideoURLsContainer.querySelectorAll('input[name="editedArticleVideos[]"]');
        if (articleVideoURLInputs.length > 1) {
            const lastArticleVideoURLInput = articleVideoURLInputs[articleVideoURLInputs.length - 1];
            lastArticleVideoURLInput.remove();
        }
    });

    addPluginIDButton.addEventListener('click', function () {
        const pluginIDInput = document.createElement('input');
        pluginIDInput.type = 'text';
        pluginIDInput.name = 'editedPluginIDs[]';
        pluginIDInput.placeholder = 'Enter Plugin ID';
        pluginIDsContainer.appendChild(pluginIDInput);
    });

    removePluginIDButton.addEventListener('click', function () {
        const pluginIDInputs = pluginIDsContainer.querySelectorAll('input[name="editedPluginIDs[]"]');
        if (pluginIDInputs.length > 1) {
            const lastPluginIDInput = pluginIDInputs[pluginIDInputs.length - 1];
            lastPluginIDInput.remove();
        }
    });

    const openPluginSelectorButton = document.getElementById('openPluginSelectorButton');

    openPluginSelectorButton.addEventListener('click', function () {
        
        const pluginSelectorWindow = window.open('plugin-selector.php', '_blank', 'width=800,height=600');
    });
});
</script>


</body>
</html>
