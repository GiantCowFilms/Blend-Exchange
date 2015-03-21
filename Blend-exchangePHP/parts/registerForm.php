<h1>Register</h1>
<form id="registerForm" style="width: 400px;">
    <div id="registerFormError" class="nwDanger noticeWarning" style="display: none; margin-bottom: 10px;">
        Register failed
    </div>
    <input id="username" class="txtBlue bodyStack" placeholder="Username"/>
    <input id="email" class="txtBlue bodyStack" placeholder="Email"/>
    <input type="password" id="password" class="txtBlue bodyStack" placeholder="Password" />
    <input type="password" id="confirmPassword" class="txtBlue bodyStack" placeholder="Confirm Password" /><div class="btnBlue" id="register" style="width: 100%; max-width: none;">
        Register
    </div>
</form>
<script src="/jquery.js"></script>
<script src="/sha256.js"></script>
<script src="/register.js"></script>