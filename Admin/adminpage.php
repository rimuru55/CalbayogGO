
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link rel="stylesheet" href="../libs/admin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="admin-nav">
    <div class="logo"><img src="../images/CalbayogGO.png" alt="logo"></div>
    <nav>
        <ul>
            <li><a href="#" data-section="Admin/dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="#" data-section="Admin/user-management.php"><i class="fa-solid fa-user"></i> User Management</a></li>
            <li><a href="#" data-section="Admin/admin-analytics.php"><i class="fa-solid fa-chart-simple"></i> Statistics</a></li>
            <li><a href="#" data-section="Admin/admin-contents.php"><i class="fa-solid fa-images"></i> Contents</a></li>
            <li><a href="#" data-section="Admin/admin-announcement.php"><i class="fa-solid fa-bullhorn"></i> Announcements</a></li>
        </ul>
    </nav>

    <form action="../Data/logout.php" method="post" style="display:inline;">
        <button type="submit" class="logout-button">Logout</button>
    </form>
  </div>
  <div class="contents">
  </div>
  <script src="../script.js"></script>
</body>
</html>
