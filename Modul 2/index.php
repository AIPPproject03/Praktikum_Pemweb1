<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/style.css">
    <title>MODUL 2</title>
</head>

<body>
    <div class="container">
        <div class="content">
            <h2>Gass Login</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" maxlength="7" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label><br>
                <input type="submit" value="Login">
            </form>
            <ul>
                <h2>Ketentuan</h2>
                <li>Username Max 7 karakter</li>
                <li>Password terdiri dari huruf kapital, huruf kecil, angka, dan karakter Khusus</li>
                <li>Password Minimal 10 karakter</li>
            </ul>

            <?php
            $username = $password = "";
            $username_err = $password_err = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (empty(trim($_POST["username"]))) {
                    $username_err = "Please enter username.";
                } elseif (strlen(trim($_POST["username"])) > 7) {
                    $username_err = "Username cannot be longer than 7 characters.";
                } else {
                    $username = trim($_POST["username"]);
                }

                if (empty(trim($_POST["password"]))) {
                    $password_err = "Please enter your password.";
                } elseif (strlen(trim($_POST["password"])) < 10) {
                    $password_err = "Password must have at least 10 characters.";
                } elseif (
                    !preg_match('/[A-Z]/', $_POST["password"]) || !preg_match('/[a-z]/', $_POST["password"]) || !preg_match('/[0-9]/', $_POST["password"]) || !preg_match('/[^A-Za-z0-9]/', $_POST["password"])
                ) {
                    $password_err = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                } else {

                    $password = trim($_POST["password"]);
                }

                if (empty($username_err) && empty($password_err)) {
                    header("location: validate.php");
                    exit;
                }
            }
            ?>

            <?php if (!empty($username_err) || !empty($password_err)) : ?>
                <div class="error">
                    <?php echo $username_err . "<br>" . $password_err; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>