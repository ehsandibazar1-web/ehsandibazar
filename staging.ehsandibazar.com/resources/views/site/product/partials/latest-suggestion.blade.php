@if(isset($suggestions) && count($suggestions) > 0)
    <table class="table table-hover">
        <thead>
        <tr class="thead-suggestions">
            <td></td>
            <td>کاربر</td>
            <td>پیشنهاد</td>
            <td>کلیک باقی مانده</td>
        </tr>
        </thead>
        <tbody>
        @foreach($suggestions->SortByDesc('amount')  as $suggestion)
            <tr>
                <td><i class="fa fa-user"></i></td>
                <td>{{ $suggestion->user->name." ".$suggestion->user->family }}</td>
                <td>{{ number_format($suggestion->amount)." تومان " }}</td>
                <td>{{  $suggestion->click_the_rest }}</td>
            </tr>
        @endforeach

        </tbody>

    </table>
@else
    <span class="alert">در حال حاضر پیشنهادی داده نشده است</span>
@endif
