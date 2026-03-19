<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | ElafTech School Services</title>
    <!-- Falcon Template & Bootstrap 5 Styles -->
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" id="style-default">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-section { background: linear-gradient(135deg, #2c7be5 0%, #152e4d 100%); height: 100vh; display: flex; align-items: center; color: white; }
    </style>
</head>
<body>
    <main class="main" id="top">
        <div class="hero-section">
            <div class="container text-center">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h1 class="display-3 fw-bold mb-3">ElafTech School Services</h1>
                        <p class="lead mb-5">Professional Management Solutions for Modern Educational Institutions.</p>
                        <div class="d-flex justify-content-center">
                            @auth
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('dashboard') }}" class="btn btn-light px-5 rounded-pill me-3">Go to Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-light px-5 rounded-pill">Logout</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-light px-5 rounded-pill me-3">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light px-5 rounded-pill">Register School</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Scripts -->
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
</body>
</html>
