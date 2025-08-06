@if($reviews->isEmpty())
    <div class="alert alert-info">
        No reviews found for this product.
    </div>
@else
    <div class="list-group">
        @foreach($reviews as $review)
            <div class="list-group-item mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $review->name }}</h6>
                        <small class="text-muted">{{ $review->email }}</small>
                        @if($review->phone)
                            <small class="text-muted"> | {{ $review->phone }}</small>
                        @endif
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <a href="{{ route('admin.reviews.destroy', $review) }}" 
                           class="btn btn-sm btn-danger delete-review">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
                <p class="mt-2 mb-3">{{ $review->message }}</p>
                <small class="text-muted">
                    Posted on {{ $review->created_at->format('M d, Y h:i A') }}
                </small>
                
                <!-- Replies Section -->
                @if($review->replies->isNotEmpty())
                    <div class="mt-3 ps-4 border-start border-3">
                        <h6 class="mb-2">Replies:</h6>
                        @foreach($review->replies as $reply)
                            <div class="mb-2 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $reply->name }}</strong>
                                    <a href="{{ route('admin.review-replies.destroy', $reply) }}" 
                                       class="btn btn-sm btn-outline-danger delete-reply">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                                <p class="mb-1">{{ $reply->reply }}</p>
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