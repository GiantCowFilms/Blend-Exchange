<template>
            <div id="header">
            <div id="logo">
               <div class="logoText">
                  <a href="/">
                  <span class="logoOrange">Blend</span><span class="logoBlue">-Exchange</span>
                  </a>
                  <div class="logoStatus">2.0&#945;</div>
               </div>
               <div class="tagLine">
                  <a class="logoCredit" href="http://blender.stackexchange.com/">A long term .blend storage solution for blender.stackexchange</a>
               </div>
               <img src="/images/logo.png" />
            </div>
            <div id="nav">
                  <ul id="navLinks">
                     <li><router-link to="/">Upload</router-link></li>
                     <li><router-link  to="/help">Help</router-link ></li>
                     <li><a href="/about">About</a></li>
                     <li><router-link to="/userscript">Userscript</router-link></li>
                     <li><router-link to="/contribute">Contribute</router-link></li>
                  </ul>
            </div>
         </div>
         <!-- Removed for now (Sentate vote passed). Will re-publish when the House of Reps vote comes up: <div class="bodyStack noticeWarning nwInfo">Notice: US based blend-exchange users. Congress is about to vote on whether to repeal Net Neutrality. Without Net Neutrality, free independantly run websites no longer enjoy protection from extortion from ISPs. Do you really want to trust <i>Comcast</i> and <i>Verizon</i> to maintain a free internet? <a href="https://www.battleforthenet.com/">Learn more.</a> </div>-->
</template>
<script>
export default {
    
}
</script>
