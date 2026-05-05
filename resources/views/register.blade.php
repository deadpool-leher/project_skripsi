<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family:'Poppins', sans-serif;
    background:linear-gradient(135deg,#5a5cff,#7b2cff);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD */
.card {
    background:white;
    padding:40px 30px;
    border-radius:20px;
    width:360px;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
    text-align:center;
}

/* ICON */
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

/* INPUT */
input {
    width:100%;
    padding:9px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    font-size:14px;
}

input:focus {
    border:1px solid #6c5cff;
}

/* BUTTON */
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

/* LOGIN LINK */
.login {
    margin-top:15px;
    font-size:14px;
    color:#666;
}

.login a {
    color:#5a5cff;
    text-decoration:none;
    font-weight:600;
}

.login a:hover {
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="card">

    <div class="icon">👤</div>

    <h2>Create Account</h2>
    <p>Sign up to get started</p>

<form action="{{ route('register.process') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Full Name">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <input type="password" name="password_confirmation" placeholder="Confirm Password">

    <button type="submit">Sign Up</button>
</form>

    <div class="login">
        Already have an account? <a href="/login">Login</a>
    </div>

</div>

</body>
</html>