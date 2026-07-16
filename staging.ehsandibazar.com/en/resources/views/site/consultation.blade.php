@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')

    <!--Contact Us Page-->
    <div class="container-fluid pink-bg wrapper default ">
        <div class="container">
            <div class="row mt-3 mb-3">
                <div class="col-12 p-0">
                    <div class="help-category-header">
                        <h5 class="title-section wow fadeInUp">For better and more effective advice, please complete the following</h5>
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
                                <label for="exampleInputEmail1">first name and last name</label>
                                <input type="text" name="name" placeholder="first name and last name ..."
                                       value="" class="form-control">
                                @if($errors->has('name'))
                                    <span class="text-danger f-size"> {{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Date of birth</label>
                                <input type="text" name="birth_date" value="" class="form-control">
                                @if($errors->has('birth_date'))
                                    <span class="text-danger f-size"> {{ $errors->first('birth_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Height</label>
                                <input type="number" name="height" value="" class="form-control">
                                @if($errors->has('height'))
                                    <span class="text-danger f-size"> {{ $errors->first('height') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Weight</label>
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
                                <label for="exampleInputPassword1">Address</label>
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
                                <label for="exampleInputPassword1">Mobile number</label>
                                <input type="number" name="mobile" value="" class="form-control">
                                @if($errors->has('mobile'))
                                    <span class="text-danger f-size"> {{ $errors->first('mobile') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">job</label>
                                <input type="text" name="job" value="" class="form-control">
                                @if($errors->has('job'))
                                    <span class="text-danger f-size"> {{ $errors->first('job') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mt-2 wow fadeInUp">
                            <div class="form-group">
                                <label for="exampleInputPassword1">History of sports activities</label>
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
                                <label for="exampleInputPassword1">
                                    Have you ever been restricted or banned from exercising due to medical problems? If yes, for what reason and in what year
                                </label>
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
                                <label for="exampleInputPassword1">Write to us if you have physical limitations such as pain or injury in the lower back, neck or knees or medical limitations such as heart, respiratory or blood pressure problems.</label>
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
                                <label for="exampleInputPassword1">Fear of injury in martial arts is a natural fear for people, especially beginners, if you have it, write to us</label>
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
                                <label for="exampleInputPassword1">
                                    Have you ever dreamed of that moment?
                                    Have you wished I had more self-defense skills and was a fighter and had more power? If yes, explain
                                </label>
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
                                <label for="exampleInputPassword1">Write to us in detail the purpose of martial arts and self-defense exercises</label>
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
                                <label for="exampleInputPassword1">
                                    How did you get acquainted with our courses? Do you have an identifier? If yes, who?
                                   </label>
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
                                <label for="exampleInputPassword1">
                                    Please write us the ID of your social network page
                                </label>
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
                                    send
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
