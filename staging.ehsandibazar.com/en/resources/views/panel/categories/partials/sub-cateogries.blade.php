@if(!empty($category->categories))
    <ol class="dd-list list-group">
        @foreach($category->categories as $kk => $subCategory)
            <li class="dd-item list-group-item" data-id="{{ $subCategory->id }}" style="text-align: left">
                <div class="dd-handle">{{ $subCategory->title }}</div>
                <div class="dd-option-handle">
                    @if($subCategory->isAttributable())
                        <a href="{{ route('panel.category.attributedForm',$subCategory->id) }}" title="انتخاب ویژگی ها"
                           class="btn btn-primary btn-circle waves-effect waves-circle waves-float">
                            <i class="material-icons">spellcheck</i>
                        </a>
                    @endif

                    @can('panel.category.update')
                        <a href="{{ route('panel.category.edit',$subCategory->id) }}"
                           class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                            <i class="material-icons">update</i>
                        </a>
                    @endcan
                    @can('panel.category.delete')
                        <a href="{{ route('panel.category.delete',$subCategory->id) }}"
                           class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                            <i class="material-icons">delete</i>
                        </a>
                    @endcan
                </div>

                @include('panel.categories.partials.sub-cateogries', [ 'category' => $subCategory])
            </li>
        @endforeach
    </ol>
@endif