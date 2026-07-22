<?php


namespace App\Providers;

use App\Model\Article;
use App\Model\Basket;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Comment;
use App\Model\Consultation;
use App\Model\Contact;
use App\Model\Menu;
use App\Model\Page;
use App\Model\Product;
use App\Model\Role;
use App\Model\Systeminf;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\Utility\CommentStatus;
use App\Utility\ProductType;
use http\Client\Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\User;
use App\Services\AiAssistant\Contracts\AiProvider;
use App\Services\AiAssistant\Providers\AnthropicProvider;
use App\Services\AiAssistant\Providers\NullProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        // The site theme is Bootstrap-based; Laravel 8+ defaults pagination
        // views to Tailwind, which renders giant unstyled SVG arrows here.
        Paginator::useBootstrap();

        Schema::defaultStringLength(191);
        Relation::morphMap([
            'product' => Product::class,
            'article' => Article::class,
            'page' => Page::class,
            'user' => User::class,
            'role' => Role::class,
            'prudoct' => Variation::class,
            'brand' => Brand::class,
            'setting' => Systeminf::class,
            'category' => Category::class
        ]);

        view()->composer(['panel.layout.partials.topNav', 'panel.layout.partials.loading', 'panel.layout.partials.rightNav'], function ($view) {
            $setting_contact = Systeminfmanage::find(17);
            $setting_logo_footer = Systeminfmanage::find(17);
            $logo = Systeminfmanage::whereStatus(1)->where('systeminf_id', 1)->first();
            $currentUser = auth()->user();
            $countContact = Contact::whereStatus(0)->count();
            $countConsultation = Consultation::whereStatus(0)->count();
            $countComment = Comment::whereStatus(CommentStatus::NOT_ACCEPTET)->count();
            $view->with([
                'logo' => $logo,
                'countConsultation' => $countConsultation,
                'currentUser' => $currentUser,
                'countContact' => $countContact,
                'countComment' => $countComment,
                'setting_contact' => $setting_contact,
                'setting_logo_footer' => $setting_logo_footer,
            ]);
        });


        view()->composer('site.product.partials.filter', function ($view) {
            $brands = Brand::select('title', 'id')->whereStatus(1)->get();
            $slug = \request()->slug;
            $categoryProduct = Category::query()->whereHas('categories')->select('title', 'id','slug')->
            where('parent_id',0)->whereType(Product::class)->whereStatus(1)->get();

            if(\Request::route()->getName() == "site.category.list" && !empty($slug)){
                $categoryProduct = Category::select('title', 'id','slug')->
                whereIn('parent_id',Category::whereSlug($slug)->pluck('id')->toArray())->
                whereType(Product::class)->whereStatus(1)->get();
            }
            $view->with([
                'brands' => $brands,
                'categoryProduct' => $categoryProduct,
            ]);

        });
        view()->composer(['site.layout.partials.basket', 'site.checkout.partials.products', 'site.checkout.partials.review', 'site.checkout.partials.index', 'site.layout.master'], function ($view) {

            $oldCart = Session::get('basket');
            $compare = Session::get('compare');
            $sessionBasket = new Basket($oldCart);
            $view->with([
                'sessionBasket' => $sessionBasket,
                'compare' => $compare
            ]);
        });


        /* Footer */
        // These settings rarely change; cache them so the footer does not run
        // ~8 queries on every request. Admin edits show up within 5 minutes
        // (or immediately after panel/manager/maintenance/cache-clear).
        view()->composer(['site.layout.partials.footer'], function ($view) {
            $data = \Cache::remember('composer.site.footer', 300, function () {
                $withActive = ['systeminfmanage' => function ($q) {
                    $q->whereStatus(1);
                }];

                return [
                    'socialNetwork' => Systeminfmanage::where('status', 1)->where('systeminf_id', 14)->get(),
                    'col1' => Systeminf::with($withActive)->find(33),
                    'col2' => Systeminf::with($withActive)->find(32),
                    'col3' => Systeminf::with($withActive)->find(39),
                    'col4' => Systeminf::with($withActive)->find(41),
                    'contactTopFooter' => Systeminfmanage::where('status', 1)->where('systeminf_id', 38)->latest()->get(),
                    'logoFooter' => Systeminfmanage::where(['status' => 1])->find(134),
                    'setting_contact' => Systeminfmanage::find(17),
                    'setting_logo_footer' => Systeminfmanage::find(134),
                ];
            });

            $view->with($data);
        });

        /* site header */
        view()->composer(['site.layout.master', 'site.layout.partials.header', 'site.layout.partials.header-mobile'],
            function ($view) {
                $data = \Cache::remember('composer.site.header', 300, function () {
                    return [
                        'logo' => Systeminfmanage::where(['status' => 1])->find(108),
                        'menus' => Menu::all(),
                        'socialNetworks' => Systeminfmanage::where(['status' => 1, 'systeminf_id' => 14])->latest()->get(),
                        'categories' => Category::query()->with(['categories'])->
                            whereType(Product::class)->whereStatus(1)->where('parent_id', 0)->orderBy('sorting', 'desc')->get(),
                    ];
                });

                $view->with($data);
            });
        /* site header */


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // انتخاب ارائه‌دهنده‌ی پیش‌فرض هوش مصنوعی (fallback قدیمی که ProviderManager به آن نیاز دارد).
        // اگر کلید Anthropic در .env نباشد، NullProvider برمی‌گردد؛ ارائه‌دهنده‌های دیگر کلیدشان را
        // از دیتابیس (پنل «AI Providers») می‌خوانند، نه از این‌جا.
        $this->app->bind(AiProvider::class, function () {
            if (blank(config('services.anthropic.key'))) {
                return new NullProvider;
            }

            return match (config('services.anthropic.driver', 'anthropic')) {
                default => new AnthropicProvider,
            };
        });
    }
}
