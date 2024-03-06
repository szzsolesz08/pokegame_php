<?php 
        include("userstorage.php");
        include("auth.php");
        
        session_start();
        
        $userStorage = new UserStorage();
        $auth = new Auth($userStorage);
        
        if ($auth->is_authenticated()) {
            $user = $auth->authenticated_user();
        } else {
            header("Location: login.php");
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
        <div class="userpage"><?= $user['username']?></div>
        <div class="userpage"><?= $user['email']?></div>
        <div class="userpage"><?= $user['money']?>💰</div>
        <?php foreach ($user['cards'] as $pokemon) : ?>
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
                <div class="buy">
                <span class="card-price"><span class="icon">💰 </span><a id="buycard" href="sell.php?<?= $pokemon['name']?>"><?= round(0.9 * $pokemon['price'])?></a></span>
                </div>
            </div>
            </a>
                <?php endforeach; ?>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>