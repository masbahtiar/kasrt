<!DOCTYPE html>
<html>

<head>
    <title>Welcome Email</title>
</head>

<body>
    <h2>Selamat datang {{$user['name']}} di Website Kami</h2>
    <br />
    ID email Anda yang terdaftar adalah {{$user['email']}} , Silakan klik tautan di bawah ini untuk memverifikasi akun email Anda
    <br />
    <a href="{{url('user/verify', $user->verifyUser->token)}}">Verify Email</a>
</body>

</html>
