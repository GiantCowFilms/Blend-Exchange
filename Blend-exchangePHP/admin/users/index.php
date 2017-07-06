    <?php 
    $requireAdmin = true;
    include("../../parts/requireLogin.php");
    include("../../parts/header.php"); 
    include("../../parts/database.php");
    $users = $db->prepare("SELECT `users`.`id`,`users`.`username`,`users`.`email`, SUM(`blends`.`owner`=`users`.`id`) AS numBlends FROM `users` 
    LEFT JOIN `blends` ON `blends`.`owner` = `users`.`id`
    GROUP BY users.id");
    
    $users->execute();
    $users = $users->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div id="mainContainer">
            <a href="/admin/"><-Back</a>
            <h2>Users:</h2>
            <table>
                <thead>
                    <tr>
                        <th>User name</th><th>Email</th><th>Blends</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($users as $user)
                    {
                        echo "<tr><td id='".$user["id"]."'><a href='/users/".$user["id"]."/'>".$user["username"]."</a></td><td><a href='mailto:".$user["email"]."'>".$user["email"]."</a></td><td>".$user["numBlends"]."</td></tr>";
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