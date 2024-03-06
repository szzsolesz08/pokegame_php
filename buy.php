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

    function buyCard(&$user, &$userStorage){
        if(!isAdmin($user)){
            $json_pokemons = file_get_contents("pokemons.json");
            $pokemons = json_decode($json_pokemons, true);
            
            $title = str_replace('_', ' ', key($_GET));

            $boughtPokemon;
            $usercp = $user;
            foreach ($pokemons as $k => $pokemon) {
                if ($pokemon['name'] == $title && $pokemon['price'] < $user['money'] && count($user['cards']) <= 5) {
                    $boughtPokemon = $pokemon;
                    $usercp['money'] -= $pokemon['price'];
                    unset($pokemons[$k]);
                    array_push($usercp['cards'], $boughtPokemon);
                    $new_array = json_encode($pokemons, JSON_PRETTY_PRINT);
                    file_put_contents("pokemons.json", $new_array);
                    $userStorage->update($user['id'], $usercp);
                    return true;
                }
            }
            return false;
        }
        else{
            $json_pokemons = file_get_contents("pokemons.json");
            $pokemons = json_decode($json_pokemons, true);
            
            $title = str_replace('_', ' ', key($_GET));

            $usercp = $user;
            foreach ($pokemons as $k => $pokemon) {
                if ($pokemon['name'] == $title) {
                    array_push($usercp['cards'], $pokemon);
                    $userStorage->update($user['id'], $usercp);
                    return true;
                }
            }
            return false;
        }
    }

    function isAdmin(&$u){
        foreach ($u['roles'] as $role) {
            if ($role == "admin") {
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
            <?php if (buyCard($user, $userStorage)): ?>
                <?="Successful purchase!"?>
            <?php else: ?>
                <?="Not enough money or too many cards!"?>
            <?php endif;?>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>