@extends('layout.app')
@section('content')
    <h1 class="mb-10 text-2xl">Thêm đánh giá cho {{ $book->title }}</h1>
    <form action="{{ route('books.reviews.store', $book) }}" method="post">
        @csrf
        @method('POST')
        <label for="reviews">Bình luận</label>
        <textarea name="reviews" id="reviews" required class="input mb-4"></textarea>
        <label for="rating">Đánh giá</label>
        <select name="rating" id="rating" class="input mb-4">
            <option value="" disabled selected>Lựa chọn đánh giá</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="btn mb-2">Thêm đánh giá</button>
    </form>
@endsection
