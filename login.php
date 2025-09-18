<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>  

<h1 class="sec text-center my-3">Social Network Login</h1>
<div class="container">

<?php
if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "database.php";

    // Prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user){
        if(password_verify($password, $user["password"])){
            $_SESSION["user"] = $user["Full_Name"];
            header("Location: index.php"); 
            exit();
        } else {
            echo "<div class='alert alert-danger'>Password does not match</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not exist</div>";
    }
}
?>

<form action="login.php" method="post" class="p-4 shadow rounded bg-light">
    <div class="form-group mb-3">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group mb-3">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
</form>

<div class="text-center mt-3">
    <p>Create Account <a href="signup.php">Signup</a></p>
</div>

</div>
</body>
</html>
