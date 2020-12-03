<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Mail</title>
</head>
<body>
    <h2>Halo, </h2>
    <p>{{$detail['body']}}, selamat datang di CoReal!</p>
    <p>Harap klik link dibawah ini untuk mengaktivasi email.</p>
    <a href="{{url('api/verify', $detail['id'])}}">Link Verifikasi</a>
</body>
</html>