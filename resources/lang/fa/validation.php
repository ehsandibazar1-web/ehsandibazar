<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"         => ":attribute باید پذیرفته شده باشد.",
    "active_url"       => "آدرس :attribute معتبر نیست",
    "after"            => ":attribute باید تاریخی بعد از :date باشد.",
    "alpha"            => ":attribute باید شامل حروف الفبا باشد.",
    "alpha_dash"       => ":attribute باید شامل حروف الفبا و عدد و خظ تیره(-) باشد.",
    "alpha_num"        => ":attribute باید شامل حروف الفبا و عدد باشد.",
    "array"            => ":attribute باید شامل آرایه باشد.",
    "before"           => ":attribute باید تاریخی قبل از :date باشد.",
    "between"          => array(
        "numeric" => ":attribute باید بین :min و :max باشد.",
        "file"    => ":attribute باید بین :min و :max کیلوبایت باشد.",
        "string"  => ":attribute باید بین :min و :max کاراکتر باشد.",
        "array"   => ":attribute باید بین :min و :max آیتم باشد.",
    ),
    "boolean"          => "The :attribute field must be true or false",
    "confirmed"        => ":attribute با تاییدیه مطابقت ندارد.",
    "date"             => ":attribute یک تاریخ معتبر نیست.",
    "date_format"      => ":attribute با الگوی :format مطاقبت ندارد.",
    "different"        => ":attribute و :other باید متفاوت باشند.",
    "digits"           => ":attribute باید :digits رقم باشد.",
    "digits_between"   => ":attribute باید بین :min و :max رقم باشد.",
    "email"            => "فرمت :attribute معتبر نیست.",
    "exists"           => ":attribute انتخاب شده، معتبر نیست.",
    "image"            => ":attribute باید تصویر باشد.",
    "in"               => ":attribute انتخاب شده، معتبر نیست.",
    "integer"          => ":attribute باید نوع داده ای عددی (integer) باشد.",
    "ip"               => ":attribute باید IP آدرس معتبر باشد.",
    "max"              => array(
        "numeric" => ":attribute نباید بزرگتر از :max باشد.",
        "file"    => ":attribute نباید بزرگتر از :max کیلوبایت باشد.",
        "string"  => ":attribute نباید بیشتر از :max کاراکتر باشد.",
        "array"   => ":attribute نباید بیشتر از :max آیتم باشد.",
    ),
    "mimes"            => ":attribute باید یکی از فرمت های :values باشد.",
    "min"              => array(
        "numeric" => ":attribute نباید کوچکتر از :min باشد.",
        "file"    => ":attribute نباید کوچکتر از :min کیلوبایت باشد.",
        "string"  => ":attribute نباید کمتر از :min کاراکتر باشد.",
        "array"   => ":attribute نباید کمتر از :min آیتم باشد.",
    ),
    "not_in"           => ":attribute انتخاب شده، معتبر نیست.",
    "numeric"          => ":attribute باید شامل عدد باشد.",
    "regex"            => ":attribute یک فرمت معتبر نیست",
    "required"         => "فیلد :attribute الزامی است",
    "required_if"      => "فیلد :attribute هنگامی که :other برابر با :value است، الزامیست.",
    "required_with"    => ":attribute الزامی است زمانی که :values موجود است.",
    "required_with_all"=> ":attribute الزامی است زمانی که :values موجود است.",
    "required_without" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "required_without_all" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "same"             => ":attribute و :other باید مانند هم باشند.",
    "size"             => array(
        "numeric" => ":attribute باید برابر با :size باشد.",
        "file"    => ":attribute باید برابر با :size کیلوبایت باشد.",
        "string"  => ":attribute باید برابر با :size کاراکتر باشد.",
        "array"   => ":attribute باسد شامل :size آیتم باشد.",
    ),
    "timezone"         => "The :attribute must be a valid zone.",
    "unique"           => ":attribute قبلا انتخاب شده است.",
    "url"              => "فرمت آدرس :attribute اشتباه است.",
    "exists_code"      => "کد ارسالی در سیستم وجود ندارد",
    "expire_code"      => "اعتبار کد ارسالی به پایان رسیده است",
    "used"             => "این کد قبلا مورد استفاده قرار گرفته است",
    "exists_phone"     => "چنین شماره ای در سیستم ثبت نشده است",
    "recaptcha"        => "کپچا اعتبار لازم را ندارد",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => array(
        "name" => "نام",
        "family" => "نام خانوادگی",
        "level" => "نوع کاربری",
        "msg" => "متن پیام",
        "username" => "نام کاربری",
        "email" => "پست الکترونیکی",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "آدرس",
        "phone" => "تلفن",
        "mobile" => "تلفن همراه",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "title" => "عنوان",
        "text" => "متن",
        "content" => "محتوا",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
        "body" => "متن",
        "city_id" => "شهر",
        "province_id" => "استان",
        "img" => "تصویر",
        "price" => "قیمت",
        "economic_code" => "کد اقتصادی",
        "images" => "تصویر",
        "count" => "تعداد",
        "tell" => "تلفن ثابت",
        "subject" => "موضوع",
        "current-password" => "رمز عبور فعلی",
        "cat_id" => "دسته بندی",
        "cat" => "زمینه کاری",
        "expiry_date" => "تاریخ انقضا",
        "comment" => "دیدگاه",
        'category' => 'دسته بندی' ,
        'type' => 'نوع محصول' ,
        'label' => 'توضیحات',
        'permission_id' => 'دسترسی ها' ,
        'user_id' => 'کاربران' ,
        'role_id' => 'مقام ها' ,
        'parent_id' => 'زیر دسته' ,
        'details' => 'توضیحات' ,
        'category_id' => 'دسته بندی' ,
        'filepath' => 'تصاویر',
        'attributes' => 'ویژگی ها' ,
        'part_id' => 'قطعه مرتبط' ,
        'pdf' => 'فایل PDF' ,
        'location' => 'جایگاه',
        'link' => 'آدرس لینک',
        'brand_id' => 'برند',
        'filter_id' => 'فیلتر',
        'question' => 'سوال دریافت ایمیل',
        'medical-system' => 'شماره نظام پزشکی',
        'studying' => 'سوال در حال تحصیل',
        'workImplant' => 'سوال فعالیت در حوزه ایمپلنت',
        'request_level' => 'درخواست نوع کاربری جدید',
        'newsLatter' => 'ایمیل',
        'sheba_number' => 'شماره شبا',
        'account_number' => 'شماره حساب',
        'cart_number' => 'شماره کارت',
        'status' => 'وضعیت',
        'attribute_group_id' => 'دسته بندی ویژگی ها',
        'sort' => 'نحوه نمایش',
        'value' => 'مقدار',
        'attribute_type_id' => 'دسته بندی ویژگی',
        'code' => 'کد محصول',
        'brand' => 'برند',
        'product_id' => 'انتخاب محصول',
        'postal_code' => 'کد پستی',
        'nameForAddress' => 'نام گیرنده کالا',
        'national_code' => 'کد ملی',
        'store_name' => 'نام فروشگاه',
        'extra_description' => 'توضیحات اضافی',
        'supply' => 'تامین کننده',
        'lang' => 'زبان',
        'score' => 'امتیاز',
        'discountable_type' => 'اعمال تخفیف',
        'cent' => 'مقدار',
        'discount_type' => 'نوع تخفیف',
        'baseon' => 'تخفیف بر اساس',
        'activation_code' => 'کد تایید',
        'license' => 'عکس پروانه کسب',
        'national_cart' => 'عکس کارت ملی',
        'low' => 'قوانین',
        'start_date' => 'تاریخ شروع',
        'end_date' => 'تاریخ پایان',
        'start_price' => 'قیمت شروع',
        'end_price' => 'قیمت پایان',
        'participant_count' => 'تعداد شرکت کنندگان',
        'click_price' => 'مبلغ کل کلیک',
        'postType' => 'نوع پست',
        'shipping-method' => 'نوع ارسال',
        'payment' => 'نحوه پرداخت',
        'sendType' => 'نوع ارسال',
        'tracking_code' => 'کد پیگیری',
        'modelName' => 'نام بخش',
        'new-password' => 'رمز عبور جدید',
        'new-password-confirmation' => 'تکرار رمز عبور جدید',
        'fullAddress' => 'آدرس تکمیلی',
        'video' => 'ویدیو',
        'avatar' => 'تصویر آواتار',
        'slug' => 'اسلاگ',
        'g-recaptcha-response' =>'کپچا'

    ),


];
