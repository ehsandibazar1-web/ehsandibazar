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

                        <strong> {{ $title ?? "" }}</strong>
                        <div class="pull-left margin-5">
                            @can('panel.category.index')
                                <a href="{{ route('panel.category.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست دسته بندی ها </a>
                            @endcan
                        </div>
                    </h2>


                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            @if(isset($attributeGroups) && !empty($attributeGroups))
                                <form action="{{ route('panel.category.attributed',$find->id) }}" method="post">
                                    @csrf
                                    @foreach($attributeGroups as $attributeGroup)
                                        <div class="panel-group" id="accordion_{{$loop->iteration}}" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading" role="tab"
                                                     id="headingOne_{{$loop->iteration}}">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion_{{$loop->iteration}}"
                                                           href="#collapseOne_{{$loop->iteration}}" aria-expanded="true"
                                                           aria-controls="collapseOne_{{$loop->iteration}}"
                                                           class="collapsed">
                                                            <b><i class="material-icons">arrow_drop_down_circle</i>
                                                                {{ $attributeGroup->name." ".$attributeGroup->label }}
                                                            </b>

                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne_{{$loop->iteration}}"
                                                     class="panel-collapse collapse @if($loop->iteration < 5) in show @endif"
                                                     role="tabpanel"
                                                     aria-labelledby="headingOne_{{$loop->iteration}}">
                                                    <div class="panel-body">
                                                        <div class="row clearfix">
                                                            @foreach($attributeGroup->attributes as $key => $attribute)
                                                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                                                    <p>
                                                                        <label for="{{$attribute->name}}">
                                                                            <input id="{{$attribute->name}}"
                                                                                   type="checkbox"
                                                                                   value="{{$attribute->id}}"
                                                                                   class="filled-in" @if(isset($find) && in_array($attribute->id,$find->attributes->pluck('id')->toArray())) checked  @endif
                                                                                   name="attributes[]">
                                                                            <span>{{$attribute->name}}</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            @endforeach


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @can('panel.category.attributed')
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                <button type="submit" class="btn-hover color-1 pull-left">ارسال
                                                </button>
                                            </div>
                                        </div>
                                    @endcan
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop
