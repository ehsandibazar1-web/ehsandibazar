@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf_viewer.min.css"
          integrity="sha512-srhhMuiYWWC5y1i9GDsrZwGM/+rZn0fsyBW/jYzbmSiwGs8I2iAX9ivxctNznU+WndPgbqtbYECLD8KYgEB3fg=="
          crossorigin="anonymous"/>
    <style>
        #the-canvas {
            border: 1px solid black;
            direction: ltr;
        }
    </style>
    @include('site.users.partials.user-style-area')
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
    </script>

@endsection
@section('content')
    <section class="page-section account-page container p-0">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m">
                    <div class="account-orders">
                        @if(isset($books) && count($books) > 0)
                            <div class="uk-alert uk-alert-danger">
                                To view the PDF, video or Weiss of each product, click on View to see its contents
                                to be displayed
                            </div>

                            <div class="uk-grid-small uk-child-width-1-3@m" uk-grid>

                                @foreach($books as $product)
                                    <div class="home-product-box">
                                        @if(isset($product->image[0]) && !empty($product->image[0]))
                                            <a uk-toggle="target: #modal-id{{ $product->id }}" >
                                                <img src="{{ $product->image[0]->url }}"
                                                     alt="{{ $product->title }}">
                                            </a>
                                        @endif
                                        <h2 class="title"><a target="_blank"
                                                             href="{{ $product->path() }}">{{ $product->title }}</a>
                                        </h2>
                                        <p class="price-box-inner">
                                            {!! $product->prices !!}
                                        </p>
                                        <div class="meta-bot"><a class="addtocart" target="_blank"
                                                                 href="{{ route('users.panel.showBook',$product) }}"
                                                                 uk-icon="icon: file-pdf">show</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="uk-alert uk-alert-warning">Your book list is empty ...</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
