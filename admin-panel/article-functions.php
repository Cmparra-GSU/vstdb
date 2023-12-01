<?php
function submitArticle($authorUID, $timestamp, $articleTitle, $articleContent, $articlePreview, $catalogImageURL, $articleImages, $articleVideos, $selectedPlugins) {

    $conn = connect();
    $publicationDate = date('Y-m-d', strtotime($timestamp));

    $query = "INSERT INTO article (Title, Content, Preview, PublicationDate, AuthorUID, visible, CatalogImageURL)
          VALUES (?, ?, ?, ?, ?, 0, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $articleTitle, $articleContent, $articlePreview, $publicationDate, $authorUID, $catalogImageURL);

    if (mysqli_stmt_execute($stmt)) {

        $articleID = mysqli_insert_id($conn);
        $insertImageQuery = "INSERT INTO articleimage (ArticleID, ImageURL) VALUES (?, ?)";
        $stmtImage = mysqli_prepare($conn, $insertImageQuery);
        mysqli_stmt_bind_param($stmtImage, "is", $articleID, $imageURL);

        foreach ($articleImages as $imageURL) {
            if (mysqli_stmt_execute($stmtImage)) {
                echo "Image inserted successfully.<br>";
            } else {
                echo "Image insertion failed: " . mysqli_error($conn) . "<br>";
            }
        }

        $insertVideoQuery = "INSERT INTO articlevideo (ArticleID, VideoURL) VALUES (?, ?)";
        $stmtVideo = mysqli_prepare($conn, $insertVideoQuery);
        mysqli_stmt_bind_param($stmtVideo, "is", $articleID, $videoURL);

        foreach ($articleVideos as $videoURL) {
            if (mysqli_stmt_execute($stmtVideo)) {
                echo "Video inserted successfully.<br>";
            } else {
                echo "Video insertion failed: " . mysqli_error($conn) . "<br>";
            }
        }

        if (!empty($selectedPlugins)) {

            $insertPluginQuery = "INSERT INTO articleplugin (ArticleID, PluginID) VALUES (?, ?)";
            $stmtPlugin = mysqli_prepare($conn, $insertPluginQuery);
            mysqli_stmt_bind_param($stmtPlugin, "ii", $articleID, $pluginID);

            foreach ($selectedPlugins as $pluginID) {
                mysqli_stmt_execute($stmtPlugin);
                echo "Plugin inserted successfully for PluginID: $pluginID<br>";
            }

            mysqli_stmt_close($stmtPlugin); 

        }

        mysqli_close($conn);
        return $articleID;
    } else {
        mysqli_close($conn);
        return "Error: " . mysqli_error($conn);
    }

}

function updateArticle(
    $articleID,
    $editedTitle,
    $editedPreview,
    $editedCatalogImg,
    $editedContent,
    $editedArticleImages,
    $editedArticleVideos,
    $editedPluginIDs,
    $conn
) {
    
    $articleID = intval($articleID); 
    $editedTitle = mysqli_real_escape_string($conn, $editedTitle); 
    $editedPreview = mysqli_real_escape_string($conn, $editedPreview); 
    $editedCatalogImg = mysqli_real_escape_string($conn, $editedCatalogImg); 
    $editedContent = mysqli_real_escape_string($conn, $editedContent); 

    $updateQuery = "UPDATE article SET Title=?, Preview=?, CatalogImageURL=?, Content=? WHERE ArticleID=?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssi", $editedTitle, $editedPreview, $editedCatalogImg, $editedContent, $articleID);

    if (mysqli_stmt_execute($stmt)) {
        
        updateArticleImages($articleID, $editedArticleImages, $conn);
        updateArticleVideos($articleID, $editedArticleVideos, $conn);
        updateArticlePlugins($articleID, $editedPluginIDs, $conn);
        mysqli_stmt_close($stmt); 
        return $articleID; 

    } else {
        return "Error: " . mysqli_error($conn);
    }
}

function updateArticleImages($articleID, $editedArticleImages, $conn) {
    
    $articleID = intval($articleID);
    $existingImages = getArticleImages($articleID, $conn);  
    $existingImageURLs = array_column($existingImages, 'ImageURL');
    
    foreach ($existingImages as $existingImage) {
        if (!in_array($existingImage['ImageURL'], $editedArticleImages)) {
            $imageURLToDelete = $existingImage['ImageURL'];
            $deleteImageQuery = "DELETE FROM articleimage WHERE ArticleID=? AND ImageURL=?";
            $stmtDeleteImage = mysqli_prepare($conn, $deleteImageQuery);
            mysqli_stmt_bind_param($stmtDeleteImage, "is", $articleID, $imageURLToDelete);
            if (mysqli_stmt_execute($stmtDeleteImage)) {
                echo "Image deleted successfully: $imageURLToDelete<br>";
            } else {
                echo "Image deletion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    foreach ($editedArticleImages as $editedImage) {
        if (!in_array($editedImage, $existingImageURLs)) {
            $insertImageQuery = "INSERT INTO articleimage (ArticleID, ImageURL) VALUES (?, ?)";
            $stmtImage = mysqli_prepare($conn, $insertImageQuery);
            mysqli_stmt_bind_param($stmtImage, "is", $articleID, $editedImage);
            if (mysqli_stmt_execute($stmtImage)) {
                echo "Image inserted successfully: $editedImage<br>";
            } else {
                echo "Image insertion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }

}

function updateArticleVideos($articleID, $editedArticleVideos, $conn) {
    
    $articleID = intval($articleID);
    $existingVideos = getArticleVideos($articleID, $conn);
    $existingVideoURLs = array_column($existingVideos, 'VideoURL');

    foreach ($existingVideos as $existingVideo) {  
        if (!in_array($existingVideo['VideoURL'], $editedArticleVideos)) {
            $videoURLToDelete = $existingVideo['VideoURL'];
            $deleteVideoQuery = "DELETE FROM articlevideo WHERE ArticleID=? AND VideoURL=?";
            $stmtDeleteVideo = mysqli_prepare($conn, $deleteVideoQuery);
            mysqli_stmt_bind_param($stmtDeleteVideo, "is", $articleID, $videoURLToDelete);
            if (mysqli_stmt_execute($stmtDeleteVideo)) {
                echo "Video deleted successfully: $videoURLToDelete<br>";
            } else {
                echo "Video deletion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }
    
    foreach ($editedArticleVideos as $editedVideo) {
        if (!in_array($editedVideo, $existingVideoURLs)) {
            $insertVideoQuery = "INSERT INTO articlevideo (ArticleID, VideoURL) VALUES (?, ?)";
            $stmtVideo = mysqli_prepare($conn, $insertVideoQuery);
            mysqli_stmt_bind_param($stmtVideo, "is", $articleID, $editedVideo);

            if (mysqli_stmt_execute($stmtVideo)) {
                echo "Video inserted successfully: $editedVideo<br>";
            } else {
                echo "Video insertion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }
}


function updateArticlePlugins($articleID, $editedPluginIDs, $conn) {
    
    $articleID = intval($articleID);
    $existingPlugins = getAssociatedPlugins($articleID, $conn);
    $existingPluginIDs = array_column($existingPlugins, 'PluginID');

    foreach ($existingPlugins as $existingPlugin) {
        $existingPluginID = $existingPlugin['PluginID'];
        if (!in_array($existingPluginID, $editedPluginIDs)) {
            $deletePluginQuery = "DELETE FROM articleplugin WHERE ArticleID=? AND PluginID=?";
            $stmtDeletePlugin = mysqli_prepare($conn, $deletePluginQuery);
            mysqli_stmt_bind_param($stmtDeletePlugin, "ii", $articleID, $existingPluginID);
            if (mysqli_stmt_execute($stmtDeletePlugin)) {
                echo "Plugin deleted successfully for PluginID: $existingPluginID<br>";
            } else {
                echo "Plugin deletion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    foreach ($editedPluginIDs as $editedPluginID) {
        if (!in_array($editedPluginID, $existingPluginIDs)) {
            $insertPluginQuery = "INSERT IGNORE INTO articleplugin (ArticleID, PluginID) VALUES (?, ?)";
            $stmtPlugin = mysqli_prepare($conn, $insertPluginQuery);
            mysqli_stmt_bind_param($stmtPlugin, "ii", $articleID, $editedPluginID);
            if (mysqli_stmt_execute($stmtPlugin)) {
                echo "Plugin inserted successfully for PluginID: $editedPluginID<br>";
            } else {
                echo "Plugin insertion failed: " . mysqli_error($conn) . "<br>";
            }
        }
    }
}




function getArticleData($articleID, $conn) {
    
    $articleID = mysqli_real_escape_string($conn, $articleID);

    $query = "SELECT a.*, 
    GROUP_CONCAT(DISTINCT ai.ImageURL) AS Images, 
    GROUP_CONCAT(DISTINCT av.VideoURL) AS Videos,
    GROUP_CONCAT(DISTINCT ap.PluginID) AS SelectedPlugins
        FROM article a
        LEFT JOIN articleimage ai ON a.ArticleID = ai.ArticleID
        LEFT JOIN articlevideo av ON a.ArticleID = av.ArticleID
        LEFT JOIN articleplugin ap ON a.ArticleID = ap.ArticleID
        WHERE a.ArticleID = $articleID
        GROUP BY a.ArticleID";
    //end of query 

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    $articleData = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $articleData;
}


function getArticleImages($articleID, $conn) {
    
    $articleID = mysqli_real_escape_string($conn, $articleID);
    $query = "SELECT * FROM articleimage WHERE ArticleID = $articleID";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    $articleImages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $articleImages[] = $row;
    }

    mysqli_free_result($result);
    return $articleImages;
}


function getArticleVideos($articleID, $conn) {
    
    $articleID = mysqli_real_escape_string($conn, $articleID);
    $query = "SELECT * FROM articlevideo WHERE ArticleID = $articleID";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    $articleVideos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $articleVideos[] = $row;
    }

    mysqli_free_result($result);
    return $articleVideos;
}

function getAssociatedPlugins($articleID, $conn) {
    
    $articleID = mysqli_real_escape_string($conn, $articleID);

    $query = "SELECT p.* FROM plugin p
              INNER JOIN articleplugin ap ON p.PID = ap.PluginID
              WHERE ap.ArticleID = $articleID";

    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    $associatedPlugins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $associatedPlugins[] = $row;
    }

    mysqli_free_result($result);
    return $associatedPlugins;
}



function getPluginsByIds($pluginIDs, $conn) {

    $sanitizedPluginIDs = array_map('intval', $pluginIDs);
    $pluginIDString = implode(',', $sanitizedPluginIDs);
    $query = "SELECT * FROM plugin WHERE PID IN ($pluginIDString)";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }
    
    $plugins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $plugins[] = $row;
    }

    mysqli_free_result($result);
    return $plugins;
}




function getAuthorUsername($authorUID, $conn) {

    $sql = "SELECT UserName FROM user WHERE UID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $authorUID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $authorUsername);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            return $authorUsername;
        }
    }
    return "Unknown";
}

function clearArticleData($articleID, $conn, $dataType) {
    
    $sql = "";
    switch ($dataType) {
        case "pluginIDs":
            $sql = "DELETE FROM articlePlugin WHERE ArticleID = ?";
            break;
        case "videoURLs":
            $sql = "UPDATE articles SET Videos = '' WHERE ArticleID = ?";
            break;
        case "imageURLs":
            $sql = "UPDATE articles SET Images = '' WHERE ArticleID = ?";
            break;
        default:
            return "Invalid data type";
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return "Error preparing SQL statement: " . $conn->error;
    }

    $stmt->bind_param("i", $articleID);

    if (!$stmt->execute()) {
        return "Error executing SQL statement: " . $stmt->error;
    }

    $stmt->close();
    return true;
}

function getRelatedPlugins($articleID, $conn) {
    
    $relatedPlugins = [];

    $sql = "SELECT p.* 
            FROM plugin p
            INNER JOIN articleplugin ap ON p.PID = ap.PluginID
            WHERE ap.ArticleID = $articleID
            LIMIT 3"; 

    $result = mysqli_query($conn, $sql);

    
    if ($result) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $relatedPlugins[] = $row;
        }
    }
    mysqli_close($conn);
    return $relatedPlugins;
}
?>