<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ice Cream Treman</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    body {
    margin:0;
    font-family: Arial;
    background:#f4f5f9;
    text-align:center;
}

.container {
    padding:40px;
}

.logo {
    font-size:40px;
    margin-bottom:10px;
}

.hero-title {
    font-size:48px;
    font-weight:bold;
    background: linear-gradient(90deg,#ff00aa,#5a5cff);
    -webkit-background-clip:text;
    color:transparent;
}

.subtitle {
    color:#555;
}

.tagline {
    font-size:14px;
    color:#777;
}

.promo-badge {
    margin:20px auto;
    display:inline-block;
    padding:10px 20px;
    border-radius:20px;
    background:linear-gradient(90deg,#ff00aa,#5a5cff);
    color:white;
}

.promo-badge span {
    background:white;
    color:#5a5cff;
    padding:5px 10px;
    border-radius:10px;
    margin-left:10px;
}

.slider {
    position:relative;
    max-width:600px;
    margin:30px auto;
    overflow:hidden;
    border-radius:15px;
}

.slide {
    display:none;
    padding:60px;
    color:white;
    background:linear-gradient(90deg,#ff416c,#ff4b2b);
}

.slide.active {
    display:block;
}

.slide h2 {
    font-size:40px;
    margin:0;
}

.prev, .next {
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:white;
    border:none;
    padding:10px;
    cursor:pointer;
    border-radius:50%;
}

.prev { left:10px; }
.next { right:10px; }

.btn {
    display:inline-block;
    margin-top:20px;
    padding:12px 25px;
    border-radius:30px;
    background:linear-gradient(90deg,#ff00aa,#5a5cff);
    color:white;
    text-decoration:none;
}

.cards {
    display:flex;
    justify-content:center;
    gap:20px;
    margin-top:40px;
}

.card {
    background:white;
    padding:20px;
    border-radius:10px;
    width:200px;
}
    </style>
</head>

<body>
<div class="container">
    @yield('content')
</div>
<script>
let index = 0;
const slides = document.querySelectorAll(".slide");

function showSlide(i) {
    slides.forEach(s => s.classList.remove("active"));
    slides[i].classList.add("active");
}

document.querySelector(".next").onclick = () => {
    index = (index + 1) % slides.length;
    showSlide(index);
};

document.querySelector(".prev").onclick = () => {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
};
</script>
</body>
</html>
