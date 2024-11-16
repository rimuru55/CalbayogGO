<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="libs/admin.css">
    <link rel="stylesheet" href="libs/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="login">
    <div>
        <form method="POST" action="Data/login.php" class="form">
            <img src="images/CalbayogGO.png" alt="logo">
            <h2>Sign in</h2>

            <div class="input-group">
                <input type="text" name="username" class="input" required>
                <label for="username" class="placeholder">Username</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" class="input" required>
                <label for="password" class="placeholder">Password</label>
            </div>

            <!-- Error message display -->
            <?php session_start(); ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']); // Clear the error message after displaying
                    ?>
                </div>
            <?php endif; ?>

            <div class="link-container">
                <a href="user-signup.php" class="right-link">Create Account</a>
            </div>
            <button type="submit">Sign In</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
