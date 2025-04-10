<!DOCTYPE html>
<html>
<head>
<title>OTP Code</title>
<style>
body { font-family: sans-serif }
a { color: blue; text-decoration: underline; }
a:hover { cursor: pointer }
.box { padding: 2rem; border: 1px solid #dadce0; border-radius: 8px; }
.w-500px { width: 500px; }
.text-center { text-align: center; }
</style>
</head>
<body>
<div style="width: 100%;">
    <div style="display: flex; align-items: center; justify-content: center">
        <div class="w-500px">
            <h3 class="text-center" style="margin-bottom: .75rem; font-weight: 400"><b>Laravel</b> Foundation</h3>
            <div class="box">
                <p class="text-center" style="font-size: 1.2rem; font-weight: 500; margin-bottom: .75rem">OTP Verification</p>
                <p>Hi User,</p>
                <p>You are receiving this email so we can confirm this email address for your account. Use this generated code to verify your email address.</p>
                <h1 class="text-center">{{ $otp }}</h1>
                <p style="font-size: .8rem; color: #666; text-align: center">This code will expires in 5 minutes.</p>
                <p>If you did not request this email, please contact our administrator at <a>irzafarabi98@gmail.com</a>.</p>
            </div>
            <div class="text-center">
                <p style="font-size: .8rem; color: #666">This email was automatically generated by <b>Laravel Foundation</b> App. Replies are not monitored or answered.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
