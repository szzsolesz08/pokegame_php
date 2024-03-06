<?php 
    include("auth.php");
    include('userstorage.php');
    session_start();

    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);
    $errors = [];
    $data = $_SESSION["user"] ?? [];
    if (count($_POST) > 0) {
        if (validate($data, $errors)) {
            if ($auth->user_exists($data["username"])) {
                $user = $auth->authenticate($data["username"], $data["password"]);
                if ($user == NULL) {
                    $errors["global"] = "Felhasználóhoz tartozó jelszó helytelen!";
                }   
                else {
                    $auth->login($user);
                    header("Location: index.php");
                }
            } 
            else {
                $errors["global"] = "Nincs a megadott névhez tartozó felhasználó!";
            }
        }
    }

    function validate(&$data, &$errors){
        if (isset($_POST["username"]) && trim($_POST["username"]) !== "") {
            $data["username"] = $_POST["username"];
        } else {
            $errors["username"] = "Felhasználó név megadása kötelező!";
        }

        if (isset($_POST["password"]) && trim($_POST["password"]) !== "") {
            $data["password"] = $_POST["password"];
        } else {
            $errors["password"] = "Jelszó megadása kötelező!";
        }

        return count($errors) === 0;
    }

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
        <p><?= $errors["global"] ?? "" ?></p>
        <form action="" method="post">
            <label for="username">Felhasználó név:</label>
            <input type="text" name="username" value="<?= $data["username"] ?? "" ?>">
            <small><?= $errors["username"] ?? ""  ?></small>
            <br>
            <label for="password">Jelszó:</label>
            <input type="password" name="password" value="<?= $data["password"] ?? "" ?>">
            <small><?= $errors["password"] ?? ""  ?></small>
            <br>
            <button type="submit">Bejelentkezés</button>
        </form>
    <form action="register.php" method="post">    
        <button type="submit">Regisztráció</button>
    </form>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>