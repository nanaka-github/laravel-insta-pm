{{-- show.blade.phpに３～１３のがあってもいいけどわかりやすくするために、comments.blade.phpに分ける --}}

<div class="mt-4">
    <form action="{{route('comment.store', $post->id)}}" method="post">
        @csrf
        <div class="input-group">
            <textarea name="comment_body{{$post->id}}" id="" rows="1" class="form-control form-control-sm" placeholder="Add your comment...">{{old('comment_body' . $post->id)}}</textarea>
            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
        </div>
        {{-- Error message area --}}
        @error('comment_body' . $post->id)
         <p class="text-danger small">{{$message}}</p>
        @enderror
    </form>

    {{-- Show all the comments --}}
    <div class="mt-3">
        @if ($post->comments->isNotEmpty())
        <hr>
        <ul class="list-group mt-2">
           @foreach ($post->comments as $comment)
               <li class="list-item border-0 p-0 m-2">
                   <a href="{{route('profile.show', $comment->user->id)}}" class="text-decoration-none text-dark fw-bold">{{$comment->user->name}}</a>
                   &nbsp;
                   <p class="d-inline fw-light">{{$comment->body}}</p>

                   <form action="{{route('comment.destroy', $comment->id)}}" method="post">
                        @csrf
                        @method('DELETE')

                        <span class="text-uppercase text-muted xsmall">{{ date('M d, Y' . strtotime($comment->created_at))}}</span>

                        {{-- If the AUTH is the owner of the comment, then display the delete buttons --}}
                        @if (AUTH::user()->id === $comment->user->id)
                           &middot;
                           <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                        @endif
                   </form>
               </li>
           @endforeach

           @if ($post->comments->count() > 3)
              <li class="list-group-item border-0 px-0 pt-0">
                <a href="{{route('post.show', $post->id)}}" class="text-decration-none small">View All {{$post->comments->count() }} comments</a>
              </li>
           @endif
       </ul>
   @endif
    </div>
</div>
