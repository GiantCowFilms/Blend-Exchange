<html>
    <?php 
    $requireAdmin = true;
    include("../../parts/requireLogin.php"); ?>
    <?php include("../../parts/header.php"); ?>
    <?php 
    include("../../parts/database.php");

    $files = $db->prepare("SELECT `blends`.`id`,`blends`.`fileName`,`blends`.`questionLink`, `blends`.`date`,`blends`.`owner`,`blends`.`fileSize`,`users`.`username` FROM `blends` 
    LEFT JOIN `users` ON `users`.`id` = `blends`.`owner`
    ORDER BY `date` DESC LIMIT 0,500");
    $files->execute();
    $files = $files->fetchAll(PDO::FETCH_ASSOC);
    
    include("../../parts/blendViewCount.php");

    
    foreach ($files as $key => $file)
    {
        $files[$key]["views"] = getViewCount($file["id"]);
    }

    
    ?>
        <div id="mainContainer">
            <a href="/admin/"><-Back</a>
            <h2>All files:</h2>
            <table>
                <thead>
                    <tr>
                        <th>File Name</th><th>Question</th><th>Size</th><th>Owner</th><th></th>Views<th>Upload Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    

                    foreach ($files as $file)
                    {
                        echo "<tr><td><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a></td><td><a href='".$file["questionLink"]."'>".substr($file["questionLink"], 32, 60)."</a></td><td>".round(intval($file["fileSize"])/1000000, 2, PHP_ROUND_HALF_UP)." MB</td><td><a href='/users/".$file["owner"]."/'>".$file["username"]."</a></td><td>".$file["views"]."</td><td>".$file["date"]."</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php include("../../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>