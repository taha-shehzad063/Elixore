@if($comments->isEmpty())
    <div class="alert alert-info">
        No comments found for this blog.
    </div>
@else
    <div class="comments-list">
        @foreach($comments as $comment)
            <div class="comment-item mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $comment->author_name }}</h6>
                        <p class="mb-2">{{ $comment->comment }}</p>
                        <small class="text-muted">
                            Posted on {{ $comment->created_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                    <a href="{{ route('admin.comments.destroy', $comment) }}" 
                       class="btn btn-sm btn-danger delete-comment">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
                
                <!-- Replies Section -->
                @if($comment->replies->isNotEmpty())
                    <div class="mt-3 ps-4 border-start border-3">
                        <h6 class="mb-2">Replies:</h6>
                        @foreach($comment->replies as $reply)
                            <div class="reply-item mb-2 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $reply->author_name }}</strong>
                                    <a href="{{ route('admin.comments.destroy', $reply) }}" 
                                       class="btn btn-sm btn-outline-danger delete-reply">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                                <p class="mb-1">{{ $reply->comment }}</p>
                                <small class="text-muted">
                                    Replied on {{ $reply->created_at->format('M d, Y h:i A') }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif