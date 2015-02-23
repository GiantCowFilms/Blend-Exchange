        <div id="uploadContainer">
            <div id="uploadTarget">
                        <h2>
                            <?php echo $blendData["fileName"] ?>
                        </h2>
                        <span>
                             - <?php echo round(intval($blendData["fileSize"])/1000000, 1, PHP_ROUND_HALF_UP); ?> MB | <?php echo $blendData["questionLink"] ?>
                        </span>
            </div>
            <div id="uploadOptions">
                <div id="cancel" class="btnBlue" style="width: 289px; display: inline-block;">
                    Flag
                </div><div id="upload" class="btnBlue" style="width: 289px; margin-left: 10px; display: inline-block;">
                    <a href="/d/<?php echo $blendData["id"] ?>/<?php echo $blendData["fileName"] ?>">Download</a>
                </div>
            </div>
            <div>Embed (Copy into your post):</div>
            <textarea id="embedCode" class="txtBlue">[<img src="http://blend-exchange.giantcowfilms.com/embedImage.png?bid=<?php echo $blendData["id"] ?>" />](http://blend-exchange.giantcowfilms.com/b/<?php echo $blendId; ?>)</textarea>
            <div id="usageNotice">
                <h2>
                    Disclaimer:
                </h2>
                Download this file at your own risk. It could contain viruses or other harmful material.
                <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
            </div>
        </div>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        </script>
    </body>
</html>