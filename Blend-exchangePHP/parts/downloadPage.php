        <?php
            //Include login check
            include($_SERVER["DOCUMENT_ROOT"]."/parts/checkLogin.php");
            //Process flags
            $virusAlert = false;
            $copyrightAlert = false;
            if(count($blendData["flags"]) != 0) {
                foreach ($blendData["flags"] as $flag)
                {
                    if ($flag["val"] == "virus"){
                        $virusAlert = true;
                    };
                     if ($flag["val"] == "copyright"){
                        $copyrightAlert = true;
                    };
                }
            }
        ?>
        <div id="mainContainer">
            <?php
            if ($blendData["adminComment"] != ""){
                echo "<div class=\"noticeWarning nwInfo bodyStack\">
                ".$blendData["adminComment"]."
                </div>";
            }
            if ($blendData["deleted"] == 1) {
                echo "            <div class=\"noticeWarning nwDanger bodyStack\">
                    This file was deleted.
                </div>";
            if ($admin != true){
                exit();
            };
            }
            if ($copyrightAlert){
                echo "            <div class=\"noticeWarning nwNotice bodyStack\">
                NOTICE: This file has been removed on a copyright claim!
                </div>";
                if ($admin != true){
                    exit();
                };
            };
            if ($virusAlert){
                echo "            <div class=\"noticeWarning nwDanger bodyStack\">
                WARNING: This blend has been reported as containing maleware. Download at your own risk. The report is unconfirmed.
                </div>";
            };
            ?>
            <div id="fileStats" class="bodyStack contentTarget">
                <div style="text-align: center;">
                        <img class="blendDisplayIcon" src="/blenderFileIcon.png"/>
                        <div style="display: inline-block; margin-top: 25px; text-align: left;">
                            <h2 class="blendDisplayTitle">
                                <?php echo $blendData["fileName"] ?>
                            </h2>
                            <span class="downloadQuestionLink">
                                 <a href="<?php echo $blendData["questionLink"] ?>">Question Url</a>
                                <br />
                                <?php echo round(intval($blendData["fileSize"])/1000000, 1, PHP_ROUND_HALF_UP); ?> MB
                                <br />
                                <?php echo $blendData["views"] ?> views <br />
                                <?php echo $blendData["downloads"] ?> downloads<br />
                                <?php echo $blendData["favorites"] ?> favorites
                            </span>
                        </div>
                </div>
            </div>
            <div class="bodyStack">
                <div id="flagBtn" class="btnBlue downloadBtnRow">
                    Flag
                </div><div id="favoriteBtn" class="btnBlue downloadBtnRow">
                    Favorite
                </div><div id="downloadFile" class="btnBlue downloadBtnRow" style="margin-right: 0">
                    <a href="/d/<?php echo $blendData["id"] ?>/<?php echo $blendData["fileName"] ?>">Download</a>
                </div>
            </div>
            <?php include("flagForm.php"); ?>
            <?php
                if ($admin == true){
                    include("adminTools.php");
                };
            ?>
            <h2 style="margin-top: 5px; margin-bottom: 5px;">Share this file:</h2>
            <div>Add this text into your post:</div>
            <textarea id="embedCode" class="txtBlue">[<img src="http://blend-exchange.giantcowfilms.com/embedImage.png?bid=<?php echo $blendData["id"]; ?>" />](http://blend-exchange.giantcowfilms.com/b/<?php echo $blendData["id"]; ?>/)</textarea>
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
            <?php             
            if ($virusAlert){
                echo '            $(document).on("click", "#downloadFile a", function (e) {
                if (confirm("I understand that I do this at my own risk, and Blend-Exchange is not liable for any damage this file may cause?") != true) {
                    e.preventDefault();
                }
            });';
            };
            ?>
            //Only on finish page
            if (window.location.pathname == "/") {
                var embed = $("#embedCode")
                embed.focus()
                embed.select()
                $("#embedCode").addClass('attention');
            }
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
            //Events for embed

            //Alert for iframe
            window.parent.postMessage({ name: "embedSource", content: $("#embedCode").val() }, "*");
            //Alert for popup
            if (window.opener != null && !window.opener.closed) {
                window.opener.postMessage({ name: "embedSource", content: $("#embedCode").val() }, "*");
            }
        </script>
        <script>
            $(document).on("click", "#deleteFile", function () {
                $.ajax({
                    url: "/admin/adminTools/",
                    type: "POST",
                    data: { fileId: "<?php echo $blendData["id"] ?>", act: "delete"},
                    success: function (r) {
                        alert([r]);
                    }
                });
            });
            $(document).on("click", "#adminComment", function () {
                $("#adminCommentForm").show();
            });
            $(document).on("click", "#adminCommentPost", function () {
                comment = $("#adminCommentText").val();
                $.ajax({
                    url: "/admin/adminTools/",
                    type: "POST",
                    data: { fileId: "<?php echo $blendData["id"] ?>", act: "Comment", text: comment },
                    success: function () {

                    }
                });
            });
            $(document).on("click", "#setValid", function () {
                var valid = 2;
                if (confirm('Is this valid?')) {
                    valid = 1;
                }
                $.ajax({
                    url: "/admin/adminTools/",
                    type: "POST",
                    data: { fileId: "<?php echo $blendData["id"] ?>", act: "setValid", type: valid },
                    success: function (r) {
                        alert([r]);
                    }
                });
            });
            $(document).on("click", "#adminDeclineFlag", function () {
                actOnFlag("decline");
            });
            $(document).on("click", "#adminAcceptFlag", function () {
                actOnFlag("accept");
            });
            function actOnFlag(action) {
                $("#adminFlagForm").show();

                var flagId = $("#adminFlagSelect option:selected").val();

                $(document).on("click", "#adminFlagContinue", function () {
                    flagId = $("#adminFlagSelect option:selected").val();
                    $.ajax({
                        url: "/admin/adminTools/",
                        type: "POST",
                        data: { fileId: "<?php echo $blendData["id"] ?>", act: "actOnFlag", flagId: flagId, type: action },
                        success: function (r) {
                            alert([r]);
                        }
                    });
                });
            }
        </script>
    </body>
</html>
