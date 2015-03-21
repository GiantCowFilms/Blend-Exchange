<html>
    <?php 
    $requireAdmin = true;
    include("../parts/requireLogin.php"); ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/admin/getFlaggedFiles.php"); ?>
        <div id="mainContainer">
            <a href="/admin/all/">All files</a>
            <h2>Flagged Files:</h2>
            <ul>
                <?php
                foreach ($files as $file)
                    {
                        echo "<li><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a> - <a href='".$file["questionLink"]."'>Question</a><b> - Flagged: ".$file["val"]."</b> on ".$file["date"]."</li>";
                    }
                    
                ?>
            </ul>
            <h2>Auto Flagged Files:</h2>
            <ul>
                <?php
                foreach ($autoFlags as $autoFlag)
                    {
                        echo "<li><a href='/b/".$autoFlag["id"]."/'>".$autoFlag["fileName"]."</a> - <a href='".$autoFlag["questionLink"]."'>Question</a><b> - Flagged: ".$autoFlag["val"]."</b> valid: ".$autoFlag["validRefs"]." invalid: ".$autoFlag["invalidRefs"]."</li>";
                    }
                    
                ?>
            </ul>
        </div>
        <?php include("../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>