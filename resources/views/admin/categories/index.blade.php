{{-- Admin = 設定（アウトプット内）　の　categoryパートの　大枠部分 --}}


@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="post">
        @csrf
        <div class="row gx-2 mb-4">
            <div class="col-4">
                <input type="text" name="name" id="" class="form-control" placeholder="Add a category..." autofocus>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add</button>
            </div>
            {{-- Error message area --}}
        </div>
    </form>
    <div class="row">
        <div class="col-7">
            <table class="table table-hover bg-white align-middle text-muted border text-center table-sm">
                <thead class="table-warning small text-secondary">
                    <th>#</th>
                    <th>NAME</th>
                    <th>COUNT</th>
                    <th>LAST UPDATED</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($all_categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                          {{-- ↓　categoryPost　＝　modalのCategory.php から--}}
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->categoryPost->count() }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            {{-- Edit --}}
                            <button class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            {{-- Delete --}}
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id}}" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                        {{-- Include a modal here --}}
                        @include('admin.categories.modal.action')
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="5"> No categories found</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td></td>
                        <td class="text-dark">
                            Uncategorized
                            <p class="xsmall mb-0 text-muted">Hidden posts are not included.</p>
                        </td>
                        {{-- ↓　CategoriesController.php line37　と繋がっている --}}
                        <td>{{ $uncategorized_count }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
             {{-- ↓　tableの下に<１，２>が表示される = paginate --}}
            {{ $all_categories->links() }}
        </div>
    </div>
@endsection










