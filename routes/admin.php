<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:web'], 'prefix' => 'panel'], function () {

    // Manager Route ...
    Route::group(['middleware' => ['checkAdmin', 'optimizeImages'], 'namespace' => 'Admin', 'prefix' => 'manager'], function () {


        //================================================== dashboard =============
        Route::get('/', 'AdminController@dashboard')->name('panel.dashboard.index');
        //================================================== dashboard =============

        // ============================== SEO red-line gate =====================
        // docs/SEO-RED-LINE.md — runs on THIS (staging) server (which can reach
        // production) and diffs every production sitemap URL against staging.
        // Batched via ?limit=&offset= so it never hits the request timeout.
        // MUST be registered BEFORE maintenance/{action} below, otherwise the
        // catch-all matches "maintenance/seo-check" first and 404s.
        Route::get('maintenance/seo-check', function (\Illuminate\Http\Request $request, \App\Services\Seo\SeoRegressionChecker $checker) {
            $base = rtrim((string) $request->query('base', 'https://ehsandibazar.com'), '/');
            $candidate = rtrim((string) $request->query('candidate', $request->getSchemeAndHttpHost()), '/');
            $limit = max(1, (int) $request->query('limit', 15));
            $offset = max(0, (int) $request->query('offset', 0));

            @set_time_limit(0);
            $report = $checker->run($base, $candidate, $limit, $offset);

            $badge = [
                'ok' => ['🟢 سبز', '#16a34a'],
                \App\Services\Seo\SeoRegressionChecker::WARNING => ['🟡 زرد — بازبینی دستی', '#ca8a04'],
                \App\Services\Seo\SeoRegressionChecker::CRITICAL => ['🔴 قرمز — عبور به production ممنوع', '#dc2626'],
            ][$report['verdict']];

            $rows = '';
            foreach ($report['rows'] as $row) {
                if (empty($row['issues'])) {
                    continue;
                }
                $items = '';
                foreach ($row['issues'] as $i) {
                    $mark = $i['severity'] === \App\Services\Seo\SeoRegressionChecker::CRITICAL ? '🔴' : '🟡';
                    $items .= '<div>'.$mark.' <b>'.e($i['field']).'</b>: ['
                        .e($i['base'] ?? '∅').'] → ['.e($i['candidate'] ?? '∅').']</div>';
                }
                $rows .= '<tr><td dir="ltr">'.e($row['path']).'</td><td>'.$row['base_status']
                    .' → '.$row['candidate_status'].'</td><td dir="ltr">'.$items.'</td></tr>';
            }
            if ($rows === '') {
                $rows = '<tr><td colspan="3">هیچ رگرسیونی در این batch پیدا نشد.</td></tr>';
            }

            $end = $offset + $report['checked'];
            $next = $end < $report['total_urls']
                ? '<a href="?base='.urlencode($base).'&candidate='.urlencode($candidate)
                    .'&limit='.$limit.'&offset='.$end.'">▶ batch بعدی ('.$end.'..'.min($end + $limit, $report['total_urls']).')</a>'
                : '<b>پایانِ فهرست.</b>';

            $html = '<!doctype html><meta charset="utf-8"><title>SEO Red-Line Gate</title>'
                .'<div style="font-family:Tahoma,sans-serif;max-width:1000px;margin:2rem auto;direction:rtl">'
                .'<h2>دروازه‌ی خط قرمز SEO</h2>'
                .'<div style="padding:.6rem 1rem;border-radius:8px;color:#fff;display:inline-block;background:'.$badge[1].'">'.$badge[0].'</div>'
                .'<p>مرجع: <code dir="ltr">'.e($base).'</code> · کاندیدا: <code dir="ltr">'.e($candidate).'</code></p>'
                .'<p>بررسی‌شده: '.$report['checked'].' از '.$report['total_urls'].' (offset '.$offset.') · '
                .'🔴 '.$report['critical'].' · 🟡 '.$report['warning'].'</p>'
                .'<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%">'
                .'<tr style="background:#f3f4f6"><th>URL</th><th>status</th><th>مشکل‌ها</th></tr>'.$rows.'</table>'
                .'<p style="margin-top:1rem">'.$next.'</p>'
                .'</div>';

            return response($html);
        })->name('panel.seo-check');
        // ============================== SEO red-line gate =====================

        // ============================== Media Library backfill ================
        // عکس‌های موجودِ سایت (App\Model\Image) را در جدولِ media ثبت می‌کند تا در Media Library
        // دیده شوند. batch به batch با ?limit=&offset=. هیچ فایلی تغییر نمی‌کند (صفر ریسکِ SEO).
        Route::get('maintenance/media-backfill', function (\Illuminate\Http\Request $request, \App\Services\Media\MediaBackfillService $svc) {
            @set_time_limit(0);
            $limit = max(1, (int) $request->query('limit', 200));
            $offset = max(0, (int) $request->query('offset', 0));
            // source=legacy → مدلِ Image (تصاویرِ محصول/مقاله)؛ source=disk → فایل‌منیجر (photos/files).
            $source = $request->query('source', 'legacy') === 'disk' ? 'disk' : 'legacy';

            $r = $source === 'disk'
                ? $svc->backfillFromPublicDisk($limit, $offset)
                : $svc->backfillFromLegacyImages($limit, $offset);

            $base = '?source='.$source.'&limit='.$limit;
            $next = $r['done']
                ? '<b>پایانِ این منبع.</b>'
                : '<a href="'.$base.'&offset='.$r['next_offset'].'">▶ batch بعدی (از '.$r['next_offset'].')</a>';

            $other = $source === 'legacy'
                ? '<a href="?source=disk">▶ حالا عکس‌های File Manager را هم ثبت کن (photos/files)</a>'
                : '<a href="?source=legacy">▶ منبعِ تصاویرِ محصول/مقاله (Image)</a>';

            $html = '<!doctype html><meta charset="utf-8"><title>Media Backfill</title>'
                .'<div style="font-family:Tahoma,sans-serif;max-width:720px;margin:2rem auto;direction:rtl">'
                .'<h2>ثبتِ عکس‌های سایت در Media Library</h2>'
                .'<p>منبع: <b>'.($source === 'disk' ? 'File Manager (photos/files)' : 'تصاویرِ محصول/مقاله (Image)').'</b></p>'
                .'<p>این batch: بررسی‌شده <b>'.$r['scanned'].'</b> · ثبت‌شده‌ی جدید <b style="color:#16a34a">'.$r['registered'].'</b> · قبلاً بود <b>'.$r['skipped'].'</b></p>'
                .'<p>کلِ این منبع: <b>'.$r['total'].'</b> (تا offset '.$r['next_offset'].' بررسی شد)</p>'
                .'<p style="margin-top:1rem">'.$next.'</p>'
                .'<hr><p>'.$other.'</p>'
                .'<p style="color:#888">بعد از پایان، به Media Library برو. WebP را per-image از دکمه‌ی «بازتولید» بساز.</p>'
                .'</div>';

            return response($html);
        })->name('panel.media-backfill');
        // ============================== Media Library backfill ================

        // ============================== maintenance (admin-only) ==============
        // Shell-less host: these let the admin run deploy-time artisan tasks
        // safely from the browser after each "Update from Remote".
        Route::get('maintenance/{action}', function ($action) {
            $allowed = [
                'cache-clear'  => ['cache:clear', 'config:clear', 'view:clear'],
                'view-cache'   => ['view:clear', 'view:cache'],
                'migrate'      => ['migrate', ['--force' => true]],
                // symlinkِ public/storage → storage/app/public تا فایل‌های آپلودشده‌ی
                // Media Library از طریق /storage/... عمومی سرو شوند (روی هاستِ بدون‌شل).
                'storage-link' => ['storage:link'],
                // clear-compiled + package:discover regenerate the package manifest
                // so newly pulled packages (e.g. Filament) are picked up on a
                // shell-less host after each "Update from Remote".
                'optimize'     => ['clear-compiled', 'package:discover', 'config:clear', 'view:clear', 'view:cache', 'cache:clear'],
            ];

            if (! array_key_exists($action, $allowed)) {
                abort(404);
            }

            $output = [];
            $commands = $allowed[$action];
            for ($i = 0; $i < count($commands); $i++) {
                $cmd = $commands[$i];
                $params = [];
                if (isset($commands[$i + 1]) && is_array($commands[$i + 1])) {
                    $params = $commands[$i + 1];
                    $i++;
                }
                \Illuminate\Support\Facades\Artisan::call($cmd, $params);
                $output[] = "$ artisan {$cmd}\n" . trim(\Illuminate\Support\Facades\Artisan::output());
            }

            // On a shell-less host with opcache.validate_timestamps=0, edits to
            // *existing* PHP files (e.g. routes/admin.php) stay cached until
            // opcache is reset. Do it here so "optimize" fully picks up a pull.
            if (in_array($action, ['optimize', 'cache-clear'], true) && function_exists('opcache_reset')) {
                $ok = @opcache_reset();
                $output[] = '$ opcache_reset()  → ' . ($ok ? 'OK' : 'unavailable');
            }

            return response('<pre dir="ltr">' . e(implode("\n\n", $output)) . '</pre>');
        })->name('panel.maintenance');
        // ============================== maintenance ===========================

        // ================================================= Start Of favorite =============
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', 'FavriteController@index')->name('panel.favorite.index');
            Route::DELETE('/delete/{id}', 'FavriteController@delete')->name('panel.favorite.delete');
        });
        // ================================================= End Of favorite =============

        // ================================================= Start Of Ticket =============
        Route::group(['prefix' => 'tickets'], function () {
            Route::get('/', 'TicketController@index')->name('panel.ticket.index');
            Route::get('view/{id}', 'TicketController@TicketView')->name('panel.ticket.view');
            Route::post('send/', 'TicketController@SendTicket')->name('panel.ticket.sent');
            Route::post('send-answer/', 'TicketController@SendTicketAnswer')->name('panel.ticket.answer.sent');
            Route::delete('delete/{id}', 'TicketController@delete')->name('panel.ticket.delete');
            Route::get('status/', 'TicketController@status')->name('panel.ticket.status');
            Route::get('status-answer/{id}', 'TicketController@StatusAnswer')->name('panel.ticket.status.answer');
        });
        // ================================================= End Of Ticket =============

        // ================================================= Start Of reporting =============
        Route::group(['prefix' => 'reporting'], function () {
            Route::get('/', 'ReportingController@index')->name('panel.reporting.index');
            Route::post('/', 'ReportingController@report')->name('panel.reporting.report');
        });
        // ================================================= End Of reporting =============

        /* start digital Product controller */
        Route::group(['prefix' => 'digital-product'], function () {
            Route::get('/', 'DigitalProductController@index')->name('panel.digitalProduct.index');
            Route::get('add', 'DigitalProductController@add')->name('panel.digitalProduct.add');
            Route::post('store', 'DigitalProductController@store')->name('panel.digitalProduct.store');
            Route::delete('delete/{userId?}', 'DigitalProductController@delete')->name('panel.digitalProduct.delete');
            Route::get('show/{user}', 'DigitalProductController@show')->name('panel.digitalProduct.show');
        });
        /* end digital Product controller */


        /* start orders controller */
        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', 'OrderController@index')->name('panel.order.index');
            Route::post('shipping-code/{id}', 'OrderController@shippingCode')->name('panel.shipping.code');
            Route::post('print', 'OrderController@print')->name('panel.order.print');
            Route::post('status-sending', 'OrderController@changeStatus')->name('panel.order.changeSending');
            Route::get('sending', 'OrderController@sendingOrder')->name('panel.order.sending');
            Route::get('canceled', 'OrderController@canceledOrder')->name('panel.order.canceled');
            Route::get('unpaid', 'OrderController@unpaidOrder')->name('panel.order.unpaid');
            Route::get('pending', 'OrderController@pendingOrder')->name('panel.order.pending');
            Route::delete('delete/{id}', 'OrderController@delete')->name('panel.order.delete');
            Route::get('search', 'OrderController@orderSearch')->name('user.order-search');
            Route::get('{status}/status/{id}', 'OrderController@status')->name('panel.order.status');
        });
        /* end orders controller */

        /* start discount controller */
        Route::group(['prefix' => 'discount'], function () {
            Route::get('/', 'DiscountController@index')->name('panel.discount.index');
            Route::get('/create', 'DiscountController@create')->name('panel.discount.create');
            Route::post('/store', 'DiscountController@store')->name('panel.discount.store');
            Route::get('/edit/{id}', 'DiscountController@edit')->name('panel.discount.edit');
            Route::PATCH('/update/{id}', 'DiscountController@update')->name('panel.discount.update');
            Route::delete('/delete/{id}', 'DiscountController@delete')->name('panel.discount.delete');
            Route::get('/status/{id}', 'DiscountController@status')->name('panel.discount.status');

            /*Route For get Category , brand , Product ,users,role On Discount*/
            Route::post('/getAllTypeOnDiscount', 'DiscountController@GetCategory')->name('get.All.TypeOn.Discount');
            /*Route For get Category , brand , Product ,users,role On Discount*/

            /*Route For get Category , brand , Product ,users,role On Discount*/
            Route::post('/getAllTypeDiscount', 'DiscountController@GetChangeType')->name('get.All.Type.Discount');
            /*Route For get Category , brand , Product ,users,role On Discount*/

        });
        /* end discount controller */


        /* start shipping-cost controller */
        Route::group(['prefix' => 'shippingCost'], function () {
            Route::get('/', 'ShippingCostController@index')->name('panel.shippingCost.index');
            Route::get('/create', 'ShippingCostController@create')->name('panel.shippingCost.create');
            Route::post('/store', 'ShippingCostController@store')->name('panel.shippingCost.store');
            Route::get('/edit/{id}', 'ShippingCostController@edit')->name('panel.shippingCost.edit');
            Route::PATCH('/update/{id}', 'ShippingCostController@update')->name('panel.shippingCost.update');
            Route::delete('/delete/{id}', 'ShippingCostController@delete')->name('panel.shippingCost.delete');
        });
        /* end shipping-cost controller */


        /* start attribute Group controller */
        Route::group(['prefix' => 'attribute-groups'], function () {
            Route::get('', 'AttributeGroupController@index')->name('panel.attributeGroup.index');
            Route::get('create', 'AttributeGroupController@create')->name('panel.attributeGroup.create');
            Route::post('store', 'AttributeGroupController@store')->name('panel.attributeGroup.store');
            Route::get('edit/{id}', 'AttributeGroupController@edit')->name('panel.attributeGroup.edit');
            Route::PATCH('update/{id}', 'AttributeGroupController@update')->name('panel.attributeGroup.update');
            Route::delete('delete/{id}', 'AttributeGroupController@delete')->name('panel.attributeGroup.delete');
            Route::get('status/{id}', 'AttributeGroupController@status')->name('panel.attributeGroup.status');
        });
        /* end attribute Group controller */


        /* start attribute controller */
        Route::group(['prefix' => 'attributes'], function () {
            Route::get('', 'AttributeController@index')->name('panel.attribute.index');
            Route::get('create', 'AttributeController@create')->name('panel.attribute.create');
            Route::post('store', 'AttributeController@store')->name('panel.attribute.store');
            Route::get('edit/{id}', 'AttributeController@edit')->name('panel.attribute.edit');
            Route::PATCH('update/{id}', 'AttributeController@update')->name('panel.attribute.update');
            Route::delete('delete/{id}', 'AttributeController@delete')->name('panel.attribute.delete');
            Route::get('status/{id}', 'AttributeController@status')->name('panel.attribute.status');
        });
        /* end attribute controller */

        /* start attribute-type controller */
        Route::group(['prefix' => 'attribute-type'], function () {
            Route::get('/', 'AttributeTypeController@index')->name('panel.attribute-type.index');
            Route::get('create', 'AttributeTypeController@create')->name('panel.attribute-type.create');
            Route::post('store', 'AttributeTypeController@store')->name('panel.attribute-type.store');
            Route::get('edit/{id}', 'AttributeTypeController@edit')->name('panel.attribute-type.edit');
            Route::PATCH('update/{id}', 'AttributeTypeController@update')->name('panel.attribute-type.update');
            Route::delete('delete/{id}', 'AttributeTypeController@delete')->name('panel.attribute-type.delete');
            Route::get('status/{id}', 'AttributeTypeController@status')->name('panel.attribute-type.status');
        });
        /* end attribute-type controller */


        /* start attribute-type-value controller */
        Route::group(['prefix' => 'attribute-type-value'], function () {
            Route::get('/', 'AttributeTypeValueController@index')->name('panel.attribute-type-value.index');
            Route::get('create', 'AttributeTypeValueController@create')->name('panel.attribute-type-value.create');
            Route::post('store', 'AttributeTypeValueController@store')->name('panel.attribute-type-value.store');
            Route::get('edit/{id}', 'AttributeTypeValueController@edit')->name('panel.attribute-type-value.edit');
            Route::PATCH('update/{id}', 'AttributeTypeValueController@update')->name('panel.attribute-type-value.update');
            Route::delete('delete/{id}', 'AttributeTypeValueController@delete')->name('panel.attribute-type-value.delete');
            Route::get('status/{id}', 'AttributeTypeValueController@status')->name('panel.attribute-type-value.status');
        });
        /* end attribute-type-value controller */

        /* start category-product controller */
//        Route::group(['prefix' => 'category-product'], function () {
//            Route::get('/', 'CategoryProductController@index')->name('panel.categoryProduct.index');
//            Route::get('create', 'CategoryProductController@create')->name('panel.categoryProduct.create');
//            Route::post('store', 'CategoryProductController@store')->name('panel.categoryProduct.store');
//            Route::get('edit/{id}', 'CategoryProductController@edit')->name('panel.categoryProduct.edit');
//            Route::PATCH('update/{id}', 'CategoryProductController@update')->name('panel.categoryProduct.update');
//            Route::delete('delete/{id}', 'CategoryProductController@delete')->name('panel.categoryProduct.delete');
//            Route::get('status/{id}', 'CategoryProductController@status')->name('panel.categoryProduct.status');
//            Route::post('is_searchFilterAble', 'CategoryProductController@isSearchFilter')->name('panel.categoryProduct.isSearchFilter');
//        });
        /* end category-product controller */

        /* start brand controller */
        Route::group(['prefix' => 'brand'], function () {
            Route::get('/', 'BrandController@index')->name('panel.brand.index');
            Route::get('create', 'BrandController@create')->name('panel.brand.create');
            Route::post('store', 'BrandController@store')->name('panel.brand.store');
            Route::get('edit/{id}', 'BrandController@edit')->name('panel.brand.edit');
            Route::PATCH('update/{id}', 'BrandController@update')->name('panel.brand.update');
            Route::delete('delete/{id}', 'BrandController@delete')->name('panel.brand.delete');
            Route::get('status/{id}', 'BrandController@status')->name('panel.brand.status');
            Route::post('save-nested', 'BrandController@saveNested')->name('panel.nestedBrand.store');
        });
        /* end brand controller */

        /* start product controller */
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'ProductController@index')->name('panel.product.index');
            Route::get('create', 'ProductController@create')->name('panel.product.create');
            Route::post('store', 'ProductController@store')->name('panel.product.store');
            Route::get('edit/{id}', 'ProductController@edit')->name('panel.product.edit');
            Route::PATCH('update/{id}', 'ProductController@update')->name('panel.product.update');
            Route::delete('delete/{id}', 'ProductController@delete')->name('panel.product.delete');
            Route::get('status/{id}', 'ProductController@status')->name('panel.product.status');
            Route::post('ajax-attributes', 'ProductController@ajax_attributes')->name('panel.product.ajax_attributes');
            Route::post('ajax-attributes-type-value', 'ProductController@ajax_attributes_type_value')->name('panel.product.ajax_attributes_type_value');
            /* color or size */
            Route::post('ajax-attributes-variations', 'ProductController@ajax_attributes_variations')->name('panel.product.ajax_attributes_variations');
            /* color or size */
        });
        /* end product controller */

        /* start Auction controller */
        Route::group(['prefix' => 'auction'], function () {
            Route::get('/', 'AuctionController@index')->name('panel.auction.index');
        });
        /* end Auction controller */

        /* start tag controller */
        Route::group(['prefix' => 'tag'], function () {
            Route::get('/', 'TagController@index')->name('panel.tag.index');
            Route::get('/create', 'TagController@create')->name('panel.tag.create');
            Route::post('/store', 'TagController@store')->name('panel.tag.store');
            Route::get('/edit/{id}', 'TagController@edit')->name('panel.tag.edit');
            Route::PATCH('/update/{id}', 'TagController@update')->name('panel.tag.update');
            Route::delete('/delete/{id}', 'TagController@delete')->name('panel.tag.delete');
            Route::get('/status/{id}', 'TagController@status')->name('panel.tag.status');
        });
        /* end tag controller */

        /* start category controller */
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('panel.category.index');
            Route::post('save-nested-categories', 'CategoryController@saveNestedCategories')->name('panel.nested-categories.store');
            Route::get('/create', 'CategoryController@create')->name('panel.category.create');
            Route::post('/store', 'CategoryController@store')->name('panel.category.store');
            Route::get('/edit/{id}', 'CategoryController@edit')->name('panel.category.edit');
            Route::PATCH('/update/{id}', 'CategoryController@update')->name('panel.category.update');
            Route::get('/delete/{id}', 'CategoryController@delete')->name('panel.category.delete');
            Route::post('/getOtherCategories', 'CategoryController@getOtherCategories')->name('panel.category.getOtherCategories');

            Route::get('/attributed/{id}', 'CategoryController@attributedForm')->name('panel.category.attributedForm');
            Route::post('/attributed/{id}', 'CategoryController@attributed')->name('panel.category.attributed');

        });
        /* end category controller */


        /* start article controller */
        Route::group(['prefix' => 'article'], function () {
            Route::get('/', 'ArticleController@index')->name('panel.article.index');
            Route::get('/create', 'ArticleController@create')->name('panel.article.create');
            Route::post('/store', 'ArticleController@store')->name('panel.article.store');
            Route::get('/edit/{id}', 'ArticleController@edit')->name('panel.article.edit');
            Route::PATCH('/update/{id}', 'ArticleController@update')->name('panel.article.update');
            Route::delete('/delete/{id}', 'ArticleController@delete')->name('panel.article.delete');
            Route::get('/status/{id}', 'ArticleController@status')->name('panel.article.status');
        });
        /* end article controller */

        // ================================================= profile =============
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', 'ProfileController@index')->name('panel.profile.index');
            Route::get('/edit/{id}', 'ProfileController@edit')->name('panel.profile.edit');
            Route::post('/changepw/', 'ProfileController@ChangePw')->name('panel.profile.changePassword');
            Route::PATCH('/update/{id}', 'ProfileController@update')->name('panel.profile.update');
            Route::post('/update-avatar/', 'ProfileController@avatar')->name('panel.profile.avatar');
            Route::post('/ajaxGetCity/', 'ProfileController@ajaxGetCity')->name('panel.profile.ajaxCity');
            Route::post('/address/{id}', 'ProfileController@addressStore')->name('panel.address.add');
            Route::get('/delete/address/{id}', 'ProfileController@addressDelete')->name('panel.address.delete');

        });
        // ================================================= profile =============

        // ================================================= setting =============
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/', 'SettingController@index')->name('panel.setting.index');
            Route::get('/create', 'SettingController@create')->name('panel.setting.create');
            Route::post('/store', 'SettingController@store')->name('panel.setting.store');
            Route::get('/edit/{id}', 'SettingController@edit')->name('panel.setting.edit');
            Route::PATCH('/update/{id}', 'SettingController@update')->name('panel.setting.update');
            Route::DELETE('/delete/{id}', 'SettingController@delete')->name('panel.setting.delete');
            Route::get('/status/{id}', 'SettingController@status')->name('panel.setting.status');
        });
        Route::group(['prefix' => 'manage'], function () {
            Route::get('/show/{id}', 'ManageController@index')->name('panel.manage.index');
            Route::get('/create/{id}', 'ManageController@create')->name('panel.manage.create');
            Route::post('/store', 'ManageController@store')->name('panel.manage.store');
            Route::get('/edit/{id}', 'ManageController@edit')->name('panel.manage.edit');
            Route::PATCH('/update/{id}', 'ManageController@update')->name('panel.manage.update');
            Route::DELETE('/delete/{id}', 'ManageController@delete')->name('panel.manage.delete');
            Route::get('/status/{id}', 'ManageController@status')->name('panel.manage.status');
        });
        // ================================================= setting =============

        // ================================================= pages =============
        Route::group(['prefix' => 'page'], function () {
            Route::get('/', 'PageController@index')->name('panel.page.index');
            Route::get('/create', 'PageController@create')->name('panel.page.create');
            Route::post('/store', 'PageController@store')->name('panel.page.store');
            Route::get('/edit/{id}', 'PageController@edit')->name('panel.page.edit');
            Route::PATCH('/update/{id}', 'PageController@update')->name('panel.page.update');
            Route::delete('/delete/{id}', 'PageController@delete')->name('panel.page.delete');
            Route::get('/status/{id}', 'PageController@status')->name('panel.page.status');
        });
        // ================================================= pages =============


        // ================================================= menu =============
        Route::group(['prefix' => 'menu'], function () {
            Route::get('/', 'MenuController@index')->name('panel.menu.index');
            Route::get('/create', 'MenuController@create')->name('panel.menu.create');
            Route::post('/store', 'MenuController@store')->name('panel.menu.store');
            Route::get('/edit/{id}', 'MenuController@edit')->name('panel.menu.edit');
            Route::PATCH('/update/{id}', 'MenuController@update')->name('panel.menu.update');
            Route::get('/delete/{id}', 'MenuController@delete')->name('panel.menu.delete');
            Route::post('save-nested-menu', 'MenuController@saveNestedMenus')->name('panel.nested-menus.store');

        });
        // ================================================= menu =============

        // ================================================= user =============
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('panel.users.index');
            Route::get('/create', 'UserController@create')->name('panel.users.create');
            Route::post('/store', 'UserController@store')->name('panel.users.store');
            Route::get('/edit/{id}', 'UserController@edit')->name('panel.users.edit');
            Route::PATCH('/update/{id}', 'UserController@update')->name('panel.users.update');
            Route::delete('/delete/{id}', 'UserController@delete')->name('panel.users.delete');
            Route::get('/active/{id}', 'UserController@active')->name('panel.users.active');
            Route::get('/status/{id}', 'UserController@status')->name('panel.users.status');
            Route::get('/search', 'UserController@Search')->name('panel.users.search');
            Route::get('/show/{user}', 'UserController@showDetail')->name('panel.users.showDetail');
            Route::get('export/', 'UserController@export')->name('panel.users.export');
            Route::post('/edit-profile/{user}', 'UserController@updateUserDetail')->name('panel.users.detailUpdate');

        });
        // ================================================= user =============

        // ================================================= role =============
        Route::group(['prefix' => 'role'], function () {
            Route::get('/', 'RoleController@index')->name('panel.role.index');
            Route::get('/create', 'RoleController@create')->name('panel.role.create');
            Route::post('/store', 'RoleController@store')->name('panel.role.store');
            Route::get('/edit/{id}', 'RoleController@edit')->name('panel.role.edit');
            Route::PATCH('/update/{id}', 'RoleController@update')->name('panel.role.update');
            Route::delete('/delete/{id}', 'RoleController@delete')->name('panel.role.delete');
            Route::get('/active/{id}', 'RoleController@active')->name('panel.role.active');

        });
        // ================================================= role =============

        // ================================================= LevelManage =============
        Route::group(['prefix' => 'LevelManage'], function () {
            Route::get('/', 'LevelManageController@index')->name('panel.LevelManage.index');
            Route::get('/create', 'LevelManageController@create')->name('panel.LevelManage.create');
            Route::post('/store', 'LevelManageController@store')->name('panel.LevelManage.store');
            Route::get('/edit/{user}', 'LevelManageController@edit')->name('panel.LevelManage.edit');
            Route::PATCH('/update/{user}', 'LevelManageController@update')->name('panel.LevelManage.update');
            Route::delete('/delete/{user}', 'LevelManageController@destroy')->name('panel.LevelManage.delete');
        });
        // ================================================= LevelManage =============


        // ================================================= permission =============
        Route::group(['prefix' => 'permission'], function () {
            Route::get('/', 'PermissionController@index')->name('panel.permission.index');
            Route::get('/create', 'PermissionController@create')->name('panel.permission.create');
            Route::post('/store', 'PermissionController@store')->name('panel.permission.store');
            Route::get('/updateToDate', 'PermissionController@updateToDate')->name('panel.permission.updateToDate');
            Route::get('/edit/{id}', 'PermissionController@edit')->name('panel.permission.edit');
            Route::PATCH('/update/{id}', 'PermissionController@update')->name('panel.permission.update');
        });
        // ================================================= permission =============

        // ================================================= exam =============
        Route::group(['prefix' => 'exams'], function () {
            Route::get('/', 'ExamController@index')->name('panel.exam.index');
            Route::get('/status/{id}', 'ExamController@status')->name('panel.exam.status');
            Route::delete('/delete/{id}', 'ExamController@delete')->name('panel.exam.delete');
        });
        // ================================================= exam =============

        // ================================================= contact =============
        Route::group(['prefix' => 'contact'], function () {
            Route::get('/', 'ContactController@index')->name('panel.contact.index');
            Route::get('/status/{id}', 'ContactController@status')->name('panel.contact.status');
            Route::delete('/delete/{id}', 'ContactController@delete')->name('panel.contact.delete');
            Route::delete('/delete1/{id}', 'ContactController@destroy')->name('panel.contact.delete1');
        });
        // ================================================= contact =============

        // ================================================= consultation =============
        Route::group(['prefix' => 'consultations'], function () {
            Route::get('/', 'ConsultationController@index')->name('panel.consultation.index');
            Route::get('/status/{id}', 'ConsultationController@status')->name('panel.consultation.status');
            Route::delete('/delete/{id}', 'ConsultationController@delete')->name('panel.consultation.delete');
        });
        // ================================================= consultation =============

        // ================================================= comments =============
        Route::group(['prefix' => 'comments'], function () {
            Route::get('/', 'CommentController@index')->name('panel.comments.index');
            Route::get('/status/{id}', 'CommentController@status')->name('panel.comments.status');
            Route::delete('/delete/{id}', 'CommentController@delete')->name('panel.comments.delete');
        });
        // ================================================= comments =============

        // ================================================= news letter =============
        Route::group(['prefix' => 'newsLetter'], function () {
            Route::get('/', 'NewsLetterController@index')->name('panel.newsLetter.index');
            Route::get('/show', 'NewsLetterController@show')->name('panel.newsLetter.show');
            Route::post('/sends', 'NewsLetterController@sends')->name('panel.newsLetter.sends');
            Route::delete('/delete/{id}', 'NewsLetterController@delete')->name('panel.newsLetter.delete');
        });
        // ================================================= news letter =============

        // ================================================= role =============
        Route::group(['prefix' => 'cities'], function () {
            Route::get('/', 'CityController@index')->name('panel.cities.index');
            Route::get('/create', 'CityController@create')->name('panel.cities.create');
            Route::post('/store', 'CityController@store')->name('panel.cities.store');
            Route::get('/edit/{id}', 'CityController@edit')->name('panel.cities.edit');
            Route::PATCH('/update/{id}', 'CityController@update')->name('panel.cities.update');
            Route::delete('/delete/{id}', 'CityController@delete')->name('panel.cities.delete');
            Route::get('/active/{id}', 'CityController@active')->name('panel.cities.active');

        });
        // ================================================= role =============

    });

});
