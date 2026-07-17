<p>
    شما هم می‌توانید نظر بدهید.
</p>
<div class="ask-your-q">
    @guest()
        <a href="{{ route('login') }}">
            برای ثبت نظر ، لازم است ابتدا وارد حساب کاربری خود شوید
        </a>
        @endguest
<!--@component('components.rating')-->
    <!--@endcomponent-->
</div>
<div class="hidden-ask-q">

    @auth()
        <form action="{{ route('site.add.comment',isset($article) ? $article->id : $product->id) }}" class="form"
              method="post">
            @csrf
            <input type="hidden" name="commentable_id"
                   value="@if(isset($article)){{ $article->id }}@else{{ $product->id }}@endif">
            <input type="hidden" name="commentable_type"
                   value="@if(isset($article)){{ get_class($article) }}@else{{ get_class($product) }}@endif">
            <div class="form-group">
                <label for="">سوال شما :</label>
                <textarea name="comment" cols="30" rows="10" class="form-control"
                          placeholder="نظر یا سوال خود را برای ما بنویسید..."></textarea>
                @if($errors->has('comment'))
                    <span class="text-danger">{{ $errors->first('comment') }}</span>
                @endif
            </div>
            <button class="btn btn-site">ارسال</button>
        </form>
    @else
        <div class="form pcomment-form">
            <span class="alert alert-info" style="display: block">برای ارسال دیدگاه ابتدا <a
                        href="{{ route('login') }}">وارد شوید</a></span>
        </div>
    @endauth
</div>


{{--foreach Comment User--}}
@if(isset($comments) && count($comments) > 0)
    <div class="hidden-question-list">
        @foreach($comments as $comment)
            <div class="question-item">
                <div class="reply-section">
                    <div class="reply-question-item">
                        <div class="reply-avatar-image">
                            @if(isset($comment->user->image[0]) && !empty($comment->user->image[0]))
                                <img loading="lazy" src="{{ Url($comment->user->image[0]->url) }}">
                            @else
                                <img loading="lazy" width="32" src="{{ url('admin_theme/img/noCustomer.svg')  }}" alt="">
                            @endif                </div>
                        <div class="reply-main-detail">
                            <p>
                                {!! $comment->comment !!}
                            </p>
                            <p class="reply-dependies">
                                <span>توسط {{ $comment->user->name }}</span>
                                <span>در تاریخ :  @php $v = verta($comment->created_at);
                                            echo $v->format('%d %B %Y');
                                    @endphp</span>
                            </p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="see-more-question">
            {{ $comments->render() }}
        </div>
    </div>
@endif
