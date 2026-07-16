@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')


    <!-- help category section -->
    <div class="container-fluid help-category wrapper default ps-0 pe-0 pb-0">
      <div class="container-fluid bg-inneer pt-xs-0 pb-5">   
        <div class="container p-0">
             <div class="row">
            <div class="col-12 p-0 text-center">
             <h5 class="title-section wow fadeInUp">Exam</h5>
            </div>
        </div>
       <div class="row mt-3">
           <div class="col-12 p-0">
                <div class="help-category-body">

            <form method="post" action="{{ route('site.exam.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-12 mt-2 wow fadeInUp">
                        <div class="form-group">
                    <label for="exampleInputEmail1">first name and last name</label>
                    <input type="text" name="name" placeholder="first name and last name..."
                           value="{{ old('name') }}" class="form-control">
                    @if($errors->has('name'))
                        <span class="text-danger f-size"> {{ $errors->first('name') }}</span>
                    @endif
                </div>
                    </div>
                    <div class="col-md-6 col-12 mt-2 wow fadeInUp">
                        <div class="form-group">
                    <label for="exampleInputPassword1">mobile</label>
                    <input type="text" name="mobile"
                           placeholder="mobile ..."
                           value="{{ old('mobile') }}"
                           class="form-control">
                    @if($errors->has('mobile'))
                        <span class="text-danger f-size"> {{ $errors->first('mobile') }}</span>
                    @endif
                </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 wow fadeInUp">
                        <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <textarea class="form-control" name="description" id="" cols="30" rows="10"
                              placeholder="Description ... ">{{ old('description') }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger f-size"> {{ $errors->first('description') }}</span>
                    @endif
                </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                      <div class="form-group">
                    <label for="exampleInputPassword1">the video</label>
                    <input  type="file" class="form-control" name="video[]" multiple />
                          <span class="text-danger f-size">The maximum size of submitted files is 200 MB</span>
                      @if($errors->has('video'))
                        <span class="text-danger f-size"> {{ $errors->first('video') }}</span>
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
       </div>  
    </div>
    <!-- contact us information -->

@endsection
