<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family:'Poppins', sans-serif;
    background:#eef1f7;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card {
    background:white;
    padding:40px 30px;
    border-radius:20px;
    width:340px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    text-align:center;
}

.icon {
    width:60px;
    height:60px;
    background:linear-gradient(135deg,#5a5cff,#7b2cff);
    border-radius:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:26px;
    margin:0 auto 20px;
}

h2 {
    margin:10px 0 5px;
}

p {
    color:#777;
    font-size:14px;
    margin-bottom:20px;
}

input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    font-size:14px;
}

input:focus {
    border:1px solid #6c5cff;
}

button {
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg,#5a5cff,#7b2cff);
    color:white;
    font-weight:600;
    font-size:15px;
    margin-top:10px;
    cursor:pointer;
    transition:0.3s;
}

button:hover {
    opacity:0.9;
}

/* SIGN UP */
.signup {
    margin-top:15px;
    font-size:14px;
    color:#666;
}

.signup a {
    color:#5a5cff;
    text-decoration:none;
    font-weight:600;
}

.signup a:hover {
    text-decoration:underline;
}

.icon img {
    width: 35px;
    height: 35px;
    object-fit: contain;
}
</style>
</head>

<body>
<div class="card">

    <div class="icon">
        <img src="{{ asset('gambar/keranjang_login.jpg') }}" alt="login">
    </div>

    <h2>Welcome</h2>
    <p>Login to your account on Es Cream Treman site</p>

    <input type="email" placeholder="Email">
    <input type="password" placeholder="Password">

    <button>Login</button>

    <div class="signup">
        <a href="{{ route('register') }}">Sign up</a>
    </div>

</div>
</body>
</html>