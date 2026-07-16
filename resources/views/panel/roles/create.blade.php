@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.role.index')
                                <a href="{{ route('panel.role.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست نقش های کاربری </a>
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
                                @can('panel.role.index')
                                    <li>
                                        <a href="{{ route('panel.role.index')  }}" style="font-size: 10px">
                                            لیست نقش کاربری
                                        </a>
                                    </li>
                                @endcan

                                @can('panel.role.create')
                                    <li>
                                        <a href="{{ route('panel.role.create')  }}" style="font-size: 10px">
                                            تعریف سطوح دسترسی
                                        </a>
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

                    @if (isset($find) && !empty($find) && !is_null($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.role.update' , ['id'=>$find->id])  }}">
                            {{ method_field('PATCH')  }}
                            @else
                                <form class="form-horizontal" method="post" action="{{ route('panel.role.store')  }}">
                                    @endif
                                    @csrf

                                    {{-- title --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="name">نام
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="name" type="text"
                                                           value="{{ isset($find) ? $find->name : "" }}" id="name"
                                                           class="form-control"
                                                           placeholder="نام نقش کاربری خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- label --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="label">برچسب
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="label" type="text" id="label"
                                                           value="{{ isset($find) ? $find->label : "" }}"
                                                           class="form-control"
                                                           placeholder="برچسب نام کاربری برای تفکیک">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- permission --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">سطوح دسترسی
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group default-select select2Style">
                                            @if (isset($allPermission) && !empty($allPermission) && count($allPermission) > 0)
                                                    <select name="permission_id[]" multiple="multiple" data-select="false" class="form-control select2">
                                                        @foreach($allPermission as $itemPermission)
                                                            <?php  $explod = explode(".", $itemPermission->name);  ?>
                                                            @if (isset($explod) && count($explod) >=3)
                                                                <option class="permission_id" name="permission_id[]"
                                                                        {{ isset($find) && !empty($find) && in_array(trim($itemPermission->id) , $find->permissions->pluck('id')->toArray()) ? 'selected' : ''  }}
                                                                        value="{{ $itemPermission->id }}"> {{ isset($itemPermission->label) ? $itemPermission->label :   \Illuminate\Support\Facades\Lang::get('cms.'.$explod[2]) . " " .\Illuminate\Support\Facades\Lang::get('cms.'.$explod[1]) }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-primary" type="button" onClick="selectAll();">انتخاب همه</button>


                                                @else
                                                    <p> سطوح دسترسی وجود ندارد </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($find) && !empty($find) && !is_null($find))
                                        @can('panel.role.update')
                                            {{-- button edit --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-4 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.role.store')
                                            {{-- button save --}}
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
<script>
    function selectAll()
    {
        options = document.getElementsByTagName("option");
        for ( i=0; i<options.length; i++)
        {
            options[i].selected = "true";
        }
    }
</script>
@endsection

