<div id="adminTools">
    <div>
        <div class="btnBlue" style="width: 136px; margin-right: 10px;" id="adminComment">
            Comment</div><div class="btnBlue" style="width: 136px; margin-right: 10px;" id="adminDeclineFlag">
          Decline Flag</div><div class="btnBlue" style="width: 136px; margin-right: 10px;" id="adminAcceptFlag">
              Accept Flag</div><div class="btnBlue" style="width: 136px; margin-right: 10px;" id="setValid">
              Set Valid</div><div class="btnBlue" style="width: 136px;" id="deleteFile">Delete</div>
    </div>
    <form id="adminCommentForm" style="display: none;">
        <textarea class="txtBlue" id="adminCommentText">Admin: </textarea>
        <div class="btnBlue" id="adminCommentPost">Post</div>
    </form>
    <form id="adminFlagForm" style="display: none;">
        Please select flag
        <select id="adminFlagSelect">
            <?php 
            foreach ($blendData["flags"] as $flag)
            {
                var_dump($flag["accept"]);
                if ($flag["accept"] == 0){
                    echo "<option val=\"".$flag["id"]."\">".$flag["val"]." (id: ".$flag["id"].")</option>";   
                }
            }
            ?>
        </select>
        <div class="btnBlue" id="adminFlagContinue">Continue</div>
    </form>
</div>