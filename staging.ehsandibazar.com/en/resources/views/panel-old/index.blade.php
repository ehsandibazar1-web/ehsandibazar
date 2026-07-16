@extends('panel-old.layout.master')
{{--@section('title')
ناحیه کاربری
@endsection--}}
@section('admin-css')
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">--}}
    <link rel="stylesheet" href="{{url('site_theme/css/bootstrap3.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
@endsection

@section('content')
    <!--state overview start-->
    <div class="row state-overview">
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            {{-- paid --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol" style="background-color: #5bbf61">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($paidPayment) && !empty($paidPayment) ? $paidPayment : 0  }}</h1>
                        <p>پرداخت های موفق</p>
                    </div>
                </section>
            </div>

            {{-- pending --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol loading-payment-box">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($pendingPayment) && !empty($pendingPayment) ? $pendingPayment : 0  }}</h1>
                        <p>پرداخت های در حال پردازش</p>
                    </div>
                </section>
            </div>

            {{-- unpaid --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol red">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($unPaidPayment) && !empty($unPaidPayment) ? $unPaidPayment : 0  }}</h1>
                        <p>پرداخت های ناموفق</p>
                    </div>
                </section>
            </div>

            {{-- canceled --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol yellow">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($canceledPayment) && !empty($canceledPayment) ? $canceledPayment : 0  }}</h1>
                        <p>پرداخت های لغو شده</p>
                    </div>
                </section>
            </div>

            {{-- return --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol default">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($returnPayment) && !empty($returnPayment) ? $returnPayment : 0  }}</h1>
                        <p>مرجوعی</p>
                    </div>
                </section>
            </div>

            {{-- waiting --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol waiting">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($waitingPayment) && !empty($waitingPayment) ? $waitingPayment : 0  }}</h1>
                        <p>درحال انتظار</p>
                    </div>
                </section>
            </div>

            {{-- online --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol online">
                        <i class="icon-shopping-cart"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($onlinePayment) && !empty($onlinePayment) ? $onlinePayment : 0  }}</h1>
                        <p> پرداخت های آنلاین </p>
                    </div>
                </section>
            </div>

            {{-- delivery --}}
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol delivery">
                        <i class="icon-user"></i>
                    </div>
                    <div class="value">
                        <h1>{{ isset($users) && !empty($users) ? $users : 0  }}</h1>
                        <p> کاربران </p>
                    </div>
                </section>
            </div>

        @else
            <div class="center">
                <div class="card-index ">
                    <div class="additional">
                        <div class="user-card">
                            @if(isset(auth()->user()->image) && !empty(auth()->user()->image[0]->url))
                                <img class="image-card-border img-circle" src="{{auth()->user()->image[0]->url}}" width="100" alt="profile">
                            @else
                                <img src="{{url('admin_theme/img/noProfile.svg')}}" alt="profile">
                            @endif
                        </div>
                        <div class="more-info">
                            <h1 class="total-price">{{auth()->user()->name}} {{auth()->user()->family}} </h1>
                            <br>
                            <div class="coords">
                                @php
                                    $v = verta(auth()->user()->created_at);
                                @endphp
                                <span> تاریخ عضویت :‌ {{ $v->format('%B %d، %Y') }}</span>
                            </div>
                            <div class="coords">
                                <span>   سطح کاربری :{{\App\Utility\Level::getLevel(auth()->user()->level,0)}}</span>
                                <br>
                                <span> موبایل : {{auth()->user()->mobile}} </span>
                                <br>
                                <span> ایمیل : {{ !empty(auth()->user()->email) ? auth()->user()->email : 'ایمیلی ثبت کرده اید' }} </span>
                                <br>
                                <br>
                                <a class="link-style-a btn btn-info"
                                   href="{{route('profile.index')}}"><span style="font-size: 15px;"> پروفایل کاربری من </span></a>
                            </div>
                            <div class="stats">
                                <div class="text-center" style="font-size: 12px;">از منوی پروفایل من ، اطلاعات خود را تکمیل نمایید.</div>
                            </div>
                        </div>
                    </div>
                    <div class="general">
                        <h1>خوش آمدید</h1>
                        <div class="content-welcome">
                            <p style="text-align:justify">با تشکر از حسن انتخاب شما , امید واریم بتوانیم به درستی خدمت رسانی کنیم.خوش حال میشویم ما را از انتقاد و پیشنهادات خود با خبر سازید.</p>

                            <p style="text-align:left"> با تشکر مدیریت سایت</p>
                        </div>
                        <span class="more">{{env('SITE_NAME')}}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>


    @if(auth()->user()->isSuperAdmin())
        <!--state overview end-->
        <a href="{{ Url('/panel/log-viewer') }}"><h1 class="page-header h-panel-log">@lang('cms.error-reporting')</h1>
        </a>
        <div class="row">
            <div class="col-md-3">
                <canvas id="stats-doughnut-chart" height="300"></canvas>
            </div>
            <div class="col-md-9">
                <section class="box-body">
                    <div class="row">
                        @foreach($percents as $level => $item)
                            <div class="col-md-4">
                                <div
                                        class="info-box level level-{{ $level }} {{ $item['count'] === 0 ? 'level-empty' : '' }}">
                                <span class="info-box-icon">
                                    {!! log_styler()->icon($level) !!}
                                </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $item['name'] }}</span>
                                        <span class="info-box-number">
                                        {{ $item['count'] }} entries - {!! $item['percent'] !!} %
                                    </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $item['percent'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    @endif



@endsection

@section('admin-js')
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
        $(function () {
            new Chart($('canvas#stats-doughnut-chart'), {
                type: 'doughnut',
                data: {!! $chartData !!},
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });
    </script>
@endsection
