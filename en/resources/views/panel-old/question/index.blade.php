@extends('panel-old.layout.master')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    لیست نظرسنجی ها
                    <a type="button" class="btn btn-success btn-xs top-left" data-toggle="modal"
                       href="{{route('panel.question.create')}}">ایجاد نظرسنجی جدید
                    </a>

                    @include('generals.allErrors')
                    @include('generals.sessionMessage')

                </header>

                <div class="form-group">


                    {{--<a  type="button" class="btn btn-warning top-left" data-toggle="modal" href="{{route('panel.category.create')}}">افزودن دسته بندی
                    </a>--}}

                </div>

                <div class="panel-body">




                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>نظرسنجی</th>
                            <th>ایجاد کننده</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if (isset($question) && $question->count() > 0)
                            <?php $i = 1 ?>
                            @foreach($question as $val)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $val->title}}</td>
                                    <td>{!! str_limit($val->question , 100) !!}</td>
                                    <td>{{ $val->user->name }}</td>
                                    <td class="view-message">

                                        <a href="{{route('panel.question.state' ,['id' => $val->id] )}}">{{ \App\Utility\Status::getStatus($val->state)}}</a>

                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-xs" title="نمایش" data-toggle="modal"
                                                href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                        <a class="btn btn-warning btn-xs" title="ویرایش"
                                           href="{{ route('panel.question.edit' , ['id' => $val->id]) }}"><i
                                                    class="icon-edit"></i></a>
                                        <button class="btn btn-danger btn-xs" title="حذف" data-toggle="modal"
                                                href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>

                                    </td>
                                </tr>
                                <?php $i++?>
                            @endforeach

                        @endif
                        </tbody>

                    </table>

                    @if(isset($question) && $question->count() > 0)
                        <span style="margin-right: 45%">{!! $question->render() !!}</span>
                    @endif

                <!-- Modal show -->
                    @if(isset($question) && $question->count() > 0)
                        @foreach($question as $val)
                            <div class="modal fade " id="show{{ $val->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                &times;
                                            </button>
                                            <h4 class="modal-title"><span
                                                        class="">نمایش کلی</span></h4>
                                        </div>
                                        <div class="modal-body">

                                            <p> متن کامل نظرسنجی : </p>
                                            <p> {!! $val->question !!} </p>
                                            <br>
                                            <p> {!! $val->description !!} </p>

                                            <hr>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <?php $sum=0; ?>
                                                    @foreach($val->answers as $key => $opt)

                                                        <?php
                                                        $all=App\Model\Vote::where('answer_id',$opt->id)->count();
                                                        $sum+=$all;
                                                        ?>
                                                    @endforeach

                                                    @foreach($val->answers as $key => $opt)
                                                        <span>{{ $loop->iteration }}) {{ $opt->title }}</span></t>
                                                        <span>
    		                                <?php
                                                            $all=App\Model\Vote::where('answer_id',$opt->id)->count();
                                                            echo $all."<br>";
                                                            if($sum>0)
                                                            {
                                                                $result=floor($result=($all / $sum) * 100);

                                                                echo "<div class='progress progress-striped progress-sm'>
                                        <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100' style='width: $result%'>
                                       $result %</div>
                                    </div>";
                                                            }

                                                            ?>
    		                             </span></br>

                                                    @endforeach

                                                </div>

                                            </div>


                                        </div>


                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                    @endforeach
                @endif
                <!-- /.modal-dialog -->
                </div>
                <!-- /.modal show -->


        </div>
        <!-- /.modal edit -->


        <!-- Modal delete -->
        @if(isset($question) && $question->count() > 0)
            @foreach($question as $val)
                <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">هشدار !</h4>
                            </div>
                            <div class="modal-body">
                                آیا مطمعن به حذف این آیتم هستید؟
                            </div>
                            <div class="modal-footer">
                                <form action="<?= route('panel.question.delete', ['id' => $val->id]); ?>" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="حذف کردن" class="btn btn-danger">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">انصراف</button>
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
    @endif

    </div>

    </section>
    </div>
    </div>

@endsection
