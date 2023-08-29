<!DOCTYPE html>
<html lang="en">

<head>
  <title>@yield('title','Home')</title>

  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">

  <!-- Google Fonts -->
  <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700%7COpen+Sans:400,400i,600,700' rel='stylesheet'>

  <!-- Css -->
  <link rel="stylesheet" href="/front/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/front/css/magnific-popup.css" />
  <link rel="stylesheet" href="/front/css/font-icons.css" />
  <link rel="stylesheet" href="/front/css/sliders.css" />
  <link rel="stylesheet" href="/front/css/style.css" />

  <!-- Favicons -->
  <link rel="shortcut icon" href="/uploads/vayya (2).ico">
  <link rel="apple-touch-icon" href="/front/img/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/front/img/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/front/img/apple-touch-icon-114x114.png">

  <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    .leaflet-container {
      height: 400px;
      width: 600px;
      max-width: 100%;
      max-height: 100%;
    }

    /* Add some basic styling to the map container */
    #map {
      width: 100%;
    height: 400px;
    margin-top: 20px;
    text-align: center;
    }

    /* Style the header */
    .map-header {
      text-align: center;
      margin-bottom: 20px;
      margin-top: 20px;
    }
  </style>

</head>

<body class="relative">
  

  <!-- Preloader -->
  <div class="loader-mask">
    <div class="loader">
      <div></div>
      <div></div>
    </div>
  </div>

  <main class="main-wrapper">

    <header class="nav-type-1">

      <!-- Fullscreen search -->
      <div class="search-wrap">
        <div class="search-inner">
          <div class="search-cell">
            <form method="get">
              <div class="search-field-holder">
                <input type="search" class="form-control main-search-input" placeholder="Search for">
                <i class="ui-close search-close" id="search-close"></i>
              </div>
            </form>
          </div>
        </div>
      </div> <!-- end fullscreen search -->



      <nav class="navbar navbar-static-top">
        <div class="navigation" id="sticky-nav">
          <div class="container relative">

            <div class="row flex-parent">

              <div class="navbar-header flex-child">
                <!-- Logo -->
                <div class="logo-container">
                  <div class="logo-wrap">
                    <a href="/">
                      @php
                      $about = App\Models\About::first();
                      @endphp
                      <img class="logo-dark2" src="/uploads/{{$about->logo}}" alt="logo">
                    </a>
                  </div>
                </div>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <!-- Mobile cart -->
                <div class="nav-cart mobile-cart hidden-lg hidden-md">
                  <div class="nav-cart-outer">
                    <div class="nav-cart-inner">
                      <a href="/front/#" class="nav-cart-icon">
                        <span class="nav-cart-badge">2</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div> <!-- end navbar-header -->

              <div class="nav-wrap flex-child">
                <div class="collapse navbar-collapse text-center" id="navbar-collapse">

                  <ul class="nav navbar-nav">

                    <li class="dropdown">
                      <a href="/">Home</a>
                    </li>

                    <li class="dropdown">
                      <a href="/about">About</a>
                    </li>

                    @php
                    $categories = App\Models\Category::all();
                    @endphp

                    <li class="dropdown">
                      <a href="#">Shop</a>
                      <i class="fa fa-angle-down dropdown-trigger"></i>
                      <ul class="dropdown-menu megamenu-wide">
                        <li>
                          <div class="megamenu-wrap container">
                            <div class="row">

                              @foreach ($categories as $category)
                              <div class="col-md-3 megamenu-item">
                                <ul class="menu-list">

                                  <li>
                                    <span>{{$category->nama_kategori}}</span>
                                  </li>
                                  @php
                                  $subcategories =
                                  App\Models\Subcategory::where('id_kategori',
                                  $category->id)->get();
                                  @endphp
                                  @foreach ($subcategories as $subcategory)
                                  <li>
                                    <a href="/products/{{$subcategory->id}}">{{$subcategory->nama_subkategori}}</a>
                                  </li>
                                  @endforeach
                              </div>
                              @endforeach
                            </div>
                          </div>
                        </li>
                      </ul>
                    </li>

                    <li class="dropdown">
                      <a href="/faq">F.A.Q</a>
                    </li>

                    <li class="dropdown">
                      <a href="/contact">Contact Us</a>
                    </li>


                    <!-- Mobile search -->
                    <li id="mobile-search" class="hidden-lg hidden-md">
                      <form method="get" class="mobile-search">
                        <input type="search" class="form-control" placeholder="Search...">
                        <button type="submit" class="search-button">
                          <i class="fa fa-search"></i>
                        </button>
                      </form>
                    </li>

                  </ul> <!-- end menu -->
                </div> <!-- end collapse -->
              </div> <!-- end col -->

              <div class="flex-child flex-right nav-right hidden-sm hidden-xs">
                <ul>
                  <li class="nav-register">
                    @if(Auth::guard('webmember')->check( ))

                    <a href="/profile">{{Auth::guard('webmember')->user()->nama_member}}</a>
                    @else

                    <a href="/login_member">Login</a>
                    @endif
                  </li>


                  <li class="nav-search-wrap style-2 hidden-sm hidden-xs">
                    <a href="/#" class="nav-search search-trigger">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                  <li class="nav-cart">
                    <div class="nav-cart-outer">
                      <div class="nav-cart-inner">
                        <a href="/cart" class="nav-cart-icon">
                        </a>
                      </div>
                    </div>
                  </li>

                  <li class="nav-register">
                    @if(Auth::guard('webmember')->check( ))
                    <a href="/logout_member">Logout</a>
                    @endif
                  </li>
                  <li class="nav-register">
                    @if(Auth::guard('webmember')->check( ))
                    <a href="/orders">My Orders</a>
                    @endif
                  </li>
                </ul>
              </div>

            </div> <!-- end row -->
          </div> <!-- end container -->
        </div> <!-- end navigation -->
      </nav> <!-- end navbar -->
    </header>

    <div class="content-wrapper oh">
      

      @yield('content')
      
      <h1 class="map-header">Explore the Map</h1>

      <div id="map"></div>
      <script>
        var map = L.map('map').setView([-1.077848937705277, 100.7875771920664], 13);

        var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([-1.077848937705277, 100.7875771920664]).addTo(map)
          .bindPopup('<b>Hello</b><br />Ini Lokasi Petshop Vayya.').openPopup();

        var popup = L.popup()
          .setLatLng([-1.077848937705277, 100.7875771920664])
          .setContent('Petshop Vayya.')
          .openOn(map);

        function onMapClick(e) {
          popup
            .setLatLng(e.latlng)
            .setContent('You clicked the map at ' + e.latlng.toString())
            .openOn(map);
        }

        map.on('click', onMapClick);
      </script>



      <!-- Footer Type-1 -->
      <footer class="footer footer-type-1">
        <div class="container">
          <div class="footer-widgets">
            <div class="row">
              <div class="col-md-3 col-sm-12 col-xs-12">

                <div class="widget footer-about-us">
                  <img src="/uploads/vayya.jpg" alt="" class="image" width="100" height="100">
                  <p class="mb-30">Petshop Vayya is a very slick and clean eCommerce template.</p>
                  <div class="footer-socials">
                    <div class="social-icons nobase">
                      <a href="#f"><i class="fa fa-twitter"></i></a>
                      <a href="#f"><i class="fa fa-facebook"></i></a>
                      <a href="#f"><i class="fa fa-google-plus"></i></a>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-md-2 col-md-offset-1 col-sm-6 col-xs-12">
                <div class="widget footer-links">
                  <h5 class="widget-title bottom-line left-align grey">Information</h5>
                  <ul class="list-no-dividers">
                    <li><a href="#f">Our stores</a></li>
                    <li><a href="#f">About us</a></li>
                    <li><a href="#f">Business with us</a></li>
                    <li><a href="#f">Delivery information</a></li>
                  </ul>
                </div>
              </div>

              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="widget footer-links">
                  <h5 class="widget-title bottom-line left-align grey">Account</h5>
                  <ul class="list-no-dividers">
                    <li><a href="#f">My account</a></li>
                    <li><a href="#f">Wishlist</a></li>
                    <li><a href="#f">Order history</a></li>
                    <li><a href="#f">Specials</a></li>
                  </ul>
                </div>
              </div>

              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="widget footer-links">
                  <h5 class="widget-title bottom-line left-align grey">Useful Links</h5>
                  <ul class="list-no-dividers">
                    <li><a href="#f">Shipping Policy</a></li>
                    <li><a href="#f">Stores</a></li>
                    <li><a href="#f">Returns</a></li>
                    <li><a href="#f">Terms &amp; Conditions</a></li>
                  </ul>
                </div>
              </div>

              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="widget footer-links">
                  <h5 class="widget-title bottom-line left-align grey">Service</h5>
                  <ul class="list-no-dividers">
                    <li><a href="#f">Support</a></li>
                    <li><a href="#f">Warranty</a></li>
                    <li><a href="#f">FAQ</a></li>
                    <li><a href="#f">Contact</a></li>
                  </ul>
                </div>
              </div>

            </div>
          </div>
        </div> <!-- end container -->

        <div class="bottom-footer">
          <div class="container">
            <div class="row">

              <div class="col-sm-6 copyright sm-text-center">
                <span>
                  &copy; 2023 Petshop Vayya <a href="hfttp://deothemes.com"></a>
                </span>
              </div>

              <div class="col-sm-6 col-xs-12 footer-payment-systems text-right sm-text-center mt-sml-10">
                <i class="fa fa-cc-paypal"></i>
                <i class="fa fa-cc-visa"></i>
                <i class="fa fa-cc-mastercard"></i>
                <i class="fa fa-cc-discover"></i>
                <i class="fa fa-cc-amex"></i>
              </div>

            </div>
          </div>
        </div> <!-- end bottom footer -->
      </footer> <!-- end footer -->

      <div id="back-to-top">
        <a href="#ftop"><i class="fa fa-angle-up"></i></a>
      </div>

    </div> <!-- end content wrapper -->
    </div>
  </main> <!-- end main wrapper -->

  <!-- jQuery Scripts -->
  <script type="text/javascript" src="/front/js/jquery.min.js"></script>
  <script type="text/javascript" src="/front/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/front/js/plugins.js"></script>
  <script type="text/javascript" src="/front/js/scripts.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</body>

@stack('js')

</body>

</html>