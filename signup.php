<?php
if (isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db.php';

    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password'])); 
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password'])); 

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) { 
        $error = "Passwords do not match.";
    } else {
        $conn = new Database();
        $sql = "SELECT id FROM users WHERE email = ?";
        $count = $conn->countRows($sql, [$email]);
        if ($count > 0) {
                $error = "This email is already registered.";
        } else {
            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $hashed_password = sha1(md5($password)); 
            $returnId = $conn->create($sql, [$email, $hashed_password]);
            if ($returnId) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="Website Icon" type="png" href="../images/w_logo.png" />
    <link rel="Website Icon" type="png" href="../images/w_logo.png" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="login.css" />
    <title>Irrigation Hub</title>
  </head>
  <body>
    <div class="parent">
      <div class="child">
        <div class="row r-margin" style="row-gap: 10px">
          <div class="col-md-6 mt-5">
            <img src="./images/logo.jpg" alt="imaages" class="bottle" />
            <p class="text-center">
              Already have an account? <a href="login.php">Login</a>
            </p>
          </div>
          <div class="col-md-6 mt-5">
            <h1 class="text-center">Sign Up</h1>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger text-center" role="alert">
                <?= $error; ?>
              </div>
            <?php endif; ?>
            <div>
              <form class="scale" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <i class="fa-solid fa-envelope"></i>
                <input
                  type="text"
                  id="email"
                  name="email"
                  placeholder="email"
                  class="lofo mb-5"
                  required
                /><br />
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="password"
                  class="lofo"
                  required
                />
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  placeholder="confirm password"
                  class="lofo"
                  required
                />
                <button class="button">Sign up</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
