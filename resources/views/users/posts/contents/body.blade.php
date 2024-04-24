{{-- Clickable image --}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id)}}">
        <img src="{{$post->image}}" alt="post id {{$post->id}}" class="w-100">
    </a>
</div>

<div class="card-body">
    {{-- Heart icon + No of likes + categories--}}
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{route('like.destroy', $post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-solid fa-heart text-danger"></i></button>
                </form>
            @else
                <form action="{{route('like.store', $post->id)}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-regular fa-heart"></i></button>
                </form>
            @endif


        </div>
        <div class="col-auto px-0">
            <span>{{$post->likes->count()}}</span>
        </div>
        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
                <span class="badge bg-secondary bg-opacity-50">{{$category_post->category->name}}</span>
            @empty
                <div class="badge bg-dark text-warp">Uncategorized</div>
            @endforelse

{{--
            @foreach ($post->categoryPost as  $category_post)
            <div class="badge bg-secondary bg-opacity-50">
                {{ $category_post->category->name }}
            </div>
            @endforeach --}}
        </div>
    </div>

    {{-- Owner of the post + description of the post --}}
    <a href="{{route('profile.show', $post->user->id)}}" class="text-decration-none text-dark fw-bold">{{$post->user->name}}</a>

    {{-- &nbsp; is use to add a small space  --}}
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>
    <p class="text-muted small">Posted in<span class="text-danger">{{$post->created_at->diffForHumans() }}</span></p>
    {{-- date(format, unix time) --}}
    {{-- strtotime(string to time)(timestamp)--> $post->created_at--> '2024-05-04 11:57:33' --}}
    {{-- date(format, 166520214) --}}
    {{-- date(April 5,2024) --}}


    {{-- Include the comments section  --}}
    @include('users.posts.contents.comments')
</div>
