
  <?php
    session_start();
   if(isset($_SESSION["user"])){
    // header("Location: index.php");
   }
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
      <h1 class="first">Join Social Network</h1>
    <div class="container mt-4">
        <?php
  print_r($_POST); 
    if (isset($_POST["submit"])) {
    $fullName = $_POST["name"];
    $date = $_POST["date"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordrepeat = $_POST["repeat-password"];  

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();

    if (empty($fullName) || empty($date) || empty($email) || empty($password)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Invalid email");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordrepeat) {
        array_push($errors, "Passwords do not match");
    }

    require_once "database.php";

    // check duplicate email
    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Email already exists");
        }
    }

    // if errors, show them
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // insert user
        $sql = "INSERT INTO user (Full_Name, Date_of_birdth, Email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $fullName, $date, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>Signup successful! Data saved.</div>";
        } else {
            echo "<div class='alert alert-danger'>Database error: Could not prepare statement.</div>";
        }
    }
}
?>


        
        <form action="signup.php" method="post">
            <div class="form-group mb-3">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Full Name">
            </div>

            <div class="form-group mb-3">
                <label for="date">Date of Birth</label>
                <input type="date" id="date" name="date" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
            </div>

            <div class="row mb-3">
                <div class="col">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            </div>

            <div class="col">
                <label for="repeat-password">Repeat Password</label>
                <input type="password" id="repeat-password" name="repeat-password" class="form-control" placeholder="Repeat Password">
            </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
        
    </div>
    
</body>
</html>
