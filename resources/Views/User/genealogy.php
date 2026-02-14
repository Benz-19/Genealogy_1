<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JST Genealogy Explorer</title>
    <!-- Styles -->
    <link rel="stylesheet" href="css/genealogy.css">
</head>

<body>
    <header>
        <div class="header-left">
            <div class="header-title">🌳 JST Genealogy Explorer</div>
            <div class="user-welcome">
                <div class="user-icon">
                    <?php 
                        $username = $_SESSION['username'] ?? 'User';
                        echo strtoupper(substr($username, 0, 1));
                    ?>
                </div>
                <span><?php echo htmlspecialchars($username); ?></span>
            </div>
        </div>
        <button class="logout-btn" onclick="handleLogout()">Logout</button>
    </header>

    <div class="breadcrumbs" id="breadcrumb-trail">Home</div>

    <div id="tree-display" class="node-container">
        <div class="loading">Loading your network...</div>
    </div>

    <!-- Js -->
    <script src="js/genealogy.js"></script>
    <script>
    function handleLogout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '/genealogy/logout';
        }
    }
    </script>
</body>

</html>