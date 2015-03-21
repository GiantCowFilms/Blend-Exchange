<html>
    <?php 
    $requireAdmin = false;
    include("../parts/requireLogin.php"); 
    if($_GET["uid"] != $userId){
        include("../parts/header.php");
        echo "<div class='noticeWarning nwDanger'>This is not your account page</div>";
        exit();  
    };
    ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/userAccount.php"); ?>
        <?php include("../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>