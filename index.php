<?php 
    include("userstorage.php");
    include("auth.php");
    
    session_start();
    
    $userStorage = new UserStorage();
    $auth = new Auth($userStorage);
    
    if ($auth->is_authenticated()) {
        $user = $auth->authenticated_user();
    } else {
        //header("Location: login.php");
    }

    function isAdmin(&$u){
        foreach ($u['roles'] as $role) {
            if ($role == "admin") {
                return true;
            }
        }
        return false;
    }

    $json_pokemons = file_get_contents("pokemons.json");
    $pokemons = json_decode($json_pokemons, true);
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
        <?php if ($auth->is_authenticated()): ?>
            <?php if(isAdmin($user)): ?>
                <button id="newcardButton" onclick="window.location.href='newcard.php'">Admin</button>
            <?php endif;?>
                <button id="logoutButton" onclick="window.location.href='logout.php'" id="logout">Logout</button><br>
                <h1 id="welcome">Welcome <a href="user.php"><?= $user['username']?></a>!</h1> <p id="money"><?= $user['money']?>💰</p>
            <?php else:?>
                <button id="newcardButton" onclick="window.location.href='register.php'">Register</button><br>
                <button id="logoutButton" onclick="window.location.href='login.php'">Login</button><br>
        <?php endif;?>
    </header>
    <div id="content">
        <div id="card-list">
                <?php foreach ($pokemons as $pokemon) : ?>
                <div class="pokemon-card">
                <div class="image clr-<?= $pokemon['type']?>">
                    <img src="<?= $pokemon['image']?>">
                </div>
                <div class="details">
                    <h2><a href="details.php?<?= $pokemon['name']?>"><?= $pokemon['name']?></a></h2>
                    <span class="card-type"><span class="icon">🏷</span><?= $pokemon['type']?></span>
                    <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> <?= $pokemon['hp']?></span>
                        <span class="card-attack"><span class="icon">⚔</span> <?= $pokemon['attack']?></span>
                        <span class="card-defense"><span class="icon">🛡</span> <?= $pokemon['defense']?></span>
                    </span>
                </div>
                <?php if ($auth->is_authenticated()): ?>
                <div class="buy">
                    <span class="card-price"><span class="icon">💰 </span><a id="buycard" href="buy.php?<?= $pokemon['name']?>"><?= $pokemon['price']?></a></span>
                </div>
                <?php endif;?>
            </div>
            </a>
                <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>