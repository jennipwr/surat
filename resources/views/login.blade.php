<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href=" {{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="container">
    <h1>Log In</h1>
    <div class="user-options">
        <div class="user-card">
            <a href="{{ route ('login.mahasiswa') }}">
                <img src=" {{ asset ('assets/images/login/user.png') }}" alt="Mahasiswa">
                <p>Mahasiswa</p>
            </a>
        </div>
        <div class="user-card">
            <a href ="{{ route ('login.kaprodi') }}">
                <img src="{{ asset ('assets/images/login/user.png') }}" alt="Kaprodi">
                <p>Kaprodi</p>
            </a>
        </div>
        <div class="user-card">
            <a href=" {{ route ('login.tu') }}">
                <img src="{{ asset ('assets/images/login/user.png') }}" alt="TU">
                <p>TU</p>
            </a>
        </div>
        <div class="user-card">
            <a href=" {{ route ('login.admin') }}">
                <img src="{{ asset ('assets/images/login/user.png') }}" alt="Admin">
                <p>Admin</p>
            </a>
        </div>
    </div>
</div>
</body>
</html>
