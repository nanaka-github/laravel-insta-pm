{{-- Admin = 設定（アウトプット内）　の　categoryパートの　edit delete部分 --}}

{{-- Edit --}}
<div class=" modal fade" id="edit-category-{{$category->id}}">
    <div class="modal-dialog">

        <form action="{{route('admin.categories.update', $category->id)}}" method="post">
            @csrf
                 {{-- PATCH = 編集edit / DELETE = 削除 --}}
            @method('PATCH')
            <div class="modal-content border-warning">
                <div class="modal-header border-warning">
                    <h3 class="h5 modal-title border-warning">
                        <i class="fa-regular fa-pen-to-square"></i> Edit Category
                    <h3>
                    <div class="modal-body">
                        <input type="text" name="new_name" id="" class="form-control" placeholder="Category name" value="{{$category->name}}">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- Delete --}}
<div class="modal fade" id="delete-category-{{$category->id}}">
    <div class="modal-dialog">
                    {{-- {{route('admin.categories.destroy', $category->id)}}
                    　　　web.phpで書いた頭のdeleteではなく、括弧内のdestroyを使う --}}
        <form action="{{route('admin.categories.destroy', $category->id)}}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h3 class="h5 modal-title border-danger">
                        <i class="fa-regular fa-trash-can"></i> Delete Category
                    <h3>
                    <div class="modal-body">
                        <input type="text" name="new_name" id="" class="form-control" placeholder="Category name" value="{{$category->name}}">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
