<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
 * Environment-aware robots.txt (a physical public/robots.txt, if present,
 * is served by the web server first and overrides this route).
 * Non-production: block everything. Production: allow everything.
 */
Route::get('/robots.txt', function () {
    $content = config('app.env') !== 'production'
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow:\n\nSitemap: " . rtrim(config('app.url'), '/') . "/sitemap.xml\n";

    return response($content, 200)->header('Content-Type', 'text/plain');
});

/*
 | English content lives on its own dedicated site (trainwithehsan.com), hosted
 | outside Iran. To avoid duplicate-content / keyword-cannibalisation between the
 | two domains — and to consolidate all English SEO authority onto the English
 | site — the legacy /en English landing on this Persian domain is 301-redirected
 | to trainwithehsan.com. Registered here, above every group/catch-all, so it
 | always wins regardless of how /en used to resolve. The header language switcher
 | already links straight to trainwithehsan.com; this redirect just catches any
 | old inbound links / bookmarks / crawler memory of /en.
 |
 | NOTE: this is a one-way link FROM this Persian domain OUT to the English site.
 | We deliberately do NOT add any reverse reference (no hreflang / link) on
 | trainwithehsan.com back to this domain, so the English site stays fully
 | independent of Iranian hosting (important for its Google AdSense eligibility).
 */
Route::redirect('/en', 'https://trainwithehsan.com', 301);
Route::get('/en/{any}', fn () => redirect('https://trainwithehsan.com', 301))->where('any', '.*');

// NOTE: the old public /clear-cache-now route was removed for security.
// Maintenance actions now live under the authenticated admin panel:
// /panel/manager/maintenance/{action} (see routes/admin.php).
/* ============================= user controller public ============================================ */
Route::group(['namespace' => 'Site','middleware' => ['checkBlock']], function () {
    /* site index*/
    Route::get('/', 'SiteController@index')->name('site.index');
    Route::get('/pdf', 'SiteController@pdf')->name('site.pdf');
    /* end site index */

    Route::get('images/{productId}/{image}', [
        'as' => 'images.show',
        'uses' => 'SiteController@image',
        'middleware' => 'auth',
    ]);

    /* start basket  , delete or add to basket */
    Route::post('add-to-cart', 'BasketController@index')->name('site.basket');
    Route::post('delete-from-basket', 'BasketController@deleteFromBasket')->name('site.deleteFromBasket');
    Route::post('insert-from-basket', 'BasketController@insertFromBasket')->name('site.insertFromBasket');
    Route::post('checkCouponDiscount', 'BasketController@checkCoupon')->name('site.check.coupon');
    /* end basket , delete or add to basket */

    /* start product */
    Route::get('/products/{slug?}', 'ProductController@products')->name('site.products');
    Route::get('/products/tag/{slug}', 'SiteController@ProductTags')->name('site.product.tag');
    Route::post('/products/ajaxVariationColor', 'ProductController@ajaxVariationColor')->name('site.product.ajaxVariationColor');
    Route::post('/products/ajaxVariationSize', 'ProductController@ajaxVariationSize')->name('site.product.ajaxVariationSize');
    /* end product */

    Route::get('file/{id}/{side?}','ProductController@getfile')->name('UserDownloadFile')->middleware(['signed','auth']);
    Route::get('files/{id}/{side?}','ProductController@getSignedUrl')->middleware('auth');



    /* start ajax when seller or clicked seller */
    Route::post('/products/ajaxSeller', 'ProductController@ajaxSeller')->name('site.product.ajaxSeller');
    /* end ajax when seller or clicked seller */


    /* start check address in basket */
    Route::post('basket/address/check', 'BasketController@addressCheck')->name('site.basket.address.check');
    /* end check address in basket */

    /* start checkout */
    Route::get('basket/checkout', 'CheckoutController@index')->name('site.basket.checkout')->middleware(['auth:web']);
    Route::get('basket/checkout/address', 'CheckoutController@address')->name('site.basket.checkout.address')->middleware(['auth:web']);
    Route::get('basket/checkout/review', 'CheckoutController@review')->name('site.basket.checkout.review')->middleware(['auth:web']);
    Route::post('basket/checkout/review/showShippingCost', 'CheckoutController@showShippingCost')->name('site.basket.checkout.shippingCost')->middleware(['auth:web']);
    /* end checkout */

    /* Start Of Compare Route*/
    Route::get('compare', 'ProductController@compare')->name('site.compare');
    Route::post('compare', 'ProductController@addCompare')->name('site.add.compare');
    Route::post('remove-compare', 'ProductController@removeCompare')->name('site.remove.compare');
    /* End Of Compare Route*/

    /* Start Of Rating*/
    Route::post('rate', 'ProductController@rating')->name('site.rate.store');
    /* End Of Rating*/


    Route::post('loadF', 'ProductController@loadF')->name('site.product.load');
    Route::post('player', 'ProductController@player')->name('site.product.player');


    /* start finish basket */
    Route::post('basket/checkout/finish', 'BasketZarinPalController@finishBasket')->name('site.basket.finish');
    Route::any('checkBasket', 'BasketZarinPalController@checkBasket')->name('site.basket.check');
    /* end finish basket */


    /* start article page */
    Route::get('/article/{slug?}', 'SiteController@article')->name('site.article');
    Route::get('/blog/', 'SiteController@article')->name('site.blog');
    Route::get('/category-article/{slug?}', 'SiteController@categoryArticle')->name('site.category.article');
    Route::get('/article/tag/{slug}', 'SiteController@ArticleTags')->name('site.article.tag');
    /* Add Comment Single article*/
    Route::post('addcomment/{id}', 'SiteController@SaveComment')->name('site.add.comment');
    /* Add Comment Single article*/
    /* end article  page */

    /* start  page */
    Route::get('/page/{slug?}', 'SiteController@page')->name('site.page');
    /* End  page */

    /* start  category Product */
    Route::get('/categorys/{slug?}', 'SiteController@categorys')->name('site.categorys');
    Route::post('/filter/', 'SiteController@FilterBaseOnAttr')->name('site.filter.attr');
    /* End  category Product */


    /* start  sales */
    Route::get('/specials', 'ProductController@specialProducts')->name('site.special.products');
    /* End  sales */


    /* start  show Products Base On Category */
    Route::get('category/{slug?}', 'SiteController@CategoryList')->name('site.category.list');
    /* end  show Products Base On Category */


    /**Route list Product Base on brand**/
    Route::get('brand/{slug}', 'SiteController@BrandList')->name('brand.list.product');
    /**Route list Product Base on brand**/

    /* Search header*/
    Route::get('search', 'SiteController@SearchHeader')->name('site.search');
    /* Search header*/

    /* search Live */
    Route::post('search/live', 'SiteController@searchLive')->name('site.search.live');
    /* search Live */


    /* start newsLatter */
    Route::post('/newsLetter/save-email', 'SiteController@saveNewsLatter')->name('site.saveNewsLatter');
    /* end newsLatter */

    /* start AddFavorites */
    Route::post('addfavorites', 'SiteController@AddFavorites')->name('add.favorites');
    /* end AddFavorites */

    /* start AddFavorites */
    Route::post('changeBrand', 'SiteController@changeBrand')->name('site.change.brand');
    /* end AddFavorites */

    /* start contact */
    Route::get('/poll', 'SiteController@poll')->name('site.poll');
    Route::post('/polls', 'SiteController@vote')->name('site.vote');
    Route::get('/faq', 'SiteController@question')->name('site.question');
    Route::get('/suggestion', 'SiteController@suggestion')->name('site.suggestion');
    Route::get('/contact-us', 'SiteController@contactUs')->name('site.contactUs');
    Route::post('/contact-us', 'SiteController@saveContactUs')->name('site.contactUs.save');
    Route::get('/exam', 'SiteController@exam')->name('site.exam');
    Route::post('/exam-store', 'SiteController@examStore')->name('site.exam.store');

    Route::get('/consultation', 'SiteController@Consultation')->name('site.consultation');
    Route::post('/consultation', 'SiteController@saveConsultation')->name('site.consultation.save');
    /* end contact */

    /* start about */
    Route::get('/about-us', 'SiteController@aboutUs')->name('site.aboutUs');
    /* end about */

    /* Start Of Auction Route ... */
    Route::get('buy-auction/{slug?}', 'SiteController@buyAuction')->name('site.buy.auction');
    Route::post('suggestion', 'SiteController@storeSuggestion')->name('site.store.suggestion');
    Route::post('up-to-date-suggestion', 'SiteController@updateSuggestion')->name('site.update.suggestions');
    Route::post('winner-auction', 'SiteController@winnerAuction')->name('site.winner.auction');
    /* End Of Auction Route ... */


});
/* ============================= end controller public ============================================ */


/* login and register */
Route::get('signin', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('signin', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');
Route::get('signup-colleague', 'Auth\RegisterController@showRegistrationColleagueForm')->name('register.colleague');
Route::post('signup-colleague', 'Auth\RegisterController@registerColleague')->name('register.colleague.store');
// Registration Routes...


/* activation */
Route::get('activation/view', 'Auth\RegisterController@activationView')->name('activation.user.view');
Route::post('activation', 'Auth\RegisterController@activation')->name('activation.user');
/* activation */


/* code activation send again */
Route::get('send/activation/code', 'Auth\RegisterController@sendCodeAgain')->name('send.activation.code.again');
Route::post('send/activation/code/request', 'Auth\RegisterController@sendCodeRequest')->name('send.activation.code.request');
/* code activation send again */


/* ajax city register */
Route::post('register/cityAjax', 'Auth\RegisterController@cityAjax')->name('site-register-ajax');
/* ajax city register */


/* update password email */
Route::get('reset/password/update', 'Auth\ForgotPasswordController@updatePassword')->name('reset.password.update.view');
Route::post('reset/password/update/store', 'Auth\ForgotPasswordController@updatePasswordStore')->name('reset.password.update.store');
/* update password email */

/* update password sms */
Route::get('reset/password/sms', 'Auth\ForgotPasswordController@updatePasswordSms')->name('reset.password.update.sms.view');
Route::post('reset/password/send/sms', 'Auth\ForgotPasswordController@updatePasswordSendSms')->name('reset.password.send.sms');
Route::get('reset/password/update/changeView', 'Auth\ForgotPasswordController@updatePasswordSmsChange')->name('reset.password.send.sms.change');
Route::post('reset/password/update/store', 'Auth\ForgotPasswordController@updatePasswordSmsChangeStore')->name('reset.password.send.sms.change.store');
/* update password sms */


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});





Route::get('/sitemap.xml', function () {
    $articles = \App\Model\Article::where('status', 1)
        ->whereNull('deleted_at')
        ->select('slug', 'updated_at')
        ->get();

    $pages = \App\Model\Page::where('status', 1)
        ->where('lang', 'fa')
        ->whereNull('deleted_at')
        ->select('slug', 'updated_at')
        ->get();

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // صفحه اصلی
    $content .= '<url><loc>https://ehsandibazar.com/</loc><priority>1.00</priority></url>';

    // درباره ما
    $content .= '<url><loc>https://ehsandibazar.com/about-us</loc><priority>0.80</priority></url>';

    // صفحات
    foreach ($pages as $page) {
        $content .= '<url>';
        $content .= '<loc>https://ehsandibazar.com/page/' . $page->slug . '</loc>';
        $content .= '<lastmod>' . ($page->updated_at ? $page->updated_at->format('Y-m-d') : date('Y-m-d')) . '</lastmod>';
        $content .= '<priority>0.80</priority>';
        $content .= '</url>';
    }

    // مقالات
    foreach ($articles as $article) {
        $content .= '<url>';
        $content .= '<loc>https://ehsandibazar.com/article/' . $article->slug . '</loc>';
        $content .= '<lastmod>' . ($article->updated_at ? $article->updated_at->format('Y-m-d') : date('Y-m-d')) . '</lastmod>';
        $content .= '<priority>0.80</priority>';
        $content .= '</url>';
    }

    // محصولات (صفحات فروش — مهم‌ترین صفحات تجاری سایت)
    $products = \App\Model\Product::where('status', 1)
        ->select('slug', 'updated_at')
        ->get();

    $content .= '<url><loc>https://ehsandibazar.com/products</loc><priority>0.90</priority></url>';

    foreach ($products as $product) {
        // Product::$updated_at may arrive as a plain string on this model,
        // so avoid assuming a Carbon instance.
        $lastmod = $product->updated_at instanceof \DateTimeInterface
            ? $product->updated_at->format('Y-m-d')
            : (is_string($product->updated_at) && $product->updated_at !== '' ? substr($product->updated_at, 0, 10) : date('Y-m-d'));

        $content .= '<url>';
        $content .= '<loc>https://ehsandibazar.com/products/' . rawurlencode($product->slug) . '</loc>';
        $content .= '<lastmod>' . $lastmod . '</lastmod>';
        $content .= '<priority>0.90</priority>';
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200)->header('Content-Type', 'application/xml');
});
