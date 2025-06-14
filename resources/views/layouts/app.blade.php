<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets') }}"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>LanggengTani</title>

  <meta name="description" content="" />


  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon/Logo Tab.png') }}" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/favicon/Logo Tab.png') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css">
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

  @yield('style')
  <style>
    #notificationDropdown .dropdown-menu {
      max-height: 300px;  /* Atur tinggi maksimum sesuai kebutuhan */
      overflow-y: auto;   /* Membuat scroll jika konten melebihi tinggi yang ditentukan */
    }

    @media (max-width: 768px) {
      #notificationDropdown .dropdown-menu .dropdown-item {
        white-space: normal;  /* Membungkus teks jika terlalu panjang */
        word-wrap: break-word; /* Memecah kata yang terlalu panjang */
      }
    }


  </style>

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo d-flex justify-content-center">
            <a href="{{ route('home') }}" class="app-brand-link">
                <span class="app-brand-logo demo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/LanggengTani.png') }}" alt="LanggengTani" class="img-fluid" style="max-height: 55px;">
                  </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item">
            <a href="{{ route('home') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Dashboard">Dashboard</div>
            </a>
          </li>

          <!-- Stocks -->
          <li class="menu-item">
            <a href="{{ route('stocks.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-box"></i>
              <div data-i18n="List Stocks">Stok Barang</div>
            </a>
          </li>

          <!-- Cart -->
          <li class="menu-item">
            <a href="{{ route('cart.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-cart"></i>
              <div data-i18n="Cart">Kasir</div>
            </a>
          </li>

          @if (auth()->user()->role == 'owner')
      <li class="menu-item">
        <a href="{{ route('pembelian.index') }}" class="menu-link">
        <!-- Changed the icon to a shopping cart icon (for purchases) -->
        <i class="menu-icon tf-icons bx bx-store"></i>
        <div data-i18n="Pembelian">Pembelian</div>
        </a>
        </li>

        <li class="menu-item">
        <a href="{{ route('penjualan.index') }}" class="menu-link">
        <!-- Changed the icon to a sell icon (for sales) -->
        <i class="menu-icon tf-icons bx bx-cart-alt"></i>
        <div data-i18n="Penjualan">Penjualan</div>
        </a>
        </li>

        <!-- Profile Settings -->
        <li class="menu-item">
        <a href="{{ route('profile.edit') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Profile">Profil</div>
        </a>
        </li>

        <!-- Pegawai Settings -->
        <li class="menu-item">
        <a href="{{ route('user.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-pin"></i>
        <div data-i18n="Pegawai">Pegawai</div>
        </a>
        </li>
    @endif

<li class="menu-item">
    <a href="https://langgengtani.gitbook.io/langgengtani/" class="menu-link" target="_blank">
      <i class="menu-icon tf-icons bx bx-file"></i>
      <div data-i18n="Dokumentasi" class="d-flex justify-content-between w-100">
        <span>Panduan</span>
        <i class="bx bx-link-external"></i>
      </div>
    </a>
  </li>
        </ul>



      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Notification -->
              <li class="nav-item dropdown" id="notificationDropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-bell fs-4"></i>
                    <span id="notificationBadge" class="badge badge-center rounded-pill bg-danger w-px-20 h-px-20">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- Notifications will be appended here via Ajax -->
                    <li><a class="dropdown-item" href="#">Loading notifications...</a></li>
                </ul>
            </li>
              <!--/ Notification -->
              <li class="nav-item">
                <span class="ms-2 d-none d-md-inline-block" id="userName">{{ auth()->user()->name }}</span>
              </li>

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown ms-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  @if (auth()->user()->role == 'owner')
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">My Profile</span>
            </a>
            </li>
            <li>
            <div class="dropdown-divider"></div>
            </li>
        @endif
                  <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-fluid flex-grow-1 container-p-y">
            @yield('content')
          </div>
          <!-- / Content -->

          {{-- <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
              </div>
              <div>
                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                <a
                  href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                  target="_blank"
                  class="footer-link me-4">Documentation</a>

                <a
                  href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                  target="_blank"
                  class="footer-link me-4">Support</a>
              </div>
            </div>
          </footer> --}}
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    $(document).ready(function () {
      // Fetch notifications using Ajax
      function loadNotifications() {
        $.ajax({
          url: '/notifications',
          method: 'GET',
          success: function (response) {
            // Update the badge with the count of unread notifications
            $('#notificationBadge').text(response.unread_count);

            // Clear existing notifications
            $('#notificationDropdown .dropdown-menu').empty();

            if (response.notifications.length > 0) {
              response.notifications.forEach(function (notification) {
                $('#notificationDropdown .dropdown-menu').append(`
                            <li>
                                <a class="dropdown-item mark-as-read" href="#" data-id="${notification.id}">
                                    <i class="bx bx-bell me-2"></i>
                                    <span class="align-middle">${notification.message}</span>
                                </a>
                            </li>
                        `);
              });
            } else {
              $('#notificationDropdown .dropdown-menu').append(`
                        <li><a class="dropdown-item" href="#">No notifications</a></li>
                    `);
            }

            $('#notificationDropdown .dropdown-menu').append(`
    <li>
        <div class="dropdown-divider"></div>
    </li>
    <li>
        <a class="dropdown-item" href="{{ route('notifications.all') }}">Lihat Semua Notifikasi</a>
    </li>
`);
          }
        });
      }

      // Load notifications when the page loads
      loadNotifications();

      // Optionally, refresh notifications periodically
      setInterval(loadNotifications, 60000); // Refresh every minute

      // Mark notification as read when clicked
      $(document).on('click', '.mark-as-read', function (e) {
        var notificationId = $(this).data('id');
        $.ajax({
          url: '/notifications/' + notificationId + '/markAsRead',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function () {
            loadNotifications();
          }
        });
      });
    });
  </script>

  @yield('script')

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>







</body>

</html>