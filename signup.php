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
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- Correct Font Awesome version -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> referrerpolicy="no-referrer" />

</head>
<body>
    <h1 class="first">Join Social Network</h1>

    <div class="container mt-4">
        <?php
        if (isset($_POST["submit"])) {
             require_once "database.php";

            $fullName = $_POST["name"];
            $date = $_POST["date"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordrepeat = $_POST["repeat-password"];  
            // $profile = $_POST["profile"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) || empty($date) || empty($email) || empty($password) || empty($profile)) {
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

            // require_once "database.php";

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
             // Handle file upload
            $profileFileName = null;
            if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] == 0) {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $profileFileName = time() . "_" . basename($_FILES["profile"]["name"]);
                $targetFile = $targetDir . $profileFileName;

                if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
                    array_push($errors, "Failed to upload profile picture.");
                }
            }

            // if errors, show them
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                // insert user
                 $sql = "INSERT INTO user (Full_Name, Date_of_birth, Email, password, profile) 
                        VALUES (?, ?, ?, ?, ?)";
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
         <div class="icon-wrapper">
  <div class="profile-pic" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
    <!-- User Icon -->
    <i class="fa-solid fa-circle-user" id="defaultIcon" style="font-size: 100px; color: gray; position: absolute; top: 0; left: 0;"></i>

    <!-- Uploaded Image -->
    <img id="previewImage" src="" alt="Profile Picture" hidden
         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; position: absolute; top: 0; left: 0;">

    <!-- Upload Button -->
    <label for="profileUpload" class="btn btn-sm btn-secondary" style="position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%);">Upload</label>
    <input type="file" id="profileUpload" name="profile" accept="image/*" hidden>
  </div>
</div>





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

            <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>   

     <script>
const profileUpload = document.getElementById("profileUpload");
const previewImage = document.getElementById("previewImage");
const defaultIcon = document.getElementById("defaultIcon");

profileUpload.addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImage.src = e.target.result;
      previewImage.hidden = false;
    //   defaultIcon.style.display = "none"; 
    };
    reader.readAsDataURL(file);
  }
});
</script>


</body>
   

</html>   