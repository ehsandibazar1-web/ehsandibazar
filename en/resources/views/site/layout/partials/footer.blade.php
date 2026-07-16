<!--فوتر-->
<section class="container-fluid info-section">
    <div class="row">
        <div class="col-xl-10 col-lg-11 mx-auto col-12 p-0 ">
            <div class="row">
                <div class="col-lg-2 col-md-4 p-0 text-center mb-xs-15">
                    <div class="icon-box d-block text-center">
                        <span class="phone-icon"></span>
                    </div>
                    <div class="d-block phone-text">
                        @if(isset($setting_contact) && !empty($setting_contact))
                            <a href="tel:{{ $setting_contact->code }}">{{ $setting_contact->code }}
                            </a>
                        @endif
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 pr-md-0 text-center mb-xs-15">
                    <div class="icon-box d-block text-center">
                        <span class="map-icon"></span>
                    </div>
                    <div class="d-block address-text">
                        @if(isset($setting_contact) && !empty($setting_contact))
                            {{ $setting_contact->code2 }}
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 pr-md-0 text-center mb-xs-15">
                    <div class="icon-box d-block text-center">
                        <span class="mail-icon"></span>
                    </div>
                    <div class="d-block mail-text">
                        @if(isset($setting_contact) && !empty($setting_contact))
                            <a href="mailto:{{ $setting_contact->code3 }}">{{ $setting_contact->code3 }}</a>
                        @endif
                    </div>

                </div>
                <div class="col-lg-2 col-md-12 p-0 text-center">
{{--                    <ul class="namad p-0">--}}
{{--                        <li>--}}
{{--                            <img src="{{ asset('site_theme/images/namad1.png') }}" class="img-fluid">--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <img src="{{ asset('site_theme/images/namad2.png') }}" class="img-fluid">--}}
{{--                        </li>--}}
{{--                    </ul>--}}
                    <a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=273076&amp;Code=iOaIjtewVli8xGqfG9QK"><img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=273076&amp;Code=iOaIjtewVli8xGqfG9QK" alt="" style="cursor:pointer" id="iOaIjtewVli8xGqfG9QK"></a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid footer">
    <div class="container p-0">
        <div class="row accordion-container">
            <div class="col-lg-4 col-md-6 col-12 p-0 ">
                <div class="row row-accordion-container">
                    @if(isset($col1) && !empty($col1))
                        <div class="col-md-6 co-12 pl-2 pr-2">
                            <div class="set">
									<span class="service-icon">
										<span class="title-footer lnk-footer un-link"><span>{{ $col1->name }}</span></span>
										<i class="fa-chevron-down fal fa-chevron-up" aria-hidden="false"></i>
									</span>
                                <div class="content">
                                    @if(isset($col1->systeminfmanage) && !empty($col1->systeminfmanage))
                                        <ul class="lnk-footers">
                                            @foreach($col1->systeminfmanage as $item)
                                                <li><a href="{{ $item->code }}">{{ $item->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($col2) && !empty($col2))
                        <div class="col-md-6 co-12 pl-2 pr-2">
                            <div class="set">
									<span class="service-icon">
										<span class="title-footer lnk-footer un-link"><span>{{ $col2->name }}</span></span>
										<i class="fa-chevron-down fal fa-chevron-up" aria-hidden="false"></i>
									</span>
                                <div class="content">
                                    @if(isset($col2->systeminfmanage) && !empty($col2->systeminfmanage))
                                        <ul class="lnk-footers">
                                            @foreach($col2->systeminfmanage as $item)
                                                <li><a href="{{ $item->code }}">{{ $item->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!--نمایش حالت دسکتاپ-->
            <div class="col-lg-4 col-md-6 col-12 p-0 d-none d-lg-block">
                <div class="row text-center">
                    <div class="col-12 p-0 logo-footer">
                        <img src="/site_theme/images/logo.png" class="img-fluid">
                    </div>
                    @if(isset($socialNetwork) && !empty($socialNetwork))
                        <div class="row">
                            <div class="col-12 p-0 text-center social-footer">
                                <ul class="social-box">
                                    @foreach($socialNetwork as $item)
                                        <li>
                                            <a href="{{ $item->code }}">
												<span class="flip">
													<img src="{{ $item->code5 }}" alt="{{ $item->name }}">
												</span>
                                                <span class="flop">
													<img src="{{ $item->code5 }}" alt="{{ $item->name }}">
												</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="row  ">
                        <div class="col-md-12 col-12 copy-right p-0">
                            All rights reserved to <span class="color">{{ env('SITE_NAME_FA') }} </span>
                        </div>
                        <div class="col-md-12 col-12 copy-left p-0">
                            Website designed by Nonegar Pardazesh , developed by Nonegar Pardazesh
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 p-0 left-link">
                <div class="row row-accordion-container">
                    @if(isset($col3) && !empty($col3))
                        <div class="col-md-6 co-12 pl-2 pr-2">
                            <div class="set">
									<span class="service-icon">
										<span class="title-footer lnk-footer un-link"><span>{{ $col3->name }}</span></span>
										<i class="fa-chevron-down fal fa-chevron-up" aria-hidden="false"></i>
									</span>
                                <div class="content">
                                    @if(isset($col3->systeminfmanage) && !empty($col3->systeminfmanage))
                                        <ul class="lnk-footers">
                                            @foreach($col3->systeminfmanage as $item)
                                                <li><a href="{{ $item->code }}">{{ $item->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($col4) && !empty($col4))
                        <div class="col-md-6 co-12 pl-2 pr-2">
                            <div class="set">
									<span class="service-icon">
										<span class="title-footer lnk-footer un-link"><span>{{ $col4->name }}</span></span>
										<i class="fa-chevron-down fal fa-chevron-up" aria-hidden="false"></i>
									</span>
                                <div class="content">
                                    @if(isset($col4->systeminfmanage) && !empty($col4->systeminfmanage))
                                        <ul class="lnk-footers">
                                            @foreach($col4->systeminfmanage as $item)
                                                <li><a href="{{ $item->code }}">{{ $item->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!--نمایش حالت گوشی-->
            <div class="col-lg-4 col-md-6 col-12 pl-md-0 d-xl-none d-lg-none mx-auto">
                <div class="row text-center">
                    <div class="col-12 p-0 logo-footer">
                        @if(isset($logoFooter) && !empty($logoFooter))
                            <img src="{{ $logoFooter->code5 }}" class="img-fluid" alt="{{ $logoFooter->name }}">
                        @endif
                    </div>
                    @if(isset($socialNetwork) && !empty($socialNetwork))
                        <div class="row">
                            <div class="col-12 p-0 text-center social-footer">
                                <ul class="social-box">
                                    @foreach($socialNetwork as $item)
                                        <li>
                                            <a href="{{ $item->code }}">
												<span class="flip">
													<img src="{{ $item->code5 }}" alt="{{ $item->name }}">
												</span>
                                                <span class="flop">
													<img src="{{ $item->code5 }}" alt="{{ $item->name }}">
												</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="row  ">
                        <div class="col-md-12 col-12 copy-right p-0">
                            All rights reserved to <span class="color"> {{ env('SITE_NAME_FA') }}  </span>
                        </div>
                        <div class="col-md-12 col-12 copy-left p-0">
                            Website designed by Nonegar Pardazesh , developed by Nonegar Pardazesh
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>