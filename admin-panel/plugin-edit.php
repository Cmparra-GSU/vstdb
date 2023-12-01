<?php
include('functions.php');
include('plugin-functions.php');
include('categories.php');
session_start();
isAdmin();

if(isset($_POST['pluginID'])) {
    $pluginID = $_POST['pluginID'];
} else {
    echo "pluginID is not received.";
}

if ($pluginID !== null) {
    
    $pluginData = getPluginData($pluginID); 
    if (!$pluginData) {
        echo "Plugin not found or you don't have permission to edit it.";
        exit();
    }
} else {
    echo "Invalid plugin ID.";
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
            <div class="logo">Article Edit</div>
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
        <div class="edit">

            <h1>Edit Plugin: <?php echo htmlspecialchars($pluginData['Name']) ; ?></h1>
            <h1>ID: <?php echo htmlspecialchars($pluginData['PID']);?></h1>
            
            <form method="POST" action="updatePlugin.php">
                
                <div class="formSection">
                    <label for="editedName">Title:</label><br>
                    <input type="text" name="editedTitle" id="editedName" value="<?php echo htmlspecialchars($pluginData['Name']); ?>" required><br>
                </div>

                <div class="formSection">
                    <label for="editedDevName">Developer Name:</label><br>
                    <input type="text" name="editedDevName" id="editedDevName" value="<?php echo htmlspecialchars($pluginData['DevName']); ?>" required><br>
                </div>

                <div class="formSection">
                    <label for="editedDevSite">Developer Website:</label><br>
                    <input type="text" name="editedDevSite" id="editedDevSite" value="<?php echo htmlspecialchars($pluginData['Website']); ?>"><br>
                </div>

                <div class="formSection">
                    <label for="type">Plugin Type:</label><br>
                    <select id="type" name="type" required>
                        <option value="Generator" <?php echo ($pluginData['TypeName'] == 'Generator') ? 'selected' : ''; ?>>Generator</option>
                        <option value="Effect" <?php echo ($pluginData['TypeName'] == 'Effect') ? 'selected' : ''; ?>>Effect</option>
                        <option value="Multi" <?php echo ($pluginData['TypeName'] == 'Multi') ? 'selected' : ''; ?>>Multi</option>
                    </select><br>
                </div>

                <div class="formSection">
                    <label>Categories:</label><br>
                    <div class="checkbox-grid">
                        <?php

                        $selectedCategories = $pluginData['Categories']; 

                        foreach ($allCategories as $category) {

                            $isChecked = in_array($category, $selectedCategories) ? 'checked' : '';
                            echo '<label><input type="checkbox" name="categories[]" value="' . $category . '" ' . $isChecked . '> ' . $category . '</label><br>';
                        }
                        ?>
                        <label><input type="text" name="newCategory" placeholder="New Category"> (Enter new category here)</label><br>
                    </div>
                </div>

                <div class="formSection">
                    <label for="editedPrice">Price:</label><br>
                    <input type="number" name="editedPrice" id="editedPrice" step="0.01" value="<?php echo htmlspecialchars($pluginData['Price']); ?>"><br>
                </div>

                <div class="formSection">
                    <label for="editedDL">Download Page:</label><br>
                    <input type="text" name="editedDL" id="editedDL" value="<?php echo htmlspecialchars($pluginData['DLPage']); ?>"><br>
                </div>

                <div class="formSection">
                    <label for="editedDemo">Demo Available:</label><br>
                    <input type="checkbox" id="editedDemo" name="editedDemo" class="checkbox-under-label" <?php echo ($pluginData['Demo'] == 1) ? 'checked' : ''; ?>>
                </div>

                <div class="formSection">
                    <label for="editedDate">Release Date:</label><br>
                    <input type="date" id="editedDate" name="editedDate" value="<?php echo htmlspecialchars($pluginData['ReleaseDate']); ?>"><br>
                </div>

                <div class="formSection">
                    <label for="editedLD">Long Description</label><br>
                    <textarea rows="25" cols="130" id="editedLD" name="editedLD" required><?php echo htmlspecialchars($pluginData['LongDescription']); ?></textarea><br>
                </div>


                <div class="formSection">
                    <label for="editedShortDescription">Short Description:</label><br>
                    <textarea id="editedShortDescription" name="editedShortDescription" rows="5" cols="80" placeholder="Required. Short summary for the catalog page" required><?php echo htmlspecialchars($pluginData['ShortDescription']); ?></textarea>
                </div>
                
                <div class="formSection">
                    <div id="imageURLsContainer">
                        <label for="pluginImages">Plugin Image URLs:</label><br>
                        <button type="button" id="addImageURL" class="addrem">+</button>
                        <button type="button" id="removeImageURL" class="addrem">-</button><br>
                        
                        <?php
                        if (isset($pluginData['imageURL']) && is_array($pluginData['imageURL'])) {
                            foreach ($pluginData['imageURL'] as $imageURL) {
                                echo '<input type="text" id="pluginImageURLs" name="pluginImages[]" value="' . htmlspecialchars($imageURL) . '" placeholder="Enter image URL" required><br>';
                            }
                        } else {

                            echo 'No image URLs found.';
                        }
                        ?>

                    </div>
                </div>
                
                <div class="formSection">
                    <label for="editedCatIMG">Catalog Image URL:</label><br>
                    <input type="text" name="editedCatIMG" id="editedCatIMG" value="<?php echo htmlspecialchars($pluginData['CatalogImageURL']); ?>"><br>
                </div>

                <input type="submit" name="updatePlugin" value="Update Plugin">
                <input type="hidden" name="pluginID" value="<?php echo $pluginID; ?>">
                
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageURLsContainer = document.getElementById('imageURLsContainer');
            const addImageURLButton = document.getElementById('addImageURL');
            const removeImageURLButton = document.getElementById('removeImageURL');

            addImageURLButton.addEventListener('click', function() {
                const imageURLInput = document.createElement('input');
                imageURLInput.type = 'text';
                imageURLInput.name = 'pluginImages[]';
                imageURLInput.placeholder = 'Enter image URL';
                imageURLsContainer.appendChild(imageURLInput);
            });

            removeImageURLButton.addEventListener('click', function() {
                const imageURLInputs = imageURLsContainer.querySelectorAll('input[name="pluginImages[]"]');
                if (imageURLInputs.length > 1) {
                    const lastImageURLInput = imageURLInputs[imageURLInputs.length - 1];
                    lastImageURLInput.remove();
                }
            });
        });
    </script>

</body>
</html>
