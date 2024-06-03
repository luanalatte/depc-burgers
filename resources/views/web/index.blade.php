@extends('web.plantilla', ['page'=>'index'])
@section('slider')
<section class="slider">
  <div id="customCarousel1" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container ">
          <div class="row">
            <div class="col-md-7 col-lg-6 ">
              <div class="detail-box">
                <h1>LAS MEJORES HAMBURGUESAS</h1>
                <!-- <p>Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.</p> -->
                <div class="btn-box">
                  <a href="" class="btn1">PEDIR AHORA</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-lg-6 ">
              <div class="detail-box">
                <h1>LAS PAPAS MÁS RICAS</h1>
                <!-- <p>Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.</p> -->
                <div class="btn-box">
                  <a href="" class="btn1">PEDIR AHORA</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container ">
          <div class="row">
            <div class="col-md-7 col-lg-6 ">
              <div class="detail-box">
                <h1>EL PAN MÁS CROCANTE</h1>
                <!-- <p>Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.</p> -->
                <div class="btn-box">
                  <a href="" class="btn1">PEDIR AHORA</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <ol class="carousel-indicators">
        <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
        <li data-target="#customCarousel1" data-slide-to="1"></li>
        <li data-target="#customCarousel1" data-slide-to="2"></li>
      </ol>
    </div>
  </div>
</section>
@endsection
@section('contenido')
<section class="offer_section layout_padding-bottom">
  <div class="offer_container">
    <div class="container ">
      <div class="row">
        <div class="col-md-6  ">
          <div class="box ">
            <div class="img-box">
              <img src="web/images/o1.jpg" alt="">
            </div>
            <div class="detail-box">
              <h5>Tasty Thursdays</h5>
              <h6><span>20%</span> Off</h6>
              <a href="">
                Order Now <i class="fa fa-shopping-cart"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6  ">
          <div class="box ">
            <div class="img-box">
              <img src="web/images/o2.jpg" alt="">
            </div>
            <div class="detail-box">
              <h5>Pizza Days</h5>
              <h6><span>15%</span> Off</h6>
              <a href="">
                Order Now <i class="fa fa-shopping-cart"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- food section -->
<section class="food_section layout_padding-bottom">
  <div class="container">
    <div class="heading_container heading_center">
      <h2>Our Menu</h2>
    </div>

    <ul class="filters_menu">
      <li class="active" data-filter="*">All</li>
      <li data-filter=".burger">Burger</li>
      <li data-filter=".pizza">Pizza</li>
      <li data-filter=".pasta">Pasta</li>
      <li data-filter=".fries">Fries</li>
    </ul>

    <div class="filters-content">
      <div class="row grid">
        <div class="col-sm-6 col-lg-4 all pizza">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f1.png" alt="">
              </div>
              <div class="detail-box">
                <h5>Delicious Pizza</h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>$20</h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all burger">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f2.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Delicious Burger
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $15
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all pizza">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f3.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Delicious Pizza
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $17
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all pasta">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f4.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Delicious Pasta
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $18
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all fries">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f5.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  French Fries
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $10
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all pizza">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f6.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Delicious Pizza
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $15
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all burger">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f7.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Tasty Burger
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $12
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all burger">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f8.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Tasty Burger
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $14
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 all pasta">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="web/images/f9.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Delicious Pasta
                </h5>
                <p>
                  Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                </p>
                <div class="options">
                  <h6>
                    $10
                  </h6>
                  <a href="">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="btn-box">
      <a href="">
        View More
      </a>
    </div>
  </div>
</section>

<!-- end food section -->

<!-- about section -->
<section class="about_section layout_padding">
  <div class="container  ">
    <div class="row">
      <div class="col-md-6 ">
        <div class="img-box">
          <img src="web/images/about-img.png" alt="">
        </div>
      </div>
      <div class="col-md-6">
        <div class="detail-box">
          <div class="heading_container">
            <h2>Take Away</h2>
          </div>
          <p>Hacé tu pedido online para retirar en cualquiera de nuestras sucursales.</p>
          <a href="/takeaway">Pedir Ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end about section -->

<section id="sucursales" class="layout_padding">
  <div class="container">
    <div class="heading_container heading_center psudo_white_primary mb_45">
      <h2>SUCURSALES</h2>
    </div>
  </div>
</section>

<!-- client section -->
<!-- 
<section class="client_section layout_padding-bottom">
  <div class="container">
    <div class="heading_container heading_center psudo_white_primary mb_45">
      <h2>
        What Says Our Customers
      </h2>
    </div>
    <div class="carousel-wrap row ">
      <div class="owl-carousel client_owl-carousel">
        <div class="item">
          <div class="box">
            <div class="detail-box">
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
              </p>
              <h6>
                Moana Michell
              </h6>
              <p>
                magna aliqua
              </p>
            </div>
            <div class="img-box">
              <img src="web/images/client1.jpg" alt="" class="box-img">
            </div>
          </div>
        </div>
        <div class="item">
          <div class="box">
            <div class="detail-box">
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
              </p>
              <h6>Mike Hamell</h6>
              <p>magna aliqua</p>
            </div>
            <div class="img-box">
              <img src="web/images/client2.jpg" alt="" class="box-img">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- end client section -->
@endsection