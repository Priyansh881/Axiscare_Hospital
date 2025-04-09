<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>

<body>
    <?php
    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"] = $date;

    // Import database
    include("connection.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];

        $error = '';

        $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
        if ($result->num_rows == 1) {
            $utype = $result->fetch_assoc()['usertype'];
            $passwordHash = md5($password);  // Hashing password for security

            if ($utype == 'p') {
                // Check for patient credentials
                $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$passwordHash'");
                if ($checker->num_rows == 1) {
                    // Patient dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'p';
                    header('Location: patient/index.php');
                    exit;
                } else {
                    $error = 'Wrong credentials: Invalid email or password';
                }
            } elseif ($utype == 'a') {
                // Check for admin credentials
                $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$passwordHash'");
                if ($checker->num_rows == 1) {
                    // Admin dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'a';
                    header('Location: admin/index.php');
                    exit;
                } else {
                    $error = 'Wrong credentials: Invalid email or password';
                }
            } elseif ($utype == 'd') {
                // Check for doctor credentials
                $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$passwordHash'");
                if ($checker->num_rows == 1) {
                    // Doctor dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'd';
                    header('Location: doctor/index.php');
                    exit;
                } else {
                    $error = 'Wrong credentials: Invalid email or password';
                }
            }
        } else {
            $error = 'We can\'t find any account for this email.';
        }
    }
    ?>

    <center>
        <div class="container">
            <form action="" method="POST">
                <table border="0" style="margin: 0;padding: 0;width: 60%;">
                    <tr>
                        <td>
                            <p class="header-text">Welcome Back!</p>
                        </td>
                    </tr>
                    <div class="form-body">
                        <tr>
                            <td>
                                <p class="sub-text">Login with your details to continue</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <label for="useremail" class="form-label">Email: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <label for="userpassword" class="form-label">Password: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <input type="password" name="userpassword" class="input-text" placeholder="Password" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>
                                <?php
                                if (!empty($error)) {
                                    echo '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">' . $error . '</label>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="Login" class="login-btn btn-primary btn">
                            </td>
                        </tr>
                    </div>
                    <tr>
                        <td>
                            <br>
                            <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                            <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                            <br><br><br>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </center>
</body>

</html>
