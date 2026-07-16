@if(!empty($menu->menus))
    <ol class="dd-list list-group">
        @foreach($menu->menus as $kk => $subMenu)
            <li class="dd-item list-group-item" data-id="{{ $subMenu->id }}" style="text-align: left">
                <div class="dd-handle">{{ $subMenu->title }}</div>
                <div class="dd-option-handle">
                    <a href="{{ route('panel.menu.edit',$subMenu->id) }}"
                       class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                        <i class="material-icons">update</i>
                    </a>
                    <a href="{{ route('panel.menu.delete',$subMenu->id) }}"
                       class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                        <i class="material-icons">delete</i>
                    </a>
                </div>

                @include('panel.menu.partials.sub-menus', [ 'menu' => $subMenu])
            </li>
        @endforeach
    </ol>
@endif