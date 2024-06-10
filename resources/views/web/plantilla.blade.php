@php($page = $page ?? '')
<!DOCTYPE html>
<html>
<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="web/images/favicon.png" type="">

    <title>Burgers SRL</title>

    <link href="web/css/style.min.css" rel="stylesheet" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="web/css/font-awesome.min.css" rel="stylesheet" />

    <!-- responsive style -->
    <link href="web/css/responsive.css" rel="stylesheet" />
</head>
<body>
    <div class="hero_area">
        <div class="bg-box">
            <img src="web/images/hero-bg.jpg" alt="">
        </div>

        <header>
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="/"><span>Burgers SRL</span></a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class=""> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                @if($page == 'index')
                                <a class="nav-link active" href="/" aria-current="page">Inicio</a>
                                @else
                                <a class="nav-link" href="/">Inicio</a>
                                @endif
                            </li>
                            <li class="nav-item">
                                @if($page == 'takeaway')
                                <a class="nav-link active" href="/takeaway" aria-current="page">Take Away</a>
                                @else
                                <a class="nav-link" href="/takeaway">Take Away</a>
                                @endif
                            </li>
                            <li class="nav-item">
                                @if($page == 'nosotros')
                                <a class="nav-link active" href="/nosotros" aria-current="page">Nosotros</a>
                                @else
                                <a class="nav-link" href="/nosotros">Nosotros</a>
                                @endif
                            </li>
                            <li class="nav-item">
                                @if($page == 'contacto')
                                <a class="nav-link active" href="/contacto" aria-current="page">Contacto</a>
                                @else
                                <a class="nav-link" href="/contacto">Contacto</a>
                                @endif
                            </li>
                        </ul>
                        <div class="user_option">
                            @if(Session::get('cliente_id'))
                            <a href="/micuenta" class="user_link" title="Mi Cuenta">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </a>
                            @else
                            <a href="/login" class="user_link" title="Iniciar Sesión">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </a>
                            @endif
                            <a href="/carrito" class="cart_link" title="Carrito">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                            <!-- <form class="form-inline">
                                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </form> -->
                            <a href="" class="btn btn-primary">Pedir Ahora</a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        @yield('slider')
    </div>

    @yield('contenido')

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-col">
                    <div class="footer_contact">
                        <h4>Contactanos</h4>
                        <div class="contact_link_box">
                            <a href="/#sucursales">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>Sucursales</span>
                            </a>
                            <a href="tel:+541112345678">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>+54 11 1234-5678</span>
                            </a>
                            <a href="mailto:burgers_srl@gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>burgers_srl@gmail.com</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <div class="footer_detail">
                        <a href="#" class="footer-logo">Burgers SRL</a>
                        <p>Seguinos en nuestras redes:</p>
                        <div class="footer_social">
                            <a href="">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <h4>Horarios</h4>
                    <p>Todos los días</p>
                    <p>10 AM - 10 PM</p>
                </div>
            </div>
            <div class="footer-info">
                <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By <a href="https://html.design/" target="_blank">Free Html Templates</a>
                <br><br>
                &copy; <span id="displayYear"></span> Distributed By <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>
                </p>
            </div>
        </div>
    </footer>
    <!-- footer section -->
    <!-- jQuery -->
    <script src="web/js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script src="web/js/bootstrap.js"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- isotope js -->
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <!-- custom js -->
    <script src="web/js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->
</body>
</html>