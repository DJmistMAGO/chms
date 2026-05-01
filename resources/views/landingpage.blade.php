<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Caree Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbz0g5a2QFhLZLJj9IYj5n3z9nN+Y+zV9zzTtmI3UksdQRVh+0Uj" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}" type="image/x-icon">

    <style>
        :root {
            --light: #ffe1a4;
            --primary: #ffd000;
            --secondary: #f9ae36;
            --dark: #000000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff;
            color: var(--dark);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            position: absolute;
            width: 100%;
            z-index: 10;
            color: rgb(0, 0, 0);
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-links a {
            margin-left: 25px;
            text-decoration: none;
            color: rgb(0, 0, 0);
            font-weight: 500;
        }

        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
            url('{{ asset('assets/images/ch2.png') }}') no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 60px;
            color: white;
        }

        .hero-content {
            max-width: 600px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-top: 50px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero p {
            margin-top: 15px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .buttons {
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            font-weight: bold;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 80px 60px;
            gap: 40px;
        }

        .text {
            flex: 1;
        }

        .text h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .text h2 span {
            color:var(--primary);
        }

        .feature {
            margin-top: 20px;
        }

        .feature h3 {
            margin-bottom: 5px;
        }

        .section .image {
            flex: 1;
            height: 600px;
            overflow: hidden;
            background-position: center top;
        }

        .image img {
            width: 100%;
            border-radius: 30px;
            background-position: center top;

        }

        @media(max-width: 768px) {
            .section {
                flex-direction: column;
            }

            .navbar {
                padding: 20px;
            }
        }

        .footer {
        background: var(--dark);
        color: white;
        text-align: center;
        padding: 20px 60px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .feature {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            height: 50px;
            width: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            color: var(--dark);
            background-color: var(--primary);
            margin-top: 5px;
        }

        .feature h3 {
            margin: 0 0 5px 0;
        }

        .feature p {
            margin: 0;
            color: #555;
        }
    </style>
</head>
    <body>
    <div class="navbar">
        <div class="logo">
            <img src="{{ asset('assets/images/chlogo.png') }}" alt="Caree Hotel Logo" width="100">
        </div>
        <div class="nav-links">
        <a href="#">HOME</a>
        <a href="#">ROOMS</a>
        <a href="#">LOGIN</a>
        {{-- <a href="#">ADMIN</a> --}}
        </div>
    </div>

    <section class="hero mt-5">
        <div class="hero-content">
        <h1>FIND YOUR <span>PEACE</span> HERE.</h1>
        <p>Experience the perfect blend of modern comfort and serene ambiance. Our micro-pricing ensures you only pay for the views and features you love.</p>
        <div class="buttons">
            <button class="btn btn-primary">Start Reservation</button>
            <button class="btn btn-outline">Explore Rooms</button>
        </div>
        </div>
    </section>

    <section class="section">
        <div class="text">
            <h2>Why Choose <span>Caree Hotel?</span></h2>

            <div class="feature">
                <span class="feature-icon"><i class="fas fa-star"></i></span>
                <div>
                    <h3>Micro Pricing Engine</h3>
                    <p>Our innovative pricing model breaks down room rates by features like view, type, and ambiance.</p>
                </div>
            </div>

            <div class="feature" style="margin-top: 30px;">
                <span class="feature-icon"><i class="fas fa-shield-alt"></i></span>
                <div>
                    <h3>Secure Identity Verification</h3>
                    <p>To prevent fake bookings and ensure guest safety, we implement a two-step identity verification process during reservation.</p>
                </div>
            </div>

            <div class="feature" style="margin-top: 30px;">
                <span class="feature-icon"><i class="fas fa-bed"></i></span>
                <div>
                    <h3>Bulan's Finest Comfort</h3>
                    <p>Located in the heart of Sorsogon, we provide high-speed internet, premium bedding, and a peaceful atmosphere for every traveler.</p>
                </div>
            </div>

        </div>

        <div class="image">
            <img src="{{ asset('assets/images/ch1.png') }}" alt="Hotel" />
        </div>
    </section>

    <section class="footer">
        <div class="footer-content">
            <div class="logo">
                <img src="{{ asset('assets/images/chlogo.png') }}" alt="Caree Hotel Logo" width="120">
            </div>
            <p class="copyright">&copy; 2026 Caree Hotel. All rights reserved.</p>
            <div>
                <a href="#" style="color: white; margin-right: 15px; text-decoration:none">Privacy Policy</a>
                <a href="#" style="color: white; text-decoration:none">Terms of Service</a>
            </div>
        </div>
    </section>

    {{-- <script src="{{ asset('assets/js/main.js') }}"></script> --}} {{-- to add once compiled for development --Mike --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
