        <?php
            //Process flags
            $virusAlert = false;
            $copyrightAlert = false;
            if(strlen($blendData["flags"]) != 0) {
                $blendData["flags"] = split(";",$blendData["flags"]);
                foreach ($blendData["flags"] as $flag)
                {
                    if ($flag == "virus"){
                        $virusAlert = true;  
                    };
                    if ($flag == "copyright"){
                        $copyrightAlert = true;  
                    };
                }
            } 
        ?>
        <div id="uploadContainer">
            <?php 
            if ($copyrightAlert){
                echo "            <div class=\"noticeWarning nwNotice\">
                NOTICE: This file has been removed on a copyright claim!
                </div>";
                exit;
            };
            if ($virusAlert){
                echo "            <div class=\"noticeWarning nwDanger\">
                WARNING: This blend has been reported as containing maleware. Download at your own risk. The report is unconfirmed.
                </div>";
            };
            ?>
            <div id="uploadTarget" class="bodyStack">
                        <img class="blendDisplayIcon" src="/blenderFileIcon.png"/>
                        <div style="width: 420px; display: inline-block; margin-top: 25px;">
                            <h2 class="blendDisplayTitle">
                                <?php echo $blendData["fileName"] ?>
                            </h2>
                            <span style="font-size: 18px;">
                                 <a href="<?php echo $blendData["questionLink"] ?>"><?php echo substr($blendData["questionLink"], 7, 45); ?>...</a>
                                <br />
                                <?php echo round(intval($blendData["fileSize"])/1000000, 1, PHP_ROUND_HALF_UP); ?> MB
                                <br />
                                <?php echo $blendData["views"] ?> views <br />
                                <?php echo $blendData["downloads"] ?> downloads<br />
                                <?php echo $blendData["favorites"] ?> favorites
                            </span>
                        </div>
            </div>
            <div class="bodyStack">
                <div id="flagBtn" class="btnBlue" style="width: 187px;">
                    Flag
                </div><div id="favoriteBtn" class="btnBlue" style="width: 187px; margin-left: 10px;">
                    Favorite
                </div><div id="downloadFile" class="btnBlue" style="width: 187px; margin-left: 10px;">
                    <a href="/d/<?php echo $blendData["id"] ?>/<?php echo $blendData["fileName"] ?>">Download</a>
                </div>
            </div>
            <?php include("flagForm.php"); ?>
            <div>Embed (Copy into your post):</div>
            <textarea id="embedCode" class="txtBlue">[<img src="http://blend-exchange.giantcowfilms.com/embedImage.png?bid=<?php echo $blendData["id"] ?>" />](http://blend-exchange.giantcowfilms.com/b/<?php echo $blendData["id"]; ?>/)</textarea>
            <div id="usageNotice">
                <h2>
                    Disclaimer:
                </h2>
                Download this file at your own risk. It could contain viruses or other harmful material.
                <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
            </div>
           <h2>
                Flags:
           </h2>
        </div>
        <script src="/jquery.js"></script>
        <script src="/dropzone.js"></script>
        <script>
            $("#flagBtn").click(function () {
                $("#flagFile").show();
            });
            $("#flagCancel").click(function () {
                $("#flagFile").hide();
            });
            $("#flagFileBtn").click(function () {
                var value = $("input:radio[name=offense]:checked").val();
                $.ajax({
                    url: "/flag",
                    type: "get",
                    success: function (result) {
                        $("#flagFile").hide();
                        alert([result]);
                    },
                    data: { id: "<?php echo $blendData["id"] ?>", flag: value }
                });
            });
            $("#favoriteBtn").click(function () {
                $.ajax({
                    url: "/favorite",
                    type: "get",
                    success: function (result) {
                        alert([result]);
                    },
                    data: { id: "<?php echo $blendData["id"] ?>"}
                });
            });
        </script>
    </body>
</html>