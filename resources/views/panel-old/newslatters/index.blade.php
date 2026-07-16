@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت |خبرنامه
@endsection--}}
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                  @lang('cms.header-list-newsletters')
                </header>
            </section>
        </div>
    </div>

    <section class="panel">
        <div class="panel-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>@lang('cms.num')</th>
                    <th>@lang('cms.email')</th>
                    <th>@lang('cms.name')</th>
                    <th>@lang('cms.mobile')</th>
                    <th>@lang('cms.operation')</th>
                </tr>
                </thead>

                <tbody>
                @foreach($newslatters as $val)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $val->email }}</td>
                        <td>{{ $val->name }}</td>
                        <td>{{ $val->mobile }}</td>

                        <td>
                            <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                    href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <span style="margin-right: 45%">{!! $newslatters->render() !!}</span>

        </div>
    </section>

    @foreach($newslatters as $val)
        <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">@lang('cms.alert')</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            @lang('cms.question-delete')
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{route('newslatters.destroy' , ['id' => $val->id])}}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">

                            <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">@lang('cms.cancel')
                            </button>
                        </form>

                    </div>


                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
        <!-- /.modal delete -->
    @endforeach

@endsection



