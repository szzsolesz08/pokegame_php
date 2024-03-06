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

    function sellCard(&$user, &$userStorage){
        $title = str_replace('_', ' ', key($_GET));
        $usercp = $user;
        $admin = $userStorage->findById('admin');
        $soldPokemon;
        foreach ($usercp['cards'] as $k => $card) {
            if ($card['name'] == $title) {
                $soldPokemon = $card;
                $usercp['money'] += round($card['price'] * 0.9);
                unset($usercp['cards'][$k]);
                array_push($admin['cards'], $soldPokemon);
                $userStorage->update('admin', $admin);
                $userStorage->update($user['id'], $usercp);
                return true;
            }
        }
        return false;
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
            <?php if (sellCard($user, $userStorage)): ?>
                <?="Successful purchase!"?>
            <?php else: ?>
                <?="Not enough money!"?>
            <?php endif;?>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>