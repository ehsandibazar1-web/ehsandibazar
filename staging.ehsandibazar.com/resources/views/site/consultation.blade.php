@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_themes/css/internal/style.css"/>
@endsection
@section('content')

    <!--Contact Us Page-->
    <div class="container-fluid pink-bg wrapper default ">
        <div class="container">
            <div class="row mt-3 mb-3">
                <div class="col-12 p-0">
                    <div class="help-category-header">
                        <h5 class="title-section wow fadeInUp">برای مشاوره بهتر و موثر لطفا موارد زیر رو تکمیل فرمایید</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contact us body -->
    <div class="container-fluid bg-inneer pt-xs-0">


        <!-- help category section -->
        <div class="container help-category">

           <div class="row mt-4 mb-4">
               <div class="col-12 p-0">
                    <div class="help-category-body">
                <form method="post" action="{{ route('site.consultation.save') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputEmail1">نام و نام خانوادگی</label>
                                <input type="text" name="name" placeholder="نام خود را وارد کنید..."
                                       value="" class="form-control">
                                @if($errors->has('name'))
                                    <span class="text-danger f-size"> {{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">تاریخ تولد</label>
                                <input type="text" name="birth_date" value="" class="form-control">
                                @if($errors->has('birth_date'))
                                    <span class="text-danger f-size"> {{ $errors->first('birth_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">قد</label>
                                <input type="number" name="height" value="" class="form-control">
                                @if($errors->has('height'))
                                    <span class="text-danger f-size"> {{ $errors->first('height') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">وزن</label>
                                <input type="number" name="weight" value="" class="form-control">
                                @if($errors->has('weight'))
                                    <span class="text-danger f-size"> {{ $errors->first('weight') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">محل سکونت</label>
                                <input type="text" name="address" value="" class="form-control">
                                @if($errors->has('address'))
                                    <span class="text-danger f-size"> {{ $errors->first('address') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">شماره همراه</label>
                                <input type="number" name="mobile" value="" class="form-control">
                                @if($errors->has('mobile'))
                                    <span class="text-danger f-size"> {{ $errors->first('mobile') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">شغل</label>
                                <input type="text" name="job" value="" class="form-control">
                                @if($errors->has('job'))
                                    <span class="text-danger f-size"> {{ $errors->first('job') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">سابقه فعالیت ورزشی</label>
                                <input type="text" name="history_sports_activities" value="" class="form-control">
                                @if($errors->has('history_sports_activities'))
                                    <span class="text-danger f-size"> {{ $errors->first('history_sports_activities') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">آیا تا به حال به دلیل مشکلات پزشکی محدود و یا منع
                                    ورزشی شده
                                    اید؟ اگر بله به چه دلیل و چه سالی</label>
                                <input type="text" name="prohibition_sports" value="" class="form-control">
                                @if($errors->has('prohibition_sports'))
                                    <span class="text-danger f-size"> {{ $errors->first('prohibition_sports') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">اگر محدودیت جسمی مثل درد یا آسیب در ناحیه کمر ، گردن
                                    و یا
                                    زانو و یا محدودیت پزشکی مثل مشکلات قلبی ، تنفسی و یا فشار خون دارید برایمان
                                    بنویسید</label>
                                <input type="text" name="physical_limitations" value="" class="form-control">
                                @if($errors->has('physical_limitations'))
                                    <span class="text-danger f-size"> {{ $errors->first('physical_limitations') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">ترس آسیب در ورزش های رزمی یک ترس طبیعی برای افراد به
                                    خصوص
                                    مبتدی هست اگر آن را دارید برایمان بنویسید</label>
                                <input type="text" name="fear_injury" value="" class="form-control">
                                @if($errors->has('fear_injury'))
                                    <span class="text-danger f-size"> {{ $errors->first('fear_injury') }}</span>
                                @endif
                            </div>
                        </div>
                    </div> 
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">آیا تا به حال اتفاقی برای شما افتاده که آن لحظه آرزو
                                    کرده
                                    باشید ای کاش مهارت دفاع شخصی بیشتری داشتم و رزمی کار بودم و قدرت بیشتری داشتم؟ اگر
                                    بله توضیح
                                    بدین</label>
                                <input type="text" name="self_defense_skills" value="" class="form-control">
                                @if($errors->has('self_defense_skills'))
                                    <span class="text-danger f-size"> {{ $errors->first('self_defense_skills') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">هدف از تمرینات ورزش های رزمی و دفاع شخصی رو به صورت
                                    دقیق
                                    برایمان بنویسید</label>
                                <input type="text" name="purpose_exercise" value="" class="form-control">
                                @if($errors->has('purpose_exercise'))
                                    <span class="text-danger f-size"> {{ $errors->first('purpose_exercise') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">از کجا با دوره های ما آشنا شدین؟ آیا معرف دارید؟ اگر
                                    بله چه
                                    کسی؟</label>
                                <input type="text" name="get_acquainted" value="" class="form-control">
                                @if($errors->has('get_acquainted'))
                                    <span class="text-danger f-size"> {{ $errors->first('get_acquainted') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row wow fadeInUp">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="exampleInputPassword1">لطفا آیدی صفحه شبکه اجتماعیتان رو برایمان
                                    بنویسید</label>
                                <input type="text" name="social_networkId" value="" class="form-control"
                                       placeholder="example : instagram/myid">
                                @if($errors->has('social_networkId'))
                                    <span class="text-danger f-size"> {{ $errors->first('social_networkId') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row wow fadeInUp">
                        <div class="col-md-3 col-12 mt-4 ms-auto ">
                            
                            <button  type="submit" class="btn-send form-control" >
                                    ارسال
                                   <i class="fal fa-paper-plane"></i>
                                </button>
                       
                        </div>
                    </div>

                </form>
            </div>
               </div>
           </div>
        </div>
        <!-- contact us information -->


    </div>
    <!--Contact Us Page-->
@endsection
