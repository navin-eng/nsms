<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP CODE</title>
    <style>
        .footer
        {
            width: 100%;
            height: 50px;
            background-color: gainsboro;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <div style="width: 100%; display:flex; justify-content:center;align-items:center;">
        <img src="{{ $message->embed(\App\Models\SiteSetting::current()->site_logo ? storage_path('app/public/' . \App\Models\SiteSetting::current()->site_logo) : public_path('backend/images/logo.png')) }}" width="50%" alt="">
    </div>
    <hr>
    <br>
    <p>OTP CODE.</p>
    <p>Otherwise, Your Account will not be created.</p>
    <h2 class="h3" style="color: green; text-align:center;">{{ $body }}</h2>
    <footer class="footer">
        <p>2022 SHOP</p>
        <p>Owner Diwash Magar</p>
    </footer>
</body>
</html>
