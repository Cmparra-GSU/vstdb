<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();

if (isset($_GET["pluginID"]) && is_numeric($_GET["pluginID"])) {
    $pluginID = $_GET["pluginID"];

    $pluginInfo = getPluginInfo($pluginID);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plugin Preview</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Preview Plugin</div>
                <div class="button-group">
                    <a href ="admin.php" class = "button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
    
        <h4>At the moment, the preview shows mobile formatting, but if it looks good here, it'll look good on desktop.</h4>
        <div class="article-preview">
            <iframe src="../index/details.php?pluginID=<?php echo $pluginID; ?>" frameborder="0" width="1600px" height="1000px"></iframe>
        </div>

        <div class="edit-buttons">

            <form method="POST" action="plugin-edit.php">
                <input type="hidden" name="pluginID" value="<?php echo $pluginID; ?>">
                <button type="submit">Edit</button>
            </form>

            <button onclick="confirmExit()">OK</button>

            <script>
            function confirmExit() {
                if (confirm('Are you sure you want to exit edit mode? Please remember to change the visibility if you are finished.')) {
                    window.location.href = 'edit.php';
                }
            }
            </script>

        </div>

    </div>
        
</body>
</html>