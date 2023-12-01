<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <div class="menu" onclick="toggleSidebar(event)">
                        <div class ="menu-icon">︾</div>
                    </div>
                    <a href = "index.php">VST Database</a>
                    <div class="search-container">
                        <form action="catalog.php" method="get">
                            <input type="text" id="searchInput" name="search" class="search-input" placeholder="Search...">
                            <label for="searchInput" class="search-icon">⌕</label>
                        </form>
                    </div>
                </div>

                    <div id="sidebar" class="sidebar">

                        <div class="closebtn" onclick="closeSidebar()">ⓧ</div>

                        <a href="index.php" class="contact-button">Home</a>
                        <a href="articles.php" class="browse-button">Articles</a>
                        <a href="catalog.php" class="browse-button">Browse</a>
                        <a href="contact.php" class="contact-button">Contact</a>

                        <div class="dropdown">

                            <a class = account-button>Account</a>

                                <div class="dropdown-content">
                                    <?php
                                    if (isset($_SESSION['UserID'])) {
                                        
                                        echo '<a href="dashboard.php">Profile</a>';
                                        echo '<a href="logout.php">Logout</a>';
                                        if ($_SESSION['UserRole'] === 'admin' || $_SESSION['UserRole'] === 'webmaster') {
                                            echo '<a href="../admin-panel/admin.php">Admin Panel</a>'; 
                                        }
                                    } else {
                                        
                                        echo '<a href="#" onclick="openLoginModal()">Log In</a>';
                                        echo '<a href="#" onclick="openModal()">Sign Up</a>';
                                    }
                                    ?>
                                </div>

                        </div>
                        
                    </div>
            </div>
        </div>
    </header>
    <div id="signup-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Sign Up</h2>
            <form action="signup.php" method="post">
                <!-- Add your sign-up form fields here -->
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>

    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2>Log In</h2>
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>