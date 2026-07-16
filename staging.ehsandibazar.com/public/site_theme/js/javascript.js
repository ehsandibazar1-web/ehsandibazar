$(document).ready(function () {
    $('.mini-search').click(function () {
        $('.xsearch-hidden').toggle();
    });
    $('.cart-li').click(function () {
        $('.hidden-cart').show();
    });
    $('.hidden-cart-close a').click(function () {
        $('.hidden-cart').hide();
    });
    $('.open-drop-down').click(function () {
        $(this).parent().siblings('ul').toggle();
    });
    $('#makeRe').click(function () {
        $('.make-a-review').toggle();
    });
    $('#prd').click(function () {
        $('.product-desc-header li').removeClass('active');
        $(this).addClass('active');
        $('.product-desc-de').hide();
        $('#prr-t').hide();
        $('#prd-t').show();
    });
    $('#prh').click(function () {
        $('.product-desc-header li').removeClass('active');
        $(this).addClass('active');
        $('.product-desc-de').hide();
        $('#prr-t').hide();
        $('#prh-t').show();
    });

    $('#prr').click(function () {
        $('.product-desc-header li').removeClass('active');
        $(this).addClass('active');
        $('.product-desc-de').hide();
        $('#prr-t').show();
    });

    $('#prt').click(function () {
        $('.product-desc-header li').removeClass('active');
        $(this).addClass('active');
        $('.product-desc-de').hide();
        $('#prt-t').show();
    });

    $('#min-navbar-link').click(function () {
        $('.hidden-side').show();
        $('body').css('position' , 'relative');
    });
    $('.hidden-close a').click(function () {
        $('.hidden-side').hide();
        $('body').css('position' , 'static');
    });


    // Show Description and Options list of a product in Product-search Page
    $('.featured-result-item').click(function () {
        var resultItem = $(this).parent().parent();
        resultItem.find('.featured').show();
    });

    // Close Option and featured list of a product in product-search page

    $('.featured-close').click(function () {
        var featuredParent = $(this).parent();
        featuredParent.hide();
    });

    // Set Filter opening section
    $('.filter-item-header').click(function () {
        var filterParent = $(this).parent();

        filterParent.find('.filter-item-body').toggle();
        var plus = $(this).find('span:first-child');


        if (plus.hasClass('filter-pink')){
            plus.removeClass('filter-pink');
            plus.html('<i class="fas fa-plus"></i>');
        }
        else{
            plus.addClass('filter-pink');
            plus.html('<i class="fas fa-minus"></i>');
        }
    });

    // set Load mores
    $('.pro-desc-load-more a').click(function () {

        var post = $(this).parent().parent().find('.product-desc-de__sizeable');
        if($(this).hasClass('hasOpend')){
            post.css({
                "height" : "200px"
            });
            $(this).removeClass('hasOpend');
            $(this).html('بیشتر بخوانید...' +
                '                        <i class="fa fa-chevron-down"></i>');
        }
        else{
            post.css({
                "height" : "unset"
            });
            $(this).addClass('hasOpend');
            $(this).html('' +
                'کمتر بخوانید' +
                '<i class="fa fa-chevron-up"></i>');

        }

    });

    // Product tab page
    $('.qa-review-header ul li a').click(function () {
        var id = $(this).attr('id');
        var typeName = id.split('-')[0];
        var tabName = typeName+'-tabBody';
        $('.tabss-body').hide();
        $('#'+ tabName).show();
    });
    $('.img-zoom-container').mouseenter(function () {
        $('#myresult').css({
            "visibility": "visible"
        });
    });
    $('.img-zoom-container').mouseleave(function () {
        $('#myresult').css({
            "visibility": "hidden"
        });
    });
});


function automateText() {
    var activeText = $('.header-right-texts p.active');
    var textLists = $('.header-right-texts');

    // activeText.slideDown();
    var activePosition = textLists.find('.active').index();
    activePosition = activePosition + 2;
    activeText.removeClass('active');
    $('.header-right-texts p:nth-child(' + activePosition + ')').addClass('active');
}

$(document).ready(function () {

    $('.product-image-list li').mouseenter(function () {
        $('.product-image-list ul li').css('border','1px solid #ddd');
        $(this).css('border','1px solid #fc2790');
        var imageSrc = $(this).find('img').attr('src');




        var myimage = $('#myimage');
        myimage.attr('src',imageSrc);
        $('.img-zoom-lens').remove();
        if($(window).width() > 768){
            imageZoom("myimage", "myresult");
        }


    });

    // automateText();
    setInterval(automateText,3000)

});



function imageZoom(imgID, resultID) {
    var img, lens, result, cx, cy;
    img = document.getElementById(imgID);
    result = document.getElementById(resultID);
    /* Create lens: */
    lens = document.createElement("DIV");
    lens.setAttribute("class", "img-zoom-lens");
    /* Insert lens: */
    img.parentElement.insertBefore(lens, img);
    /* Calculate the ratio between result DIV and lens: */
    cx = result.offsetWidth / lens.offsetWidth;
    cy = result.offsetHeight / lens.offsetHeight;
    /* Set background properties for the result DIV */
    result.style.backgroundImage = "url('" + img.src + "')";
    result.style.backgroundSize = (img.width * cx) / 3 + "px " + (img.height * cy) / 3 + "px";
    /* Execute a function when someone moves the cursor over the image, or the lens: */
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);
    /* And also for touch screens: */
    lens.addEventListener("touchmove", moveLens);
    img.addEventListener("touchmove", moveLens);
    function moveLens(e) {
        var pos, x, y;
        /* Prevent any other actions that may occur when moving over the image */
        e.preventDefault();
        /* Get the cursor's x and y positions: */
        pos = getCursorPos(e);
        /* Calculate the position of the lens: */
        x = pos.x - (lens.offsetWidth / 14);
        y = pos.y - (lens.offsetHeight / 14);
        /* Prevent the lens from being positioned outside the image: */
        if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
        if (x < 0) {x = 0;}
        if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
        if (y < 0) {y = 0;}
        /* Set the position of the lens: */
        lens.style.left = x + "px";
        lens.style.top = y + "px";
        /* Display what the lens "sees": */
        result.style.backgroundPosition = "-" + (x * cx) / 6 + "px -" + (y * cy) / 6 + "px";
    }
    function getCursorPos(e) {
        var a, x = 0, y = 0;
        e = e || window.event;
        /* Get the x and y positions of the image: */
        a = img.getBoundingClientRect();
        /* Calculate the cursor's x and y coordinates, relative to the image: */
        x = e.pageX - a.left;
        y = e.pageY - a.top;
        /* Consider any page scrolling: */
        x = x - window.pageXOffset;
        y = y - window.pageYOffset;
        return {x : x, y : y};
    }
}
/******tab menu *****/

function tab(tabBtn , tabBody){
    $(tabBtn).click(
        function () {
            $(tabBtn).removeClass('active');
            $(this).addClass('active');
            var index = $(this).index();
            index = index + 1;


            $(tabBody).hide();
            $(tabBody+':nth-child('+index+')').show();
        }
    );
}
$( document ).ready(function() {
    tab('.sub-brand-header li' , '.sub-brand-detail-list');
});

/*** About Us  Quick Access Links ***/
$(document).ready(function(){
    $('.about-page-abs2 a').click(function(){

        var id = $(this).data('dest');

        var dest = $('#' + id).offset().top;

        /**$(window).scrollTop = dest;*/
        $('html, body').animate({
            scrollTop: dest - 150
        }, 800);

    });
});
/*** About Us  Quick Access Links ***/

