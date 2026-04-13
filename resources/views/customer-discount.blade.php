<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Discount</title>

<style>
body {
    margin:0;
    font-family: Arial;
    display:flex;
    background:#f5f7fb;
}

/* SIDEBAR */
.sidebar {
    width:220px;
    background:white;
    padding:20px;
    height:100vh;
    border-right:1px solid #eee;

    display: flex;              
    flex-direction: column;
    justify-content: space-between;
}

.logo {
    font-weight:600;
    margin-bottom:20px;
}

.sidebar h2 {
    font-size:18px;
    margin-bottom:30px;
}

.menu a {
    display:block;
    padding:10px;
    margin:5px 0;
    text-decoration:none;
    color:#333;
    border-radius:8px;
}

.menu a.active {
    border:2px solid #6366f1;
}

.menu a:hover {
    background:#f3f4f6;
}

/* PROFILE */
.profile {
    font-size:14px;
}

.content {
    display: flex;
    justify-content: space-between; /* 🔥 dorong kiri & kanan */
    align-items: flex-start;
    padding-right: 80px; /* 🔥 kasih napas kanan */
}

/* MAIN */
.main {
    flex:1;
    padding:30px;
}

.title {
    font-size:22px;
    font-weight:bold;
}

.cards {
    display:flex;
    gap:20px;
    margin:20px 0;
}

.card {
    flex:1;
    padding:20px;
    border-radius:15px;
    color:white;
    font-weight:bold;
}

.blue { background:linear-gradient(90deg,#3b82f6,#6366f1); }
.green { background:linear-gradient(90deg,#22c55e,#16a34a); }
.purple { background:linear-gradient(90deg,#a855f7,#7c3aed); }

.discount-grid {
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:20px;
}

.discount-card {
    background:white;
    border-radius:15px;
    padding:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.badge {
    display:inline-block;
    background:#e0f2fe;
    color:#0284c7;
    padding:5px 10px;
    border-radius:10px;
    font-size:12px;
}

.percent {
    float:right;
    font-weight:bold;
}

.code-box {
    background:#f3f4f6;
    padding:10px;
    border-radius:8px;
    margin:10px 0;
}

.progress {
    height:6px;
    background:#eee;
    border-radius:10px;
    overflow:hidden;
    margin:10px 0;
}

.progress-bar {
    height:100%;
    width:60%;
    background:#6366f1;
}

.btn {
    width:100%;
    padding:10px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg,#6366f1,#7c3aed);
    color:white;
    cursor:pointer;
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
     <div>
        <h2>🍦 Es Cream Treman</h2>

        <div class="menu">
            <a href="/customer">Order</a>
            <a href="/discount" class="active">Discount</a>
        </div>
    </div>

    <div class="profile">
        <strong>{{ session('user') }}</strong><br>
        <small>{{ session('email') }}</small><br><br>
        <a href="{{ route('login') }}" style="color:red;">Logout</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="title">Discounts & Promotions</div>

    <div class="cards">
        <div class="card blue">4<br>Active Promotions</div>
        <div class="card green">294<br>Total Redemptions</div>
        <div class="card purple">4<br>Total Discounts</div>
    </div>

    <div class="discount-grid">

        <div class="discount-card">
            <span class="badge">ACTIVE</span>
            <span class="percent">30%</span>

            <h3>Weekend Special</h3>
            <p>Get 30% off on weekends</p>

            <div class="code-box">WEEKEND30</div>

            <small>Valid until: Feb 27</small>

            <div class="progress">
                <div class="progress-bar"></div>
            </div>

            <button class="btn claim-btn" onclick="claimDiscount(this)">
            Claim Discount</button>
        </div>

        <div class="discount-card">
            <span class="badge">ACTIVE</span>
            <span class="percent">20%</span>

            <h3>New Customer</h3>
            <p>For first-time users</p>

            <div class="code-box">NEW20</div>

            <small>Valid until: Mar 30</small>

            <div class="progress">
                <div class="progress-bar" style="width:40%"></div>
            </div>

            <button class="btn claim-btn" onclick="claimDiscount(this)">
            Claim Discount</button>
        </div>

    </div>

</div>

<script>
function claimDiscount(button) {
    button.innerHTML = "✔ Claimed";
    button.style.background = "#bbf7d0";
    button.style.color = "#166534";
    button.style.cursor = "default";
    button.disabled = true;
}
</script>
</body>
</html>