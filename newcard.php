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

    function isAdmin(&$u){
        foreach ($u['roles'] as $role) {
            if ($role == "admin") {
                return true;
            }
        }
        return false;
    }

    $data = [];
    $errors = [];

    if(validate($errors, $data)){
        $json_data = file_get_contents("pokemons.json");

        $pokemons = json_decode($json_data, true);

        $new_data = [
            'name' => $data['name'],
            'type' => $data['type'],
            'hp' => $data['hp'],
            'attack' => $data['attack'],
            'defense' => $data['defense'],
            'price' => $data['price'],
            'description' => $data['description'],
            'image' => $data['image']
        ];

        array_push($pokemons, $new_data);
        $new_array = json_encode($pokemons, JSON_PRETTY_PRINT);
        file_put_contents("pokemons.json", $new_array);
    }

    function validate(&$errors, &$data){
        if(isset($_POST['name']) && trim($_POST['name']) !== ""){
            $data['name'] = $_POST['name']; 
        }
        else {
            $errors['name'] = "Should not be empty!";
        }

        if(isset($_POST['type']) && trim($_POST['type']) !== "" && validType(trim($_POST['type']))){
            $data['type'] = $_POST['type'];
        }
        else{
            $errors['type'] = "Not a valid type!";
        }

        if(isset($_POST['hp']) && $_POST['hp'] >= 1){
            $data['hp'] = $_POST['hp'];   
        }
        else{
            $errors['hp'] = "Must be greater than 0!";
        }

        if(isset($_POST['attack']) && $_POST['attack'] >= 1){
            $data['attack'] = $_POST['attack'];   
        }
        else{
            $errors['attack'] = "Must be greater than 0!";
        }

        if(isset($_POST['defense']) && $_POST['defense'] >= 1){
            $data['defense'] = $_POST['defense'];   
        }
        else{
            $errors['defense'] = "Must be greater than 0!";
        }

        if(isset($_POST['price']) && $_POST['price'] >= 1){
            $data['price'] = $_POST['price'];   
        }
        else{
            $errors['price'] = "Must be greater than 0!";
        }

        if(isset($_POST['description']) && trim($_POST['description']) !== ""){
            $data['description'] = $_POST['description']; 
        }
        else {
            $errors['description'] = "Should not be empty!";
        }

        if(isset($_POST['image']) && trim($_POST['image']) !== "" && filter_var($_POST['image'], FILTER_VALIDATE_URL)){
            $data['image'] = $_POST['image']; 
        }
        else {
            $errors['image'] = "Not a valid url!";
        }

        return count($errors) === 0;
    }

    function validType($typeStr){
        $types = ["normal", "fire", "water", "electric", "grass", "ice",
            "fighting", "poison", "ground", "psychic", "bug", "rock", "ghost",
            "dark", "steel"];
        $valid = false;
        foreach ($types as $type){
            if($type == $typeStr){
                $valid = true;
            }
        }
        return $valid;
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
        <?php if(isAdmin($user)): ?>
        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= $data["name"] ?? "" ?>">
            <small><?= $errors["name"] ?? ""  ?></small>
            <br>
            <label for="type">Type:</label>
            <input type="text" name="type" value="<?= $data["type"] ?? "" ?>">
            <small><?= $errors["type"] ?? ""  ?></small>
            <br>
            <label for="hp">HP:</label>
            <input type="number" name="hp" value="<?= $data["hp"] ?? "" ?>">
            <small><?= $errors["hp"] ?? ""  ?></small>
            <br>
            <label for="attack">Attack:</label>
            <input type="number" name="attack" value="<?= $data["attack"] ?? "" ?>">
            <small><?= $errors["attack"] ?? ""  ?></small>
            <br>
            <label for="defense">Defense:</label>
            <input type="number" name="defense" value="<?= $data["defense"] ?? "" ?>">
            <small><?= $errors["defense"] ?? ""  ?></small>
            <br>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?= $data["price"] ?? "" ?>">
            <small><?= $errors["price"] ?? ""  ?></small>
            <br>
            <label for="description">Description:</label>
            <input type="text" name="description" value="<?= $data["description"] ?? "" ?>">
            <small><?= $errors["description"] ?? ""  ?></small>
            <br>
            <label for="image">Image:</label>
            <input type="text" name="image" value="<?= $data["image"] ?? "" ?>">
            <small><?= $errors["image"] ?? ""  ?></small>
            <br>
            <input type="submit" value="Submit">
        </form>
        <?php else: ?>
            <?php header("Location: index.php");?>
        <?php endif;?>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>