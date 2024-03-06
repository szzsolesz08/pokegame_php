<?php
  $json_pokemons = file_get_contents("pokemons.json");
  $json_users = file_get_contents("users.json");

  $pokemons = json_decode($json_pokemons, true);
  $users = json_decode($json_users, true);

  $title = str_replace('_', ' ', key($_GET));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $title?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        
        <h1><a href="index.php">IKémon</a> > <?= $title?></h1>
    </header>
    <div id="content">
        <div id="details">
            <?php foreach ($pokemons as $pokemon): ?>
                <?php if ($title == $pokemon['name']) :?>
                    <div class="image clr-<?= $pokemon['type']?>"> 
                        <img src="<?= $pokemon['image']?>" alt="">
                    </div>
                    <div class="info">
                        <div class="description">
                            <?= $pokemon['description']?> 
                        </div>
                        <span class="card-type"><span class="icon">🏷</span> Type: <?= $pokemon['type']?></span>
                        <div class="attributes">
                            <div class="card-hp"><span class="icon">❤</span> Health: <?= $pokemon['hp']?></div>
                            <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $pokemon['attack']?></div>
                            <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $pokemon['defense']?></div>
                        </div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>