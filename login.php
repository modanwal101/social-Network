  
 <?php
    session_start();
   if(!isset($_SESSION["user"])){
    header("Location: index.php");
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

     <h1 class="sec">Social Network Login</h1>
    <div class="container">
        <?php
// session_start();
if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "database.php";
    $sql ="SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn,$sql);
    $user = mysqli_fetch_array($result,MYSQLI_ASSOC);

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




        
         <form action="login.php" method="post">
            
    <div class="form-group mb-3">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group mb-3">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary">Login</button>
</form>
     <div ><p>Create Account <a href="signup.php">Sinup</a></p></div>

    </div>
    
</body>
</html>