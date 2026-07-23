<?php

namespace App\Filament\Pages;

use App\Model\Category;
use App\Model\OrderItem;
use App\Model\Product;
use App\Utility\Status;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Hekmatinasser\Verta\Verta;
use UnitEnum;

/**
 * گزارش‌گیریِ فروش — پورتِ وفادارِ Admin\ReportingController@report به پنلِ جدید:
 * آیتم‌های سفارش‌های «ارسال‌شده» (Status::SENDING) با فیلترِ محصول/دسته (شاملِ زیر دسته‌ها)
 * و بازه‌ی تاریخِ شمسی (فرمتِ ۱۴۰۴/۰۵/۰۱ — همان تبدیلِ Verta::getGregorian قدیمی).
 * فقط-خواندنی؛ هیچ داده‌ای تغییر نمی‌دهد.
 */
class SalesReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'فروش';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'گزارش‌گیریِ فروش';

    protected static ?string $title = 'گزارش‌گیریِ فروش';

    protected string $view = 'filament.pages.sales-report';

    public ?int $productId = null;

    public ?int $categoryId = null;

    /** تاریخِ شمسی به شکلِ ۱۴۰۴/۰۵/۰۱ */
    public string $startDate = '';

    public string $endDate = '';

    /** @var array<int, array{product:string,count:int,amount:float,discount:float,date:string}> */
    public array $rows = [];

    public array $totals = ['count' => 0, 'amount' => 0.0, 'discount' => 0.0];

    public bool $ran = false;

    /** @return array<int|string, string> */
    public function productOptions(): array
    {
        return Product::query()->orderBy('title')->pluck('title', 'id')->all();
    }

    /** @return array<int|string, string> دسته‌های دارای والد، با نامِ والد (عینِ کوئریِ قدیمی) */
    public function categoryOptions(): array
    {
        return Category::query()
            ->where('status', 1)
            ->where('parent_id', '!=', 0)
            ->with('parent')
            ->orderBy('title')
            ->get()
            ->mapWithKeys(fn ($c) => [$c->id => $c->title.' ('.optional($c->parent)->title.')'])
            ->all();
    }

    public function runReport(): void
    {
        $start = $this->toGregorian($this->startDate);
        $end = $this->toGregorian($this->endDate);

        if (($this->startDate !== '' && $start === null) || ($this->endDate !== '' && $end === null)) {
            Notification::make()->danger()->title('فرمتِ تاریخ نادرست است')
                ->body('تاریخ را شمسی و به شکلِ ۱۴۰۴/۰۵/۰۱ وارد کنید.')->send();

            return;
        }

        $query = OrderItem::query()
            ->with('product')
            ->whereHas('order', fn ($q) => $q->where('status', Status::SENDING));

        if ($this->categoryId) {
            $ids = [$this->categoryId];
            $category = Category::find($this->categoryId);
            if ($category) {
                $ids = array_merge($ids, $category->subCategory()->pluck('id')->all());
            }
            $query->whereHas('product', fn ($q) => $q->whereIn('category_id', $ids));
        }

        if ($this->productId) {
            $query->whereIn('product_id', [$this->productId]);
        }

        if ($start && $end) {
            $query->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
        }

        $items = $query->orderBy('product_id', 'desc')->get();

        $this->rows = $items->map(fn ($item) => [
            'product' => optional($item->product)->title ?? ('#'.$item->product_id),
            'count' => (int) $item->itemCount,
            'amount' => (float) $item->amount,
            'discount' => (float) ($item->amount_discount ?? 0),
            'date' => (string) $item->created_at, // اکسسورِ شمسیِ خودِ مدل
        ])->all();

        $this->totals = [
            'count' => array_sum(array_column($this->rows, 'count')),
            'amount' => array_sum(array_column($this->rows, 'amount')),
            'discount' => array_sum(array_column($this->rows, 'discount')),
        ];

        $this->ran = true;
    }

    /** «۱۴۰۴/۰۵/۰۱» یا "1404/05/01" → "2025-07-23" — همان منطقِ convertToMiladi/convert2english قدیمی. */
    private function toGregorian(string $jalali): ?string
    {
        $jalali = trim($jalali);
        if ($jalali === '') {
            return null;
        }

        $jalali = strtr($jalali, array_combine(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        ));

        $parts = explode('/', $jalali);
        if (count($parts) !== 3) {
            return null;
        }

        try {
            // نکته: APIِ قدیمیِ Verta::getGregorian در نسخه‌ی نصب‌شده وجود ندارد (به همین دلیل
            // گزارش‌گیریِ پنلِ قدیمی هم با تاریخ فتال می‌شد)؛ از APIِ جدید استفاده می‌کنیم.
            return Verta::parse(sprintf('%04d/%02d/%02d', (int) $parts[0], (int) $parts[1], (int) $parts[2]))
                ->toCarbon()
                ->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
