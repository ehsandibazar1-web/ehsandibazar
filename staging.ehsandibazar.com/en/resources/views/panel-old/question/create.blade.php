@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($question) && $question->count() > 0)
                        نظرسنجی | ویرایش نظرسنجی
                    @else
                        نظرسنجی | ایجاد کردن نظرسنجی جدید
                    @endif

                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($question) && $question->count() > 0)
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.question.update' , ['id' => $question->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.question.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group" id="titles">
                                            <label for="title" class="control-label col-lg-2">عنوان </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($question) && $question->count() > 0 ? $question->title : null }}"
                                                       id="title" name="title" minlength="2" type="text" />
                                            </div>
                                        </div>

                                        <div id="answer" class="pull-left">
{{--                                             <button class="btn btn-info">پاسخ ها</button>--}}

                                            <button class="btn btn-info" title="نمایش" data-toggle="modal"
                                                    href="#show1">
                                                پاسخ ها
                                            </button>


                                        </div>
                                        <br>
                                        <br>
                                        <br>

                                        <div class="form-group" id="bodies">
                                            <label for="body" class="control-label col-lg-2">متن نظرسنجی
                                            </label>
                                            <div class="col-lg-10">
                                                    <textarea name="body" required id="editor1"
                                                              class="form-control ckeditor"> {!! isset($question) && $question->count() > 0 ?  $question->question  : null !!} </textarea>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            @if(isset($question) && $question->count() > 0)
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit" value=" ویرایش">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit" value=" ذخیره">
                                                </div>
                                            @endif

                                        </div>


                                        <!-- Modal show -->
                                        <div class="modal fade" id="show1" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">
                                                            &times;
                                                        </button>
                                                        <h4 class="modal-title"><span class=""> ایجاد پاسخ : </span>
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div id="input"></div>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <div class="col-md-6">
                                                                    <button type="button" class="btn btn-primary"
                                                                            data-dismiss="modal" aria-hidden="true">
                                                                        ارسال
                                                                    </button>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <button id="createinput"
                                                                                class="btn btn-info btn-small ">+
                                                                        </button>
                                                                        <button id="deleteinput"
                                                                                class="btn btn-danger btn-small ">-
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>


                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->

                                    </form>

                    </div>

                </div>


            </section>
        </div>
    </div>
@endsection






@section('admin-js')

    <script>

        $(document).ready(function () {

            $(".type").change(function () {
                var selecteds = $(".type option:selected").val();

                if (selecteds > 2 ) {

                    /* start ajax */
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "post",
                        url: "{{route('panel.question.ajax')}}",
                        data: {isRequestID: selecteds, _token: CSRF_TOKEN},
                        success: function (data) {
                            $("#result").html(data.html);
                        },
                        error: function (error) {
                            alert("لطفا چند لحظه دیگر امتحان نمایید")
                        }
                    });
                    /* end ajax */

                } else {
                    $('#result').html("");
                }

                if(selecteds > 4){
                    $('#titles').css('display', 'none');
                    $('#bodies').css('display', 'none');
                }else{
                    $('#titles').css('display', 'block');
                    $('#bodies').css('display', 'block');
                }

                if (selecteds  > 3 ) {
                    $('#answer').css('display', 'block');
                } else {
                    $('#answer').css('display', 'none');
                }

            });


            /* edit start ajax */

            @if(isset($question) && $question->count() > 0)

            // edit brand id ajax
            var brand_id_ajax_value =  $('#brand_ajax').val();
            if(brand_id_ajax_value == 4){
                $('#answer').css('display' ,'block');
            }
            if( brand_id_ajax_value > 2 ){

                /* start ajax */
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "post",
                    url: "{{route('panel.question.ajax.edit')}}",

                    /* brand_id_ajax_value => id->type ====== question_id => id->question */
                    data: {isRequestID: brand_id_ajax_value,
                        question_id : "<?= $question->id ?>",
                        _token: CSRF_TOKEN},
                    success: function (data) {
                   /*     alert(data);*/
                        $("#result").html(data.html);
                    },
                    error: function (error) {
                        alert("لطفا چند لحظه ديگر امتحان نماييد")
                    }
                });
            }else{}
            @endif
            /* edit end ajax */

        });

    </script>

    {{-- answer button --}}
    <script>

        $("#createinput").click(function (e) {

            e.preventDefault();

            $("#input").append(" <div id=\"addinput\" class=\"form-group{{ $errors->has('option') ? ' has-error' : '' }}\">\n" +
                "                                            <label  for=\"mobile\"\n" +
                "                                                   class=\"col-md-4 control-label\">گزینه</label>\n" +
                "                                            <div class=\"col-md-6\">\n" +
                "                                                <input id=\"mobile\" type=\"text\" class=\"form-control\" name=\"option[]\"\n" +
                "                                                       value=\"{{ old('option') }}\" required autofocus>\n" +
                "\n" +
                "                                                @if ($errors->has('option'))\n" +
                "                                                    <span class=\"help-block\">\n" +
                "                                        <strong>{{ $errors->first('option') }}</strong>\n" +
                "                                                    </span>\n" +
                "                                                @endif\n" +
                "                                            </div>\n" +
                "\n" +
                "\n" +
                "                                        </div>");


        });

        $("#deleteinput").click(function (e) {

            e.preventDefault();

            $('#input div#addinput:last').remove();


        });


        @if(isset($question))
            //  $t->title
            $(".type").change(function (){
                $('#input').html("");
            });

            <?php
                    $answer = \App\Model\Answer::where('question_id' , $question->id)->get();
            ?>
            @foreach($answer as $val)

        $("#input").append(" <div id=\"addinput\" class=\"form-group{{ $errors->has('option') ? ' has-error' : '' }}\">\n" +
            "                                            <label  for=\"mobile\"\n" +
            "                                                   class=\"col-md-4 control-label\">گزینه</label>\n" +
            "                                            <div class=\"col-md-6\">\n" +
            "                                                <input id=\"mobile\" type=\"text\" class=\"form-control\" name=\"option[]\"\n" +
            "                                                       value=\"{{ isset($question)  ? $val->title  : old('option') }}\"  autofocus>\n" +
            "\n" +
            "                                                @if ($errors->has('option'))\n" +
            "                                                    <span class=\"help-block\">\n" +
            "                                        <strong>{{ $errors->first('option') }}</strong>\n" +
            "                                                    </span>\n" +
            "                                                @endif\n" +
            "                                            </div>\n" +
            "\n" +
            "\n" +
            "                                        </div>");


        @endforeach

        @endif

    </script>

@endsection
