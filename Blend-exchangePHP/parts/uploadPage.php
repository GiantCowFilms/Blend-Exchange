            <form id="uploadTarget" class="bodyStack">
                <div id="uploadText">
                    <div class="centerText">
                        Drag a file here to upload a .blend<br>or click to browse
                    </div>
                </div>
                    <div id="uploadArea">

                    </div>
            </form>
            <div id="uploadOptions">
               <input class="txtBlue bodyStack" <?php if(isset($embedUpload) && ($embedUpload == true)){echo 'style="display: none;"';}?> id="questionUrl" placeholder="Enter the url of the queston on blender.stackexchange" value="<?php if(isset($questionLink)) { echo $questionLink; }?>"/>
                  <div>
                     <input class="txtBlue bodyStack" id="password" placeholder="Enter a password to enable editing or deleting (optional)"/>
                     <div id="upload" class="btnBlue bodyStack">
                        Upload
                     </div>
                  </div>
            </div>
               
