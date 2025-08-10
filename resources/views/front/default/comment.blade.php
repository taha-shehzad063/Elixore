<div class="comment-list" id="comment-{{ $comment->id }}" style="margin-left: {{ $level * 50 }}px;">
    <div class="single-comment justify-content-between d-flex">
        <div class="user justify-content-between d-flex">
            <div class="thumb">
                <img src="{{ asset('user/images.png') }}" alt="Author"
                    onerror="this.onerror=null;this.src='https://placehold.co/50x50/EFEFEF/AAAAAA?text=Author';">
            </div>
            <div class="desc">
                <p class="comment">{{ $comment->comment }}</p>
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5><a href="#">{{ $comment->author_name }}</a></h5>
                        <p class="date">{{ $comment->created_at->diffForHumans() }}</p>
                        <a href="#" class="reply-btn" data-comment-id="{{ $comment->id }}">Reply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="replies" id="replies-{{ $comment->id }}">
        @foreach($comment->replies as $reply)
            @include('front.default.partials.comment', ['comment' => $reply, 'level' => $level + 1])
        @endforeach
    </div>
    <div class="comment-form reply-form" id="reply-form-{{ $comment->id }}">
        <h5 class="no-dark">Reply to {{ $comment->author_name }}</h5>
        <form class="form-contact comment_form" action="{{ route('blog.comment') }}" method="POST">
            @csrf
            <input type="hidden" name="blog_id" value="{{ $blogs->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <textarea class="form-control w-100" name="comment" cols="30" rows="5" placeholder="Write Reply" required></textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input class="form-control" name="name" type="text" placeholder="Name" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input class="form-control" name="email" type="email" placeholder="Email">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input class="form-control" name="website" type="text" placeholder="Website">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="main_btn">Submit Reply</button>
            </div>
        </form>
    </div>
</div>