@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop


@section('content')

    <!-- Your content goes here  -->
    <div class="row clearfix">
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="m-b-20">
                    <div class="contact-grid">
                        <div class="profile-header bg-dark">
                            <div class="user-name">{{ $profile->name." ". $profile->family }}</div>
                            <div class="name-center">{{ \App\Utility\Level::getLevel($profile->level) }}</div>
                        </div>
                        <img
                                src="{{ isset($profile->image[0]) ? $profile->image[0]->url : url('admin/assets/images/default/noCustomer.svg')  }}"
                                class="user-img" alt="{{ $profile->name }}">
                        <p>
                            {{ isset($profile->email) ? $profile->email : 'ایمیل خود وارد نکرده اید' }}
                        </p>
                        <p>
                            @if(isset($profile->whoPresenter) && !empty($profile->whoPresenter))
                                <label for="">معرف شما :</label>
                                {{ $profile->whoPresenter->name." ".$profile->whoPresenter->family }}
                            @endif
                        </p>
                        <div>
                            <span class="phone">
                                <i class="material-icons">phone</i>{{ isset($profile->mobile ) ? $profile->mobile : 'موبایل خود را وارد نکرده اید' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation">
                    <a href="#home_with_icon_title" data-toggle="tab" class="show active">
                        <i class="material-icons">error_outline</i> عمومی
                    </a>
                </li>
                <li role="presentation">
                    <a href="#password" data-toggle="tab" class="">
                        <i class="material-icons">redo</i>تغییر گذرواژه
                    </a>
                </li>
                <li role="presentation">
                    <a href="#address" data-toggle="tab" class="">
                        <i class="material-icons">location_on</i>آدرس
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active show" id="home_with_icon_title">
                    @include('generals.allErrors')
                    <form class="form-horizontal card" method="post"
                          action="{{ route('panel.profile.update',auth()->id()) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}

                        <div class="header">
                            <h2><strong>اطلاعات پروفایل</strong></h2>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <label for="inputName" class="col-sm-3 col-form-label">نام</label>
                                <input type="text" class="form-control" id="inputName"
                                       placeholder="نام" name="name"
                                       value="{{ isset($profile) ? $profile->name : old('name') }}">
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="inputName" class="col-sm-3 col-form-label">نام خانوادگی</label>
                                <input type="text" class="form-control" id="Family"
                                       placeholder="نام خانوادگی"
                                       name="family"
                                       value="{{ isset($profile) ? $profile->family : old('family') }}">
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="inputEmail" class="col-sm-3 col-form-label">ایمیل</label>
                                <input type="email" class="form-control" id="inputEmail"
                                       placeholder="ایمیل" name="email"
                                       value="{{ isset($profile) ? $profile->email : old('email') }}">
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="inputName2" class="col-sm-3 col-form-label">موبایل</label>
                                <input type="tel" class="form-control" id="inputName2"
                                       placeholder="موبایل" name="mobile"
                                       value="{{ isset($profile) ? $profile->mobile : old('mobile') }}" disabled>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="inputName2" class="col-sm-3 col-form-label">تلفن</label>
                                <input type="tel" class="form-control" id="inputName2"
                                       placeholder="تلفن" name="tell"
                                       value="{{ isset($profile) ? $profile->tell : old('tell') }}">
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="avatar" class="col-sm-3 col-form-label">آواتار</label>
                                <input type="file" name="avatar" class="form-control-file image">
                                <img class="img-thumbnail img-100"
                                     src="{{ isset($profile->image[0]) ? $profile->image[0]->url : url('admin//assets/images/default/noCustomer.svg')  }}"
                                     alt="{{ $profile->fullName }}">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <button type="submit" class="btn btn-danger pull-left">ویرایش پروفایل</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="password">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>تغییر گذرواژه</strong></h2>
                        </div>
                        <div class="body">
                            <form method="post" action="{{ route('panel.profile.changePassword') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="password" name="current-password" class="form-control"
                                           placeholder="گذر واژه فعلی">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="new-password" class="form-control"
                                           placeholder="گذرواژه جدید">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="تکرار گذرواژه جدید"
                                           name="new-password_confirmation">
                                </div>
                                @can('panel.profile.changePassword')
                                    <button type="submit" class="btn btn-info btn-round">ذخیره تغییرات</button>
                                @endcan
                            </form>
                        </div>

                    </div>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="address">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>آدرس ها</strong></h2>
                        </div>
                        <div class="body">
                            <form method="post" action="{{ route('panel.address.add' , ['id' =>$profile->id]) }}"
                                  enctype="multipart/form-data">
                                @include('generals.allErrors')
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <select name="province_id" id="province">
                                            <option>@lang('cms.choose-state')</option>
                                            @foreach(\App\Model\Province::all() as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div id="result-ajax">
                                            <select name="city_id" id="city">
                                                <option value="">@lang('cms.choose-city')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" placeholder="@lang('cms.address')" name="fullAddress"
                                           class="form-control" value="{{ old('address') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="@lang('cms.name')" name="name" class="form-control"
                                           value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="@lang('cms.tell')" name="tell" class="form-control"
                                           value="{{ old('tell') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mobile" placeholder="@lang('cms.mobile')"
                                           class="form-control" value="{{ old('mobile') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="postal_code" placeholder="@lang('cms.postal-code')"
                                           class="form-control" value="{{ old('postal_code') }}">
                                </div>

                                @can('panel.profile.changePassword')
                                    <button type="submit" class="btn btn-info btn-round">ثبت آدرس جدید</button>
                                @endcan
                            </form>
                        </div>
                    </div>
                    <div class="row clearfix">
                        @if(isset($address) & !empty($address))
                            @foreach($address as $item)
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card">
                                        <div class="header bg-orange">
                                            <h2>
                                                {{ $item->name }}
                                                <small>{{ $item->mobile }} - {{ $item->tell }}</small>
                                            </h2>
                                            <ul class="header-dropdown ">
                                                <li>
                                                    <a href="{{ route('panel.address.delete',$item->id) }}" >
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="body">
                                            {{ $item->province->name }} ،  @lang('cms.city')  {{ $item->city->name }} ، {{ $item->fullAddress }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">قسمت مورد نظر از تصویر خود را انتخاب نمایید</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-1">
                                <!--<div class="preview"></div>-->
                            </div>
                            <div class="col-md-10">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-1">
                                <!--<div class="preview"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" id="crop">انتخاب</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCover" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">قسمت مورد نظر از تصویر کاور خود را انتخاب نمایید</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-1">
                                <!--<div class="preview"></div>-->
                            </div>
                            <div class="col-md-10">
                                <img id="cover" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-1">
                                <!--<div class="preview"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" id="cropCover">انتخاب</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('admin-css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

@section('admin-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Custom Js -->
    <script src="{{ asset('admin/assets/js/pages/medias/image-gallery.js') }}"></script>

    <script src="{{ asset('js/vendors.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

    <script>
        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;
        $("body").on("change", ".image", function (e) {
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $("#crop").click(function () {
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });
            canvas.toBlob(function (blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function () {
                    var base64data = reader.result;
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('panel.profile.avatar') }}",
                        data: {_token: CSRF_TOKEN, image: base64data},
                        success: function (data) {
                            console.log(data);
                            $modal.modal('hide');
                            Swal.fire({
                                title: "موفقیت آمیز",
                                text: "تصویر با موفقیت بروز گردید",
                                icon: "success",
                                button: "تایید",
                            }).then(function () {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        $('#province').change(function (e) {
            var province_id = $(this).val();
            //  alert(province_id);
            e.preventDefault();
            /* start ajax */
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "post",
                url: "{{route('panel.profile.ajaxCity')}}",
                data: {provinceID: province_id, _token: CSRF_TOKEN},
                success: function (data) {
                    $('#result-ajax').html(data.html);
                },
                error: function (error) {
                    //alert(error);
                    alert("لطفا چند لحظه دیگر امتحان نمایید")
                }
            });
            /* end ajax */
        });

    </script>
@endsection
