@if ($rating)
    @for ($i = 0; $i < 5; $i++)
        {{ $i <= round($rating) ? '★' : '☆' }}
    @endfor
@else
    Không có đánh giá
@endif
