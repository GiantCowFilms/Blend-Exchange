<?php include("../parts/head.php"); 
       include("../parts/checkLogin.php");
      ?>
<body style="width: 600px;">
<?php 

$questionLink = $_GET["qurl"];
?><div style="margin-top: 20px;"></div><?php 
$embedUpload = true;
include("../parts/uploadPage.php"); 
?>
<span>By using this service you agree to our <a href="/terms">terms of service</a></span>
<script src="/jquery.js"></script>
<script src="/dropzone.js"></script>
<script src="/upload.js"></script>
</body>
</html>
