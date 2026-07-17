@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/compare.css"/>
@endsection

@section('content')
    <div class="container">
        <section class="o-page">

            <div class="o-headline"><span>لیست مقایسه {{ $category->title }} </span></div>
            <div class="c-compare js-compare-products-container">
                <ul class="c-compare__list c-compare__list--header">
                    <li class="is-header">
                        @if(isset($products) && !empty($products))
                            @foreach($products as $product)
                                <div class="c-compare__list-value js-compare-product">
                                    <div class="c-compare__img">
                                        <div class="c-compare__content-holder"><a
                                                href="{{ $product->path() }}" target="_blank"
                                                class="img swiper-container js-compare-product-images swiper-container-horizontal swiper-container-rtl">
                                                <div>
                                                    <img loading="lazy" class="img-fluid" alt="{{ $product->title }}"
                                                         src="{{ $product->image[0]->url }}">
                                                </div>
                                            </a><span class="title">{{ $product->title }}</span>
                                            <div class="c-price">
                                                <div class="c-price__value">{!!  $product->prices !!}</div>
                                            </div>
                                        </div>
                                        <a class="btn-primary"
                                           href="{{ $product->path() }}">مشاهده
                                            و خرید محصول</a></div>

                                    <span class="c-compare__btn-remove js-remove-compare-product"
                                          onclick="removeCompare('{{$product->id}}')">
                                    <i class="fa fa-window-close" aria-hidden="true"></i></span>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($products) && count($products) <= 3)
                            <div class="c-compare__list-value js-add-compare-product">
                                <div class="c-compare__add add-compare">
                                    <button href="#" class="c-compare__placement" data-toggle="modal"
                                            data-target="#myModal">
                                        <i class="fa fa-plus add_compare"
                                           aria-hidden="true"></i>
                                        برای افزودن کالا به لیست مقایسه کلیک
                                        کنید
                                    </button>
                                    <button type="button" class="btn-primary btn-primary--gray" data-toggle="modal"
                                            data-target="#myModal">افزودن کالا به مقایسه
                                    </button>
                                </div>
                            </div>
                        @endif
                    </li>
                </ul>
                @if(isset($attributeGroup) && !empty($attributeGroup) && count($attributeGroup) > 0)
                    @foreach($attributeGroup as $itemAttributeGroup)
                        <h4 class="c-compare-quick__title">{{ $itemAttributeGroup->name }}</h4>
                        <ul class="c-compare-quick__list">
                            @if(isset($itemAttributeGroup->attributes) && !empty($itemAttributeGroup->attributes))
                                @foreach($itemAttributeGroup->attributes as $attribute)
                                    <li>
                                        <div class="c-compare__list-title">
                                            {{ $attribute->name }}
                                        </div>
                                    </li>
                                    <li>
                                        @foreach($products as $itemProduct)
                                            @foreach($itemProduct->attributevalues as $itemValue)
                                                @if(!in_array($attribute->id,$itemProduct->attributevalues->pluck('attribute_id')->toArray()))
                                                    <div class="c-compare__list-value">
                                                        <span class="block"></span>
                                                    </div>
                                                @endif
                                                @if($itemValue->attribute_id == $attribute->id)
                                                    <div class="c-compare__list-value">
                                                        <span class="block">{{ $itemValue->value }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach


                                    </li>
                                @endforeach
                            @endif

                        </ul>
                    @endforeach
                @endif
            </div>
        </section>

    </div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header c-remodal-compare__header">
                    <form class="c-form-compare-search">
                        <div class="c-form-compare-search__row">
                            <div class="c-form-compare-search__col">
                                جستجو
                            </div>
                            <div class="c-form-compare-search__col c-form-compare-search__col--field">
                                <label class="c-ui-input c-ui-input--search">
                                    <input onkeyup="filter()" class="c-ui-input__field js-product-title" id="title" type="text"
                                           placeholder="کالای مورد نظر خود را جستجو کنید...">
                                </label>

                            </div>
                        </div>
                    </form>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="c-remodal-compare__content">
                        <div class="c-form-compare-selector">
                            <div class="c-form-compare-selector__row">
                                <div class="c-form-compare-selector__col">
                                    @if(isset($similarProducts) && !empty($similarProducts) && count($similarProducts))
                                        <ul id="list-ul" class="c-form-compare-selector__items js-compare-container">
                                            @foreach($similarProducts as $item)
                                                <li>
                                                    <label class="c-form-compare-selector__item js-compare-selector"
                                                           onclick="compare('{{$item->id}}',200)">
                                                        <figure class="img">
                                                            <img loading="lazy" class="js-compare-image"
                                                                 alt="{{ $item->title }}"
                                                                 src="{{ $item->image[0]->url }}">
                                                        </figure>
                                                        <span class="title js-compare-title">{{ $item->title }}</span>
                                                        <div class="text-center">
                                                            <div
                                                                class="c-price__value price-size">{!!  $item->prices !!}</div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="label label-info">محصولی برای انتخاب و مقایسه وجود ندارد ، لطفا دسته بندی دیگری را بررسی نمایید</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('site-js')

    <script>
        $(".det-like").click(function () {
            var id = $(this).attr('data-url');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if (id != "") {
                $.ajax({
                    type: "post",
                    url: "{{ route('add.favorites') }}",
                    data: {
                        id: id,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 100) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.msg,
                                icon: "error",
                                button: "@lang('cms.accept-2')",
                            });
                        }
                        if (data.status == 200) {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.msg,
                                icon: "success",
                                button: "@lang('cms.accept-2')",
                            });
                        }
                        if (data.status == 101) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.msg,
                                icon: "warning",
                                button: "@lang('cms.accept-2')",
                            });
                        }


                    },
                    error: function (error) {
                        Swal.fire({
                            title: "@lang('cms.error')",
                            text: "@lang('cms.try-again-few-moments')",
                            icon: "error",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                });

            }
        });

        function removeCompare(id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if (id != "") {
                $.ajax({
                    type: "post",
                    url: "{{ route('site.remove.compare') }}",
                    data: {
                        id: id,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.status == 404) {
                            Swal.fire({
                                title: "@lang('cms.alert')",
                                text: data.message,
                                icon: "error",
                                button: "@lang('cms.accept-2')",
                            });
                        }
                        if (data.status == 200) {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.message,
                                icon: "success",
                                button: "@lang('cms.accept-2')",
                            }).then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (error) {
                        Swal.fire({
                            title: "@lang('cms.error')",
                            text: "@lang('cms.try-again-few-moments')",
                            icon: "error",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                });

            }
        }

        function filter() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("title");
            filter = input.value.toUpperCase();
            ul = document.getElementById("list-ul");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("label")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        if (matchMedia('only screen and (min-width: 768px)').matches) {
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                if (scroll >= 50) {
                    $(".c-compare__list--header").addClass("fixed");
                } else {
                    $(".c-compare__list--header").removeClass("fixed");
                }
            });
        }
    </script>


@endsection
