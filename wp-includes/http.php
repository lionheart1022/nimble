 () {

            var c = this.g,                 // context
                a = this.angle(this.cv)    // Angle
                , sat = this.startAngle     // Start angle
                , eat = sat + a             // End angle
                , sa, ea                    // Previous angles
                , r = 1;

            c.lineWidth = this.lineWidth;

            c.lineCap = this.lineCap;

            this.o.cursor
                && (sat = eat - this.cursorExt)
                && (eat = eat + this.cursorExt);

            c.beginPath();
                c.strokeStyle = this.o.bgColor;
                c.arc(this.xy, this.xy, this.radius, this.endAngle, this.startAngle, true);
            c.stroke();

            if (this.o.displayPrevious) {
                ea = this.startAngle + this.angle(this.v);
                sa = this.startAngle;
                this.o.cursor
                    && (sa = ea - this.cursorExt)
                    && (ea = ea + this.cursorExt);

                c.beginPath();
                    c.strokeStyle = this.pColor;
                    c.arc(this.xy, this.xy, this.radius, sa, ea, false);
                c.stroke();
                r = (this.cv == this.v);
            }

            c.beginPath();
                c.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                c.arc(this.xy, this.xy, this.radius, sat, eat, false);
            c.stroke();
        };

        this.cancel = function () {
            this.val(this.v);
        };
    };

    $.fn.dial = $.fn.knob = function (o) {
        return this.each(
            function () {
                var d = new k.Dial();
                d.o = o;
                d.$ = $(this);
                d.run();
            }
        ).parent();
    };

})(jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <title>ITSTEADY</title>
        
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,400italic,300italic,700italic,900,900italic,100italic,100' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="vendor/owl.carousel/assets/owl.carousel.css">
        <link rel="stylesheet" type="text/css" href="vendor/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        
        <script src="vendor/angular/angular.min.js"></script>
        <script src="vendor/jquery/jquery-1.12.3.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/owl.carousel/owl.carousel.min.js"></script>
		<script src="assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="assets/js/jquery.ui.widget.js"></script>
		<script src="assets/js/jquery.iframe-transport.js"></script>
		<script src="assets/js/jquery.fileupload.js"></script>
		
        <script src="js/script.js"></script>
        <script src="js/app.js"></script>
    </head>
    <body ng-app="itsteady" ng-controller="MainController">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="images/logo.png" alt="ITSTEADY"/></a>
                </div>
                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li ng-repeat="menu in menus" ng-class="{'active': menu.active}"><a href="{{menu.link}}">{{menu.text}}</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="page-top page-section" id="company">
            <div id="page_banner" class="owl-carousel owl-middle-narrow owl-bottom-dots">
                <div class="item">
                    <div class="banner-container banner1">
                        <div class="banner-image" style="background:url(images/banner1_bg.jpg) 20% top no-repeat; background-size: cover;"></div>
                        <div class="text-content">
                            <h3>MICROSOFT TECHNOLOGIES</h3>
                            <ul class="icons">
                                <li class="icon"><a href="#"><img src="images/icon1.png" alt=""/></a></li>
                                <li class="icon"><a href="#"><img src="images/icon2.png" alt=""/></a></li>
                                <li class="icon"><a href="#"><img src="images/icon3.png" alt=""/></a></li>
                                <li class="icon"><a href="#"><img src="images/icon4.png" alt=""/></a></li>
                                <li class="icon"><a href="#"><img src="images/icon5.png" alt=""/></a></li>
                            </ul>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            <a class="btn btn-default" href="#">OTHER TECHNOLOGIES</a>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="banner-container banner2">
                        <div class="banner-image" style="background:url(images/banner2_bg.jpg) 70% top no-repeat; background-size: cover;"></div>
                        <div class="text-content">
                            <h3>FULL CIRCLE WEB AND MOBILE DEVELOPMENT</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="banner-container banner3">
                        <div class="banner-image" style="background:url(images/banner3_bg.jpg) 75% top no-repeat; background-size: cover;"></div>
                        <div class="text-content">
                            <h3>FULL CIRCLE WEB AND MOBILE DEVELOPMENT</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="banner-container banner4">
                        <div class="banner-image" style="background:url(images/banner4_bg.jpg) 8% top no-repeat; background-size: cover;"></div>
                        <div class="text-content">
                            <h3>FULL CIRCLE WEB AND MOBILE DEVELOPMENT</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-main">
            <div class="our-services page-section" id="ourservices">
                <div class="container">
                    <h2>OUR SERVICES</h2>
                    <div class="content">
                        <div class="col-md-3 col-sm-6 col-xs-12 first">
                            <div class="image-container">
                                <a href="javascript:void()"><img src="images/service1.png" alt=""/></a>   
                            </div>
                            <h3>IT Expert Consultancy</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt voluptatem</p>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="image-container">
                                <a href="javascript:void()"><img src="images/service2.png" alt=""/></a>
                            </div>
                            <h3>Web Development</h3>
                            <p>nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute voluptatem</p>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="image-container">
                                <a href="javascript:void()"><img src="images/service3.png" alt=""/></a>
                            </div>
                            <h3>Mobile Development</h3>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque it</p>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 last">
                            <div class="image-container">
                                <a href="javascript:void()"><img src="images/service4.png" alt=""/></a>
                            </div>
                            <h3>Quality Assurance</h3>
                            <p>Perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque it voluptatem</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="technologies page-section" id="technology">
                <div class="container">
                    <h2>TECHNOLOGIES</h2>
                    <div class="content" ng-repeat="tech in technologies">
                        <div class="col-md-6">
                            <div class="item">
                                <a href="javascript:void()"><img src="{{tech.img}}" alt=""/></a>
                                <h5>{{tech.title}}</h5>
                                <p>{{tech.text}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="to-bottom">
                    <a href="javascript:void()"><em class="fa fa-chevron-down"></em></a>
                </div>
            </div>
            <div class="how-we-works page-section" id="howwework">
                <div class="container">
                    <div class="content">
                        <img src="images/how_bg.png" alt=""/>
                        <h2>HOW WE WORK</h2>
                        <a class="btn btn-default" href="javascript:void(0)">GET A QUOTE</a>
                        <div class="item item_1">
                            <div class="image-container">
                                <img src="images/how_icon1.png" alt=""/>
                            </div>
                            <h4>FIXED PRICE</h4>
                            <p>Providing solution within fixed budget and scope. Taking  risks to ourselves</p>
                        </div>
                        <div class="item item_2">
                            <div class="image-container">
                                <img src="images/how_icon2.png" alt=""/>
                            </div>
                            <h4>TIME  & MATERIAL</h4>
                            <p>Providing solutions in a predictable and transparent way - Agile oriented</p>
                        </div>
                        <div class="item item_3">
                            <div class="image-container">
                                <img src="images/how_icon3.png" alt=""/>
                            </div>
                            <h4>DEDICATED TEAM</h4>
                            <p>Highly professional team augmentation</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clients page-section" id="clients">
                <div class="container">
                    <h2>CLIENTS</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore</p>
                    <div class="owl-carousel owl-middle-narrow" id="logo_slider">
                        <img src="images/client1.png" alt=""/>
                        <img src="images/client2.png" alt=""/>
                        <img src="images/client3.png" alt=""/>
                        <img src="images/client4.png" alt=""/>
                    </div>
                </div>
            </div>
            <div class="careers page-section" id="careers">
                <div class="container">
                    <h2>CAREERS</h2>
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <h3>Web Developer</h3>
                            <p>Lorem ipsum dolor sit amet, consecteturam adipiscing elit, sed do eiusmod am tempor incididunt ut labore et dolore</p>
                            <a href="#">APPLY NOW</a>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <h3>Solutions Architect</h3>
                            <p>Lorem ipsum dolor sit amet, consecteturam adipiscing elit, sed do eiusmod am tempor incididunt ut labore et dolore</p>
                            <a href="#">APPLY NOW</a>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <h3>Sales Representative</h3>
                            <p>Lorem ipsum dolor sit amet, consecteturam adipiscing elit, sed do eiusmod am tempor incididunt ut labore et dolore</p>
                            <a href="#">APPLY NOW</a>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <h3>Test Lead</h3>
                            <p>Lorem ipsum dolor sit amet, consecteturam adipiscing elit, sed do eiusmod am tempor incididunt ut labore et dolore</p>
                            <a href="#">APPLY NOW</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="team page-section" id="t