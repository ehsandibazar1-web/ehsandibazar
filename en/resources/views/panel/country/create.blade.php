@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop
@section('admin-css')
    <link href="{{ Url('admin/assets/leaflet/leaflet.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.country.index')
                                <a href="{{ route('panel.country.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> فهرست کشور ها </a>
                            @endcan
                        </div>
                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                                @can('panel.country.index')
                                    <li>
                                        <a href="{{ route('panel.country.index')  }}" style="font-size: 10px">فهرست
                                            کشور ها</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <br>
                    @if(isset($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.country.update',$find->id)  }}">
                            {{ method_field("PATCH") }}
                            @else
                                <form class="form-horizontal" method="post"
                                      action="{{ route('panel.country.store')  }}">
                                    @endif

                                    @csrf

                                    {{-- fa_name --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="fa_name">نام فارسی کشور
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="fa_name" type="text" id="fa_name" class="form-control"
                                                           placeholder="نام فارسی کشور را بنویسید"
                                                           value="{{ isset($find) ? $find->fa_name : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- en_name --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="en_name">نام انگلیسی کشور
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="en_name" type="text" id="en_name" class="form-control"
                                                           placeholder="نام انگلیسی کشور را بنویسید"
                                                           value="{{ isset($find) ? $find->en_name : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- status --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="status">وضعیت
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="">
                                                    <select name="status" id="status" class="form-group">

                                                        <option value="0"> -- وضعیت را انتخاب کنید --</option>
                                                        @foreach(\App\Utility\Status::Status() as $key => $itemStatus)
                                                            <option
                                                                value="{{ $key  }}" {{ isset($find) && $key == $find->status ? 'selected' : null }}> {{ $itemStatus  }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- map --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">نقشه
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div>
                                                    <div id="map_wrapper"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="text" name="lat" id="blockinfoform-lat"
                                           value="@if(isset($find)) {{ $find->lat }}  @else {{ old('lat') }} @endif">
                                    <input type="text" name="lon" id="blockinfoform-lon"
                                           value="@if(isset($find)) {{ $find->lon }}  @else {{ old('lon') }} @endif">


                                    @if(isset($find))
                                        @can('panel.country.update')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.country.store')
                                            {{-- button --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @endif


                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop

@section('admin-js')
    <script src="{{ Url('admin/assets/leaflet/leaflet.js') }}"></script>

    {{-- map --}}
    <script>
        @if(isset($find) && !empty($find->lat))
        function MapCreate() {
            var map;
            var pin;

            var latitude = $('#blockinfoform-lat').val();
            var longitude = $('#blockinfoform-lon').val();
            var tilesURL = 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png';
            var mapAttrib = ' <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>';
            // add map container
            $('#map_wrapper').prepend('<div id="map" style="height:350px;width:100%;"></div>');
            // MapCreate();


            // create map instance
            if (!(typeof map == "object")) {
                map = L.map('map', {
                    center: [35.699231059010316, 51.38922386402513],
                    zoom: 12
                });
                if (latitude.length !== 0 && longitude.length !== 0) {
                    pin = L.marker([{{$find->lat}},{{$find->lon}}], {
                        riseOnHover: true,
                        draggable: true
                    }).addTo(map);
                    map.setView([{{$find->lat}},{{$find->lon}}], 15);


                }
            } else {
                map.setZoom(3).panTo([40, 0]);
            }
            // create the tile layer with correct attribution
            L.tileLayer(tilesURL, {
                attribution: mapAttrib,
                maxZoom: 19
            }).addTo(map);
            map.setView([{{$find->lat}},{{$find->lon}}], 15);



            map.on('click', function (ev) {
                var c = map.getCenter();
                $('#blockinfoform-lat').val(ev.latlng.lat);
                $('#blockinfoform-lon').val(ev.latlng.lng);
                $('#blockinfoform-x_coordinate').val(c.lat);
                $('#blockinfoform-y_coordinate').val(c.lng);
                if (typeof pin == "object") {
                    pin.setLatLng(ev.latlng);

                } else {
                    pin = L.marker(ev.latlng, {riseOnHover: true, draggable: true});
                    pin.addTo(map);


                    pin.on('drag', function (ev) {
                        $('#blockinfoform-lat').val(ev.latlng.lat);
                        $('#blockinfoform-lon').val(ev.latlng.lng);
                    });
                }
            });
        }

        MapCreate();

        @else
        function MapCreate() {
            var map;
            var pin;
            var latitude = $('#blockinfoform-lat').val();
            var longitude = $('#blockinfoform-lon').val();
            var tilesURL = 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png';
            var mapAttrib = ' <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>';
            // add map container
            $('#map_wrapper').prepend('<div id="map" style="height:350px;width:100%;"></div>');
            // MapCreate();


            // create map instance
            if (!(typeof map == "object")) {
                map = L.map('map', {
                    center: [{{ isset($find->lat) ? $find->lat : '35.699231059010316' }}, {{ isset($find->lon) ? $find->lon : '51.38922386402513' }}],
                    zoom: 12
                });

            } else {
                map.setZoom(3).panTo([40, 0]);
            }
            // create the tile layer with correct attribution
            L.tileLayer(tilesURL, {
                attribution: mapAttrib,
                maxZoom: 19
            }).addTo(map);


            map.on('click', function (ev) {
                var c = map.getCenter();
                $('#blockinfoform-lat').val(ev.latlng.lat);
                $('#blockinfoform-lon').val(ev.latlng.lng);
                $('#blockinfoform-x_coordinate').val(c.lat);
                $('#blockinfoform-y_coordinate').val(c.lng);


                if (typeof pin == "object") {
                    pin.setLatLng(ev.latlng);
                } else {
                    pin = L.marker(ev.latlng, {riseOnHover: true, draggable: true});
                    pin.addTo(map);


                    pin.on('drag', function (ev) {
                        $('#blockinfoform-lat').val(ev.latlng.lat);
                        $('#blockinfoform-lon').val(ev.latlng.lng);
                    });
                }
            });


        }
        MapCreate();
        @endif


    </script>
    {{-- map --}}
@endsection
