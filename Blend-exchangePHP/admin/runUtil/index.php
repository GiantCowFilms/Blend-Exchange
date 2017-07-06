    <?php 
    $requireAdmin = true;
    include("../../parts/requireLogin.php"); 
    $utilName = $_GET["utilName"];
    ?>
    <?php include("../../parts/header.php"); ?>
    <?php include("../../parts/admin/utils/".$utilName.".php"); ?>
        <?php include("../../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>