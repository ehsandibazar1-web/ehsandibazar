@extends('site.layout.master')
@section('site.css')
    @include('users.layouts.partials.styles')
@endsection
@section('content')
    <section class="page-section account-page">
        <div class="uk-container uk-containcer-center uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-grid" uk-grid>
                @include('site.users.partials.menu')
                <div class="uk-width-3-4@m uk-background-muted uk-padding">
                    <h5>فهرست مزایده های شرکت کرده</h5>
                    @if(isset($auctions) && count($auctions) > 0)
						<div class="uk-overflow-auto">
                        <table class="cart-page-table orders-table uk-table uk-table-hover uk-table-divider">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>مزایده</th>
                                <th>برنده/بازنده</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auctions as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if(auth()->user()->isSuperAdminOrAdmin())<td>{{ $val->user->name." ".$val->user->family }}</td>@endif
                                    <td><a href="{{ $val->auction->product->path() }}" target="_blank">{{ $val->auction->product->title }}</a></td>
                                    <td>{!! $val->type == 1 ? '<span class="btn btn-success">برنده</span>' : '<span class="btn btn-danger">بازنده</span>' !!}</td>
                                    <td>{{ $val->created_at }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
						</div>
                    @else
                        <div class="uk-alert uk-alert-warning">شما تا به حال در مزایده شرکت نکرده اید!</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
