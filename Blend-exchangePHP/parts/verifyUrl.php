<?php
/*
 * Test set, must match all of the provided,but nothing more:
 * http://blender.stackexchange.com/q/21800/undo-cursor-move
 * http://blender.stackexchange.com/questions/21800/undo-cursor-move
 * http://blender.stackexchange.com/questions/21800/
 * http://blender.stackexchange.com/questions/21800/245
 * http://blender.stackexchange.com/q/21800/245
 * http://blender.stackexchange.com/questions/21800/undo-cursor-move/21802#21802
 * http://blender.stackexchange.com/questions/21800/undo-cursor-move/?abcd=efg
 * http://blender.stackexchange.com/questions/21800/undo-cursor-move/?abcd=efg&hijk=lmn
 */
//
// Set Regexes
//
    //Match url
    
    // Regex Explanation:
    // Part of expression               | Explanation: 
    // ---------------------------------|---------------------------------
    // ^                                | Start of string, means nothing can be infront of our URL
    // http                             | Check for the text http at the beggining
    // s?                               | Optional s for https protocal, the questionmark means the proceeding character is optional
    // :\/\/blender.stackexchange.com\/ | Check for ://blender.stackexchange.com/. The backslashes "\" are to escape the forwardslashes "/"
    // q(?:uestions)?                   | Check that the slash is followed by a q, then optionally uestions so it will match either q or questions
    // \/[0-9]+\/                       | Match a number any lenght for the ID
    // (?:                              | "If statement/conditional", open
    // [A-z\-#0-9\/_?=&]+               | Match any alphabet letter, a dash, a hash sign (used to link to comments), a slash; a question mark , "=" sign, and a "&" (url variables)
    // |                                | Else part of the if statement
    // [0-9]+                           | Can also match a number, technically not necessary, since the otherone does, but that may change since this identifies a different URL
    // )?                               | end if
    // $                                | End of string, stops invalid material form being tacked onto the end.
    
    //Match url
    $_Match_Url = "/^https?:\/\/blender.stackexchange.com\/q(?:uestions)?\/[0-9]+\/(?:[A-z\-#0-9\/_?=&]+|[0-9]+)?$/";
    //Detect all invalid characters in Title
    $_Invalid_Charicters = "/^(?<=https?:\/\/blender.stackexchange.com\/q(?:uestions)?\/[0-9]+\/[A-z\-#0-9\/_?=&]+)[^A-z\-#0-9\/_?=]/";
    //Part of hack for PHP flavored regex limitation
    $_Invalid_Charicters_Pre = "/^(https?:\/\/blender.stackexchange.com\/q(?:uestions)?\/[0-9]+\/)/";
    $_Invalid_Charicters_Post = "/[^A-z\-#0-9\/_?=]/";
    
    //Catch ID
    //$_Question_Id = "/^(?<=^https?:\/\/blender.stackexchange.com\/q(?:uestions|)\/)[0-9]+(?=\/(?:[A-z\-#0-9\/_?=]+|[0-9]+)?$)/";
    //Hacky way to avoid the lookback limiatation
    $_Question_Id = "/^https?:\/\/blender.stackexchange.com\/q(?:uestions)?\/([0-9]+)\/(?:[A-z\-#0-9\/_?=&]+|[0-9]+)?$/";
    
    function removeInvalid ($url)
    {
        //The particular lookback isn't supported, this is a workaround
        global $_Invalid_Charicters;
        global $_Invalid_Charicters_Pre;
        global $_Invalid_Charicters_Post;
        //Grab the bit
        $parts = preg_split($_Invalid_Charicters_Pre, $url, null , PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        //Take out the invalid characters in the question title, this stops errors from odd Unicode characters.1
        $url = $parts["0"].preg_replace($_Invalid_Charicters_Post,"",$parts["1"]);
    	return $url;
    };
    
    function verifyUrl ($url, $removeInvalid = false)
    {
        global $_Match_Url;
        if ($removeInvalid){
            global $_Invalid_Charicters_Pre;
            if(preg_match($_Invalid_Charicters_Pre, $url)){
                removeInvalid($url);
            }

        }
        $valid = (bool) preg_match($_Match_Url, $url);
    	return $valid;
    };
    
     function getId ($url)
     {
         global $_Question_Id;
         $qid = preg_replace($_Question_Id, "$1", $url);
        return $qid;
     }
     
     function cleanUrl($url)
     {
         $id = getId($url);
         $url = "http://blender.stackexchange.com/q/".$id."/";
         return $url;
     }
?>