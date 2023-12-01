<?php


function submitPlugin($pluginName, $developerName, $developerSite, $type, $categories, $price, $os, $dlPage, $demo, $releaseDate, $longDescription, $shortDescription, $catalogImageURL, $imagePaths) {
    $conn = connect();

    
    $existingDeveloper = getDeveloperByName($developerName);

    if ($existingDeveloper) {
        
        $developerName = $existingDeveloper['DevName'];
        $developerSite = $existingDeveloper['Website'];
    } else {
        
        $developerInsertQuery = "INSERT INTO Developer (DevName, Website) VALUES (?, ?)";
        $developerStmt = mysqli_prepare($conn, $developerInsertQuery);
        mysqli_stmt_bind_param($developerStmt, "ss", $developerName, $developerSite);

        if (!mysqli_stmt_execute($developerStmt)) {
            mysqli_close($conn);
            throw new Exception("Error inserting data into Developer table: " . mysqli_error($conn));
        }
    }

    
    $typeID = getTypeIDByName($type);

    if (!$typeID) {
        
        $typeInsertQuery = "INSERT INTO plugintype (TypeName) VALUES (?)";
        $typeStmt = mysqli_prepare($conn, $typeInsertQuery);

        if (!$typeStmt) {
            mysqli_close($conn);
            throw new Exception("Error preparing the SQL statement for Type insert: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($typeStmt, "s", $type);

        if (!mysqli_stmt_execute($typeStmt)) {
            mysqli_close($conn);
            throw new Exception("Error executing the SQL statement for Type insert: " . mysqli_error($conn));
        }

        
        $typeID = mysqli_insert_id($conn);
    }

    
    $pluginInsertQuery = "INSERT INTO Plugin (Name, DevName, TypeID, Price, OS, DLPage, Demo, ReleaseDate, LongDescription, ShortDescription, CatalogImageURL)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $pluginInsertQuery);

    if (!$stmt) {
        mysqli_close($conn);
        throw new Exception("Error preparing the SQL statement for Plugin insert: " . mysqli_error($conn));
    }

    if (empty($releaseDate)) {
        $releaseDate = null;
    }

    mysqli_stmt_bind_param($stmt, "sssiisissss", $pluginName, $developerName, $typeID, $price, $os, $dlPage, $demo, $releaseDate, $longDescription, $shortDescription, $catalogImageURL);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_close($conn);
        throw new Exception("Error executing the SQL statement for Plugin insert: " . mysqli_error($conn));
    }

    
    $pluginID = mysqli_insert_id($conn);

    
    updatePluginCategories($pluginName, $categories);

    
    foreach ($imagePaths as $imagePath) {
        $sql = "INSERT INTO pluginimage (PluginID, ImageURL) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $pluginID, $imagePath);
            if (!mysqli_stmt_execute($stmt)) {
                mysqli_close($conn);
                throw new Exception("Error inserting plugin image: " . mysqli_error($conn));
            }
        }
    }

    mysqli_close($conn);
    return $pluginID;
}




function getDeveloperByName($developerName) {
    $conn = connect();
    $sql = "SELECT * FROM Developer WHERE DevName = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $developerName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $row;
        }
    }

    mysqli_close($conn);
    return null;
}

function insertDeveloper($developerName, $developerSite) {
    $conn = connect();
    $sql = "INSERT INTO Developer (DevName, Website) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $developerName, $developerSite);
        mysqli_stmt_execute($stmt);
    }

    mysqli_close($conn);
}



function getTypeIDByName($typeName) {
    $conn = connect();
    $sql = "SELECT TypeID FROM PluginType WHERE TypeName = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $typeName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $typeID);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $typeID;
        } else {
            
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return null; 
        }
    }

    mysqli_close($conn);
    return null;
}

function getPluginIDByName($pluginName) {
    $conn = connect();
    $sql = "SELECT PID FROM Plugin WHERE Name = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $pluginName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $pluginID);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $pluginID;
        } else {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return null; 
        }
    }

    mysqli_close($conn);
    return null;
}

function getCategoryIDByName($categoryName) {
    $conn = connect();
    $sql = "SELECT CategoryID FROM Category WHERE CategoryName = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $categoryName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $categoryID);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $categoryID;
        } else {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return null; 
        }
    }

    mysqli_close($conn);
    return null;
}


function getAllPlugins() {
    
    $conn = connect();

    $query = "SELECT * FROM plugin WHERE visible = 1";
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $plugins = [];
    while ($row = $result->fetch_assoc()) {
        $plugins[] = $row;
    }

    $conn->close();
    return $plugins;
}



function updatePluginCategories($pluginName, $categories) {
    $conn = connect();
    $pluginID = getPluginIDByName($pluginName);

    if (!$pluginID) {
        mysqli_close($conn);
        throw new Exception("Plugin not found: " . $pluginName);
    }

    
    $deleteSql = "DELETE FROM hasType WHERE PluginID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);

    if ($deleteStmt) {
        mysqli_stmt_bind_param($deleteStmt, "i", $pluginID);
        mysqli_stmt_execute($deleteStmt);
    }

    
    foreach ($categories as $category) {
        
        $categoryID = getCategoryIDByName($category);

        if (!$categoryID) {
            
            $insertCategorySql = "INSERT INTO Category (CategoryName) VALUES (?)";
            $insertCategoryStmt = mysqli_prepare($conn, $insertCategorySql);

            if ($insertCategoryStmt) {
                mysqli_stmt_bind_param($insertCategoryStmt, "s", $category);
                if (!mysqli_stmt_execute($insertCategoryStmt)) {
                    mysqli_close($conn);
                    throw new Exception("Error inserting category: " . mysqli_error($conn));
                }
                
                $categoryID = mysqli_insert_id($conn);
            } else {
                mysqli_close($conn);
                throw new Exception("Error preparing SQL statement for category insert: " . mysqli_error($conn));
            }
        }

        $insertSql = "INSERT INTO hasType (PluginID, CategoryID) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);

        if ($insertStmt) {
            mysqli_stmt_bind_param($insertStmt, "ii", $pluginID, $categoryID);
            mysqli_stmt_execute($insertStmt);
        }
    }
    
    $updateCategoryIDSql = "UPDATE Plugin SET CategoryID = ? WHERE PID = ?";
    $updateCategoryIDStmt = mysqli_prepare($conn, $updateCategoryIDSql);

    if ($updateCategoryIDStmt) {
        mysqli_stmt_bind_param($updateCategoryIDStmt, "ii", $categoryID, $pluginID);
        mysqli_stmt_execute($updateCategoryIDStmt);
    }

    mysqli_close($conn);
}


function getPluginInfo($pluginID) {
    $conn = connect();

    $sql = "SELECT P.*, D.Website, T.TypeName
            FROM Plugin P
            LEFT JOIN Developer D ON P.DevName = D.DevName
            LEFT JOIN PluginType T ON P.TypeID = T.TypeID
            WHERE P.PID = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pluginID);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $pluginInfo = mysqli_fetch_assoc($result);
        mysqli_close($conn);
        return $pluginInfo;
    } else {
        mysqli_close($conn);
        return null;
    }
}

function getCategoriesByPluginID($pluginID) {
    $conn = connect();
    $categories = array();

    $sql = "SELECT C.CategoryName
            FROM Category C
            INNER JOIN hasType H ON C.CategoryID = H.CategoryID
            WHERE H.PluginID = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pluginID);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row['CategoryName'];
        }

        mysqli_close($conn);
        return $categories;
    } else {
        mysqli_close($conn);
        return null;
    }
}

function getPluginImagePaths($pluginID) {
    $conn = connect();
    $imagePaths = array();

    $sql = "SELECT ImageURL FROM pluginimage WHERE PluginID = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pluginID);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $imagePaths[] = $row['ImageURL'];
        }

        mysqli_close($conn);
        return $imagePaths;
    } else {
        mysqli_close($conn);
        return null;
    }
}

function getRandomPlugins($count) {
    $conn = connect();

    $query = "SELECT * FROM Plugin WHERE visible = 1 ORDER BY RAND() LIMIT $count";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $randomPlugins = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $randomPlugins[] = $row;
        }
        mysqli_close($conn);
        return $randomPlugins;
    } else {
        mysqli_close($conn);
        return [];
    }
}




function getAllCategories() {
    $conn = connect();

    $sql = "SELECT * FROM Category";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $categories = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        mysqli_close($conn);
        return $categories;
    } else {
        mysqli_close($conn);
        return array();
    }
}

function getFilteredPlugins($search = "", $category = "", $sortingCriterion = "name") {
    $conn = connect();

    $sql = "SELECT * FROM Plugin WHERE visible = 1";

    if (!empty($search)) {
        $sql .= " AND (Name LIKE '%$search%' OR LongDescription LIKE '%$search%')";
    }

    
    if (!empty($category)) {
        $sql .= " AND CategoryID = " . intval($category);
    }

    $orderBy = "";
    if ($sortingCriterion === 'name') {
        $orderBy = " ORDER BY Name";
    } elseif ($sortingCriterion === 'price') {
        $orderBy = " ORDER BY Price";
    } elseif ($sortingCriterion === 'release_date') {
        $orderBy = " ORDER BY ReleaseDate";
    }

    $sql .= $orderBy;

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $plugins = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $plugins[] = $row;
        }
        mysqli_close($conn);
        return $plugins;
    } else {
        mysqli_close($conn);
        return array();
    }
}


function getPluginData($pluginID) {
    $conn = connect();

    $sql = "SELECT P.*, D.Website, T.TypeName, GROUP_CONCAT(DISTINCT C.CategoryName) AS CategoryNames
        FROM plugin P
        LEFT JOIN developer D ON P.DevName = D.DevName
        LEFT JOIN plugintype T ON P.TypeID = T.TypeID
        LEFT JOIN hasType H ON P.PID = H.PluginID
        LEFT JOIN category C ON H.CategoryID = C.CategoryID
        LEFT JOIN pluginimage I ON P.PID = I.PluginID
        WHERE P.PID = ?
        GROUP BY P.PID, D.Website, T.TypeName";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pluginID);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $pluginData = mysqli_fetch_assoc($result);

        
        $imageURLs = getPluginImagePaths($pluginID);
        $pluginData['imageURL'] = $imageURLs;

        
        $categoryNames = explode(",", $pluginData['CategoryNames']);
        $pluginData['Categories'] = $categoryNames;

        mysqli_close($conn);
        return $pluginData;
    } else {
        mysqli_close($conn);
        return null;
    }
}


function getPluginCategories() {
    $conn = connect(); 

    $sql = "SELECT * FROM Category";
    $result = mysqli_query($conn, $sql);

    $categories = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    mysqli_close($conn);

    return $categories;
}


function updatePlugin(
    $pluginID,
    $editedTitle,
    $editedDevName,
    $editedDevSite,
    $editedType,
    $editedCategories,
    $editedPrice,
    $editedDL,
    $editedDemo,
    $editedDate,
    $editedLD,
    $editedShortDescription,
    $editedCatIMG,
    $editedImageURLs,
    $conn
) {
    
    $editedTitle = mysqli_real_escape_string($conn, $editedTitle);
    $editedDevName = mysqli_real_escape_string($conn, $editedDevName);
    $editedDevSite = mysqli_real_escape_string($conn, $editedDevSite);
    $editedType = mysqli_real_escape_string($conn, $editedType);
    $editedPrice = floatval($editedPrice);
    $editedDL = mysqli_real_escape_string($conn, $editedDL);
    $editedDemo = isset($editedDemo) ? 1 : 0;
    $editedDate = mysqli_real_escape_string($conn, $editedDate);
    $editedLD = mysqli_real_escape_string($conn, $editedLD);
    $editedShortDescription = mysqli_real_escape_string($conn, $editedShortDescription);
    $editedCatIMG = mysqli_real_escape_string($conn, $editedCatIMG);

    $typeMapping = [
        'Generator' => 1,
        'Effect' => 2,
        'Multi' => 3,
    ];
    
    $editedTypeID = $typeMapping[$editedType] ?? 1;

    $updateQuery = "UPDATE plugin SET
        Name = '$editedTitle',
        DevName = '$editedDevName',
        TypeID = $editedTypeID, 
        Price = $editedPrice,
        DLPage = '$editedDL',
        Demo = $editedDemo,
        ReleaseDate = '$editedDate',
        LongDescription = '$editedLD',
        ShortDescription = '$editedShortDescription',
        CatalogImageURL = '$editedCatIMG'
        WHERE PID = $pluginID";


    
    $result = mysqli_query($conn, $updateQuery);

    if (!$result) {
        
        return "Update failed: " . mysqli_error($conn);
    }

    clearPluginCategories($pluginID, $conn);
    clearPluginImageURLs($pluginID, $conn);

    if (!empty($editedCategories)) {
        foreach ($editedCategories as $categoryName) {
            
            $categoryID = getCategoryIDByName($categoryName);
    
            if (!$categoryID) {
                $insertCategoryQuery = "INSERT INTO Category (CategoryName) VALUES ('$categoryName')";
                mysqli_query($conn, $insertCategoryQuery);
                $categoryID = mysqli_insert_id($conn);
            }
            $insertCategoryAssociationQuery = "INSERT INTO hasType (PluginId, CategoryId)
                VALUES ($pluginID, $categoryID)";
            mysqli_query($conn, $insertCategoryAssociationQuery);
        }
    }

    if (!empty($editedImageURLs)) {
        foreach ($editedImageURLs as $imageURL) {
            $insertImageURLQuery = "INSERT INTO pluginimage (PluginID, imageURL)
                VALUES ($pluginID, '$imageURL')";
            mysqli_query($conn, $insertImageURLQuery);
        }
    }

    if ($editedDevSite !== null) {
        $updateDeveloperWebsiteQuery = "UPDATE developer SET
            Website = '$editedDevSite'
            WHERE DevName = '$editedDevName'";
        mysqli_query($conn, $updateDeveloperWebsiteQuery);
    }

    mysqli_close($conn);
    return $pluginID; 
}




function clearPluginCategories($pluginID, $conn) {
    
    $pluginID = mysqli_real_escape_string($conn, $pluginID);
    $query = "DELETE FROM hasType WHERE PluginID = $pluginID";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }
    return true; 
}


function clearPluginImageURLs($pluginID, $conn) {
    
    $pluginID = mysqli_real_escape_string($conn, $pluginID);
    $query = "DELETE FROM pluginimage WHERE PluginID = $pluginID";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }
    return true; 
}
?>