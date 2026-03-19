<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $school->name }} | Powered by ESS</title>

    <!-- Favicon -->
    @if($school->favicon)
        <link rel="icon" type="image/webp" href="{{ asset($school->favicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    @endif

    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/user.css') }}" rel="stylesheet">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #000000 100%);
            color: white;
            padding: 100px 0;
        }
        .school-logo-lg {
            max-height: 120px;
            margin-bottom: 2rem;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));
        }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-standard navbar-expand-lg fixed-top navbar-dark" data-navbar-darken-on-scroll="data-navbar-darken-on-scroll">
      <div class="container">
        <a class="navbar-brand" href="#">
          <div class="d-flex align-items-center">
            @if($school->logo)
                <img class="me-2" src="{{ asset($school->logo) }}" alt="" width="40" />
            @else
                <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
            @endif
            <span class="text-white fw-bold">{{ $school->name }}</span>
          </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard" aria-controls="navbarStandard" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarStandard">
          <ul class="navbar-nav ms-auto text-white">
            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#about">About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#contact">Contact</a></li>
            <li class="nav-item ms-lg-3">
                <a class="btn btn-falcon-default btn-sm px-4" href="{{ route('school.login', $school->slug) }}">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="main" id="top">
        @yield('content')
    </main>

    <footer class="bg-dark pt-5 pb-4 text-white">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <h5 class="text-uppercase text-white mb-3">{{ $school->name }}</h5>
            <p class="text-500 fs-11">Empowering the next generation with quality education and modern management systems. Fully managed by ElafTech School Services.</p>
          </div>
          <div class="col-lg-2 offset-lg-2">
            <h6 class="text-uppercase text-white mb-3">Links</h6>
            <ul class="list-unstyled fs-11">
              <li class="mb-2"><a class="text-500" href="#!">Privacy Policy</a></li>
              <li class="mb-2"><a class="text-500" href="#!">Terms of Service</a></li>
            </ul>
          </div>
          <div class="col-lg-4">
            <h6 class="text-uppercase text-white mb-3">Contact</h6>
            <p class="text-500 fs-11 mb-2"><span class="fas fa-envelope me-2"></span>{{ $school->email }}</p>
            <p class="text-500 fs-11 mb-2"><span class="fas fa-phone me-2"></span>{{ $school->phone }}</p>
            <p class="text-500 fs-11"><span class="fas fa-map-marker-alt me-2"></span>{{ $school->mainCampus->address ?? 'Main Campus' }}</p>
          </div>
        </div>
        <hr class="border-700 mt-4 mb-3" />
        <div class="row justify-content-between fs-11">
          <div class="col-auto">
            <p class="mb-0 text-600">&copy; {{ date('Y') }} {{ $school->name }} | Powered by <a href="https://elaftech.com" class="text-white">ElafTech</a></p>
          </div>
        </div>
      </div>
    </footer>

    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
  </body>
</html>
