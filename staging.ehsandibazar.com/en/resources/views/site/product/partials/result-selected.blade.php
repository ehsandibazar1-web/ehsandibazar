<ul>
    @if(isset($selected) && !empty($selected))
        @foreach($selected as $itemSelect)
            <li>
                <span>{{ $itemSelect }}</span>
            </li>
        @endforeach
    @endif
</ul>