<?php
include('functions.php');
session_start();
isAdmin();


$totalUsers = getTotalUsers();
$flaggedUsersCount = getRecentlyFlaggedUsers();
$totalArticles = getTotalArticles();
$totalPlugins = getTotalPlugins();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VST Database Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <script src="index.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Admin Dashboard</div>
                <div class="button-group">
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="controls">
            <h2 class = action>Admin Actions</h2><br>
            <div class = "button-group">
                <a href="create-article.php" class = "button">Create Article</a>
                <a href="edit-article.php" class = "button">Edit Article</a>
                <a href="plugin-create.php" class = "button">Create Plugin</a>
                <a href="edit.php" class = "button">Edit Plugin</a>
                <a href="accounts.php" class = "button">View Users</a>
            </div>
        </div>

        <div class="controls">
            <h2 class = action>User Stats</h2><br>
            <div class = "button-group">
                <ul>
                    <li>Total Number of Users: <?php echo $totalUsers; ?></li>
                    <li>Recently Flagged Users: <?php echo $flaggedUsersCount; ?></li>
                </ul>
            </div>
        </div>

        <div class="controls">
            <h2 class = action>Website Stats</h2><br>
            <div class = "button-group">
                <ul>
                    <li>Total Number of Articles: <?php echo $totalArticles; ?></li>
                    <li>Total Number of Plugins: <?php echo $totalPlugins; ?></li>
                </ul>
            </div>
        </div>

    </div>
    
</body>
</html>
