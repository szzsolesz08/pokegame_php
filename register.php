<?php 
    include('userstorage.php');
    include('auth.php');

    $data = [];
    $errors = [];

    $userStorage = new UserStorage();
    $auth = new Auth($userStorage);
    if (count($_POST) > 0) {
        if (validate($data, $errors)) {
            if (!$auth->user_exists($data['username'])) {
                $auth->register($data);
                header("Location: login.php");
            }
        }
    }

    function validate(&$data, &$errors){
        if (isset($_POST["username"]) && trim($_POST["username"]) !== "") {
            $data["username"] = $_POST["username"];
        } else {
            $errors["username"] = "Should not be empty!";
        }

        if(isset($_POST["email"]) && trim($_POST["email"]) !== "" && filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)){
            $data["email"] = $_POST["email"];
        }
        else{
            $errors["email"] = "Email not valid!";
        }

        if (isset($_POST["password"]) && trim($_POST["password"]) !== "") {
            $data["password"] = $_POST["password"];
        } else {
            $errors["password"] = "Should not be empty!";
        }

        return count($errors) === 0;
    }

    /*function validate(&$errors, &$data){
        if(trim($_POST['username']) == ""){
            $errors['username'] = "Should not be empty!";
        }
        else{
            $data['username'] = $_POST['username'];
        }

        if($_POST['password'] == ""){
            $errors['password'] = "Should not be empty!";
        }
        else{
            $data['password'] = $_POST['password'];
        }

        if($_POST['email'] == ""){
            $errors['email'] = "Should not be empty!";
        }
        else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Email not valid!";
        }
        else{
            $data['email'] = $_POST['email'];
        }

        return count($errors) === 0;
    }*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home</h1>
    </header>
    <div id="content">
    <form action="" method="post">
        <p id="globalError" hidden>User already exists</p>
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= $data["username"] ?? "" ?>">
        <small><?= $errors["username"] ?? ""  ?></small>
        <br>
        <label for="email">Email:</label>
        <input type="text" name="email" value="<?= $data["email"] ?? "" ?>">
        <small><?= $errors["email"] ?? ""  ?></small>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" value="<?= $data["password"] ?? "" ?>">
        <small><?= $errors["password"] ?? ""  ?></small>
        <br>
        <button type="submit">Register</button>
    </form>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>