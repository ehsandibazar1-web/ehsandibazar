@for($i =1; $i<=5; $i++)
    <i class="{{ $i <= (int)$product->averageRating ? 'fas fa-star' : 'far fa-star' }}"></i>
@endfor
