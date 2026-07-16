
function getVals(){
    // Get slider values
    var parent = this.parentNode;
    var slides = parent.getElementsByTagName("input");
    var slide1 = parseFloat( slides[0].value );
    var slide2 = parseFloat( slides[1].value );
    // Neither slider will clip the other, so make sure we determine which is larger
    if( slide1 > slide2 ){ var tmp = slide2; slide2 = slide1; slide1 = tmp; }

    var displayElement = document.getElementsByClassName("result-page-low")[0];

    var displayElement2 = document.getElementsByClassName("result-page-high")[0];
    displayElement.innerHTML = slide1;
    displayElement2.innerHTML = slide2;
}

window.onload = function(){
    // Initialize Sliders
    var sliderSections = document.getElementsByClassName("range-slider");
    for( var x = 0; x < sliderSections.length; x++ ){
        var sliders = sliderSections[x].getElementsByTagName("input");
        for( var y = 0; y < sliders.length; y++ ){
            if( sliders[y].type ==="range" ){
                sliders[y].oninput = getVals;
                // Manually trigger event first time to display values
                sliders[y].oninput();
            }
        }
    }
}
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
// $(function(){
//
//     // var note = $('#note'),
//     //     ts = new Date(2012, 0, 1),
//     //     newYear = true;
//
//     // if((new Date()) > ts){
//     //     // The new year is here! Count towards something else.
//     //     // Notice the *1000 at the end - time must be in milliseconds
//     //     ts = (new Date()).getTime() + 10*24*60*60*1000;
//     //     newYear = false;
//     // }
//
//     // $('#countdown').countdown({
//     //     timestamp   : ts,
//     //     callback    : function(days, hours, minutes, seconds){
//     //
//     //         var message = "";
//     //
//     //         message += days + " روز" + ", " + ( days==1 ? '':'' ) ;
//     //         message += hours + "ساعت" + ", " + ( hours==1 ? '':'' ) ;
//     //         message += minutes + " دقیقه" + " و " + ( minutes==1 ? '':'' ) ;
//     //         message += seconds + " ثانیه" + ( seconds==1 ? '':'' ) + " <br />";
//     //
//     //         if(newYear){
//     //             message += "left until the new year!";
//     //         }
//     //         else {
//     //             message += "فقط 10 روز دیگر مانده است!";
//     //         }
//     //
//     //         note.html(message);
//     //     }
//     // });
//
// });

$(document).ready(function () {

    $('.cart-body').click(function(){
        $('.cart-detail').slideToggle();
    });
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    $('.leftLst, .rightLst').click(function () {
        var condition = $(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize();




    $(window).resize(function () {
        ResCarouselSize();
    });

    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
        var sampwidth = $(itemsMainDiv).width();
        var bodyWidth = $('body').width();
        $(itemsDiv).each(function () {
            id = id + 1;
            var itemNumbers = $(this).find(itemClass).length;
            btnParentSb = $(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            $(this).parent().attr("id", "MultiCarousel" + id);


            if (bodyWidth >= 1200) {
                incno = itemsSplit[2];
                itemWidth = sampwidth /5;
            }
            else if (bodyWidth >= 992) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / 2;
            }
            else if (bodyWidth >= 768) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / 1;
            }
          
            else {
                incno = itemsSplit[2];
                itemWidth = sampwidth /1;
            }
            $(this).css({ 'transform': 'translateX(0px)', 'width': itemWidth * itemNumbers });
            $(this).find(itemClass).each(function () {
                $(this).outerWidth(itemWidth);
            });

            // $(".leftLst").addClass("over");
            // $(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';

        var divStyle = $(el + ' ' + itemsDiv).css('transform');

        var values = divStyle.match(/-?[\d\.]+/g);

        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            $(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                $(el + ' ' + leftBtn).addClass("over");
            }
        }
        else if (e == 1) {
            var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            $(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                $(el + ' ' + rightBtn).addClass("over");
            }
        }
        $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + $(ee).parent().parent().parent().attr("id");
        var slide = $(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    }


    $('.slider-ads').height($('.slider-slider').height());
    $('.offer-side').height($('.offer-main').height());
    $('.offer-ads').height($('.offer-section').height());
    $('.offer2').height($('.offer2-slider').height());
    $('.see-all').height($('.see-all-move').height());
    $('.bank-section').height($('.banks-sliders').height());


    $('.toggle-btn').click(function(){
        $('.hidden-menu').toggle();
    });
    $('.close').click(function(){
        $('#myModal').hide();
    });
    $('#see-all-comments').click(function(){
        $('.all-comments').show();
    });
    $('.search-toggle').click(function(){
        $('.small-search').toggle();
    });
});


$(window).resize(function () {

    $('.slider-ads').height($('.slider-slider').height());
    $('.offer-side').height($('.offer-main').height());
    $('.offer-ads').height($('.offer-section').height());
    $('.offer2').height($('.offer2-slider').height());
    $('.see-all').height($('.see-all-move').height());
    $('.bank-section').height($('.banks-sliders').height());

});




$(document).ready(function(){



    $('.mimage').click(function(){
        var id = $(this).attr('id');
        var src = $('#'+id+' .modalim').attr('src');

        $('.modal-content').attr('src',src);
        $('#myModal').show();
    });
});


