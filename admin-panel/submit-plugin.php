<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $pluginName = $_POST["pluginName"];
    $developerName = $_POST["developerName"];
    $developerSite = $_POST["developerSite"];
    $type = $_POST["type"];
    $categories = isset($_POST["categories"]) ? $_POST["categories"] : array();
    $price = $_POST["price"];
    $os = $_POST["os"];
    $dlPage = $_POST["dlPage"];
    $demo = isset($_POST["demo"]) ? 1 : 0;
    $releaseDate = $_POST["releaseDate"];
    $longDescription = $_POST["longDescription"];
    $shortDescription = $_POST["shortDescription"];
    $catalogImageURL = isset($_POST["catalogImageURL"]) ? $_POST["catalogImageURL"] : "";
    $newCategories = isset($_POST['newCategories']) ? $_POST['newCategories'] : array();

    $imagePaths = isset($_POST['pluginImages']) ? $_POST['pluginImages'] : array(); 

    if (!is_array($categories)) {
        $categories = array(); 
    }
    
    if (!empty($newCategories)) {
        
        foreach ($newCategories as $newCategory) {
            $categoryInsertQuery = "INSERT INTO Category (CategoryName) VALUES (?)";
            $categoryStmt = mysqli_prepare($conn, $categoryInsertQuery);
    
            if ($categoryStmt) {
                mysqli_stmt_bind_param($categoryStmt, "s", $newCategory);
                if (!mysqli_stmt_execute($categoryStmt)) {
                    mysqli_close($conn);
                    throw new Exception("Error inserting new category: " . mysqli_error($conn));
                }
            }
        }
    }
    
    
    $allCategories = array_merge($categories, $newCategories);

    
    $pluginID = submitPlugin($pluginName, $developerName, $developerSite, $type, $allCategories, $price, $os, $dlPage, $demo, $releaseDate, $longDescription, $shortDescription, $catalogImageURL, $imagePaths);
    
    
    if (is_numeric($pluginID)) {
        
        
        echo "Plugin data submitted successfully!";
        header("refresh:5;url=preview.php?pluginID=" . $pluginID); 
    } else {
        
        echo "Error: " . $pluginID;
    }
}
?>
