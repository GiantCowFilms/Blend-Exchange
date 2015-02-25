            <form id="uploadTarget" class="bodyStack">
                <div id="uploadText">
                    <div class="centerText">
                        Drag a file here to upload a .blend<br /> or<br /> click to browse
                    </div>
                </div>
                    <div id="uploadArea">

                    </div>
            </form>
            <div id="uploadOptions">
                <input class="txtBlue bodyStack" id="questionUrl" placeholder="Enter the url of the queston on blender.stackexchange" value="<?php if(isset($questionLink)) { echo $questionLink; }?>"/>
                
                <div>
                    <input class="txtBlue bodyStack" id="password" placeholder="Enter a password to enable editing or deleting (optional)"/>
                </div>
                <div id="cancel" class="btnBlue bodyStack" style="width: 289px; display: inline-block;">
                    Cancel
                </div><div id="upload" class="btnBlue bodyStack" style="width: 289px; margin-left: 10px; display: inline-block;">
                    Upload
                </div>
            </div>