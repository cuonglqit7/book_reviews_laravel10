@extends('layout.app')

@section('content')
    <div class="mb-4">
        <h1 class="sticky top-0 mb-2 text-2xl">Review sách list</h1>
    </div>
    <form action="{{ route('books.index') }}" method="get" class="mb-4 flex items-center space-x-2">
        @csrf
        <input type="text" name="title" id="title" placeholder="Tìm kiếm bằng tên" value="{{ request('title') }}"
            class="input h-10">
        <input hidden type="text" name="filter" id="filter" value="{{ request('filter') }}">
        <button type="submit" class="btn h-10">Tìm</button>
        <a href="{{ route('books.index') }}" class="btn h-10">Xóa</a>
    </form>
    <div class="filter-container mb-4 flex">
        @php
            $filter = [
                'new' => 'Mới nhất',
                'popular_last_month' => 'Phổ biến tháng trước',
                'popular_last_6months' => 'Phổ biến 6 tháng trước',
                'highest_rated_last_month' => 'Đánh giá cửa tháng trước',
                'highest_rated_last_6months' => 'Đánh giá cửa 6 tháng trước',
            ];
        @endphp

        @foreach ($filter as $key => $value)
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}" class="filter-item">
                {{ $value }}
            </a>
        @endforeach
    </div>
    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">của {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                <x-star-rating :rating="$book->reviews_avg_rating" />
                            </div>
                            <div class="book-review-count">
                                trong tổng {{ $book->reviews_count }} reviews
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">Không tìm thấy dữ liệu</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Trở về Trang chủ </a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
