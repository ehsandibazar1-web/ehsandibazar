@extends('site.layout.master')
@section('site.css')
    <style>.main-inten {
    height: auto;
    min-height: 100%
}


ul {
    list-style: none;
}


/*Footer*/
.footer-bottom-bg {
    padding: 16px 0;
    background-color: #fc2779;
    color: #fff;
    font-size: 14px;
}

.footer-bottom-bg ul {
    list-style: none;
    margin: 0;
}

.footer-bottom-bg ul li {
    display: inline-block;
    padding: 10px 18px;
}

.bottom-footer-center {
    margin: auto;
    text-align: center;
}

.footer-bottom-bg ul li a {
    text-decoration: none;
    color: white;

}

.copy-size {
    font-size: 22px;
}

.bottom-footer-center p a {
    text-decoration: none;
    color: white;
    font-weight: bolder;
    margin: 1px;
}

.footer-semi-bottom-bg {
    background-color: #f3f3f3;
    color: #003243;
    padding-top: 40px;
    padding-bottom: 40px;
}

.semi-footer-logo span {
    height: 50px;
    width: 50px;
    background-color: #fc2779;
    color: white;
    border-radius: 50%;
    text-align: center;
    float: left;
    line-height: 50px;
}

.semi-footer-text h3 {
    font-size: 20px;
    border-bottom: 1px solid #ebebeb;
    padding: 4px;
}

.semi-footer-text p {
    padding: 0 8px;
}

.semi-footer-social li {
    display: inline-block;
}

.semi-footer-social li a {
    text-decoration: none;
    color: #333;
    margin: 0px 13px;
    -webkit-transition: color 800ms;
    -moz-transition: color 800ms;
    -ms-transition: color 800ms;
    -o-transition: color 800ms;
    transition: color 800ms;
}

.semi-footer-social li a:hover {
    color: #666;
}

.semi-footer-social li svg {
    font-size: 20px;
}

.semi-footer-item > h3 {
    font-size: 17px;
    padding: 6px 0;
}

.semi-footer-item > h3 svg {
    color: #fc2779
}

.footer-middle-bg {
    background-color: #8c8d94;
    color: #fff;
    padding-top: 45px;
    padding-bottom: 30px;
    text-transform: uppercase;
}

.footer-menu li {
    margin-bottom: 20px;
}

.footer-middle-bg h2 {
    font-size: 20px;
    margin-bottom: 16px;
    font-weight: normal;
}

.footer-menu ul li a {
    text-decoration: none;
    color: white;
    display: inline-block;
    font-size: 13px;
}

.footer-menu ul li a:hover {
    color: #fc2779;
}

.footer-top-bg {
    background-color: #3f414d;
    padding: 40px 0;
    color: #fff;
    font-size: 15px;
}

.footer-contact svg {
    font-size: 31px;
    margin: 10px 3px;
}

.footer-contact > div > p {
    margin: 0;
}

.footer-newsletter p svg {
    font-size: 23px;
    margin-left: 5px;
}

.footer-newsletter-form input[type=email] {
    background: transparent;
    padding: 3px;
    border: none;
    width: 260px;
    border-bottom: 0.5px solid #737373;
    margin: 0 10px;
    color: white;
}

.footer-newsletter-form button {
    background: transparent;
    cursor: pointer;
    border: none;
    padding: 5px 16px;
    color: white;
    border: 1px solid white;
    border-radius: 4px;
}

/* off picture*/
.off-picture {
    padding: 0;
    margin: 30px auto;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    transition: 800ms;
}

.off-picture:hover {
    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.off-picture img {
    width: 100%;
}

.best-brands-item {

    margin: 5px;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    transition: box-shadow 800ms;
}

.best-brands-item img {
    width: 100%;

}

.best-brands-item:hover {
    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.best-brands h3, .spotlight h3, .quick-links h5, top-brands h3, .choice h3, .buy-guide-header h3, .advice h3, .popup h3, .top-brands h3, .discover h3, .exclusive h3, .off-picture1 h3 {
    text-align: center;
    margin: 12px auto;
    color: #3F4158;
    font-size: 25px;
    font-weight: 400;
}

.carousel-indicators {
    bottom: -50px !important;
}

.advice-media {
    height: 316px;
}

.advice-media video, .advice-media img {
    width: 100%;
    height: 100%;
}

.advice-footer ul li svg {
    color: #fc2779;
}

.advice-footer, .spotlight-caption {
    padding: 4px 11px;
}

.advice-footer ul li {
    margin: 0px 0px 0px 8px;
    display: inline-block;
    color: #fc2779;
    font-size: 13px;

}

.advice-footer ul li .grey {
    color: #8c8d94;
    margin: 0 5px;
}

.advice-footer h6, .spotlight-caption h6 {
    font-size: 15px;
    margin: 7px 0;
}

.spotlight, .choice, .exclusive, .off-picture1, .discover, .top-brands, .popup, .advice, .best-brands {
    margin-top: 50px;
}

.advice-footer p, .spotlight-caption p {
    color: #3f414d;
    font-size: 12px;
    margin: 0;
}

.advice-footer a {
    text-decoration: none;
    color: #fc2779;
}

.spotlight-item-img {
    height: 316px;
}

.spotlight-item-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: white;
}

.spotlight-item a {
    text-decoration: none;
    color: black;
}

.top-brand-item {
    text-align: center;

}

.top-brand-image:hover {

    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.top-brand-content {
    color: #3f414d;
    font-size: 15px;
    margin: 11px;
}

.top-brand-image {
    margin: auto;
    width: 77px;
    height: 77px;
    background: white;
    border-radius: 50%;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    transition: box-shadow 800ms;
}

.discover-item {
    margin: 12px 0;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    background-color: white;
}

.discover-image img {
    max-width: 100%;
    height: auto;
}

.discover-content {
    padding-right: 13px;
}

.discover-content a {
    text-decoration: none;
    color: black;
}

.choice .spotlight-item-img {
    height: 300px;
}

.choice .spotlight-caption {
    text-align: center;
    background-color: white;
    padding: 12px;
}

.choice .spotlight-item {
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.32);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.32);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.32);
    margin: 10px 0;
    transition: box-shadow 800ms;
}

.choice .spotlight-item:hover {
    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.buy-guide-header h3 a {
    text-decoration: none;
    float: left;
    font-size: 12px;
    margin-top: 14px;
    vertical-align: middle;
    color: #fc2779;
}

.buy-guide-header h3 a svg {
    font-size: 20px;
    font-style: normal;
}

.exclusive-item-img {
    height: 281px;
}

.exclusive-item-img img {
    width: 100%;
    height: 100%;
}

.exclusive-item {
    position: relative;
    margin-top: 10px;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    transition: box-shadow 800ms;
    margin-bottom: 10px;
}

.exclusive-item:hover {
    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.exclusive-caption {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translate(-50%, 0%);
    background-color: white;
    width: 72%;
    text-align: center;
    padding: 10px;
    border-radius: 4px;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
}

.exclusive-caption p {
    margin: 0;
    color: #3f414d;
    font-size: 10px;
}

.exclusive-caption h6 {
    font-weight: bold;
    color: #fc2779;
    margin: 0;
}

.exclusive-item a {
    text-decoration: none;
    color: black;
}

.carousel-indicators li {
    margin: 0 6px;
    background-color: #b8b8b8;
    border-radius: 50%;
    width: 10px !important;
    height: 10px !important;
    cursor: pointer;
}

.carousel-indicators .active {
    background-color: #fc2779 !important;
}

.carousel {
    margin-bottom: 60px;
}

.carousel-control-next, .carousel-control-prev {
    width: 3%;
}

.header-right-texts p {
    font-size: 14px;
}

.header-left-icons li {
    padding: 0 10px;
    position: relative;
    display: inline-block;
}

.header-left-icons li:after {
    position: absolute;
    content: "|";
    color: black;;
    left: 0;
    top: 0
}

.header-left-icons li:last-child:after {
    content: "";
}

.header-left-icons li svg {
    margin-left: 7px;
    font-size: 15px;
}

.header-left-icons li a {
    text-decoration: none;
    color: black;
    font-size: 15px;
}

.header-left-icons li a span {
    font-size: 12px;
}

.header-right-texts {
    overflow: hidden;
    height: 40px;
    line-height: 40px;
}

.navbar-list li {
    margin: 0px 15px;
    display: inline-block;
    font-size: 13px;
}

.navbar-list li:hover .hidden-brand-sub {
    display: block;
    z-index: 999;
}

.navbar-list li a {
    text-decoration: none;
    color: black;
}

.nav-brand {
    float: right;
    width: 20%;
}

.navbar-list {
    width: 40%;
    float: right;
}

.navbar-list ul {
    margin: 0
}

.navbar-search {
    width: 20%;
    float: right;
}

.navbar-icons {
    width: 20%;
    float: right;
    text-align: left;
}

.navbar-search input[type=search] {
    background: #f4f4f4;
    font-size: 11px;
    background-image: url(/site_themes/css/img/search-icon.png);
    background-size: 28px 28px;
    background-repeat: no-repeat;
    background-position: 99% 2px;
    padding-right: 32px;
    transition: width 500ms, border 500ms;
    padding-top: 0;
    padding-bottom: 0;
    height: 40px;
    vertical-align: middle;
    display: inline;
}

.navbar-search input[type=search]:focus {
    box-shadow: none;
    outline: none;
    /* border: none; */
    width: 130%;
    border: 1px solid #fc2779;
}

.cart-li svg {
    font-size: 20px;
}

.navbar-icons li {
    float: left;
    display: inline-block;
    margin: 0 14px;
}

.navbar-icons li a {
    text-decoration: none;
    color: black;
    font-size: 12px;
}

.navbar-icons li i {
    font-size: 20px;
}

.clear {
    clear: both;
}

.navbar-list li a:hover {
    color: #fc2779;
}

.first-ads img {
    max-width: 100%;
    height: auto;
}

.first-ads {

    padding: 0;
    margin: 20px auto;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -webkit-transition: box-shadow 80ms;
    -moz-transition: box-shadow 800ms;
    -ms-transition: box-shadow 800ms;
    -o-transition: box-shadow 800ms;
    transition: box-shadow 800ms;
}

.first-ads:hover {

    -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

/*** Minimum Navbar **/
.min-nav-button {
    text-align: right;

}

.min-nav-button a {
    text-decoration: none;
    color: #646464;
    display: block;
    margin-top: 7px;
}

.min-nav-button a i {
    font-size: 22px;
}

/* Media Queries */
@media (min-width: 768px) {
    .c-product__feature-item .icons {
        width: 50px;
    }

    .c-product__feature-item span {
        display: inline-block;
        vertical-align: middle;
    }

    .c-product__feature-col {
        -ms-flex: 0 0 20%;
        flex: 0 0 20%;
        max-width: 20%;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .main-header {
        display: block;
    }

    .min-navbar {
        display: none;
    }
}

@media (max-width: 768px) {
    .nav-brand {
        width: 100%;
    }

    .navbar-list {
        width: 100%;
    }

    .main-header {
        display: none;
    }

    .min-navbar {
        display: block;
    }

    .container {
        width: 90%;
    }
}


/* product page style */
.path-list {
    padding: 10px 0
}

.path-list ul {
    margin: 0
}

.path-list li {
    display: inline-block;
}

.path-list li a {
    text-decoration: none;
    color: #888888;
    font-size: 13px;
}

.path-list li::after {
    content: "\f053";
    color: #888888;
    margin-right: 3px;
    margin-left: 5px;
    font-size: 8px;
    font-family: "Font Awesome 5 Pro";
    font-weight: 900;
    vertical-align: middle
}

.path-list li:last-child::after {
    content: none;
}

.product-image-list ul li img {
    display: block;
    margin: auto;
    width: 100%;
}

.product-image-list ul li {
    padding: 9px;
    border: 1px solid #ddd;
    border-radius: 2px;
    margin: 3px;
    height: 50px;
    width: 50px;
}

.product-image-list ul li.active {
    border: 1px solid darkpink;
}

.product-image-main img {

    width: 100%;
}

.product-detail {
    border-right: 1px solid #eee;
    padding: 30px 10px 0 !important;
}

/*contact us style*/
.pink-bg {
    background-color: #d9bb75;
    padding: 17px;
    margin: 0 0 20px auto;
}

.faqs-form {
    margin: auto;
}

.help-category-header {
    text-align: center;
}



.help-cat-item {
    padding: 10px;
    text-align: center;
    border: 1px solid transparent;
}

.help-cat-i i {
    font-size: 46px;
}

.help-cat-item a {
    text-decoration: none;
    color: #333333;
}

.help-cat-item:hover {
    border: 1px solid #dedede;
}

.help-cat-title {
    font-size: 15px;
    color: rgba(252, 39, 121, 1);
    margin-top: 10px;
}

.help-category {
 padding-bottom: 26px;
 
}

.contact-us-block {
    text-align: center;
    border-bottom: 2px solid #375699;
    padding-bottom: 11px;
    margin-bottom: 18px;
}


/* Product */
.search-result-header {
    text-align: center;
    border-bottom: 1px solid #ebebeb;
    padding-bottom: 17px;
}

.search-result-header h3 {
    font-size: 22px;
    color: #3f414d;
    margin: auto;
}

.search-product-header {
    text-align: right;
margin: 34px auto 20px auto;
color: #3f414d;
border-bottom: 4px solid #d9bb75;
font-size: 18px;
font-weight: 800;
color: #000;
padding-bottom: 11px !important;

}

.search-product-header h3 {
    font-size: 18px;
}

.result-item {
    border: 1px solid #ddd;

}

.result-item:hover .result-offerd-button-add {
    display: block;
}

.result-offered-image img {

    display: block;
    margin: auto;
    max-width: 100%;
    width: auto;
}

.result-offered-image {
    overflow: hidden;

}

.result-offered-title h5 {
    font-size: 14px;
    color: #2a2a2a;
    line-height: 1.7;
    padding: 0 10px;
}

.result-offered-title a:hover {
    text-decoration: none;
}

.result-offerd-button-add, .featured {
    display: none;
}

.result-offerd-text {
    text-align: right;
}

.result-offerd-text a {
    text-decoration: none;
    color: #fc2779;
    font-size: 13px;
    font-weight: bold;
    display: inline-block;
    margin: 0 5px;
}

.product-off {
    color: #fc2779;
}

.product-price {
    margin-top: 20px;
}

.product-price-orgin {
    color: #8c8d94;
    text-decoration: line-through;
    font-size: 11px;
    margin-right: 5px
}

.product-price-offed {
    color: #375699;
}

.result-offerd-button-add {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
}

.result-offerd-button-add a {
    display: inline-block;
}

.result-offerd-button-add a:first-child {
    width: 30%;
    float: right;
    padding: 12px;
    text-decoration: none;
    color: #ec1010;
    font-size: 24px;
    border-top: 1px solid #eee;
    background-color: white;
}

.result-offerd-button-add a:last-child {
    width: 70%;
    float: left;
    background-color: #375699;
    padding: 16px;
    text-decoration: none;
    color: white;
}

.size-section-search {
    padding: 21px;
    color: #888888;
}


.result-offered-price ul li {
    display: inline-block;
    margin: 3px;
    padding: 0 8px;
    color: #375699;
    font-size: 18px;
    font-weight: bold;
}

.featured {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: white;
    padding: 10px;
}

.featured ul {
    list-style: disc;
}

.featured a:first-child {
    float: left;
    text-decoration: none;
    color: #fa2779;
}

.feature-hover-list {
    padding: 24px;
}

.loadmore-result a {
    display: block;
    margin: 10px auto;
    text-decoration: none;
    color: white;
    background-color: rgba(3, 2, 26, 0.6);
    /* margin: 10px; */
    padding: 6px 28px;
}

.loadmore-result {
    text-align: center;
}

/* * * * * Product Search Page ----> Filter Section Style * * * * */

.search-product-filter {
    background-color: white;
    padding: 10px;
    margin-top: 10px;

}

.search-product-filter input[type=submit] {
    color: white;
    font-size: 13px;
}

.filter-item-header {
    overflow: hidden;
}



.filter-item-choose-options li {
    margin: 10px;
}

.filter-checkbox-container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.filter-checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom checkbox */
.checkmark {

    position: absolute;
    top: 0;
    right: 0;
    height: 19px;
    width: 19px;
    background-color: #fff;
    border: 1px solid #969696;
    border-radius: 4px;
}

/* On mouse-over, add a grey background color */
.filter-checkbox-container:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.filter-checkbox-container input:checked ~ .checkmark {
    background-color: #5cd285;
    border-color: #5cd285;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.filter-checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.filter-checkbox-container .checkmark:after {
    left: 6px;
    top: 2px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.filter-checkbox-container span:first-child {
    padding-right: 33px;
    font-size: 14px;
}

.filter-item-body {
    display: none;
}

.filter-item-header {
    cursor: pointer;
    
    padding: 5px 2px;
   font-size: 18px;
font-weight: 800;
color: #000;
}

.float-left {
    float: left !important;
}

.filter-item-header span:first-child {
    color: #cbcbcb;
}

.filter-pink {
    color: #375699 !important;
}

.filter-item-header .fas {
    font-size: 11px;
    color: #375699;
}

.filter-applied {
    background-color: white;
    padding: 10px;
    -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
}

.filter-applied-list ul li {
    cursor: pointer;
    display: inline-block;
    border: solid 1px #e1e1e1;
    padding: 5px;
    border-radius: 2px;
    margin: 4px;
}

.filter-applied-list ul li span {
    margin: 4px;
    color: #989898;
}

.filter-applied-header a {
    text-decoration: none !important;
    color: #fc2779 !important;
}

.filter-applied-header {
    margin-bottom: 10px;
    padding: 3px;
    font-size: 14px;
}

#sort-filter {
    margin: 10px auto;
    background-color: white;
    /* border: unset; */
    padding: 7px;
}


/* Customize the label (the container) */

/* Hide the browser's default radio button */

/* Create a custom radio button */
.checkmark2 {
    position: absolute;
    top: 11px;
    right: 0;
    height: 19px;
    width: 19px;
    background-color: white;
    border: 2px solid #979797;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.filter-checkbox-container:hover input ~ .checkmark2 {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.filter-checkbox-container input:checked ~ .checkmark2 {
    background-color: #375699;
    border-color: #acb1aebf;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.filter-checkbox-container input:checked ~ .checkmark2:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.filter-checkbox-container .checkmark2:after {
    top: 9px;
    left: 9px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
}

#sort-filter .filter-item-body li {
    border-bottom: 1px dashed #d8d8d8;
padding: 10px 0px;
}


/* * ** * * * * Product Page Style ** * * * * */


.product-detail h3 {
    font-size: 22px;
    font-weight: bold;
    text-align: center;
}

.rating-product ul li:last-child {
    border-left: none;
}

.product-price ul li {
    display: inline-block;
    margin: 4px 10px;
    padding-left: 10px;
    font-size: 16px;
}
.filter-item-choose-options li a {
  color: #888;
}
.product-price ul li:first-child {
    /*border-left: 1px solid #dadada;*/
}

.product-offer {
    font-size: 14px;
    color: #3f3f3f;
    padding: 14px 0;
}

.size-choose select {
    border: none;
    font-family: IRANSans;
    font-size: 12px;
    /*margin-top: 11px;*/
}

.size-lists li {
    display: inline-block;
}

.size-lists li a {
    text-decoration: none;
    color: #8c8d94;
    border: 1px solid #8c8d94;
    -webkit-border-radius: 40%;
    -moz-border-radius: 40%;
    border-radius: 40px;
    padding: 4px 8px;
    font-size: 11px;
}

.product-add-to-cart a {

    border-radius: 4px;
}

.product-add-to-cart a:hover {
 text-decoration: none !important;
color: white !important;
background-color: #c4a660;
border-color: #c4a660;
}

.product-auction a {
    text-decoration: none !important;
    color: white !important;
    background-color: #e2081f;
    padding: 10px 15px;
    display: inline-block;
    border-radius: 0;
    font-size: 12px;
    float: right;
    cursor: pointer;

}

.product-auction a:hover {
    text-decoration: none !important;
    color: white !important;
    background-color: #e2081f;
}

.product-features {
    display: none;
    overflow: hidden;
    color: #3f414d;
    background-color: #f7f7f7;
    padding: 0 17px;
    -webkit-box-shadow: 0px 0px 3px 0 rgba(0, 0, 0, 0.1);

    -moz-box-shadow: 0px 0px 3px 0 rgba(0, 0, 0, 0.1);

    box-shadow: 0px 0px 3px 0 rgba(0, 0, 0, 0.1);

}

.product-fe-list {
    float: right;
    margin: 7px 0px;
}

.product-fe-list ul li {
    display: inline-block;
    margin-right: 14px;
    font-size: 13px;
}

.product-fe-list ul li i {
    margin-left: 9px;
    border-radius: 50%;
    padding: 10px;
    text-shadow: -1px -1px 2px rgb(0, 0, 0);
    background-color: #8c8d94;
    color: white;
    -webkit-box-shadow: inset 0px 0px 4px 0px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: inset 0px 0px 4px 0px rgba(0, 0, 0, 0.75);
    box-shadow: inset 0px 0px 4px 0px rgba(0, 0, 0, 0.75);
}

.product-fe-list ul {
    margin-bottom: 0;
}

.product-powered {
    float: left;
}

.product-powered p {
    margin-top: 14px;
    margin-bottom: 0px;
    font-size: 12px;
}



.product-add-to-cart {
    /*border-left: 1px solid #eee;*/
    text-align: center;

}

.whole-form {
    border: 1px solid #eee;
    padding: 5px 14px;
    border-radius: 5px;
}

.whole-form input[type=text] {
    border: none;
    font-family: INHERIT;
    font-size: 12px;
}

.whole-form input[type=submit] {
    border: none;
    background-color: white;
    color: #fc2779;
    float: left;
    cursor: pointer;
    font-family: inherit;
    font-size: 12px;
    padding-top: 4px;
}

.delivery-product i {
    margin-left: 8px;
    color: #fc2779;
}

.delivery-product {
    margin-top: 23px;
}

.product-add-to-cart p {
    background-color: #2f314a;
    margin-top: 11px;
    padding: 10px;
    border-radius: 7px;
    color: white;
    width: 62%;
    margin: 10px auto;
}


.product-desc-de__sizeable {
    overflow: hidden;
}

.product-desc-de__sizeable img {
    max-width: 100% !important;
    object-fit: content;
}

.pro-desc-load-more, .display-review-load-more, .see-more-question {
    text-align: center;
    border-top: 1px solid #eee;
}

.pro-desc-load-more a, .display-review-load-more a, .see-more-question a {
    text-decoration: none;
    color: #343637;
    padding: 10px;
    display: inline-block;
}

.pro-desc-load-more a i, .display-review-load-more a i, .see-more-question a i {
    color: #061226;
}

.reviews-qa-tab {
    background-color: #f7f7f7;
    margin: 10px auto;
}

.qa-review-header {

    background-color: white;
    padding: 0;
    border-bottom: 1px solid #eee;
}

.qa-review-header ul li {
    display: inline-block;

}

.qa-review-header ul li:hover a, .qa-review-header ul li.active a {
    color: #fc2779;
    border-bottom: 2px solid #fc2779;
}

.qa-review-header ul li a {
    display: inline-block;
    text-decoration: none;
    color: #60606f;
    padding: 18px;
    border-bottom: 2px solid transparent;
}

.qa-review-header ul {
    margin-bottom: 0px;
}

.review-display-bg {
    background-color: white;
}

.main-rating-display, .main-rating-opend-stars, .write-a-review, .make-new-star {

    float: right;
    width: 50%;
    text-align: center;
    margin-top: 10px;
    padding: 6px;
    margin-bottom: 12px;
}

.star-sec span {

    display: inline-block;
}

.star-sec span:first-child {
    float: right;
}

.star-sec span:last-child {
    float: left;
}

.star-sec .main-progress {
    width: 60%;
}

.progress {
    height: 3px !important;
}

.progress-bar {
    background-color: #3f414d;
}

.make-new-rating {
    overflow: hidden;
}

.hav-pur {
    font-size: 10px;
    color: #3e3e3e;
}

.write-a-review {
    font-size: 13px;
    color: #3e3e3e;
}

.write-a-review a {
    text-decoration: none;
    background-color: #fc2779;
    color: white;
    padding: 15px 23px;
    display: inline-block;
    border-radius: 50px;
    font-size: 14px;
}

.display-review-body {
    background-color: white;
    padding: 17px;
}

.mt43 {
    margin-top: 43px;
}

.display-reviwes-header h3 {
    font-size: 17px;
    margin-bottom: 13px;
    padding-right: 4px;

}

.display-reviews-item {
    border-bottom: 1px solid #3333;
    padding-bottom: 19px;
    margin-bottom: 19px;
    font-size: 14px;
}

.display-reviews-item:nth-last-child(2) {
    margin-bottom: 0;
    border-bottom: 0;
}

.display-review-item-header h4 {
    font-size: 16px;
}

.review-item-dates-rate ul li {
    display: inline-block;
    border-left: 1px solid #eee;
    padding: 0px 6px;
}

.review-item-dates-rate ul li:last-child {
    border-left: none;
}

.review-item-main p a {

    text-decoration: none;
    color: #fc2779;
}

.review-item-main p {
    color: #666;
}

.review-like a {

    text-decoration: none;
    color: #666;
    font-size: 11px;
}

.reviews-tab-body {
    display: none;
}

.q-a-tab-body {
    background-color: white;
    padding: 35px;
}

.q-a-tab-body p {
    text-align: center;
    font-size: 14px;
    color: #232238;
}

.ask-your-q a {
    text-align: center;
    display: block;
    text-decoration: none;
    color: #375699;
    font-size: 19px;
}

.hidden-ask-q form input, .hidden-ask-q form textarea {
    border-color: #eee;
    box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5) !important;
}

.hidden-ask-q label {
    color: #666
}

.hidden-ask-q {
    padding-bottom: 20px;
    border-bottom: 1px solid #dedede;
}

.hidden-ask-q a {
    text-decoration: none;
    color: #375699;
    float: left;
    padding-left: 16px;
}

.hidden-ask-q form button {
    background-color: #375699;
    padding: 10px 40px;
    color: white;
    border-color: #375699;
    box-shadow: none !important;
}

.hidden-question-header {
    border-bottom: 1px solid #dedede;
    overflow: hidden;
    font-size: 14px;
}

.hidden-question-nums {
    float: right;

}

.hidden-question-nums p {
    color: #666 !important;
    margin-bottom: 0px;
}

.hidden-question-sort {
    float: left;
    color: #666;
}

.hidden-question-sort a {
    display: inline-block;
    text-decoration: none;
    color: #666;
    border-left: 1px solid #dedede;
    padding: 3px 9px;
}

.hidden-question-sort a:hover, .hidden-question-sort a.active {
    color: #375699;
    border-bottom: 2px solid #375699;
}

.hidden-question-sort a:last-child {
    border-left: none;
}

.question-item {
    padding: 5px;
    border-bottom: 1px solid #dedede;
    margin-bottom: 16px;
    padding-bottom: 35px;
}

.question-item:nth-last-child(2) {
    border-bottom: none;
    margin-bottom: 0px;
}

.reply-section {
    clear: both;
    margin: 23px;
    background-color: #ddd;
    padding: 24px;
}

.reply-avatar-image, .question-avatar-image {
    width: 60px;
    height: 60px;
    float: right;
    -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    margin-left: 17px;
}

.reply-avatar-image img, .question-avatar-image img {
    width: 100%;
}

.reply-question-item {
    border-bottom: 1px solid #ccc;
    margin-bottom: 23px;
    padding-bottom: 16px;
}

.question-quote p, .reply-main-detail p {
    text-align: right;
}

.question-dependies, .reply-dependies {
    color: #9A9AA3 !important;
}

.question-dependies span, .reply-dependies span {
    font-size: 12px;
    border-left: 1px solid #ebebeb;
    margin: 5px;
    padding-left: 10px;
}

.question-dependies span:last-child, .reply-dependies span:last-child {
    border-left: none;
}

.like-question, .like-question-reply {
    cursor: pointer;
    float: left;
    background-color: #f3f3f3;
    padding: 4px 10px;
    color: #5a5a5a;
    border: 1px solid #ccc;
    border-radius: 3px;

}

.have-a-question {
    text-align: center;
}

.have-a-question a {
    text-decoration: none;
    color: #fc2779;
    text-align: center;
}


/******** about us page ************/
.about-us-page {
       background: #fff url("/site_themes/images/bg-design.png") no-repeat;
       background-size: contain !important;
background-position: bottom;
    padding: 24px;
    margin: 20px auto;
    box-shadow: 0px 1px 2px rgba(170, 170, 170, 0.5);
    position: relative;
    line-height: 35px;
    text-align: justify
}

.page-header-about {
    position: absolute;
    top: 17px;
    right: 0;
    background-color: #d9bb75;
    color: white;
    padding: 10px 42px;
    border-radius: 10px 0px 0px 95px;
}

.about-us-header {
    margin-top: 58px;
    overflow: hidden;
}


/*Contact Us*/
.faqs-search input[type=search] {
    padding: 6px 19px;
    border: none;
    width: 92%;
}

@media (max-width: 580px) {
    .faqs-search input[type=search] {
        width: 83%;
    }
}

.faqs-search button {
    background-color: transparent;
    color: #d9bb75;
    border: none;
    font-size: 21px;
   
}

.faqs-form {
    width: 80%;
}

.faqs-search {
    background-color: white;
    padding: 9px;
    width: 100%;
    border-radius: 6px;
}

.faqs-form {
    width: 80%;
}

.access-menu-item ul li a {
    text-decoration: none;
    color: rgb(139, 139, 139);
    font-size: 15px;
}

.access-menu-item ul li a:hover {
    color: #fc2779;
}

.access-menu-item h5 {
    margin-bottom: 20px;
}

.nyka-access-footer {
    border-bottom: 3px solid #fc2779;
    padding-bottom: 28px;
}

.contact-footer-copy {
    float: right;
}

.contact-footer-socials {
    float: left;
}

.contact-footer-socials ul li {
    display: inline-block;
}

.contact-footer-socials ul li a {
    display: inline-block;
    text-decoration: none;
    color: #fc2779;
    font-size: 20px;
    margin: 0 10px;
}

.contact-footer-socials ul li a:hover {
    color: #343637;
}

.access-menu-item {
    text-align: center;
}

.con-footer {
    padding: 18px 0;
}

.contact-us-logo img {
    display: block;
    float: left;
}

.back-to-help a {
    text-decoration: none;
    color: #373737;
    display: inline-block;
    margin-top: 15px;
}


/* Image Zoomer*/
* {
    box-sizing: border-box;
}

.img-zoom-container {
    position: relative;
}

.img-zoom-lens {
    cursor: crosshair;
    position: absolute;
    border: 1px solid #d4d4d4;
    /*set the size of the lens:*/
    width: 40px;
    height: 40px;
}

#myresult {
    box-shadow: rgba(202, 202, 202, 0.75) 0px 0px 10px 0px;
}

.img-zoom-result {
    visibility: hidden;
    position: absolute;
    top: 0;
    left: 37px;
    width: 50%;
    border: 1px solid #d4d4d4;
    /* width: 300px; */
    height: 500px;
    background-repeat: no-repeat;
}


/** Slider Start **/

.MultiCarousel {
    float: left;
    overflow: hidden;
    width: 100%;
    position: relative;
}

.MultiCarousel .MultiCarousel-inner {
    transition: 1s ease all;
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item {
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item > div {
    text-align: center;
    padding: 10px;
    margin: 10px;
}

/*.MultiCarousel .leftLst, .MultiCarousel .rightLst { position:absolute; border-radius:50%;top:calc(50% - 20px); }
.MultiCarousel .leftLst { left:0; }
.MultiCarousel .rightLst { right:0; }*/

.leftLst {
    cursor: pointer;

    position: absolute;
    cursor: pointer;
    float: right;
    top: 50%;
    left: 0%;
    background-color: white;
    display: inline-block;
    padding: 9px;
    box-shadow: 0px 0px 2px #868686;
}

.rightLst {
    cursor: pointer;

    position: absolute;
    cursor: pointer;
    float: right;
    top: 50%;
    right: 0%;
    background-color: white;
    display: inline-block;
    padding: 9px;
    box-shadow: 0px 0px 2px #868686;
}

.MultiCarousel .leftLst.over, .MultiCarousel .rightLst.over {
    pointer-events: none;
    background: #ccc;
}

.product-similar {
    overflow: hidden;
}

.MultiCarousel-inner .item .result-item {
    margin: 5px !important;
}

/** Slider End **/

/*Hidden Menus*/
.sub-menu-item {
    width: 16.666667%;
    float: right;
    text-align: center;
}

.sub-menu-item li a {
    text-decoration: none;
    color: rgba(3, 2, 26, 0.5);
    font-size: 12px;
}

.sub-menu-item li a:hover {
    color: #fc2779;
}

.sub-menu-item h4 {
    color: rgba(3, 2, 26, 0.8);
    font-size: 13px;
    margin: 20px 0 10px;
}

.sub-menu-item:nth-child(odd) {
    background-color: white;
}

.sub-menu-item:nth-child(even) {
    background-color: #f7f7f7;

}

.header-very-down ul li:hover .sub-menu-hidden {
    display: block
}

.header-very-down > ul > li {
    display: inline-block;
    padding: 0 20px;
    text-align: center;
    font-size: 13px;
    border-bottom: 3px solid transparent;
}

.header-very-down > ul > li:hover {
    border-bottom: 3px solid #fc2779;
    font-weight: 600;
}

.header-very-down > ul > li:hover > a {
    color: #fc2779;
}

.header-very-down ul {
    margin: 0;
}

.header-very-down li a {
    text-decoration: none;
    color: rgba(3, 2, 26, 0.5);
    display: block;
}

.sub-brand-details {
    padding: 30px;
    background-color: #f7f7f7;
}

.sub-brand-header {
    text-align: center;
}

.sub-brand-header li a {
    text-decoration: none;
    color: #333;
    background-color: #d8d8d8;
    display: inline-block;
    padding: 0;
    margin: 0 8px;
    width: 134px;
    height: 36px;
    line-height: 36px;
    text-align: center;
}

.sub-brand-header li {
    display: inline-block;
    margin: 0;
}

.sub-brand-header li:hover a {
    background-color: #fc2779;
    color: white;
}

.sub-brand-detail-img-item img {

    width: 100%;
    max-height: 100px;

}

.sub-brand-detail-img-item {
    margin-bottom: 10px;
}

.sub-brand-search-form form input[type=search] {
    border: 1px solid #eee;
    padding: 8px;
    /* font-size: 19px; */
    width: 100%;
    background-image: url(/site_themes/css/img/search-icon.png);
    background-repeat: no-repeat;
    background-position: 1px 50%;
    background-size: 36px 36px;
    padding-left: 39px;
    height: 40px
}

.sub-brand-list {
    border-top: 1px solid #ddd;
    margin-top: 21px;
    padding-top: 11px;
}

.sub-brand-list li a {
    text-decoration: none;
    color: #212121de;
    font-size: 14px;
    display: inline-block;
    font-weight: 600;
}

.hidden-brand-sub {
    display: none;
    position: absolute;
    background-color: white;
    left: 50%;
    transform: translate(-50%, 0);
}

.search-brand-index {
    padding-top: 25px;
}

.header-very-down {

    line-height: 50px;
    position: relative;
}

.sub-menu-hidden {
    display: none;
    position: absolute;
    background-color: white;
    left: 50%;
    top: 100%;
    transform: translate(-50%, 0);
    line-height: 30px;
}


/**** Hidden Slide ****/
.hidden-side {
    display: none;
    position: fixed;
    top: 0;
    right: 0px;
    background-color: #f2f2f2;
    height: 100%;
    width: 80%;
    z-index: 99999;
    /* padding: 20px; */
    text-align: left;
    box-shadow: 0px 0px 17px -2px #aeaeae;
    padding: 10px;
}

.hidden-side a {
    text-decoration: none;
    color: #585858;
    display: block;
    padding: 8px;

}

.hidden-side ul li:last-child {
    border-bottom: none;
}

.hidden-side ul li {
    list-style: -moz-gujarati;

}

.hidden-main-ul {
    border-bottom: 1px solid #dedede;
}

.hidden-close {
    text-align: right;
}

.hidden-side ul li a:hover {
    background-color: #fc2790;
}

.hidden-main-ul ul ul {
    display: none;
}

.hidden-side ul li a:hover {
    color: white;
}


/**********/
.product-image-like {
    margin-top: 33px;
}

.product-image-like p a {
    text-decoration: none;
    color: #e2081f;
    display: inline-block;
    float: left;
    font-size: 24px;
}

#demo {
    width: 100%;
}

#demo img {
    width: 100%;
}

.brand-sliders-item {
    width: 100%;
}


.brand-slider-header {
    margin: 20px 0 10px;
}

.brand-slider-header a {
    text-decoration: none;
    color: #fc2790;
}

.brand-slider-header i {
    margin-right: 5px;
}

.d25d {
    width: 33%;
    float: left;
}

.d25d img {
    width: 100%;
}

.d75d {
    width: 67%;
    float: left;
    text-align: center;
    padding: 41px;
    background-color: #ddd;
    vertical-align: middle;
    margin-top: 20 p;
    padding-top: 61px;
}

.d75d p {
    font-size: 11px;
}

.d75d a {
    text-decoration: none;
    color: #fc2779;
}

.tak-image img {
    width: 100%;
}

.header-left-icons {
    text-align: left;
    line-height: 40px;
}

.header-top {
    height: 40px;
    background-image: url(/site_themes/css/img/usbar.jpg);
}

.header-top > img {
    width: 100%;
}

.header-left-icons a i {
    color: #2b3e45;
}

.header-left-icons a:hover {
    color: white;
}

.header-left-icons a:hover i {
    color: white;
}

.header-left-icons ul {
    margin: 0;
}

/**** Automata Header text*****/

.header-right-texts p {
    display: none;
    margin: 0;

}

.header-right-texts p.active {
    display: block;
    margin: 0;


}

.header-right-texts p a {
    text-decoration: none;
    color: black;
}

.header-right-texts p a:hover {
    color: white;
}

/******/
.header-mddle {
    border-bottom: solid 1px #e6dede;
    line-height: 62px;
    height: 62px;
}

.header-down {
    overflow: hidden;
}

.navbar-icons li:first-child i {
    font-size: 11px;
    margin-right: 2px;
}

.nav-brand img {
    width: 100px;
    height: 35px;
}

.toTop {
    background-color: #000000a6;
    color: white;
    font-size: 17px;
    /* opacity: 0.85; */
    position: fixed;
    bottom: 20px;
    right: 0;
    border-radius: 5px 0 0 5px;
    padding: 10px 24px;
}

.toTop a {
    text-decoration: none;
    color: white;
}

/**********/
.about-page-image {
    position: relative;
    background-color: #353535;
    height: 500px;
    overflow: hidden;
}

.about-page-image img {
    width: 100%;
    opacity: 0.20;
    height: 100%;
    display: block;
    object-fit: cover;
}

.about-page-abs {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
}

.about-page-abs2 {
    position: absolute;
    top: 93%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    text-shadow: 0px 0px 12px #fff;
}

.about-page-abs2 li {
    display: inline-block;
    margin: 10px;
    text-shadow: none !important;

}

.about-page-abs2 a {
    text-decoration: none;
    color: white;
}

.about25 {
    width: 25%;
    float: right;
    padding: 20px;

}

.about25 img {
    max-width: 100%;
}

.about75 {
    width: 75%;
    float: left;
    padding: 20px;

}
.filter-item-choose-options ul {
  padding-right: 0 !important;
}
.filter-item-body {
    max-height: 280px;
    overflow-y: auto;
}
@media (max-width: 768px ) {
    
    .about25 {
        width: 40%;


    }

    .about75 {
        width: 60%;


    }
}

@media (max-width: 468px ) {
    .about25 {
        width: 100%;


    }

    .about75 {
        width: 100%;


    }
}

.product-image-list li {
    cursor: pointer;
}

.brand-slider .carousel-indicators li {
    width: 8px !important;
    height: 8px !important;
    border-radius: unset !important;
    background-color: black;
}

.black-narrow ul li {
    list-style: none;
    display: inline-block;

    padding: 10px 3.65%;
}

.black-narrow ul li:last-child {
    padding-left: 0;
}

.black-narrow ul li:first-child {
    padding-right: 0;
}

.black-narrow ul li a {
    text-decoration: none;
    color: white;

}

.black-narrow {
    background-color: black;
    margin: 10px auto;
}

.fourImg-item {
    width: 25%;
    float: right;
}

.fourImg-item img {
    width: 100%;
    height: 350px;
}

.fourImg {
    overflow: hidden;
    margin: 50px auto;
}

.about-brand img {
    width: 100%;
}

.shop-all-brand {
    text-align: center;
}

.shop-all-brand a {
    display: inline-block;
    background-color: black;
    text-decoration: none;
    color: white;
    padding: 13px 58px;
    /* border-radius: 5px; */
    margin: 61px;
}

.social-brand {
    border-top: 2px solid #fc2790;
    padding-top: 21px;
    margin: 0;
    font-size: 13px;
}

.brand-social-item ul li {
    display: inline-block;
}

.brand-social-item ul li a {
    text-decoration: none;
    color: white;
    display: inline-block;
    background-color: black;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    padding: 3px;
    text-align: center;
    font-size: 23px;
}

.brand-social-item h5 {
    font-size: 14px;
}

.brand-social-item form {
    width: 72%;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin: auto;
}

.brand-social-item form input[type=email] {
    background-color: transparent;
    border: navajowhite;
    padding: 9px;
    width: 80%;
}

.brand-social-item form button {
    background-color: transparent;
    border: navajowhite;
    width: 18%;
    color: #d7d7d7;
    font-size: 21px;
    padding: 3px;
}

.brand-social-item p i {
    border: 2px solid #262626;
    padding: 4px;
    border-radius: 50%;
    margin-left: 17px;
}

.nyka-access-brand-footer {
    border-top: 2px solid #fc2790;
    margin-top: 13px;
    padding-top: 25px;
}

.no-margin .row {
    margin-right: 0;
    margin-left: 0;
}

.nyka-access-brand-footer .col-md-25 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}

@media (max-width: 768px) {
    .nyka-access-brand-footer .col-md-25 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }
}

.nyka-certificate li {
    display: inline-block;
    margin: 16px 50px;
    font-size: 14px;
}

.nyka-certificate {
    margin-top: 20px;
    background-color: #dedede;
}

.nyka-certificate ul {
    margin: 0;
}

.nyka-certificate li i {
    margin-left: 10px;
}

.nyka-footer-parag {
    margin-top: 43px;
    font-size: 13px;
    text-align: justify;
    color: #000000d1;
}

.nyka-black-brand-footer {
    margin-top: 20px;
    background-color: #333;
    text-align: center;
}

.nyka-black-brand-footer ul li {
    display: inline-block;
    margin: 14px 27px;
}

.nyka-black-brand-footer ul li a {
    text-decoration: none;
    color: white;
}

.nyka-black-brand-footer ul {
    margin: 0;
}

.nyka-black-brand-footer ul a:hover {
    color: #fc2790;
}

.nyka-black-brand-footer p {
    color: #535353;
    font-size: 12px;
    margin-bottom: 2px;
}

.social-brand .col-4:last-child .brand-social-item {
    float: left;
    margin-left: 20px;
}

.social-brand .col-4:nth-child(2) .brand-social-item {
    text-align: center;
}

.nyka-black-brand-footer p a {
    text-decoration: none;
    color: #535353;
}

.brand-item-detail h5 a {
    text-decoration: none;
    color: black;
    font-size: 14px;
}

.brand-item-detail h5 a:hover {
    color: #fc2790;
}

.brand-item-detail p {
    margin-bottom: 2px;
}

.brand-price {
    color: #fc2790;
}

.brand-gram {
    color: #eb7487 !important;
    font-size: 13px;
}

.brand-item {
    position: relative;
}

.brand-item-offer {
    position: absolute;
    top: 15%;
    left: 5%;
}

.brand-item-like {
    position: absolute;
    top: 5%;
    left: 5%;
}

.brand-item-like a {
    text-decoration: none;
    color: #fc2790;
}

.brand-item-add-to-bag {
    display: none;
    position: absolute;
    top: 82%;
    left: 37%;
}

.brand-item-add-to-bag a {
    text-decoration: none;
    color: black;
    font-size: 12px;
    background-color: white;
    display: inline-block;
    border: 1px solid black;
    padding: 3px 5px;
}

.bestseller-brand h5 {
    text-align: center;
}

.bestseller-brand h5 span {
    border-bottom: 1px solid #0f0f10;
}

@media (max-width: 768px) {
    #myresult {
        display: none;
    }
}

.product-desc-header ul li {
    display: inline-block;
    padding: 9px;
    border: 1px solid transparent;
    font-size: 14px;
    margin-bottom: -1px;

}

.product-desc-header ul li.active, .product-desc-header ul li:hover {

    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: rgb(221, 221, 221);
    border-bottom-color: transparent;

}

.product-desc-header ul li.active a, .product-desc-header ul li:hover a {
    color: #375699;
    font-weight: 500;
}

.product-desc-header ul li a {
    text-decoration: none;
    color: black;
}

.product-desc-de {
    display: none;
}

#prd-t {
    display: block;

}

.product-desc-header ul {
    margin: 0;
}

.product-desc-header {
    background-color: white;
    border-bottom: 1px solid #eee;
}

.make-a-review {
    display: none;
    padding: 30px;
}

.make-a-review button {
    text-decoration: none;
    background-color: #fc2779;
    color: white;
    padding: 15px 23px;
    display: inline-block;
    font-size: 14px;
}

.hidden-cart {
    display: none;
    font-size: 13px;
    position: fixed;
    top: 0;
    left: 0;
    width: 300px;
    z-index: 999999;
    background-color: #eeeeee;
    height: 100%;
}

.hidden-cart-close {
    background-color: white;
    padding: 16px;
    text-align: center;
}

.cart-item-pic img {
    width: 100px;
    height: 96px;
    display: block;
    margin: auto;
}

.hidden-cart-item {
    background-color: white;
    margin: 10px;
    padding: 9px;
    box-shadow: 0px 0px 5px #b8b8b8;
}

.bt1 {
    border-top: 1px solid #a6a6a6;
}

.hidden-cart-payment-details h5 {
    text-align: center;
}

.hidden-cart-payment-details-body {
    overflow: hidden;
    background-color: white;
    margin: 10px;
    padding: 5px;
    box-shadow: 0px 0px 5px #b8b8b8;
}

.hidden-cart-payment-details-body li {
    display: block;
    clear: both;
}

.hidden-cart-footer {
    bottom: 0;
    position: absolute;
    width: 100%;
}

.w1 {
    width: 50%;
    background-color: #ffffff;
    padding: 12px;
    text-align: center;
}

.w2 {
    width: 50%;
    background-color: #fc2770;
    padding: 12px;
    text-align: center;
    text-decoration: none;
    color: white;
}

.w2:hover {
    text-decoration: none;
    color: white;
}

.cart-item-details h4 {
    font-size: 16px;
}

.hidden-cart-item .row {
    margin: 0;
}

.hidden-cart-item .row > div {
    padding: 0;
}

.cart-item-delete a {
    text-decoration: none;
    color: #dedede;
}

.hidden-cart-close a {
    text-decoration: none;
    color: #fc2790;
}

.hidden-cart-payment-details h5 {
    font-size: 14px;
}

.min-searc-cart ul {
    margin: 0;
}

.min-searc-cart ul li {
    display: inline-block;
    padding: 6px;
}

.min-searc-cart ul li a {
    text-decoration: none;
    color: #646464;
}

.min-nav-brand img {
    height: 27px;
    text-align: center;
    margin: auto;
    display: block;
    width: 85px;
    margin-top: 3px;
}

.min-navbar {
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: 1px 0px 5px #dedede;
    background-color: #eeeeee;
}

.search-hidden input {
    padding: 0px 20px;
    border-radius: 20px;
}

.xsearch-hidden {
    display: none;
}

.header-very-down ul ul li {
    display: block;
    width: 100%;
    font-size: 17px;
}

.brand-item {
    background-color: white;
    box-shadow: 0px 0px 8px #e6e5e5;
}

.brand-item:hover {
    border-bottom: 2px solid #000;
}

.brand-item:hover .brand-item-add-to-bag {
    display: block;
}

.bestseller-brand {
    margin-bottom: 50px;
    overflow: hidden;

}

.best-brand .col-12, .best-brand .col-sm-6, .best-brand .col-md-3 {
    padding-right: 0px !important;
    padding-left: 0px !important;
}

.copyright-inten {
    margin: 0;
    color: #fff;
    color: #eee;
    font-size: 13px;
}

.bottom-footer-center p.copyright-inten a {
    font-weight: normal;
    color: #eee;
}

/* # Semantic UI 2.4.0 - Rating*/
.ui.progress {
    position: relative;
    display: block;
    max-width: 100%;
    border: none;
    margin: 1em 0 2.5em;
    -webkit-box-shadow: none;
    box-shadow: none;
    background: rgba(0, 0, 0, .1);
    padding: 0;
    border-radius: .28571429rem
}

.ui.progress:first-child {
    margin: 0 0 2.5em
}

.ui.progress:last-child {
    margin: 0 0 1.5em
}

.ui.progress .bar {
    display: block;
    line-height: 1;
    position: relative;
    width: 0%;
    min-width: 2em;
    background: #888;
    border-radius: .28571429rem;
    -webkit-transition: width .1s ease, background-color .1s ease;
    transition: width .1s ease, background-color .1s ease
}

.ui.progress .bar > .progress {
    white-space: nowrap;
    position: absolute;
    width: auto;
    font-size: .92857143em;
    top: 50%;
    right: .5em;
    left: auto;
    bottom: auto;
    color: rgba(255, 255, 255, .7);
    text-shadow: none;
    margin-top: -.5em;
    font-weight: 700;
    text-align: left
}

.ui.progress > .label {
    position: absolute;
    width: 100%;
    font-size: 1em;
    top: 100%;
    right: auto;
    left: 0;
    bottom: auto;
    color: rgba(0, 0, 0, .87);
    font-weight: 700;
    text-shadow: none;
    margin-top: .2em;
    text-align: center;
    -webkit-transition: color .4s ease;
    transition: color .4s ease
}

.ui.indicating.progress[data-percent^="1"] .bar, .ui.indicating.progress[data-percent^="2"] .bar {
    background-color: #d95c5c
}

.ui.indicating.progress[data-percent^="3"] .bar {
    background-color: #efbc72
}

.ui.indicating.progress[data-percent^="4"] .bar, .ui.indicating.progress[data-percent^="5"] .bar {
    background-color: #e6bb48
}

.ui.indicating.progress[data-percent^="6"] .bar {
    background-color: #ddc928
}

.ui.indicating.progress[data-percent^="7"] .bar, .ui.indicating.progress[data-percent^="8"] .bar {
    background-color: #b4d95c
}

.ui.indicating.progress[data-percent^="100"] .bar, .ui.indicating.progress[data-percent^="9"] .bar {
    background-color: #66da81
}

.ui.indicating.progress[data-percent^="1"] .label, .ui.indicating.progress[data-percent^="2"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent^="3"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent^="4"] .label, .ui.indicating.progress[data-percent^="5"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent^="6"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent^="7"] .label, .ui.indicating.progress[data-percent^="8"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent^="100"] .label, .ui.indicating.progress[data-percent^="9"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress[data-percent="1"] .bar, .ui.indicating.progress[data-percent="2"] .bar, .ui.indicating.progress[data-percent="3"] .bar, .ui.indicating.progress[data-percent="4"] .bar, .ui.indicating.progress[data-percent="5"] .bar, .ui.indicating.progress[data-percent="6"] .bar, .ui.indicating.progress[data-percent="7"] .bar, .ui.indicating.progress[data-percent="8"] .bar, .ui.indicating.progress[data-percent="9"] .bar {
    background-color: #d95c5c
}

.ui.indicating.progress[data-percent="1"] .label, .ui.indicating.progress[data-percent="2"] .label, .ui.indicating.progress[data-percent="3"] .label, .ui.indicating.progress[data-percent="4"] .label, .ui.indicating.progress[data-percent="5"] .label, .ui.indicating.progress[data-percent="6"] .label, .ui.indicating.progress[data-percent="7"] .label, .ui.indicating.progress[data-percent="8"] .label, .ui.indicating.progress[data-percent="9"] .label {
    color: rgba(0, 0, 0, .87)
}

.ui.indicating.progress.success .label {
    color: #1a531b
}

.ui.progress.success .bar {
    background-color: #21ba45 !important
}

.ui.progress.success .bar, .ui.progress.success .bar::after {
    -webkit-animation: none !important;
    animation: none !important
}

.ui.progress.success > .label {
    color: #1a531b
}

.ui.progress.warning .bar {
    background-color: #f2c037 !important
}

.ui.progress.warning .bar, .ui.progress.warning .bar::after {
    -webkit-animation: none !important;
    animation: none !important
}

.ui.progress.warning > .label {
    color: #794b02
}

.ui.progress.error .bar {
    background-color: #db2828 !important
}

.ui.progress.error .bar, .ui.progress.error .bar::after {
    -webkit-animation: none !important;
    animation: none !important
}

.ui.progress.error > .label {
    color: #912d2b
}

.ui.active.progress .bar {
    position: relative;
    min-width: 2em
}

.ui.active.progress .bar::after {
    content: '';
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #fff;
    border-radius: .28571429rem;
    -webkit-animation: progress-active 2s ease infinite;
    animation: progress-active 2s ease infinite
}

@-webkit-keyframes progress-active {
    0% {
        opacity: .3;
        width: 0
    }
    100% {
        opacity: 0;
        width: 100%
    }
}

@keyframes progress-active {
    0% {
        opacity: .3;
        width: 0
    }
    100% {
        opacity: 0;
        width: 100%
    }
}

.ui.disabled.progress {
    opacity: .35
}

.ui.disabled.progress .bar, .ui.disabled.progress .bar::after {
    -webkit-animation: none !important;
    animation: none !important
}

.ui.inverted.progress {
    background: rgba(255, 255, 255, .08);
    border: none
}

.ui.inverted.progress .bar {
    background: #888
}

.ui.inverted.progress .bar > .progress {
    color: #f9fafb
}

.ui.inverted.progress > .label {
    color: #fff
}

.ui.inverted.progress.success > .label {
    color: #21ba45
}

.ui.inverted.progress.warning > .label {
    color: #f2c037
}

.ui.inverted.progress.error > .label {
    color: #db2828
}

.ui.progress.attached {
    background: 0 0;
    position: relative;
    border: none;
    margin: 0
}

.ui.progress.attached, .ui.progress.attached .bar {
    display: block;
    height: .2rem;
    padding: 0;
    overflow: hidden;
    border-radius: 0 0 .28571429rem .28571429rem
}

.ui.progress.attached .bar {
    border-radius: 0
}

.ui.progress.top.attached, .ui.progress.top.attached .bar {
    top: 0;
    border-radius: .28571429rem .28571429rem 0 0
}

.ui.progress.top.attached .bar {
    border-radius: 0
}

.ui.card > .ui.attached.progress, .ui.segment > .ui.attached.progress {
    position: absolute;
    top: auto;
    left: 0;
    bottom: 100%;
    width: 100%
}

.ui.card > .ui.bottom.attached.progress, .ui.segment > .ui.bottom.attached.progress {
    top: 100%;
    bottom: auto
}

.ui.red.progress .bar {
    background-color: #db2828
}

.ui.red.inverted.progress .bar {
    background-color: #ff695e
}

.ui.orange.progress .bar {
    background-color: #f2711c
}

.ui.orange.inverted.progress .bar {
    background-color: #ff851b
}

.ui.yellow.progress .bar {
    background-color: #fbbd08
}

.ui.yellow.inverted.progress .bar {
    background-color: #ffe21f
}

.ui.olive.progress .bar {
    background-color: #b5cc18
}

.ui.olive.inverted.progress .bar {
    background-color: #d9e778
}

.ui.green.progress .bar {
    background-color: #21ba45
}

.ui.green.inverted.progress .bar {
    background-color: #2ecc40
}

.ui.teal.progress .bar {
    background-color: #00b5ad
}

.ui.teal.inverted.progress .bar {
    background-color: #6dffff
}

.ui.blue.progress .bar {
    background-color: #2185d0
}

.ui.blue.inverted.progress .bar {
    background-color: #54c8ff
}

.ui.violet.progress .bar {
    background-color: #6435c9
}

.ui.violet.inverted.progress .bar {
    background-color: #a291fb
}

.ui.purple.progress .bar {
    background-color: #a333c8
}

.ui.purple.inverted.progress .bar {
    background-color: #dc73ff
}

.ui.pink.progress .bar {
    background-color: #e03997
}

.ui.pink.inverted.progress .bar {
    background-color: #ff8edf
}

.ui.brown.progress .bar {
    background-color: #a5673f
}

.ui.brown.inverted.progress .bar {
    background-color: #d67c1c
}

.ui.grey.progress .bar {
    background-color: #767676
}

.ui.grey.inverted.progress .bar {
    background-color: #dcddde
}

.ui.black.progress .bar {
    background-color: #1b1c1d
}

.ui.black.inverted.progress .bar {
    background-color: #545454
}

.ui.tiny.progress {
    font-size: .85714286rem
}

.ui.tiny.progress .bar {
    height: .5em
}

.ui.small.progress {
    font-size: .92857143rem
}

.ui.small.progress .bar {
    height: 1em
}

.ui.progress {
    font-size: 1rem
}

.ui.progress .bar {
    height: 1.75em
}

.ui.large.progress {
    font-size: 1.14285714rem
}

.ui.large.progress .bar {
    height: 2.5em
}

.ui.big.progress {
    font-size: 1.28571429rem
}

.ui.big.progress .bar {
    height: 3.5em
}

/*!

*/
.ui.rating {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    white-space: nowrap;
    vertical-align: baseline
}

.ui.rating:last-child {
    margin-right: 0
}

.ui.rating .icon {
    padding: 0;
    margin: 0;
    text-align: center;
    font-weight: 400;
    font-style: normal;
    -webkit-box-flex: 1;
    -ms-flex: 1 0 auto;
    flex: 1 0 auto;
    cursor: pointer;
    width: 1.25em;
    height: auto;
    -webkit-transition: opacity .1s ease, background .1s ease, text-shadow .1s ease, color .1s ease;
    transition: opacity .1s ease, background .1s ease, text-shadow .1s ease, color .1s ease
}

.ui.rating .icon {
    background: 0 0;
    color: rgba(0, 0, 0, .15)
}

.ui.rating .active.icon {
    background: 0 0;
    color: rgba(74, 174, 238, .85)
}

.ui.rating .icon.selected, .ui.rating .icon.selected.active {
    background: 0 0;
    color: rgba(74, 174, 238, .85);
}

.ui.star.rating .icon {
    width: 1.25em;
    height: auto;
    background: 0 0;
    color: rgba(0, 0, 0, .15);
    text-shadow: none
}

.ui.star.rating .active.icon {
    background: 0 0 !important;
    color: #ffe623 !important;
    text-shadow: 0 -1px 0 #ddc507, -1px 0 0 #ddc507, 0 1px 0 #ddc507, 1px 0 0 #ddc507 !important
}

.ui.star.rating .icon.selected, .ui.star.rating .icon.selected.active {
    background: 0 0 !important;
    color: #fc0 !important;
    text-shadow: 0 -1px 0 #e6a200, -1px 0 0 #e6a200, 0 1px 0 #e6a200, 1px 0 0 #e6a200 !important
}

.ui.heart.rating .icon {
    width: 1.4em;
    height: auto;
    background: 0 0;
    color: rgba(0, 0, 0, .15);
    text-shadow: none !important
}

.ui.heart.rating .active.icon {
    background: 0 0 !important;
    color: #ff6d75 !important;
    text-shadow: 0 -1px 0 #cd0707, -1px 0 0 #cd0707, 0 1px 0 #cd0707, 1px 0 0 #cd0707 !important
}

.ui.heart.rating .icon.selected, .ui.heart.rating .icon.selected.active {
    background: 0 0 !important;
    color: #ff3000 !important;
    text-shadow: 0 -1px 0 #aa0101, -1px 0 0 #aa0101, 0 1px 0 #aa0101, 1px 0 0 #aa0101 !important
}

.ui.disabled.rating .icon {
    cursor: default
}

.ui.rating.selected .active.icon {
    opacity: 1
}

.ui.rating .icon.selected, .ui.rating.selected .icon.selected {
    opacity: 1
}

.ui.mini.rating {
    font-size: .78571429rem
}

.ui.tiny.rating {
    font-size: .85714286rem
}

.ui.small.rating {
    font-size: .92857143rem
}

.ui.rating {
    font-size: 1rem
}

.ui.large.rating {
    font-size: 1.14285714rem
}

.ui.huge.rating {
    font-size: 1.42857143rem
}

.ui.massive.rating {
    font-size: 2rem
}

@font-face {
    font-family: Rating;
    src: url(data:application/x-font-ttf;charset=utf-8;base64,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) format('truetype'), url(data:application/font-woff;charset=utf-8;base64,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kIiRi5MI5vdUFfcni4vCBYufhJx8mn2ZepJ3i3aLeoR9fX18g3qLdwiLVAUO+yaLshWL+AQFi5GNkY+RjpCQj5KNj42PjI+LCPfAiwWPi4+Kj4mRiZCHj4aPhY2Fi4UIi/wEBYuEiYWHhoeGhoeFiIiKhoqHi4GLhI6EkQj7EvcN+xL7DQWEhYOIgouHi4eLh42EjoaPiJCHkImRi5IIDov3XRWLko2Rj5Kltq+vuKW4pbuZvYu9i7t9uHG4ca9npWCPhI2Fi4SLhYmEh4RxYGdoXnAIXnFbflmLWYtbmF6lXqZnrnG2h5KJkouRCLCLFaRkq2yxdLF0tH+4i7iLtJexorGiq6qksm64Z61goZZ3kXaLdItnfm1ycnJybX9oiwhoi22XcqRypH6pi6+LopGglp9gdWdpbl4I9xiwFYuHjIiOiI6IjoqPi4+LjoyOjo2OjY6Lj4ubkJmXl5eWmZGbi4+LjoyOjo2OjY6LjwiLj4mOiY6IjYiNh4tzi3eCenp6eoJ3i3MIDov3XRWLko2Sj5GouK+utqW3pbqYvouci5yJnIgIm6cFjY6NjI+LjIuNi42JjYqOio+JjomOiY6KjomOiY6JjoqNioyKjomMiYuHi4qLiouLCHdnbVVjQ2NDbVV3Zwh9cgWJiIiJiIuJi36SdJiIjYmOi46LjY+UlJlvl3KcdJ90oHeie6WHkYmSi5IIsIsVqlq0Z711CKGzBXqXfpqCnoKdhp6LoIuikaCWn2B1Z2luXgj3GLAVi4eMiI6IjoiOio+Lj4uOjI6OjY6NjouPi5uQmZeXl5aZkZuLj4uOjI6OjY6NjouPCIuPiY6JjoiNiI2Hi3OLd4J6enp6gneLcwji+10VoLAFtI+wmK2hrqKnqKKvdq1wp2uhCJ2rBZ1/nHycepx6mHqWeY+EjYWLhIuEiYWHhIR/gH1+fG9qaXJmeWV5Y4Jhiwi53BXb9yQFjIKMg4uEi3CDc3x1fHV3fHOBCA6L1BWL90sFi5WPlJKSkpKTj5aLCNmLBZKPmJqepJaZlZeVlY+Qj5ONl42WjpeOmI+YkZWTk5OSk46Vi5uLmYiYhZiFlIGSfgiSfo55i3WLeYd5gXgIvosFn4uchJl8mn2Seot3i3qGfIJ9jYSLhYuEi3yIfoR+i4eLh4uHi3eGen99i3CDdnt8CHt8dYNwiwhmiwV5i3mNeY95kHeRc5N1k36Ph4sIOYsFgIuDjoSShJKHlIuVCLCdFYuGjIePiI+Hj4mQi5CLj42Pj46OjY+LkIuQiZCIjoePh42Gi4aLh4mHh4eIioaLhgjUeRWUiwWNi46Lj4qOi4+KjYqOi4+Kj4mQio6KjYqNio+Kj4mQio6KjIqzfquEpIsIrosFr4uemouri5CKkYqQkY6QkI6SjpKNkouSi5KJkoiRlZWQlouYi5CKkImRiZGJj4iOCJGMkI+PlI+UjZKLkouViJODk4SSgo+CiwgmiwWLlpCalJ6UnpCbi5aLnoiYhJSFlH+QeYuGhoeDiYCJf4h/h3+IfoWBg4KHh4SCgH4Ii4qIiYiGh4aIh4mIiIiIh4eGh4aHh4eHiIiHiIeHiIiHiIeKh4mIioiLCIKLi/tLBQ6L90sVi/dLBYuVj5OSk5KSk46WiwjdiwWPi5iPoZOkk6CRnZCdj56Nn4sIq4sFpougg5x8m3yTd4txCIuJBZd8kHuLd4uHi4eLh5J+jn6LfIuEi4SJhZR9kHyLeot3hHp8fH19eoR3iwhYiwWVeI95i3mLdIh6hH6EfoKBfoV+hX2He4uBi4OPg5KFkYaTh5SHlYiTipOKk4qTiJMIiZSIkYiPgZSBl4CaeKR+moSPCD2LBYCLg4+EkoSSh5SLlQiw9zgVi4aMh4+Ij4ePiZCLkIuPjY+Pjo6Nj4uQi5CJkIiOh4+HjYaLhouHiYeHh4iKhouGCNT7OBWUiwWOi46Kj4mPio+IjoiPh4+IjoePiI+Hj4aPho6HjoiNiI6Hj4aOho6Ii4qWfpKDj4YIk4ORgY5+j36OgI1/jYCPg5CGnYuXj5GUkpSOmYuei5aGmoKfgp6GmouWCPCLBZSLlI+SkpOTjpOLlYuSiZKHlIeUho+Fi46PjY+NkY2RjJCLkIuYhpaBlY6RjZKLkgiLkomSiJKIkoaQhY6MkIyRi5CLm4aXgpOBkn6Pe4sIZosFcotrhGN9iouIioaJh4qHiomKiYqIioaKh4mHioiKiYuHioiLh4qIi4mLCIKLi/tLBQ77lIv3txWLkpCPlo0I9yOgzPcWBY6SkI+RiwiL/BL7FUcFh4mHioiLh4uIjImOiY6KjouPi4yLjYyOCKP3IyPwBYaQiZCLjwgOi/fFFYu1l6yjoqOjrZe2i5aLl4mYh5eHloWWhJaElIWShZOE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) format('woff');
    font-weight: 400;
    font-style: normal
}

.ui.rating .icon {
    font-family: Rating;
    line-height: 1;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    font-weight: 400;
    font-style: normal;
    text-align: center
}

.ui.rating .icon:before {
    content: '\f005'
}

.ui.rating .active.icon:before {
    content: '\f005'
}

.ui.star.rating .icon:before {
    content: '\f005'
}

.ui.star.rating .active.icon:before {
    content: '\f005'
}

.ui.star.rating .partial.icon:before {
    content: '\f006'
}

.ui.star.rating .partial.icon {
    content: '\f005'
}

.ui.heart.rating .icon:before {
    content: '\f004'
}

.ui.heart.rating .active.icon:before {
    content: '\f004'
}

/*!

/******shafiee style*******/
.help-category-body svg {
    font-size: 2.8em;
}

.product-des-qa h4 {
    margin: 20px 0;
}

.brand-slider-header h4 {
    margin: 0;
}


/**** Login and Register Page ****/
.site-login-register .site-login-register-inner {
    width: 50%;
    margin: auto;
    border: 1px solid #eee;
    padding: 10px 54px;
    box-shadow: -2px 3px 8px 0px #dcdcdc;
    margin: 20px auto;
    border: 1px dashed #e8e8e8;
    background-color: #fff;
}

.site-login-register .site-login-register-inner .site-login-register-inner-logo {
    text-align: center;
}

.site-login-register .site-login-register-inner .site-login-register-inner-logo a img {
    width: 100px;
    height: 56px;
}

.site-login-register .site-login-register-inner .site-login-register-inner-header {
    color: white;
    text-align: center;
    padding: 10px;
}

.site-login-register .site-login-register-inner .site-login-register-inner-header h1 {
    color: black;
    font-size: 20px;
    font-weight: 800;
}

.site-login-register .site-login-register-inner .site-login-register-inner-header p {
    color: black;
    font-weight: 100;
    font-size: 16px;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item {
    margin: 10px auto;
    overflow: hidden;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item label {
    display: block;
    color: #375599;
    font-size: 16px;
    font-weight: bold;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item textarea, .site-login-register .site-login-register-inner .site-login-register-inner-form-item input {
    width: 100%;
    background-color: #eee;
    border: 1px solid #ddd;
    font-size: 15px;
    padding: 5px;
    padding-right: 10px;
    border-radius: 3px;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item button {
    background-color: #375599;
    color: whitesmoke;
    border: 1px solid #eee;
    float: left;
    border-radius: 70px;
    font-size: 13px;
    font-weight: 100;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item button:hover {
    background-color: #375699;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item-check {
    margin: 10px auto;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item-check input {
    width: unset;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item-check span {
    font-size: 11px;
}


/** Sanjari Editing By Hemmat ***/
.search-product {
    width: 100%;
}

.sub-brand-header ul li.active a {
    background-color: #fc2779;
    color: white;
}

html {
    scroll-behavior: smooth;
}

.site-login-register .site-login-register-inner .site-login-register-inner-form-item button {
    padding: 10px 26px;
    cursor: pointer;

}

.spotlight-grey {
    height: 32px;
    color: rgb(252, 39, 121);
}

.discover-image img {

    width: 100px;
    height: 100px;
    object-fit: contain;


}

.header-top {
    position: relative;
    background-image: unset;
}

.header-pos-ansol {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
}

.result-offered-title {
    text-align: center;
    padding-top: 5px;
}

.btn-filter {
    width: 100%;
    background-color: #d9bb75;
}

.btn-filter:hover {
    background-color: silver;
}

/**** Safi Private Style ***/

.clear {
    clear: both;
}

.site-main .site-main-search h1 {
    font-size: 36px;
    font-weight: 600;
    margin-bottom: 20px;
}

.site-main .site-main-search img {
    width: 150px;
    height: 120px;
}

.site-main .site-main-search h2 {
    font-size: 14px;
    font-weight: 800;
}

.site-main .site-main-search .site-main-search-form {
    margin: 25px 0px;
}

.site-main .site-main-search .site-main-search-form .site-main-search-form-item {
    width: 30%;
    float: right;
    border: 1px solid #e1e1e1;
}

.site-main .site-main-search .site-main-search-form .site-main-search-form-item input, .site-main .site-main-search .site-main-search-form .site-main-search-form-item select {
    width: 100%;
    border: none;
    height: 50px;
    padding-right: 20px;
}

.site-main .site-main-search .site-main-search-form .site-main-search-form-item input:focus, .site-main .site-main-search .site-main-search-form .site-main-search-form-item select:focus {
    outline: none;
}

.site-main .site-main-search .site-main-search-form .site-main-search-form-item--btn {
    width: 10% !important;
}

.site-main .site-main-search .site-main-search-form .site-main-search-form-item--btn button {
    height: 50px;
    width: 100%;
    background-color: #7f0b69;
    border: none;
    color: white;
}

.site-main .site-main-category .site-main-category-item {
    text-align: center;
    /* border: 1px solid #eee; */
    padding: 10px;
    box-shadow: 0px 0px 11px -3px #9f9f9f;
    background-color: #eee;
    margin-top: 15px;
}

.site-main .site-main-category .site-main-category-item-img {
    padding-bottom: 10px;
}

.site-main .site-main-category .site-main-category-item-img img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.site-main .site-main-category .site-main-category-item-desc-title {
    font-size: 15px;
}

.site-main .site-main-category .site-main-category-item-desc-title a {
    text-decoration: none;
    color: #888;
}

.site-main .site-main-category .site-main-category-item-desc-title a:hover {
    color: #7f0b69;
}

.site-main-search {
    text-align: center;
}

.site-blog__sidebar__item__header fieldset {
   border-bottom: 4px solid #d9bb75;
}

.site-blog__sidebar__item__header fieldset legend {
    font-size: 18px;
    font-weight: 800;
    color: #000;
    margin-top: 20px !important
}

.site-blog {
    background-color: #f6f6f6;
    overflow: hidden;
}

.site-blog .site-blog__box {
    box-shadow: 2px 5px 14px -5px #dbdbdb;
    background-color: white;
    margin: 20px auto;
    padding: 22px;
  
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item {
    margin-bottom: 20px;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__cat__list li {
    display: block;
    border-bottom: 1px dashed #d8d8d8;
    padding: 10px 0px;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__cat__list li a {
    text-decoration: none;
    color: #888;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__cat__list li a:hover {
    color: #7f0b69;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item {
    border-bottom: 1px dashed #d8d8d8;
    padding: 10px 0px;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__desc p {
    margin-bottom: 7px;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__desc__title {
    font-size: 15px;
    font-weight: 800;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__desc__title a {
    text-decoration: none;
    color: #696969;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__desc__title a:hover {
    color: #7f0b69;
}

.site-blog .site-blog__sidebar .site-blog__sidebar__item__body .site-blog__sidebar__item__body__ads__list__item__desc__place {
    font-size: 11px;
    color: #888;
}

.site-blog__posts__item {
    margin: 12px auto;
}

.site-blog__posts__item__image {
    position: relative;
}

.site-blog__posts__item__image img {
    width: 100%;
    height: 282px;
    object-fit: cover;
}

.site-blog__posts__item__image .site-blog__posts__item__cat {
    position: absolute;
    top: 0;
    right: 0;
}

.site-blog__posts__item__image .site-blog__posts__item__cat__label {
  background-color: #d9bb75;
color: #1d1d1d;
font-size: 17px;
padding: 5px 10px;
font-weight: 500;
}

.site-blog__posts__item__desc {
    padding: 10px;
}

.site-blog__posts__item__desc__title {
    font-size: 16px;
}

.site-blog__posts__item__desc__title a {
    text-decoration: none;
    color: #3a3a3a;
    font-weight: 600;
}

.site-blog__posts__item__desc__title a:hover {
    color: #7f0b69;
}

.site-blog__posts__item__desc__list li {
    display: inline-block;
}

.site-blog__posts__item__desc__list li a {
    text-decoration: none;
    color: #888;
}

.site-blog__posts__item__desc__list li a:hover {
    color: #888;
}

.site-blog__posts__item__desc__list li {
    font-size: 11px;
    color: #666;
    margin: 0px 3px;
}

.site-blog__posts__item__desc__list li:after {
    content: '.';
    font-size: 17px;
}

.site-blog__posts__item__desc__list li:last-child:after {
    display: none;
}

.site-blog__posts__item__desc__detail {
    font-size: 15px;
    color: #666;
    text-align: justify;
}

.site-blog__posts__item {
    overflow: hidden
}

.site-blog__posts__item a {
    overflow: hidden;
    display: block;
}

.site-blog__posts__item a img {
    transition: all .4s ease-in-out;
}

.site-blog__posts__item:hover img {
    transform: scale(1.1);
}

.site-blog__sidebar__item__body__ads__list__item__image {
    transition: all .6s ease-in-out;
}

.site-blog__sidebar__item__body__ads__list__item__image:hover {
    transform: scale(1.1);
}

.site-blog-post__path {
    margin: 10px 0px;
}

.site-blog-post__path ul li {
    display: inline-block;
}

.site-blog-post__path ul li a {
    text-decoration: none;
    color: #666;
}

.site-blog-post__path ul li a:hover {
    color: #7f0b69;
}

.site-blog-post__path ul li {
    font-size: 13px;
}

.site-blog-post__path ul li:after {
    content: '.';
}

.site-blog-post__path ul li:last-child:after {
    display: none;
}

.site-blog-post__box__body {
    border-bottom: 1px dashed #cccccc;
    padding-bottom: 10px;
}

.site-blog-post__box__body__image {
    margin-bottom: 10px;
}

.site-blog-post__box__body__image img {
    width: 100%;
    /*height: 300px;*/
    object-fit: cover;
}

.site-blog-post__box__body__cat {
    margin-bottom: 10px;
}

.site-blog-post__box__body__cat ul li {
    display: inline-block;
}

.site-blog-post__box__body__cat ul li a {
    text-decoration: none;
    color: white;
}

.site-blog-post__box__body__cat ul li a:hover {
    color: white;
}

.site-blog-post__box__body__title {
    margin-bottom: 10px;
}

.site-blog-post__box__body__title h1 {
    
    font-weight: 600;
}

.site-blog-post__box__body__info {
    margin-bottom: 10px;
}

.site-blog-post__box__body__info ul li {
    display: inline-block;
}

.site-blog-post__box__body__info ul li a {
    text-decoration: none;
    color: white;
}

.site-blog-post__box__body__info ul li a:hover {
    color: white;
}

.site-blog-post__box__body__detail {
    margin-bottom: 10px;
}

.site-blog-post__box__body__detail p {
    text-align: justify;
    font-size: 17px;
    font-weight: 300;
    color: #666;
}

.site-blog-post__box__body__detail a {
    color: #8a6d1f;
    font-weight: 700;
}

.site-blog-post__box__body__share li {
    display: inline-block;
}

.site-blog-post__box__body__share li a {
    text-decoration: none;
    color: white;
}

.site-blog-post__box__body__share li a:hover {
    color: white;
}

.site-blog-post__box__body__tags ul li {
    display: inline-block;
}

.site-blog-post__box__body__tags ul li a {
    text-decoration: none;
    color: white;
}

.site-blog-post__box__body__tags ul li a:hover {
    color: white;
}

.site-blog-post .site-blog-post__last .site-blog-post__last__item {
    border-bottom: 1px dashed #ddd;
    padding: 6px 0px;
    margin-bottom: 6px;
}

.site-blog-post .site-blog-post__last .site-blog-post__last__item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.site-blog-post .site-blog-post__last .site-blog-post__last__item__desc h5 {
    font-size: 15px;
}

.site-blog-post .site-blog-post__last .site-blog-post__last__item__desc h5 a {
    text-decoration: none;
    color: #666;
}

.site-blog-post .site-blog-post__last .site-blog-post__last__item__desc h5 a:hover {
    color: #7f0b69;
}

.site-blog-post__related {
    padding-top: 20px;
}

.site-blog-post__comment__form__body__item {
    margin: 10px 0px;
}

.site-blog-post__comment__form__body__item label {
    display: block;
}

.site-blog-post__comment__form__body__item input, .site-blog-post__comment__form__body__item textarea {
    border: 1px solid #d6d6d6;
    padding: 10px;
    width: 100%;
    font-size: 11px;
    color: #c6c6c6;
    border-radius: 30px;
}

.site-blog-post__comment__form__body__item input:focus, .site-blog-post__comment__form__body__item textarea:focus {
    outline: none;
}

.site-blog-post__comment__list__body__item__image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.commenti {
    font-size: 13px;
    font-weight: 100;
}

.site-ads-list {
    margin-top: 20px;
}

.site-ads-list .site-ads-list-item {
    -webkit-box-shadow: 0px 0px 3px 0px #d4d4d4;
    -moz-box-shadow: 0px 0px 3px 0px #d4d4d4;
    box-shadow: 0px 0px 19px -2px #c6c6c6;
    margin: 10px 0px;
    background-color: #eee;
}

.badge-primary {
    color: #fff;
    background-color: #7f0b69;
}

.badge-danger {
    color: #fff;
    background-color: #f78e3e;
}

.site-ads-single-page-body-other .btn:hover {
    color: white;
}

.btn-site {
   color: #1b1b1b !important;

}


/* Added Style By Hemmat --- 2 Bahman 98 */
.navbar-icons li i, .navbar-icons li svg {
    margin-left: 5px;
}


.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    padding-right: 10px;
    padding-left: 10px;
}

.quick-links h5 {
    font-size: 14px;
}

.quick-links .spotlight-grey {
    color: rgb(63, 65, 88);
}


.product-select-size p {
    font-size: 14px;
    font-weight: 100;
}

.product-select-size select {
    border: 1px solid #e2e2e2;
    /* padding: 10px; */
    border-radius: 50px;
    color: #868686;
    background-color: #f3f3f3;
    font-size: 12px;
}

.badge-success {
    background-color: #d2ad68;
}


.product-des-qa h4 {

    margin: 20px 0;
    font-size: 20px;
    font-weight: 400;
    margin-top: 47px;
}

.loadmore-result button {
    font-size: 13px;
    color: #888;
    padding: 5px 32px;
    border: 1px solid;
}

.loadmore-result button:hover {
  font-size: 13px;
color: white;
background-color: #d9bb75;
padding: 5px 32px;
border-color: #d9bb75;
}

.sticky-filter {
    position: sticky;
    top: 170px;
}

.pcomment-form .alert {
    overflow: hidden;
}


.carousel-item, .carousel-item img {
    height: 100%;
    object-fit: contain;
    display: block;
}

@media (max-width: 768px) {
    .site-login-register .site-login-register-inner {
        width: 47%;
    }

    .carousel-inner {
        height: 313px;
    }

}

.min-searc-cart {
    text-align: left;
}

.hidden-side-main {
    height: 100%;
    overflow-y: auto;
}

.hidden-main-ul li > span {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.hidden-main-ul ul {
    text-align: right;
}

.hidden-side ul li {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-bottom: 1px solid #d9d9d9;
}

.link-form {
    color: #ffffff;
    font-size: 16px;
}

.blog_badge_category {
    background-color: #d9bb75  !important;
    font-size: 95% !important;
}

.suggestion {
    height: 44px;
}

.bg-suggetion-auction {
    background-color: #212529;
    text-align: center;
    padding: 10px 0;
    font-size: 20px;
    position: absolute;
    width: 100%;
}

.balavar::-webkit-scrollbar-track {
    background-color: #fff;
}

.balavar::-webkit-scrollbar {
    width: 4px;
    background-color: #F5F5F5;
}

.balavar::-webkit-scrollbar-thumb {
    background: linear-gradient(0deg, #375699, #375699);
    border-radius: 9px;
}

.table td, .table th {
    padding: 5px;
    text-align: center;
    vertical-align: middle;
}

#ajax-price {
font-weight: bold;
font-size: 20px;
}

#ajax-priceDiscount {
    color: #375699;
    font-weight: bold;
    font-size: 21px;
    margin-right: 20px;
}

.product-detail h3 {
    text-align: right;
}

.container.path-list {
    margin-bottom: 10px;
}

.latest-suggestion table tr {
    cursor: pointer;
}

.brand-slider {
    background-color: #fff;
}

.latest-suggestion table tr:nth-child(2n) {
    background-color: #eee;
}

.float-right {
    float: right !important;
}

.result-offered-image img {
    -webkit-transition: all 600ms ease;
    -moz-transition: all 600ms ease;
    -ms-transition: all 600ms ease;
    -o-transition: all 600ms ease;
    transition: all 600ms ease;
}

.result-item:hover .result-offered-image img {
    opacity: 0.90;
    -webkit-transform: scale(1.1, 1.1);
    -ms-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
    -webkit-transition: all 600ms ease;
    -moz-transition: all 600ms ease;
    -ms-transition: all 600ms ease;
    -o-transition: all 600ms ease;
    transition: all 600ms ease;
}

.product-price-offed {
    font-size: 18px;
    font-weight: bold;
}

@media screen and (max-width: 767.99px) {
    .search-product {
  background-color: #fff;
}
    .pt-xs-0 {
  padding-top: 0 !important;
}
    .faqs-form {
  width: 100% !important;
}
    .site-login-register .site-login-register-inner {
        width: 95%;
        background-color: #fff;
    }
}

.site-login-register-inner-form-item a {
    color: #000;
    font-weight: bold;
}

.categori-desc {
    line-height: 2;
    font-size: 15px;
    text-align: justify;
}

.cost-rang {
    text-align: center;
}

.cost-rang li {
    display: inline-block;
}

.min-value, .max-value {
    border: 1px solid #eee;
    width: 97px;
    text-align: center;
}

.noUi-connect {
    background: #375699 !important;
}

.double-handle-slider.noUi-target.noUi-ltr.noUi-horizontal {
    width: 80%;
    margin: 45px auto 0;
}

.c-product__feature-item {
    color: #000;
    font-size: 12px;
}

.c-product__feature-item .icons img {
    width: 40px !important;
}

.product-detail, .menu-item ul {
    list-style: none;
    padding: 0;
    color: #55565a;
    font-size: 12px;
    line-height: 1.833;
}

.product-detail p {
    color: #000;
    font-weight: 400;
}

.product-detail li, .menu-item ul li {
    margin: 5px 0;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-flex-align: center;
    align-items: center;

}

.product-detail li::before, .menu-item ul li::before {
    content: "";
    width: 5px;
    height: 5px;
    background: #ccc;
    vertical-align: middle;
    margin-left: 5px;
    border-radius: 50%;
}

.detail ul li span:nth-child(1) {
    font-weight: 500 !important;
    display: inline-block;
    color: #000;
    margin-left: 10px;
}

#showmenu {
    position: relative;
    padding-right: 13px;
    color: #1ca2bd;
    line-height: 2;
    font-size: 12px;
    display: inline-block;
    cursor: pointer;
}

#showmenu::before {
    left: 0;
    right: 12px;
    top: 50%;
    margin-top: .85em;
    content: "";
    position: absolute;
    border-bottom: 1px dashed #1ca2bd;
}

#showmenu::after {
    content: '+';
    color: #1ca2bd;
    background: none;
    width: unset;
    height: unset;
    position: absolute;
    right: 0;
    vertical-align: middle;
    margin-left: 5px;
    border-radius: 50%;
}

.close-box {
    color: #1ca2bd;
    padding: 0;
    line-height: 2;
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.close-box::before {
    content: '-';
    color: #1ca2bd;
    background: none;
    width: unset;
    height: unset;
    vertical-align: middle;
    margin-left: 5px;
    border-radius: 50%;
}

.close-box::after {
    left: 0;
    right: 0;
    top: 50%;
    margin-top: .85em;
    content: "";
    position: absolute;
    border-bottom: 1px dashed #1ca2bd;
}

.detail p {
    text-align: right;
}

.c-product__title {
    font-size: 22px;
    line-height: 1.467;
    color: #494949;
    font-weight: bold;
    padding-top: 10px;
}

.row.row-pro-name {
    border-bottom: 1px solid #f4f4f4;
    margin-bottom: 20px !important;
}

.c-product__directory li {
    margin-left: 28px;
    display: inline-block;
}

.btn-link-spoiler {
    color: #bfa465;
    padding: 0;
    line-height: 2;
    display: inline-block;
    position: relative;
}

.link-view li {
    display: inline-block;
    vertical-align: middle;

}

.row.row-pro-name {
    align-items: center;
}

@media (min-width: 992px) {
    .link-view {
      text-align: left !important;
padding-bottom: 5px;
    }
}

.v-link {
    color: #375699;
    font-weight: bold;
    display: inline-block;
    margin-left: 2px;
}

#result-description {
    color: #8e8a8a;
    line-height: 2;
}

.favo, .btn-group.btn-share {
    background-color: #375699;
    line-height: 3;
    text-align: center;
    width: 100%;
}

.favo a, .btn-group.btn-share a {
    color: #fff !important;
    font-size: 15px;
    font-weight: bold;
    display: block;
    width: 100%;
    text-align: center;
}

.det-like i {
    font-size: 25px;
    vertical-align: middle;
    margin-left: 5px;
}

.show-gallery.icon-gallery {
    width: 60px;
    height: 60px;
    font-size: 28px;
    color: #777;
    background-color: transparent !important;
    border-radius: 3px;
    border: 1px solid #e2e2e2;
    line-height: 56px;
    cursor: pointer;
}

.row.row-feature {
    margin-top: 40px;
    border-top: 1px solid #eee;
    padding-top: 20px;
    padding-bottom: 20px;
}

.det-like:hover {
    color: #fff !important;
}

#SocialShare i {
    font-size: 20px;
    vertical-align: middle;
    margin-left: 5px;
}

.favo:hover, .btn-group.btn-share:hover {
    background-color: #d2a34c;
}

.site-low a {
    color: #375599;
    margin: 0 4px;
    font-weight: bold;
    font-size: 14px;
    text-decoration: underline !important;
}

.link-reg {
    color: #375599 !important;
    margin: 0 4px;
    font-weight: bold;
    font-size: 14px;
    text-decoration: underline !important;
}

.item-share li {
    display: inline-block;
    margin: 0 10px;
}

.item-share {
    text-align: center;
}

.sharing-shortlink input {
    background-color: #e6e6e6;
    cursor: not-allowed;
    display: block;
    box-sizing: border-box;
    width: 100%;
    height: 2.4375rem;
    margin: 0 0 1rem;
    padding: .5rem;
    border: 1px solid #cacaca;
    border-radius: 0;
    background-color: #fefefe;
    box-shadow: inset 0 1px 2px hsla(0, 0%, 4%, .1);
    font-family: inherit;
    font-size: 1rem;
    font-weight: 400;
    color: #0a0a0a;
    -webkit-transition: border-color .25s ease-in-out, -webkit-box-shadow .5s;
    transition: box-shadow .5s, border-color .25s ease-in-out;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.sharing-panel > div {
    padding: 0 25px;
}

.clearfix {
    display: block;
}

.sharing-socials ul {
    float: left;
}

#lineModalLabel {
    width: 100%;
    text-align: center;
}

.sharing-shortlink {
    margin: 20px 0 13px;
    background: #f5f6f7;
    padding: 7px 25px !important;
}

#shareModal .modal-dialog {
    flex: 0 1 auto;
    max-height: calc(100% - 96px);
    height: 100%;
    vertical-align: middle;
    align-items: center;
    display: flex;
}

#shareModal .modal-content {
    vertical-align: middle;
    min-height: 300px;
}

.link-pro li {
    display: block !important;
    margin-bottom: 20px;
    width: 200px;
}

@media (max-width: 991.99px) {
    .c-product__feature-item {

        width: 100% !important;
        display: flex;
        align-items: center;
    }

    .c-product__feature-col {
        width: 49%;
        text-align: center;
        margin-bottom: 10px;
    }

    .link-pro li {
        margin: 0 auto 20px !important;
    }
}

.site-blog__sidebar__item__body__ads__list__item .row {
    align-items: center;
}

.card.crd-info {
    background-color: #f5f5f5;
    border-color: #f5f5f5;
    border-radius: 0;
}

.product-thumb .button-group button {
    width: 60%;
    border: none;
    display: inline-block;
    float: left;
    background-color: #eee;
    color: #888;
    line-height: 38px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
}

.product-thumb .button-group button + button {
    width: 20%;
    border-left: 1px solid #ddd;
}

element.style {
}

.product-thumb .button-group button + button {
    width: 20%;
    border-left: 1px solid #ddd;
}

.product-thumb .button-group button {
    width: 60%;
    border: none;
    display: inline-block;
    float: left;
    background-color: #eee;
    color: #888;
    line-height: 38px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
}

.product-thumb .button-group button + button {
    width: 20%;
    border-left: 1px solid #ddd;
}

.product-thumb .button-group button {
    width: 60%;
    border: none;
    display: inline-block;
    float: left;
    background-color: #eee;
    color: #888;
    line-height: 38px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
}

.button-group svg {
    width: 14px;
    display: inline-block;
}

.product-thumb .button-group button:hover {
    color: #444;
    background-color: #ddd;
    text-decoration: none;
    cursor: pointer;
}

.result-item {
    border: 1px solid #ddd;
    position: relative;
    background-color: #fff;
    padding: 7px 7px 0 4px;
}

.col-12.col-lg-3.col-md-4.item.product-thumb.pr-2.ol-2 {
    padding-right: 0 !important;
}

.item.product-thumb {
    margin-bottom: 20px;
}

.carousel-item, .carousel-item img {
    height: 100%;
    object-fit: contain;
    display: block;
    max-height: 500px;
    width: 100%;
}

.row-category .px-2 {
    padding-left: .25rem !important;
    padding-right: .25rem !important;
}

.row-category .item {
    background-color: #fff;
    -webkit-transition-duration: .1s;
    transition-duration: .1s;
    box-shadow: 0 1rem 4rem rgba(0, 0, 0, 0.075);
    margin-bottom: .7rem;
    border-radius: 5px;
    padding: 15px;
}.position-relative {
     position: relative!important;
 }.lbl-pro {
      height: 65px;
      overflow: hidden;
      position: absolute;
      right: 0;
      top: 0;
      width: 65px;
      z-index: 1;
  }.lbl-pro span {
       background: #ff7c49 none repeat scroll 0 0;
       color: #fff !important;
       display: block;
       font-size: 12px;
       font-weight: 600;
       height: 85px;
       line-height: 15px;
       padding-top: 65px;
       position: absolute;
       right: -42px;
       text-align: center;
       text-transform: uppercase;
       top: -42px;
       transform: rotate(45deg);
       width: 85px;
   }.btn-favo {
line-height: 1;
        background-color: transparent;
        width: 32px;
        height: 32px;
        border: 1px solid #e5e5e5;
        border-radius: 100%;
        font-size: 18px;
        color: #c7c2c2;
        z-index: 2;
    }
@media screen and (min-width: 768px){
    .img-pro a {
        height: 197px !important;
    }
}

.img-pro a {
    padding: 0 !important;
}

@media (min-width: 1200px)
{
    .h3, h3 {
        font-size: 1.75rem;
    }
}

.product-box-text h3 a {
    font-size: 12px;
    direction: rtl;
    margin: 10px 0;
    height: 45px;
    line-height: 22px;
    color: #535353;
    max-width: 90%;
    overflow: hidden;
    display: block;
    text-align: center;
}
.product-box-price-row {
    height: 55px;
}
.add-crd i {
    font-size: 19px;
    -webkit-transform: scaleX(-1);
    transform: scaleX(-1);
    color: #000;
}
.text-end {
    text-align: left!important;
}
.old-cost {
    font-size: 13px;
    color: #4c4c4c;
    font-weight: 400;
    text-decoration: line-through;
}
.offer-mob {
    background-color: #fb3449;
    color: #fff;
    border-radius: 25px;
    padding: 0 8px;
    margin-right: 5px;
}
.cost-total {
    color: #000;
    font-weight: bold;
    font-size: 16px;
}
.unit {
    color: #9c9c9c;
    font-size: 10px;
    font-weight: 500;
}
.inner-section {
    padding-top: 5px;
    padding-bottom: 5px;
    min-height: 300px;
}
.product-top {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 1rem 4rem rgba(0, 0, 0, 0.075) !important;
    margin-bottom: 15px;
    margin-top: 15px;
    padding: 9px 0;
}
.product-options li button {
    background-color: transparent !important;
    vertical-align: top;
    z-index: 6;
    border: none;
    width: 24px;
    height: 24px;
    text-align: center;
    background: none;
    font-size: 20px;
}
.gallery-thumb-items {
    list-style: none;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    border-top: none;
    padding: 0;
}
.gallery-thumb-items .product-thumb:nth-child(-n+4), .gallery-thumb-items .product-thumb:nth-child(-n+5) {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
.gallery-thumb-items .product-thumb {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
    -ms-flex: 0 0 18%;
    flex: 0 0 18%;
    padding: 18% 0 0;
    max-width: none;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
    cursor: pointer;
    height: 68px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    display: none;
    position: relative;
}
.gallery-thumb-items .product-thumb .thumb-wrapper {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 10px;
}
.gallery-thumb-items .product-thumb img {
    max-width: 100%;
    max-height: 100%;
    vertical-align: top;
    opacity: .7;
    position: relative;
}
@media screen and (min-width:768px) {
    .img-pro a {
     padding: 10px;
     text-align: center;
     display: -webkit-box;
     display: -ms-flexbox;
     display: flex;
     -webkit-box-align: center;
     -ms-flex-align: center;
     align-items: center;
     -webkit-box-pack: center;
     -ms-flex-pack: center;
     justify-content: center;
     -ms-flex-negative: 0;
     flex-shrink: 0;
     max-width: 100%;
     position: relative;
     height: 223px;
 }
.img-pro img {
    max-width: 100%;
    max-height: 100%;
    -o-object-fit: fill;
    object-fit: fill;
    height: 100% !important;
}
    .product-price-area {
        background-color: #f8f8f8;
        border-radius: 2px;
        padding: 22px;
        border: 1px solid #e2e5ec;
        margin: .8rem 0;
        position: relative;
    }
    .gallery-thumb-items .product-thumb:nth-of-type(5) img {
        -webkit-filter: blur(3px);
        filter: blur(3px);
    }
    .product-options {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 51px;
        flex: 0 0 51px;
        list-style: none;
        padding: 0;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        z-index: 2;
        -ms-flex: 0 0 24px;
        flex: 0 0 24px;
        margin-right: 0;
        position: absolute;
        top: 0px;
        right: 0;
        background: #fff;
        padding: 10px !important;
        box-shadow: 0 1rem 4rem rgba(0, 0, 0, 0.075) !important;
    }
    .product-options li {
        display: block;
        margin-top: 20px;
        position: relative;
    }
    .product-options li:first-child {
        margin-top: 0;
    }
    .d-block.gallery-main.position-relative {
        border-left: 1px solid #f4f4f4;
        margin-right: 1rem;
        padding-right: 1rem;
        height: 100%;
    }
}
.gallery-thumb-items .product-thumb:not(:first-of-type) {
    margin-right: 2.5%;
}
.gallery-thumb-items .product-thumb img {
    max-width: 100%;
    max-height: 100%;
    vertical-align: top;
    opacity: .7;
    position: relative;
}
.zoom-overlay-icon {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    width: 34px;
    height: 34px;
    border-radius: 34px;
    color: #fff;
    background-color: rgba(115, 114, 115, .6);
    font-size: 0.897rem;
    font-size: 1rem;
    line-height: 14px;
    left: 0;
    right: 0;
    top:0;
    bottom: 0;
    margin:auto;
    text-align: center;
    position: absolute;
}
.zoom-overlay {
    left: 0;
    right: 0;
    top:0;
    bottom: 0;
    margin:auto;
    text-align: center;
    position: absolute;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
}
.zoom-overlay-icon .fal {
    left: 0;
    right: 0;
    top:0;
    bottom: 0;
    margin:auto;
    text-align: center;
    position: absolute;
    height: 17px;
}
.product-title h1{
    margin: 0;
    font-size: 16px;
    font-size: 1.143rem;
    line-height: 24px;
    font-weight: 600;
    color: #000;
}
.product-en-title {
    margin: .5rem 0;
    font-size: 13px;
    font-weight: 300;
}
.product-sku span{
    position: relative;
    padding-left: 8px;
    padding-right: 8px;
    color: #afafaf;
    background-color: #fff;
    font-size: 12px;
    line-height: 20px;
    display: inline-block;
    position: relative;
    z-index: 1;
}
.product-sku::after {
    position: absolute;
    content: '';
    right: 0;
    width: 100%;
    height: 1px;
    background-color: #f2f2f2;
    top: 13px;
}
.v-opt {
    margin-left: 1rem;
}
.d-block.opt-pro {
    font-weight: 300;
}
.v-opt a {
    font-weight: 400;
    color: #00c6db;
    position: relative;
    font-size: 12px;
}
.v-opt a::after {
    left: 0;
    right: 0;
    top: 50%;
    margin-top: 0.8em;
    content: "";
    position: absolute;
    border-bottom: 1px dashed #8b91a2;
}
.product-brief ul li::before {
    content: "";
    width: 5px;
    height: 5px;
    background: #ccc;
    vertical-align: middle;
    margin-left: 5px;
    border-radius: 50%;
    display: inline-block;
    position: absolute;
    right: 0;
    top: calc(50% - 2.5px);
}
.product-brief ul li {
    margin: 3px 0;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: baseline;
    -ms-flex-align: baseline;
    align-items: baseline;
    width: 100%;
    font-size:12.5px;
    font-weight: 300;
    line-height: 1.8;
    padding-right: 15px;
    position: relative;
}

.product-price-area  .badge {
    height: 38px;
    border-radius: 20px;
    background-color: #748ae0;
    text-align: center;
    display: inline-block;
    line-height: 40px;
    color: #fff;
    margin-right: 10px;
    font-size: 15px;
    vertical-align: middle;
    padding: 0 20px;
    font-weight: 600;
}
.product-price-area  del {
    font-size: 14px;
    font-weight: 300;
    display: inline-block;
    vertical-align: middle;
}
.product-price-sale {
    text-align: center;
    display: inline;
    font-size: 24px;
    font-size: 1.5rem;
    line-height: 1.419;
    margin-top: 10px;
    margin-bottom: 10px;
    font-weight: 600;
    margin-bottom: 0;
}
.product-price-sale .currency {

    font-size: 0.987rem;
    font-weight: 300;
}
.thumb-wrapper {
    transition: none !important;
}
#myTab {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    list-style: none;
    background-color: #f8f8f8;
    border-bottom: 1px solid #dfdfdf;
}
#myTab li {
    border-left: 1px solid #dfdfdf;
    position: relative;
    margin-bottom: -1px;
}
#myTab li button {
    text-align: center;
    border: 0;
    border-radius: 0;
    padding: 14px 40px;
    font-weight: 500;
    line-height: 27px;
    color: #6f6f6f;
    font-size: 14px;
}
#myTab li button i {
    display: inline-block;
    vertical-align: top;
    margin-left: 11px;
    font-size: 27px;
    color: #9c9c9c;
    vertical-align: middle;
}
#myTabContent {
    padding: 30px;
    border-top: none;
    background-color: #fff;
}
.product-description h2 {
    margin: 5px 0 5px;
    font-size: 0.987rem;
    font-weight: 600;
    color: #222222;
}
.product-description p, .product-description span {
    margin: 5px 0 5px;
    font-size: 13px;
    line-height: 2.2;
    font-weight: 300;
    color: #212529;
}
.product-description a {
    position: relative;
    color: #00c6db;
}

.product-description {
    font-size: 12px;
    line-height: 2.2;
    font-weight: 300;
}
.specs-label {
    min-width: 30%;
    margin-left: .5rem;
    color: #000000;
    text-align: right;
    font-weight: 300;
    font-size: 0.897rem;
    background: #edf8fc;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-radius: .25rem;
    list-style: none;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    display: flex;
    flex-direction: row;
    height: 100%;
}
.specs-value {
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-radius: .25rem;
    background-color: #f8f8f8;
    list-style: none;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    display: flex;
    flex-direction: row;
    font-size: 0.897rem;
    font-weight: 600;
    height: 100%;
    color: #4d4d4d;
}
.number-opt {
    font-size: 1rem;
    margin-right: 7px;
    color: #a1a3a8;
    vertical-align: middle;
    display: inline-block;
}
.number-opt:hover{
    color: #000;
}
.comment-hint-title{
    font-size: 16px;
    line-height: 1.158;
    color: #5a5a5a;
    letter-spacing: -.4px;
    margin-bottom: 20px;
    display: block;
    font-weight: 600;
}
.comment-hint-text, .question-hint-text {
    font-size: 14px;
    line-height: 2.27;
    margin-bottom: 35px;
    color: #676767;
}
.comment-hint-btn .btn,.question-hint-btn .btn{
    line-height: 1.5;
    padding: .475rem 1.25rem .375rem 1.25rem;
    padding-right: 1.25rem;
    border-radius: 2px;
    transition: all .1s ease-in-out;
    font-size: 13px;
    height: 48px;
    position: relative;
    padding-right: 50px;
    border-color: #748ae0;
    background: #748ae0;
    color: #fff;
    fill: #3d464d;
    box-shadow: none !important;
}
.comment-hint-btn .btn span, .question-hint-btn .btn span {
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
    font-size: 0.897rem;
}
.comment-hint-btn .btn i, .question-hint-btn .btn i {
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
    font-size: 17px;
    margin-left: 5px;
    position: absolute;
    top: calc(50% - 9px);
    right: 12px;
    left: unset;
}
.c-message-light {
    background-color: #fffde4;
    border: 1px solid #f6e8a1;
    color: #a37731;
    text-align: center;
    font-size: .897rem;
    padding: 15px 20px 17px 20px;
    position: relative;
    border-radius: 2px;
    line-height: 22px;
    font-weight: 300;
}
.comment-hint-btn .btn:hover,.question-hint-btn .btn:hover{
    border-color: #3d464d;
    background: #3d464d;
    color: #fff;
    fill: #fff;
}
.btn:hover {
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
}
.list-comment li {
    list-style: none;
}
.top-review {
    margin: 12px 0 20px 0;
    position: relative;
}
.top-review ul li {
    display: inline-block;
    vertical-align: middle;
    margin-left: 10px;
    color: #6e6a6a;
    font-weight: 500;
}
.row.body-revirew {
    font-size: 14px;
    font-weight: 500;
}
.rating .fa-star {
    color: #FC0;
    font-size: 10px;
}
.top-review::after {
    background-color: #eee;
    position: absolute;
    right: 0;
    width: 30%;
    content: '';
    height: 1px;
    bottom: -7px;
}
.rating .fa-stack {
    font-size: 6px;
}
.more::before {
    border-radius: 100px;
    border: 1px solid #111;
    box-sizing: border-box;
    content: '';
    display: block;
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    -webkit-transition: border-width .1s;
    transition: border-width .1s;
    width: 100%;
}
.list-comment {
    padding-right: 0 !important;
}
.top-review ul {
    padding-right: 0 !important;
}
li.more {
    clear: both;
    cursor: pointer;
    display: block;
    font-size: 14px;
    margin-top: 6px;
    padding: 12px 24px !important;
    text-align: center;
    width: 164px;
    margin: 0 auto;
    margin-bottom: 0px;
    margin-bottom: 0px;
    position: relative;
    margin-bottom: 30px;
    color: #111 !important;
}
.frm-comment input,.frm-comment-mob input {
    box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5);
    height: 40px;
    line-height: 40px;
    font-size: 13px;
}
.star-rating__input {
    display: none;
}
.star-rating__wrap .fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome !important;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
[type="checkbox"] + label[for], [type="radio"] + label[for] {
    cursor: pointer;
}
.star-rating__wrap .fa {
    font-family: "Font Awesome 5 Pro";
    font-weight: 900;
    font-size: inherit;
    text-rendering: auto;
}
label.star-rating__ico {
    margin: 0 !important;
}
.star-rating__ico {
    cursor: pointer;
    color: #FFB300;
    font-size: 1em !important;
    line-height: 1.75em !important;
}
[type="checkbox"] + label, [type="radio"] + label {
    display: inline-block;
    vertical-align: baseline;
    margin-left: .5rem;
    margin-right: 1rem;
    margin-bottom: 0;
}
.star-rating__input:checked ~ .star-rating__ico::before {
    font-weight: 900;
    color: #FFB300;
}
.rate-list li span {
    display: inline-block;
    vertical-align: middle;
}
.rate-list li {
    list-style: none;
}
.rate-list {
    margin: 10px 0 !important;
}
#content {
    box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5);
    height: 151px !important;
    width: 100% !important;
    font-size: 13px;
}
.modal-am-price-chart__base {
    width: 100%;
    height: 440px;
    direction: ltr !important;
}
.modal-title {
    font-size: 15px;
}
.share__social--twitter {
    background: #4dcceb;
    border: 1px solid #4dcceb;
}
.share__social {
    width: 42px;
    height: 42px;
    border-radius: 5px;
    margin-left: 16px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
}
.share__options, .share__social, .share__social-buttons {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
.wf-icon-twitter::before {
    content: "\ea96";
}
.share__social--fb {
    background: #4d8deb;
    border: 1px solid #4d8deb;
}
.share__social i {
    font-size: 22px;
    font-size: 1.614rem;
    line-height: 24px;
    color: #fff;
}
.wf-icon-facebook::before {
    content: "\ea90";
}
.share__social--whatsapp {
    background: #1bd741;
    border: 1px solid #1bd741;
}
.wf-icon-whatsapp::before {
    content: "\ea93";
}
.share__social--telegram {
    background: #272a2f;
    border: 1px solid #272a2f;
}
.wf-icon-telegram::before {
    content: "\ea95";
}
.share__btn {
    cursor: pointer;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    border-radius: 5px;
    background: none;
    border: none;
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    position: relative;
    padding: 8px 16px;
    font-size: 12px;
    font-size: .857rem;
    line-height: 1.833;
    font-weight: 700;
    border: 1px solid #81858b;
    color: #81858b;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
}
.share__title {
    font-size: 13px;
    line-height: 2;
    color: #232933;
    margin-top: 0;
    text-align: right;
    font-weight: 300;
    text-align: justify;
    margin-bottom: 20px;
}
.modal .modal-content {
    box-shadow: none;
    padding: 0 !important;
    border-radius: 0 !important;
}
#shareModal .modal-dialog {
    max-width: 400px;

}
.input-number__add {
    right: 1px;
    text-align: center;
    line-height: 3.5;
}
.input-number__add, .input-number__sub {
    position: absolute;
    height: 100%;
    width: 31px;
    top: 0;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    opacity: .3;
    transition: opacity .18s;
}

.input-number {
    display: block;
    width: 100%;
    position: relative;
}
.product-add-to-card .form-control {
    border-left: 0;
    border-radius: 0;
    height: 48px;
    font-size: 17px;
    font-weight: 300;
    background: #fff;
    box-shadow: none !important;
    text-align: center
}
.input-number__sub {
    left: 1px;
    text-align: center;
    line-height: 3.5;
}
.product-add-to-card .btn-basket {
    height: 48px;
    padding: .375rem 1.25rem .475rem 1.25rem;
    border-color: #748ae0;
    background: #748ae0;
    color: #fff;
    fill: #3d464d;
    border-radius: 2px;
    transition: all .1s ease-in-out;
    font-size: 13px;
    position: relative;
    width: 100%;
}
.product-add-to-card .btn-basket.btn i {
    left: 12px;
    right: unset;
    font-size: 17px;
    margin-left: 5px;
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
    font-size: 17px;
    margin-left: 5px;
    position: absolute;
    top: calc(50% - 9px);
}
.product-card-area {
    background-color: #f8f8f8;
    border-radius: 2px;
    padding: 22px;
    border: 1px solid #e2e5ec;
    margin: .8rem 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}
.product-custom-banner-image {
    position: relative;
    display: inline-block;
    width: 45px;
    height: 100%;
    text-align: center;
    font-size: .897rem;
    line-height: 32px;
    vertical-align: middle;
    border-radius: 3px;
}
.product-custom-banners img {
    height: 45px;
    width: 45px;
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
}
.product-icon-h5 {
    font-size: 12px;
    color: #565656;
    font-weight: 300;
    margin-top: 7px;
}
.product-icon-h4 {
    font-size: .9rem;
    color: #565656;
    font-weight: 600;
}
.product-top .icon-col {
    width: 100%;
    border-radius: 8px;
    color: #3d464d;
    border: 1px solid #e0e0e2;
    padding: 12px;
    background: #fff;
}
.spoiler-link {
    font-weight: 400;
    color: #00c6db;
    position: relative;
    font-size: 12px
}
.spoiler-link:hover {
    color: #858585;
}
.spoiler-link::after {
    left: 0;
    right: 0;
    top: 50%;
    margin-top: 0.8em;
    content: "";
    position: absolute;
    border-bottom: 1px dashed #8b91a2;
}
#suggestionModal .modal-content .form-control {
    border-radius: 5px;
    background: #fff;
    border: 1px solid #e6e6e6;
    color: #717171;
    font-size: .897rem;
    font-size: 1rem;
    line-height: 1.571;
    padding: 11px 12px;
    width: 100%;
    padding: 13px 12px 12px 12px;
    font-size: 13px;
    line-height: 21px;
    height: auto;
    border-radius: 2px;
    font-weight: 300;
}
#price_suggestion_data_price-error {
    color: #e91e63;
    font-size: .75rem;
    padding: 5px;
}
.c-ui-statusswitcher {
    position: absolute !important;
    right: 0;
    top: 4px;
}
.c-ui-statusswitcher input[type="checkbox"] {
    visibility: hidden;
    position: absolute;
}
.c-ui-statusswitcher__slider {
    display: inline-block;
    position: relative;
    cursor: pointer;
    width: 40px;
    height: 21px;
    border-radius: 29px;
    border: 1px solid #8c8c8c;
    background: #e5e5e5;
    transition: .15s ease-in;
}
.c-ui-statusswitcher__slider__toggle {
    display: block;
    width: 15px;
    height: 15px;
    background-color: #fff;
    border: 1px solid #959595;
    transition: all .4s ease;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    right: 2px;
}
input[type="checkbox"]:checked + .c-ui-statusswitcher__slider {
    background-color: #64bd63;
    border-color: #64bd63;
}
input[type="checkbox"]:checked + .c-ui-statusswitcher__slider span {
    right: 21px;
}
.status {
    margin-right: 55px;
}
.modal .modal-foot {
    font-size: 12px;
    font-weight: 300;
    border: none;
    padding: 15px;
    margin: 0;
    background-color: #fbfbfb;
    border-top: 1px solid #ececec;
    border-radius: 0;
}
.btn-success {
   color: #1b1b1b !important;
   background-color: #d9bb75;
border-color: #d9bb75;
    border-radius: 2px;
    transition: all .1s ease-in-out;
    font-size: 12px;
    height: calc(2.25rem + 2px);
    line-height: 1.5;
    padding: 10px;
font-weight: 500;
}
.btn-secondary, .btn-secondary.disabled, .btn-secondary:disabled {
    border-color: #f0f0f0;
    background: #f0f0f0;
    color: #3d464d;
    fill: #3d464d;
    border-radius: 2px;
    transition: all .1s ease-in-out;
    font-size: 12px;
    height: calc(2.25rem + 2px);
    line-height: 1.5;
    padding: .475rem 1.25rem .375rem 1.25rem;
    font-weight: 500;
}
.btn-primary, .btn-primary.disabled, .btn-primary:disabled {
    border-color: #748ae0;
    background: #748ae0;
    color: #fff;
    fill: #fff;
    font-size: 13px;
    border-radius: 2px;
    transition: all .1s ease-in-out;
}
@media screen and (max-width:767.99px) {
    .row ,.gap-col-mob{
  padding-left: 0 !important;
  padding-right: 0 !important;
}
    .img-pro {
  height: 180px !important;
  margin-top: 5px;
}
    .search-product {
  padding-left: 0 !important;
  padding-right: 0 !important;
}
    .img-pro a {
     padding: 10px;
     text-align: center;
     display: -webkit-box;
     display: -ms-flexbox;
     display: flex;
     -webkit-box-align: center;
     -ms-flex-align: center;
     align-items: center;
     -webkit-box-pack: center;
     -ms-flex-pack: center;
     justify-content: center;
     -ms-flex-negative: 0;
     flex-shrink: 0;
     max-width: 100%;
     position: relative;
     height: 180px !important;
 }
.img-pro img {
    max-width: 100%;
    max-height: 100%;
    -o-object-fit: fill !important;
    object-fit: fill !important;
    height: 100% !important;
}
    .blocks-icon-h4 {
        font-weight: 400 !important;
        font-size: 11px !important;

    }
    .row.row-cost-mob {
        padding: 5px;
        font-weight: 300;
        display: none;
    }
    .bld-col-cost {
        font-weight: 500;
        font-size: 16px !important;
    }
    .product-card-area.highlight {
        position: fixed;
        bottom: 0;
        width: 100%;
        right: 0;
        z-index: 100;
        margin-bottom: 0 !important;
        padding: 2px !important;
        background-color: #fff !important;
        border-color: #fff !important;
        border-radius: 0 !important;
    }
    .product-options li {
        display: inline-block !important;
        margin-right: 15px;
    }
    div.product-thumb:nth-child(4) {
        display: none !important;
    }
    .gallery-thumb-items .product-thumb {
        -ms-flex: 0 0 23% !important;
        flex: 0 0 23% !important;
        max-width: 23%  !important;
        -ms-flex: 0 0 18% !important;
        flex: 0 0 23% !important;
    }
    .product-options.p-0 {
        text-align: left;
        margin-bottom: 30px !important;
    }
    .product-title h1 {
        font-size: 16px;
        margin-top: 20px !important;
    }
    .product-description .h2 {
        margin: 5px 0 5px;
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: 600;
        color: #222222;
        margin-bottom: 15px;
    }
    .product-description h1 {
        margin: 5px 0 5px;
        font-size: 14px;
        font-weight: 600;
        color: #222222;
        line-height: 2;
    }
    .table-borderless  tr th, .table-borderless  tr td {
        display: block;
    }
    .specs-label {
        margin-left: 0 !important;
    }
    .product-description {
        max-height: 200px;
        overflow: hidden;
    }
    .more-desc-info {
        position: relative;
        z-index: 2;
        background: none;
        font-size: 12px;
        color: #3d464d;
        border: 1px solid #3d464d;
        border-radius: 3px;
        padding: 5px 10px;
        margin-top: 10px;
        display: inline-block;
        cursor: pointer;
    }
    .tbl-atrib {
        max-height: 420px;
        overflow: hidden;
    }
    .product-description.show-content {
        max-height: inherit !important;
    }
    .tbl-atrib.show-content {
        max-height: inherit !important;
    }
    .more-desc-info.hide {
        display: none;
    }
    .row.row-cost-mob.show-bottom {
        display: block !important;
    }
    .p-bottom {
        padding-bottom: 98px !important;
    }
}
svg {
    max-width: 100% !important;
}
.resize-sensor div {
    max-width: 100% !important;
}
.btn{
    box-shadow: none !important;
}
.timer {
    border: none !important;
    background-color: transparent !important;
    font-size: 14px;
}
.timer-clock {
    background-color: #f7f7f7;
    padding: 15px;
    border: 1px solid #e2e5ec;
    border-radius: 2px;
    margin: .8rem 0;
}
.timer-text {
    color: #777;
    font-size: 11px;
}
.product-promotion-badge {
    background: #fe0404;
    color: #ffffff;
    padding: 2px 10px 3px;
    margin-bottom: 4px;
    font-size: .707rem;
    border-radius: 4px;
    position: absolute;
    left: 12px;
    top: 12px;
    z-index: 2;
    font-size: 12px;
    font-weight: 300;
}
.zoomContainer {
    z-index: 10;
}
.text-body-1 {
    color: #80848b;
    padding-left: 5px;
}
.ls-atrib li {
    font-weight: 500 !important;
}
.css-cwolnn {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background: transparent;
    border: none;
    padding: 0;
    margin-left: 6px;
    margin-bottom: 0;
}
.css-a1e3mv {
    display: inline-block;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 20%);
    width: 32px;
    height: 32px;
    position: relative;
    margin-bottom: 0.25rem;
    border-radius: 100%;
}
.css-a1e3mv img {
    width: 100%;
    height: 100%;
    border-radius: 100%;
}
.css-cwolnn {
    display: inline-block;
}
.color-box input[type="radio"] {
    visibility: hidden;
    position: absolute;
}
.color-box input[type="radio"]:checked + .css-a1e3mv::after {
    content: "";
    width: 7px;
    height: 15px;
    position: absolute;
    top: 5px;
    left: 12px;
    border: solid #2ed0e1;
    border-width: 0 2px 2px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
.color-box input[type="radio"]:checked + .css-a1e3mv{
    border: 2px solid #2ed0e1;
}
.select2-container {
    min-width: 100px;
}
.d-block.info-col {
    font-size: 12px;
    text-align: justify;
    margin-top: 20px;
    border-top: 1px solid #eee;
    padding-top: 5px;
}
.info-col i {
    font-size: 17px;
    vertical-align: middle;
    color: #80848b;
}
.tiltle-slider {
    position: relative;
    font-size: 15px;
    font-weight: 500;
}
.tiltle-slider::after {
    position: absolute;
    bottom: -9px;
    width: 100px;
    height: 2px;
    background-color: #748ae0;
    content: '';
    right: 0;
}
.t-question {
    color: #a5a6ab;
    font-weight: 500;
    font-size: 13px;
}
.t-question a{
    color: #a5a6ab;

}
.fal.fa-question-square {
    color: #00c6db;
    vertical-align: middle;
    font-size: 20px;
}
.message-question {
    box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5);
    height: 151px !important;
    width: 100% !important;
    font-size: 13px;
}
.replay {
    background-color: transparent !important;
    font-size: 12px;
    color: #00c6db;
    font-weight: 600;
}
.replay i{
    vertical-align: middle;
    font-size: 10px;
    padding-right: 5px;
}
.list-question li {
    margin-bottom: 20px;
    list-style: none;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}
.rate-pro i {
  color: #d9bb75 !important;
}
.btn-groups li {
  width: 35px;
  height: 35px;
  border: 1px solid #eee;
  line-height: 35px;
  text-align: center;
  cursor: pointer;
  display: block;
  margin-bottom: 10px;
}
.btn-groups i {
  color: #b5b1b1;
font-size: 20px !important;
  vertical-align: middle;
}
.faqs-search button i {
  vertical-align: middle;
}
.box-price {
  background-color: #eee;
  border-radius: 5px;
  padding: 20px !important;
}
.product-price li {
  list-style: none;
}
.value a, .key {
  font-weight: 500;
}
.btn-site.add-basket.btn.btn-success {
  font-size: 14px;
  display: block;
  height: 43px;
}
.dk-btn.dk-btn-info.position-relative {
  position: relative;
  overflow: hidden;
}
.faqs-search input {
  width: calc(100% - 35px) !important;
  padding-right: 5px !important;
}
.help-category-body label {
  color: #706f6f;
  margin-bottom: 10px;
  font-weight: 500;
}
.help-category-body textarea {
  box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5) !important;
  height: 150px !important;
  width: 100% !important;
  display: block !important;
}
.form-control:focus {
  border-color: #d9bb75 !important;
}
.help-category-body input {
  box-shadow: inset 0px 0px 7px 0px rgba(198, 196, 196, 0.5) !important;
  min-height: 40px;
  line-height: 40px;
}
.btn-send {
  background-color: #d9bb75;
  color: #292929 !important;
  font-weight: 600;
  width: 100%;
  font-size: 1.286rem;
  padding: 18px 80px;
  border-radius: 8px;
  color: #fff;
  text-align: center;
  display: inline-block;
  border: none;
  cursor: pointer;
  position: relative;
  white-space: nowrap;
  line-height: 22px;
  text-decoration: none;
  margin-top: 5px;

  overflow:hidden;
}
.btn-send::before {
  transition: all .3s ease-in-out;
  width: 21%;
  height: 150%;
  position: absolute;
  right: -11px;
  top: -17px;
  background: hsla(0, 0%, 100%, .21);
  content: "";
  border-radius: 50%;
}
.btn-send > i {
  font-size: 25px;
  position: absolute;
  right: 15px;
  top: 19px;
  color: #292929;
  font-style: normal;
}
.btn-send:hover::before {
  width: 900px;
  height: 900px;
  right: -450px;
  top: -450px;
  transform: unset;
}
.alert-info {
  color: #055160;
  background-color: #d9bb7594;
  border-color: #d9bb75;
  color: #140101 !important;
}

.about-us-header img {
  height: auto !important;
  max-width: 100% !important;
}
.similarProducts{
    background: #e1e1e1 url("/site_themes/images/bg-news.png") no-repeat;
background-attachment: fixed;
}
.filter-item-header {
  border-bottom: 4px solid #d9bb75;
}
.container.site-blog__box {
  box-shadow: 2px 5px 14px -5px #dbdbdb;
  background-color: white;
  padding: 22px 0;
}</style>
    <style>
        img{ max-width: 100%; height: auto; }

        /* CLS Fix: تصاویر داخل متن مقاله */
        #article-content img { height: auto !important; max-width: 100% !important; }

        /* Progress Bar */
        #reading-progress{
            position:fixed !important;
            top:0 !important;
            left:0 !important;
            right:auto !important;
            width:0 !important;
            height:4px !important;
            background:linear-gradient(to right,#d9bb75,#f5e27a) !important;
            z-index:2147483647 !important;
            pointer-events:none !important;
            transition:width 0.15s linear;
            direction:ltr !important;
        }
        .c-header.js-header{ z-index:99 !important; }
        .c-header, .js-header, header { z-index:9998 !important; }

        /* CLS Fix: تصویر اصلی مقاله */
        .site-blog-post__box__body__image{
            aspect-ratio: 16/9;
            overflow: hidden;
            background: #f0f0f0;
        }
        .site-blog-post__box__body__image img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Meta Bar */
        .article-meta{display:flex;flex-wrap:wrap;align-items:center;gap:14px;padding:10px 0;border-top:1px solid #eee;border-bottom:1px solid #eee;margin-bottom:1.2rem;font-size:13px;color:#666;direction:rtl;}
        .article-meta span{display:flex;align-items:center;gap:5px;}
        .article-meta i{color:#d9bb75;font-size:13px;}

        /* TOC */
        .toc-box{background:#fafaf8;border:1px solid #e8e3d5;border-right:4px solid #d9bb75;border-radius:8px;padding:18px 22px;margin:1.5rem 0;direction:rtl;}
        .toc-box h2{font-size:14px;font-weight:700;margin-bottom:10px;color:#333;display:flex;align-items:center;gap:7px;}
        .toc-box ul{list-style:none;padding:0;margin:0;counter-reset:toc-c;}
        .toc-box ul li{counter-increment:toc-c;padding:5px 0;font-size:13px;border-bottom:1px dashed #e5e5e5;color:#444;}
        .toc-box ul li:last-child{border-bottom:none;}
        .toc-box ul li::before{content:counter(toc-c) ". ";color:#d9bb75;font-weight:700;margin-left:5px;}
        .toc-box ul li a{color:#444;text-decoration:none;}
        .toc-box ul li a:hover{color:#d9bb75;}

        /* CLS Fix: TOC پنهان با visibility به جای display:none */
        .toc-box--hidden{
    opacity: 0 !important;
    pointer-events: none !important;
}

        /* Share */
        .share-box{background:#f9f7f2;border:1px solid #e8e3d5;border-radius:10px;padding:16px 18px;margin:1.5rem 0;direction:rtl;}
        .share-box h3{font-size:13px;color:#888;margin-bottom:10px;font-weight:600;}
        .share-buttons{display:flex;flex-wrap:wrap;gap:9px;}
        .share-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:25px;font-size:13px;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:opacity 0.2s,transform 0.15s;}
        .share-btn:hover{opacity:0.85;transform:translateY(-1px);}
        .share-btn-tg{background:#0088cc;color:#fff;}
        .share-btn-wa{background:#25D366;color:#fff;}
        .share-btn-cp{background:#f0f0f0;color:#333;}
        .share-btn-cp.copied{background:#d9bb75;color:#fff;}
        .badge-danger{background:#d9bb75 !important;color:#1a1a1a !important;}
        .badge-dark{background:#333 !important;color:#fff !important;}

        /* Author+Hoosh Box */
        .hoosh-box{background:linear-gradient(135deg,#1a1a1a 0%,#2d2410 100%);border-radius:14px;padding:26px;margin:1.5rem 0;direction:rtl;position:relative;overflow:hidden;}
        .hoosh-box::before{content:'';position:absolute;top:-30px;left:-30px;width:110px;height:110px;border-radius:50%;background:rgba(212,175,55,0.07);}
        .hoosh-box p{font-size:14px;color:#bbb;line-height:2;margin-bottom:10px;}
        .hoosh-box p.hl{color:#d9bb75;font-weight:600;}
        .hoosh-box p strong{color:#d9bb75;}
        .hoosh-btn{display:inline-flex;align-items:center;gap:7px;background:#d9bb75;color:#1a1a1a;font-size:14px;font-weight:700;padding:11px 22px;border-radius:8px;text-decoration:none;transition:background 0.2s,transform 0.15s;margin-top:18px;}
        .hoosh-btn:hover{background:#c9a227;color:#1a1a1a;transform:translateY(-2px);text-decoration:none;}
    </style>
    


@endsection

 <!--add new-->
@section('site-js-header')

@endsection

@section('content')

{{-- Progress Bar --}}
<div id="reading-progress"></div>

    <main class="site-blog-post wrapper default">
        <div class="container">
            <div class="site-blog-post__path">
                <ul>
                    <li>
                        <a href="{{ route('site.index') }}">خانه</a>
                    </li>
                    @if($article->categories->count())
                    <li>
                        <a href="#">
                            {{ $article->categories[0]->title }}
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ $article->path() }}">
                            {{ $article->title }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="site-blog-post__box">
                <div class="row">
                    <div class="col-12 col-md-8">

                        <div class="site-blog-post__box__body">
                            {{-- CLS Fix: تصویر اصلی - بدون loading=lazy، با fetchpriority=high --}}
                            <div class="site-blog-post__box__body__image">
                                <img src="{{ isset($article->image[0]) ? url($article->image[0]->url) : null }}"
                                     alt="{{ $article->title }}"
                                     fetchpriority="high"
                                     decoding="async"
                                     width="800" height="450">
                            </div>
                            @if(isset($article->tags))
                            <!--<div class="site-blog-post__box__body__cat">-->
                            <!--    <ul>-->
                            <!--        @foreach($article->tags as $tag)-->
                            <!--        <li class="badge badge-info">-->
                            <!--            <a href="{{ $tag->path() }}">{{ $tag->title }}</a>-->
                            <!--        </li>-->
                            <!--        @endforeach-->
                            <!--    </ul>-->
                            <!--</div>-->
                            @endif
                            <div class="site-blog-post__box__body__title">
                                <h1>{{ $article->title }}</h1>
                            </div>

                            {{-- Meta Bar --}}
                            <div class="article-meta">
                                @if($article->user->first_name)
                                <span><i class="fas fa-user-circle"></i> {{ $article->user->first_name }}</span>
                                @endif
                                <span><i class="fas fa-calendar-alt"></i> {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], $article->created_at) }}</span>
                                @if($article->study_time)
                                <span><i class="fas fa-clock"></i> {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], explode(',', $article->study_time)[0]) }} زمان مطالعه</span>
                                @endif
                                <span><i class="fas fa-eye"></i> {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], number_format($article->viewCount)) }} بازدید</span>
                            </div>

                            @php
                                $tocData = buildTableOfContents(lazyLoadAparatIframes(fixImageDimensions($article->body)));
                            @endphp
                            {{-- CLS Fix: TOC به‌صورت سمت سرور ساخته می‌شود تا بعد از لود، ارتفاع جعبه تغییر نکند --}}
                            <div id="toc-container" class="toc-box @if($tocData['count'] < 2) toc-box--hidden @endif">
                                <h2><i class="fas fa-list-ul"></i> فهرست مطالب</h2>
                                <ul id="toc-list">{!! $tocData['list'] !!}</ul>
                            </div>
                            
                            <!--<div class="site-blog-post__box__body__info">-->
                            <!--    <ul>-->
                            <!--        <li class="badge badge-pill badge-dark">-->
                            <!--            <i class="fas fa-user"></i>-->
                            <!--            <span>{{ $article->user->name }}</span>-->
                            <!--        </li>-->
                            <!--        <li class="badge badge-pill badge-dark">-->
                            <!--            <i class="fas fa-date"></i>-->
                            <!--            <span>{{ $article->created_at }}</span>-->
                            <!--        </li>-->
                            <!--        <li class="badge badge-pill badge-dark">-->
                            <!--            <i class="fas fa-comment"></i>-->
                            <!--            <span>{{ $article->commentCount }}</span>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</div>-->
                            
                            <!--<a href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AE%D8%B5%D9%88%D8%B5%DB%8C-%D9%88%D8%B1%D8%B2%D8%B4-%D9%87%D8%A7%DB%8C-%D8%B1%D8%B2%D9%85%DB%8C-%D9%88-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C" target="_blank">-->
                            <!--        <img src="{{ asset('site_themes/images/dibazar-banner.gif') }}" loading="lazy" />-->
                            <!--</a>-->
                            
                            
                            <div class="site-blog-post__box__body__detail" id="article-content">
                                <p>{!! $tocData['html'] !!}</p>
                            </div>


<!--<style>.h_iframe-aparat_embed_frame{position:relative;}.h_iframe-aparat_embed_frame .ratio{display:block;width:100%;height:auto;}.h_iframe-aparat_embed_frame iframe{position:absolute;top:0;left:0;width:100%;height:100%;}</style><div class="h_iframe-aparat_embed_frame"><span style="display: block;padding-top: 57%"></span><iframe src="https://www.aparat.com/video/video/embed/videohash/UqbKW/vt/frame"  allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div>-->


			          @if($article->faq)
                                        <div class="accordion my-5" id="accordionExample1">
                                            <h4 class="text-center text-secondary"><span class="fa fa-question-circle"></span> سوالات متداول</h4>
                                            @foreach($article->faq as $f)
                                                <div class="accordion-item">
                                                    <h5 class="accordion-header" id="heading{{ $loop->iteration }}">
                                                        <button class="accordion-button"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse{{ $loop->iteration }}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{ $loop->iteration }}">
                                                            {{ $f['question'] }}
                                                        </button>
                                                    </h5>
                                                    <div id="collapse{{ $loop->iteration }}"
                                                         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                         aria-labelledby="heading{{ $loop->iteration }}"
                                                         data-bs-parent="#accordionExample1">
                                                        <div class="accordion-body">
                                                            {{ $f['answer'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
									

                            {{-- Share --}}
                            <div class="share-box">
                                <h3><i class="fas fa-share-alt" style="color:#d9bb75;"></i> این مقاله رو به اشتراک بذار</h3>
                                <div class="share-buttons">
                                    <a href="https://t.me/share/url?url={{ urlencode(url($article->path())) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener" class="share-btn share-btn-tg"><i class="fab fa-telegram-plane"></i> تلگرام</a>
                                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url($article->path())) }}" target="_blank" rel="noopener" class="share-btn share-btn-wa"><i class="fab fa-whatsapp"></i> واتساپ</a>
                                    <button class="share-btn share-btn-cp" onclick="copyLink(this)" data-url="{{ url($article->path()) }}"><i class="fas fa-link"></i> کپی لینک</button>
                                </div>
                            </div>

                            {{-- Author + Hoosh Razmi --}}
                            <div class="hoosh-box">
                                <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;">
                                    <img src="https://ehsandibazar.com/public/storage/files/shares/ehsan-profile.jpg" alt="احسان دیبازر" style="width:60px;height:60px;border-radius:50%;object-fit:cover;object-position:top;border:2px solid #d9bb75;flex-shrink:0;">
                                    <div>
                                        <div style="font-size:16px;font-weight:800;color:#fff;">سلام، من {{ $article->user->first_name ?? 'احسان دیبازر' }} هستم</div>
                                        <div style="font-size:12px;color:#d9bb75;margin-top:3px;">مربی هنرهای رزمی و دفاع شخصی | کارشناس ارشد علوم ورزشی | توسعه‌دهنده مفهوم هوش رزمی</div>
                                    </div>
                                </div>
                                <p>سال‌هاست زندگی من با ورزش‌های رزمی گره خورده؛ اما چیزی که همیشه بیشتر از مدال، مدرک یا مسابقه برایم اهمیت داشته، رشد انسان‌ها بوده است.</p>
                                <p>من باور دارم هنرهای رزمی فقط برای مبارزه نیستند. اگر درست آموزش داده شوند، اعتمادبه‌نفس می‌سازند، قدرت تصمیم‌گیری را بالا می‌برند، ذهن را تحت فشار آرام‌تر می‌کنند و به آدم‌ها یاد می‌دهند در موقعیت‌های سخت بهتر عمل کنند.</p>
                                <p>امروز تمام این تجربه‌ها را در یک مسیر واحد به کار گرفته‌ام؛ مسیری که آن را <strong>«هوش رزمی»</strong> می‌نامم — توانایی حفظ آرامش زیر فشار، تصمیم‌گیری درست، کنترل ذهن و محافظت مؤثر از خود و عزیزانت.</p>
                                <a href="https://ehsandibazar.com/about-us" onclick="gtag('event', 'click', {event_category: 'outbound', event_label: 'احسان_دیبازر', value: 1});" class="hoosh-btn">
                                    با احسان دیبازر بیشتر آشنا شوید
                                </a>
                            </div>

                            @include('site.product.partials.comment')
                        </div>


					

									

                        @if(isset($similarArticles) && count($similarArticles) > 0)
                            <div class="site-blog-post__related">
                                <div class="site-blog__sidebar__item__header">
                                    <fieldset>
                                        <legend>
                                            مطالب مرتبط
                                        </legend>
                                    </fieldset>
                                </div>
                                <div class="row">
                                    @foreach($similarArticles as $itemArticle)
                                        <div class="col-12 col-md-4">
                                            <div class="site-blog__posts__item">
                                                <div class="site-blog__posts__item__image">
                                                    <a href="{{ $itemArticle->path() }}">
                                                        <img src="{{ isset($itemArticle->image[0]) ? url($itemArticle->image[0]->url) : null }}"
                                                             alt="{{ $itemArticle->title }}" loading="lazy">
                                                    </a>
                                                    <div class="site-blog__posts__item__cat">
                                            <span class="site-blog__posts__item__cat__label">
                                                 {{ $itemArticle->categories[0]->title }}
                                            </span>
                                                    </div>

                                                </div>
                                                <div class="site-blog__posts__item__desc">
                                                    <h4 class="site-blog__posts__item__desc__title">
                                                        <a href="{{ $itemArticle->path() }}">
                                                            {{ $itemArticle->title }}
                                                        </a>
                                                    </h4>
                                                    <ul class="site-blog__posts__item__desc__list">
													@if($itemArticle->user->first_name)
                                                        <li>
                                                            {{ $itemArticle->user->first_name }}
                                                        </li>
														@endif

                                                        <li>
                                                            {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], $itemArticle->created_at) }}
                                                        </li>
                                                    </ul>
                                                    <p class="site-blog__posts__item__desc__detail">
                                                        {!! \Illuminate\Support\Str::limit(strip_tags($itemArticle->body),100) !!}

                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(isset($lastArticles) && count($lastArticles) > 0)
                        <div class="col-12 col-md-4">
                            <div class="site-blog__sidebar__item__header">
                                <fieldset>
                                    <legend>
                                        آخرین مطالب
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="site-blog-post__last">
                                @foreach($lastArticles as $itemLastArticle)
                                    <div class="site-blog-post__last__item">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="site-blog-post__last__item__image">
                                                    <a href="{{ $itemLastArticle->path() }}">
                                                        <img src="{{ isset($itemLastArticle->image[0]) ? url($itemLastArticle->image[0]->url) : null }}"
                                                             alt="{{ $itemLastArticle->title }}" loading="lazy">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="site-blog-post__last__item__desc">
                                                    <h5>
                                                        <a href="{{ $itemLastArticle->path() }}">{{ $itemLastArticle->title }}</a>
                                                    </h5>
                                                    <ul>
                                                        <li class="badge badge-danger">
                                                            <i class="fas fa-date"></i>
                                                            <span>{{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], $itemLastArticle->created_at) }}</span>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </main>

@endsection

    @section('site-json-ld')
    <script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "@id": "{{ url($article->path()) }}#article",
    "headline": "{{ $article->title }}",
    "url": "{{ url($article->path()) }}",
    "datePublished": "{{ $article->updated_at ? $article->updated_at->toIso8601String() : '' }}",
    "dateModified": "{{ $article->updated_at ? $article->updated_at->toIso8601String() : '' }}",
    "image": "{{ isset($article->image[0]) ? url($article->image[0]->url) : '' }}",
    "author": {"@id": "https://ehsandibazar.com/#person"},
    "publisher": {"@id": "https://ehsandibazar.com/#organization"},
    "mainEntityOfPage": {"@type": "WebPage", "@id": "{{ url($article->path()) }}"}
}
</script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "@id": "https://ehsandibazar.com/#person",
        "name": "احسان دیبازر",
        "alternateName": "Ehsan Dibazar",
        "url": "https://ehsandibazar.com",
        "jobTitle": "مربی هنرهای رزمی و دفاع شخصی",
        "description": "کارشناس ارشد علوم ورزشی و توسعه‌دهنده مفهوم هوش رزمی",
        "sameAs": ["https://www.instagram.com/ehsandibazarcoaching","https://ehsandibazar.com"],
        "knowsAbout": ["موی‌تای","جوجیتسو برزیلی","دفاع شخصی","هوش رزمی","علوم ورزشی"]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "خانه", "item": "{{ route('site.index') }}"}
            @if($article->categories->count())
            ,{"@type": "ListItem", "position": 2, "name": "{{ addslashes($article->categories[0]->title) }}", "item": "{{ url($article->path()) }}"}
            @endif
        ]
    }
    </script>
    @if($article->faq)
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            @foreach($article->faq as $f)
            {"@type":"Question","name":"{{ addslashes($f['question']) }}","acceptedAnswer":{"@type":"Answer","text":"{{ addslashes($f['answer']) }}"}}{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
    }
    </script>
    @endif
    @endsection

    @section('site-js')
    <script>

    // Progress Bar
    (function progressBar() {
        var bar = document.getElementById('reading-progress');
        if (!bar) { setTimeout(progressBar, 500); return; }
        var lastPos = -1;
        function update() {
            var s = window.pageYOffset || document.documentElement.scrollTop;
            if (s !== lastPos) {
                lastPos = s;
                var d = document.documentElement.scrollHeight - window.innerHeight;
                bar.style.width = (d > 0 ? (s / d * 100) : 0) + 'vw';
            }
            requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    })();

    // TOC - CLS Fix: لیست از قبل سمت سرور ساخته شده، فقط کلیک نرم اضافه می‌شود
    var tocList = document.getElementById('toc-list');
    if (tocList) {
        tocList.querySelectorAll('a').forEach(function(a) {
            a.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(a.getAttribute('href'));
                if (target) target.scrollIntoView({behavior:'smooth'});
            });
        });
    }

    // Copy link
    function copyLink(btn) {
        var url = btn.getAttribute('data-url');
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() { showCopied(btn); });
        } else {
            var ta = document.createElement('textarea');
            ta.value = url; document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
            showCopied(btn);
        }
    }
    function showCopied(btn) {
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check"></i> کپی شد!';
        setTimeout(function() { btn.classList.remove('copied'); btn.innerHTML = '<i class="fas fa-link"></i> کپی لینک'; }, 2500);
    }
</script>
@endsection
