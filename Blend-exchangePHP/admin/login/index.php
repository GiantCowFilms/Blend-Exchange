 <?php
    session_start();
 
    $username = $_GET["username"];
    $password = $_GET["password"];

    
    $secretKeys = json_decode(file_get_contents("../../secret/secret.json"));
    
    include("../../parts/database.php");
    
    $userData = $db->prepare("SELECT `id` FROM `admins` WHERE `username`=:username AND `password`=:password");
    $userData->execute(array('username' => $username,"password" => $password));
    //Not the best way to check for results, doing the login check in a query might be bad, but it sure is fast!
    if($userData->rowCount() == 1){
        $userData = $userData->fetchAll(PDO::FETCH_ASSOC)["0"];
        $userId = $userData;
        
        //Set session
        $_SESSION["loggedIn"] = true;
        $_SESSION["userId"] = $userId;
        
        //Send status
        echo '{
            "status": 1,
            "message": "You are logged in"
        }';
    } else {
        //Send status
        echo '{
            "status": 0,
            "message": "Login failed"
        }';
    }
    
?>
