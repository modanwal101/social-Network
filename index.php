

  <?php
    session_start();
   if(!isset($_SESSION["user"])){
    header("Location: login.php");
   }
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        <a href="logout" class="btn btn-warning">Lonout</a>

    </div>
    
</body>
</html>