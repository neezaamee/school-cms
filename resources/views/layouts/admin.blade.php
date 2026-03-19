<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard') | ElafTech School Services</title>

    @php
        $schoolBranding = auth()->check() && auth()->user()->school ? auth()->user()->school : null;
    @endphp
    <!-- Falcon Favicons -->
    @if($schoolBranding && $schoolBranding->favicon)
        <link rel="icon" type="image/webp" href="{{ asset($schoolBranding->favicon) }}">
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    @endif
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>

    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user.css') }}" rel="stylesheet" id="user-style-default">
  </head>

  <body>
    <main class="main" id="top">
      <div class="container" data-layout="container">
        <!-- Sidebar -->
        <nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
          <div class="d-flex align-items-center">
            <div class="toggle-icon-wrapper">
              <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
            </div>
            <a class="navbar-brand" href="{{ url('/') }}">
              <div class="d-flex align-items-center py-3">
                @if($schoolBranding && $schoolBranding->logo)
                    <img class="me-2" src="{{ asset($schoolBranding->logo) }}" alt="" width="40" />
                @else
                    <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
                @endif
                <span class="font-sans-serif text-primary">{{ $schoolBranding ? Str::limit($schoolBranding->name, 10) : 'ESS' }}</span>
              </div>
            </a>
          </div>
          <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
            <div class="navbar-vertical-content scrollbar">
              <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/dashboard') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('profile.edit') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user-circle"></span></span><span class="nav-link-text ps-1">My Profile</span></div>
                  </a>
                </li>
                @hasrole('super admin')
                <li class="nav-item">
                  <a class="nav-link text-uppercase" href="#!" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1 fs-11 fw-bold text-600">Administration</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}" href="{{ route('schools.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-university"></span></span><span class="nav-link-text ps-1">Schools</span></div>
                  </a>
                </li>
                @role('super admin')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}" href="{{ route('audit-logs.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-history"></span></span><span class="nav-link-text ps-1">Audit Logs</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.changelogs.*') ? 'active' : '' }}" href="{{ route('admin.changelogs.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-bullhorn"></span></span><span class="nav-link-text ps-1">Manage Changelog</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-comments"></span></span><span class="nav-link-text ps-1">User Feedback</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.campuses.*') ? 'active' : '' }}" href="{{ route('admin.campuses.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-map-marker-alt"></span></span><span class="nav-link-text ps-1">All Campuses</span></div>
                  </a>
                </li>
                @endrole
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('subscription-packages.*') ? 'active' : '' }}" href="{{ route('subscription-packages.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-tags"></span></span><span class="nav-link-text ps-1">Subscriptions</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('users.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-users"></span></span><span class="nav-link-text ps-1">User Management</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link dropdown-indicator" href="#rbac-nav" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="rbac-nav">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user-shield"></span></span><span class="nav-link-text ps-1">RBAC</span></div>
                  </a>
                  <ul class="nav collapse" id="rbac-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Roles</span></div>
                      </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('permissions.index') }}">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Permissions</span></div>
                      </a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}" href="{{ route('admin.staffs.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user-tie"></span></span><span class="nav-link-text ps-1">Staff Management</span></div>
                  </a>
                </li>
                @endhasrole

                @hasanyrole('school owner|principal|school manager|school administrator|campus manager')
                <li class="nav-item">
                  <a class="nav-link text-uppercase" href="#!" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1 fs-11 fw-bold text-600">School Management</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}" href="{{ route('admin.staffs.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user-tie"></span></span><span class="nav-link-text ps-1">Staff Members</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link border-2 border-dashed border-warning" href="{{ route('school-users.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-users-cog"></span></span><span class="nav-link-text ps-1">Students (Temp)</span></div>
                  </a>
                </li>
                @endhasanyrole

                @hasrole('school owner')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.campuses.*') ? 'active' : '' }}" href="{{ route('admin.campuses.index') }}" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-map-marker-alt"></span></span><span class="nav-link-text ps-1">My Campuses</span></div>
                  </a>
                </li>
                @endhasrole

                <li class="nav-item">
                  <a class="nav-link text-uppercase" href="#!" role="button">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1 fs-11 fw-bold text-600">Community</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('user.changelogs') ? 'active' : '' }}" href="{{ route('user.changelogs') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-rocket"></span></span><span class="nav-link-text ps-1">What's New</span></div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('user.feedback.*') ? 'active' : '' }}" href="{{ route('user.feedback.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-comment-dots"></span></span><span class="nav-link-text ps-1">Feedback History</span></div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="content">
          <!-- Top Nav -->
          <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
            <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ url('/') }}">
              <div class="d-flex align-items-center">
                @if($schoolBranding && $schoolBranding->logo)
                    <img class="me-2" src="{{ asset($schoolBranding->logo) }}" alt="" width="40" />
                @else
                    <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
                @endif
                <span class="font-sans-serif text-primary">{{ $schoolBranding ? Str::limit($schoolBranding->name, 10) : 'ESS' }}</span>
              </div>
            </a>

            <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
              <li class="nav-item dropdown">
                <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div class="avatar avatar-xl">
                    <img class="rounded-circle" src="{{ asset('assets/img/team/3-thumb.png') }}" alt="" />
                  </div>
                </a>
                <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                  <div class="bg-white dark__bg-1000 rounded-2 py-2">
                    <div class="px-3 py-2">
                        <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                        <p class="fs-11 mb-0 text-600">{{ auth()->user()->getRoleNames()->first() ?? 'No Role' }}</p>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile &amp; account</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </div>
                </div>
              </li>
            </ul>
          </nav>

          <!-- Main Content Area -->
          @yield('content')

          <!-- Footer -->
          <footer class="footer">
            <div class="row g-0 justify-content-between fs-11 mt-4 mb-3">
              <div class="col-12 col-sm-auto text-center">
                <p class="mb-0 text-600">ElafTech School Services <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" /> {{ date('Y') }} &copy; <a href="https://elaftech.com">ElafTech</a></p>
              </div>
              <div class="col-12 col-sm-auto text-center">
                <p class="mb-0 text-600">IP: {{ request()->ip() }} <span class="d-none d-sm-inline-block">| </span> v{{ config('app.version', '1.1.0') }}</p>
              </div>
            </div>
          </footer>
        </div>
      </div>
    </main>

    <div class="offcanvas offcanvas-end settings-panel border-0" id="settings-offcanvas" tabindex="-1" aria-labelledby="settings-offcanvas">
      <div class="offcanvas-header settings-panel-header justify-content-between bg-shape">
        <div class="z-1 py-1">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <h5 class="text-white mb-0 me-2"><span class="fas fa-palette me-2 fs-9"></span>Settings</h5>
            <button class="btn btn-primary btn-sm rounded-pill mt-0 mb-0" data-theme-control="reset" style="font-size:12px"> <span class="fas fa-redo-alt me-1" data-fa-transform="shrink-3"></span>Reset</button>
          </div>
          <p class="mb-0 fs-10 text-white opacity-75"> Set your own customized style</p>
        </div>
        <div class="z-1" data-bs-theme="dark">
          <button class="btn-close z-1 mt-0" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
      </div>
      <div class="offcanvas-body scrollbar-overlay px-card h-100" id="themeController">
        <h5 class="fs-9">Color Scheme</h5>
        <p class="fs-10">Choose the perfect color mode for your app.</p>
        <div class="btn-group d-block w-100 btn-group-navbar-style">
          <div class="row gx-2">
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherLight" name="theme-color" type="radio" value="light" data-theme-control="theme" />
              <label class="btn d-inline-block btn-navbar-style fs-10" for="themeSwitcherLight"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="{{ asset('assets/img/generic/falcon-mode-default.jpg') }}" alt=""/></span><span class="label-text">Light</span></label>
            </div>
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherDark" name="theme-color" type="radio" value="dark" data-theme-control="theme" />
              <label class="btn d-inline-block btn-navbar-style fs-10" for="themeSwitcherDark"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="{{ asset('assets/img/generic/falcon-mode-dark.jpg') }}" alt=""/></span><span class="label-text"> Dark</span></label>
            </div>
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherAuto" name="theme-color" type="radio" value="auto" data-theme-control="theme" />
              <label class="btn d-inline-block btn-navbar-style fs-10" for="themeSwitcherAuto"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="{{ asset('assets/img/generic/falcon-mode-auto.jpg') }}" alt=""/></span><span class="label-text"> Auto</span></label>
            </div>
          </div>
        </div>
        <hr />
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-start"><img class="me-2" src="{{ asset('assets/img/icons/left-arrow-from-left.svg') }}" width="20" alt="" />
            <div class="flex-1">
              <h5 class="fs-9">RTL Mode</h5>
              <p class="fs-10 mb-0">Switch your language direction </p>
            </div>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input ms-0" id="mode-rtl" type="checkbox" data-theme-control="isRTL" />
          </div>
        </div>
        <hr />
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-start"><img class="me-2" src="{{ asset('assets/img/icons/arrows-h.svg') }}" width="20" alt="" />
            <div class="flex-1">
              <h5 class="fs-9">Fluid Layout</h5>
              <p class="fs-10 mb-0">Toggle container layout system </p>
            </div>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input ms-0" id="mode-fluid" type="checkbox" data-theme-control="isFluid" />
          </div>
        </div>
        <hr />
        <div class="d-flex align-items-start"><img class="me-2" src="{{ asset('assets/img/icons/paragraph.svg') }}" width="20" alt="" />
          <div class="flex-1">
            <h5 class="fs-9 d-flex align-items-center">Navigation Position</h5>
            <p class="fs-10 mb-2">Select a suitable navigation system for your web application </p>
            <div>
              <select class="form-select form-select-sm" aria-label="Navbar position" data-theme-control="navbarPosition">
                <option value="vertical">Vertical</option>
                <option value="top">Top</option>
                <option value="combo">Combo</option>
                <option value="double-top">Double Top</option>
              </select>
            </div>
          </div>
        </div>
        <hr />
        <h5 class="fs-9 d-flex align-items-center">Vertical Navbar Style</h5>
        <p class="fs-10 mb-0">Switch between styles for your vertical navbar </p>
        <div class="btn-group d-block w-100 btn-group-navbar-style">
          <div class="row gx-2">
            <div class="col-6">
              <input class="btn-check" id="navbar-style-transparent" type="radio" name="navbarStyle" value="transparent" data-theme-control="navbarStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-10" for="navbar-style-transparent"> <img class="img-fluid img-prototype" src="{{ asset('assets/img/generic/default.png') }}" alt="" /><span class="label-text"> Transparent</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbar-style-inverted" type="radio" name="navbarStyle" value="inverted" data-theme-control="navbarStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-10" for="navbar-style-inverted"> <img class="img-fluid img-prototype" src="{{ asset('assets/img/generic/inverted.png') }}" alt="" /><span class="label-text"> Inverted</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbar-style-card" type="radio" name="navbarStyle" value="card" data-theme-control="navbarStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-10" for="navbar-style-card"> <img class="img-fluid img-prototype" src="{{ asset('assets/img/generic/card.png') }}" alt="" /><span class="label-text"> Card</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbar-style-vibrant" type="radio" name="navbarStyle" value="vibrant" data-theme-control="navbarStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-10" for="navbar-style-vibrant"> <img class="img-fluid img-prototype" src="{{ asset('assets/img/generic/vibrant.png') }}" alt="" /><span class="label-text"> Vibrant</span></label>
            </div>
          </div>
        </div>
        <div class="text-center mt-5"><img class="mb-4" src="{{ asset('assets/img/icons/spot-illustrations/47.png') }}" alt="" width="120" />
          <h5>Like What You See?</h5>
          <p class="fs-10">Get Falcon now and create beautiful dashboards with hundreds of widgets.</p><a class="mb-3 btn btn-primary" href="https://themewagon.com/themes/falcon/" target="_blank">Purchase</a>
        </div>
      </div>
    </div><a class="card setting-toggle" href="#settings-offcanvas" data-bs-toggle="offcanvas">
      <div class="card-body d-flex align-items-center py-md-2 px-2 py-1">
        <div class="bg-primary-subtle position-relative rounded-start" style="height:34px;width:28px">
          <div class="settings-popover"><span class="ripple"><span class="fa-spin position-absolute all-0 d-flex flex-center"><span class="icon-spin position-absolute all-0 d-flex flex-center">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.7369 12.3941L19.1989 12.1065C18.4459 11.7041 18.0843 10.8487 18.0843 9.99495C18.0843 9.14118 18.4459 8.28582 19.1989 7.88336L19.7369 7.59581C19.9474 7.47484 20.0316 7.23291 19.9474 7.03131C19.4842 5.57973 18.6843 4.28943 17.6738 3.20075C17.5053 3.03946 17.2527 2.99914 17.0422 3.12011L16.393 3.46714C15.6883 3.84379 14.8377 3.74529 14.1476 3.3427C14.0988 3.31422 14.0496 3.28621 14.0002 3.25868C13.2568 2.84453 12.7055 2.10629 12.7055 1.25525V0.70081C12.7055 0.499202 12.5371 0.297594 12.2845 0.257272C10.7266 -0.105622 9.16879 -0.0653007 7.69516 0.257272C7.44254 0.297594 7.31623 0.499202 7.31623 0.70081V1.23474C7.31623 2.09575 6.74999 2.8362 5.99824 3.25599C5.95774 3.27861 5.91747 3.30159 5.87744 3.32493C5.15643 3.74527 4.26453 3.85902 3.53534 3.45302L2.93743 3.12011C2.72691 2.99914 2.47429 3.03946 2.30587 3.20075C1.29538 4.28943 0.495411 5.57973 0.0322686 7.03131C-0.051939 7.23291 0.0322686 7.47484 0.242788 7.59581L0.784376 7.8853C1.54166 8.29007 1.92694 9.13627 1.92694 9.99495C1.92694 10.8536 1.54166 11.6998 0.784375 12.1046L0.242788 12.3941C0.0322686 12.515 -0.051939 12.757 0.0322686 12.9586C0.495411 14.4102 1.29538 15.7005 2.30587 16.7891C2.47429 16.9504 2.72691 16.9907 2.93743 16.8698L3.58669 16.5227C4.29133 16.1461 5.14131 16.2457 5.8331 16.6455C5.88713 16.6767 5.94159 16.7074 5.99648 16.7375C6.75162 17.1511 7.31623 17.8941 7.31623 18.7552V19.2891C7.31623 19.4425 7.41373 19.5959 7.55309 19.696C7.64066 19.7589 7.74815 19.7843 7.85406 19.8046C9.35884 20.0925 10.8609 20.0456 12.2845 19.7729C12.5371 19.6923 12.7055 19.4907 12.7055 19.2891V18.7346C12.7055 17.8836 13.2568 17.1454 14.0002 16.7312C14.0496 16.7037 14.0988 16.6757 14.1476 16.6472C14.8377 16.2446 15.6883 16.1461 16.393 16.5227L17.0422 16.8698C17.2527 16.9907 17.5053 16.9504 17.6738 16.7891C18.7264 15.7005 19.4842 14.4102 19.9895 12.9586C20.0316 12.757 19.9474 12.515 19.7369 12.3941ZM10.0109 13.2005C8.1162 13.2005 6.64257 11.7893 6.64257 9.97478C6.64257 8.20063 8.1162 6.74905 10.0109 6.74905C11.8634 6.74905 13.3792 8.20063 13.3792 9.97478C13.3792 11.7893 11.8634 13.2005 10.0109 13.2005Z" fill="#2A7BE4"></path>
                  </svg></span></span></span></div>
        </div><small class="text-uppercase text-primary fw-bold bg-primary-subtle py-2 pe-2 ps-1 rounded-end">customize</small>
      </div>
    </a>

    <!-- Scripts -->
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Global DataTable initialization for tables with .datatable class
            $('.datatable').each(function() {
                $(this).DataTable({
                    "paging": false, // Disable DataTables pagination as we use Laravel's for now
                    "info": false,
                    "searching": true,
                    "ordering": true,
                    "responsive": true,
                    "language": {
                        "searchPlaceholder": "Search..."
                    }
                });
            });

            // Global Select2 initialization
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
    </script>

    @stack('scripts')
  </body>
</html>
