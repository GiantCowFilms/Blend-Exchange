<html>
    <?php
    
    //ALL OF THIS CURL GARBAGE HERE DOESN'T WORK. ALL THE LIBRARIES REQUIRE USING COMPOSER *SIGH*... I can't fix this
    
    //$blob = file_get_contents($_FILES['file']['tmp_name']);
    
    $blob = "Hello World";
    
    $curl = curl_init("https://api.github.com/?access_token=[INTSERT HERE]");
    
    //$curl = curl_init("https://api.github.com/repos/GiantCowFilms/blend-exhcange/contents/");
    //SJIS - encoding... also could be called Shift_JIS
    $data_string = '{
        "content": "' + $blob +'",
        "encoding": "utf-8"
    }';
    curl_setopt($curl, CURLOPT_URL, "https://api.github.com/repos/GiantCowFilms/blend-exhcange/contents/");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");                                                                     
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );                                                                                                                   
    
    $result = curl_exec($curl);
    echo $result;
    curl_close($curl);
    ?>

    <?php include("../parts/header.php"); ?>
        <div id="uploadContainer">
            <div id="uploadTarget">
                <div id="uploadText">
                    <div class="centerText">
                        File stats
                    </div>
                </div>
            </div>
            <div id="uploadOptions">
                <div id="cancel" class="btnBlue" style="width: 289px; display: inline-block;">
                    Flag
                </div><div id="upload" class="btnBlue" style="width: 289px; margin-left: 10px; display: inline-block;">
                    Download
                </div>
            </div>
            <div id="usageNotice">
                <h2>
                    Disclaimer:
                </h2>
                Download this file at your own risk. It could contain viruses or other harmful material.
            </div>
        </div>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        </script>
    </body>
</html>
