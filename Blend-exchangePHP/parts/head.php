    <?php if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include_once($_SERVER["DOCUMENT_ROOT"]."/parts/logger.php");
    
    $ref = "";
    if (isset($_GET["ref"])) {
        $ref = " PAGE_REF_CODE:".$_GET["ref"];
    }
    
    
    logger("PAGE_HIT LOC:".$_SERVER['REQUEST_URI'].$ref,$_SERVER["DOCUMENT_ROOT"]."/logs/","hits.log");
    
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Blend-Exchange</title>
        <link rel="stylesheet" media="screen" href="/layout.css" />
        <link rel="stylesheet" media="screen" href="/lighttheme.css" />
        <!-- pick stylesheet here with php and cookie magic -->
        
        <link rel="shortcut icon" href="/favicon.ico">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>

        <meta name="description" content="Blend-Exchange is a website dedicated to hosting blends, free of charge, for Blender.StackExchange users. Blend upload and download is completely free, no adds, no login or registration required. Share your blends with ease!">
    </head>
