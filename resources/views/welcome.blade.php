<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ESS | ElafTech School Services</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>

    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/theme-rtl.css') }}" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.css') }}" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('assets/css/user.css') }}" rel="stylesheet" id="user-style-default">
  </head>


  <body>
    <main class="main" id="top">
      <nav class="navbar navbar-standard navbar-expand-lg fixed-top navbar-dark" data-navbar-darken-on-scroll="data-navbar-darken-on-scroll">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}">
            <div class="d-flex align-items-center">
              <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
              <span class="text-white fw-bold">ESS</span>
            </div>
          </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard" aria-controls="navbarStandard" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse scrollbar" id="navbarStandard">
            <ul class="navbar-nav" data-top-nav-dropdowns="data-top-nav-dropdowns">
              <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
              <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
              <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
              @auth
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
              @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="btn btn-falcon-default btn-sm px-4 ms-lg-3" href="{{ route('register') }}">Register School</a></li>
              @endauth
            </ul>
          </div>
        </div>
      </nav>

      <!-- Hero Section -->
      <section class="py-0 overflow-hidden" id="banner" data-bs-theme="light">
        <div class="bg-holder overlay" style="background-image:url({{ asset('assets/img/generic/bg-1.jpg') }});background-position: center bottom;"></div>
        <div class="container">
          <div class="row flex-center pt-8 pt-lg-10 pb-lg-9 pb-xl-0">
            <div class="col-md-11 col-lg-8 col-xl-4 pb-7 pb-xl-9 text-center text-xl-start">
              <a class="btn btn-outline-danger mb-4 fs-10 border-2 rounded-pill" href="#pricing">
                <span class="me-2" role="img" aria-label="Gift">🎁</span>Early Access Offer
              </a>
              <h1 class="text-white fw-light">Modernize <span class="typed-text fw-bold" data-typed-text='["your school.","your teachers.","your campus.","your destiny."]'></span></h1>
              <p class="lead text-white opacity-75">Professional ElafTech School Services (ESS) provides the ultimate management experience for modern educational institutions.</p>
              <a class="btn btn-outline-light border-2 rounded-pill btn-lg mt-4 fs-9 py-2" href="{{ route('register') }}">Start your journey<span class="fas fa-play ms-2" data-fa-transform="shrink-6 down-1"></span></a>
            </div>
            <div class="col-xl-7 offset-xl-1 align-self-end mt-4 mt-xl-0">
                <a class="img-landing-banner rounded shadow-lg overflow-hidden" href="#!">
                    <img class="img-fluid" src="{{ asset('brain/4e8a932c-8942-41e2-abf4-8dd1c9cd0a41/ess_dashboard_mockup_1773951813302.png') }}" alt="" />
                </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Key Features -->
      <section class="py-3 bg-body-tertiary shadow-sm">
        <div class="container">
          <div class="row flex-center">
            <div class="col-3 col-sm-auto my-1 my-sm-3 px-x1"><img class="landing-cta-img" height="40" src="{{ asset('assets/img/logos/b&w/6.png') }}" alt="" /></div>
            <div class="col-3 col-sm-auto my-1 my-sm-3 px-x1"><img class="landing-cta-img" height="45" src="{{ asset('assets/img/logos/b&w/11.png') }}" alt="" /></div>
            <div class="col-3 col-sm-auto my-1 my-sm-3 px-x1"><img class="landing-cta-img" height="30" src="{{ asset('assets/img/logos/b&w/2.png') }}" alt="" /></div>
            <div class="col-3 col-sm-auto my-1 my-sm-3 px-x1"><img class="landing-cta-img" height="30" src="{{ asset('assets/img/logos/b&w/4.png') }}" alt="" /></div>
          </div>
        </div>
      </section>

      <section id="features">
        <div class="container">
          <div class="row justify-content-center text-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
              <h1 class="fs-7 fs-sm-5 fs-md-4 text-primary">Next Generation Management</h1>
              <p class="lead">ESS is built to scale, providing powerful tools for multi-campus schools with robust role-based access control.</p>
            </div>
          </div>
          <div class="row flex-center mt-8">
            <div class="col-md col-lg-5 col-xl-4 ps-lg-6 text-center"><span class="fas fa-school fa-10x text-primary shadow-sm rounded p-4"></span></div>
            <div class="col-md col-lg-5 col-xl-4 mt-4 mt-md-0">
              <h5 class="text-danger"><span class="far fa-lightbulb me-2"></span>ISOLATION</h5>
              <h3>Campus Autonomy</h3>
              <p>Each campus operates as a dedicated micro-ecosystem while sharing the central institutional standards and reports.</p>
            </div>
          </div>
          <div class="row flex-center mt-7">
            <div class="col-md col-lg-5 col-xl-4 pe-lg-6 order-md-2 text-center"><span class="fas fa-users-cog fa-10x text-info shadow-sm rounded p-4"></span></div>
            <div class="col-md col-lg-5 col-xl-4 mt-4 mt-md-0">
              <h5 class="text-info"><span class="far fa-object-ungroup me-2"></span>ROLES</h5>
              <h3>Flexible RBAC</h3>
              <p>Dynamic permission systems allowing School Owners, Principals, and Staff to perform their duties with absolute precision.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Pricing Section -->
      <section class="bg-body-tertiary dark__bg-opacity-50 text-center" id="pricing">
        <div class="container">
          <div class="row">
            <div class="col">
              <h1 class="fs-7 fs-sm-5 fs-md-4">Choose Your Success Plan</h1>
              <p class="lead">Transparent pricing tailored for every institution size.</p>
            </div>
          </div>
          <div class="row mt-6 g-4 justify-content-center">
            @foreach($packages as $package)
            <div class="col-lg-4">
              <div class="card shadow-lg h-100 border-top border-primary border-4">
                <div class="card-body p-4 p-sm-5">
                  <h4 class="fw-bold text-primary">{{ $package->name }}</h4>
                  <p class="fs-10 text-600">Perfect for growing institutions</p>
                  <div class="display-4 fw-normal text-primary mb-3">
                    <span class="fs-1">$</span>{{ number_format($package->price, 0) }}<span class="fs-10 text-600">/mo</span>
                  </div>
                  <ul class="list-unstyled mt-4 fs-10 text-start mx-auto" style="max-width: 200px;">
                    <li class="mb-2"><span class="fas fa-check text-success me-2"></span>{{ $package->student_limit }} Students</li>
                    <li class="mb-2"><span class="fas fa-check text-success me-2"></span>{{ $package->staff_limit }} Staff Members</li>
                    <li class="mb-2"><span class="fas fa-check text-success me-2"></span>{{ $package->entry_limit }} Records/Day</li>
                    @if($package->has_tech_support)
                        <li class="mb-2"><span class="fas fa-headset text-primary me-2"></span>Premium Tech Support</li>
                    @else
                        <li class="mb-2 text-400"><span class="fas fa-times me-2"></span>Standard Support</li>
                    @endif
                  </ul>
                  <a class="btn btn-primary rounded-pill mt-4 px-5" href="{{ route('register') }}">Join Now</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </section>

      <!-- Footer -->
      <section class="bg-dark pt-8 pb-4" data-bs-theme="light" id="contact">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <h5 class="text-uppercase text-white opacity-85 mb-3">Our Mission</h5>
              <p class="text-600">ElafTech School Services (ESS) is dedicated to digitizing the educational landscape. We provide tools that not only manage operations but empower the people behind the desk.</p>
              <div class="icon-group mt-4">
                <a class="icon-item bg-white text-facebook" href="#!"><span class="fab fa-facebook-f"></span></a>
                <a class="icon-item bg-white text-twitter" href="#!"><span class="fab fa-twitter"></span></a>
                <a class="icon-item bg-white text-linkedin" href="#!"><span class="fab fa-linkedin-in"></span></a>
              </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <h5 class="text-uppercase text-white opacity-85 mb-3">Stay Connected</h5>
                <p class="text-600">Email: support@elaftech.com</p>
                <p class="text-600">Phone: +92 300 0000000</p>
            </div>
          </div>
          <hr class="my-4 text-600 opacity-25" />
          <div class="row justify-content-between fs-10">
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600 opacity-85">© {{ date('Y') }} ElafTech School Services | Powered by <a class="text-white opacity-85" href="https://elaftech.com">ElafTech</a></p>
            </div>
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600 opacity-85">v1.0.0</p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- JavaScripts -->
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('vendors/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

  </body>

</html>
