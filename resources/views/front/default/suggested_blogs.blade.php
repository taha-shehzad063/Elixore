<aside class="single_sidebar_widget suggested_post_widget">
    <h3 class="widget_title no-dark">Suggested Posts</h3>
    @forelse($suggestedBlogs as $blog)
        <div class="media post_item no-dark">
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->name }}"
                style="width: 80px; height: 60px; object-fit: cover;"
                onerror="this.onerror=null;this.src='https://placehold.co/80x60/EFEFEF/AAAAAA?text=Image+Error';">
            <div class="media-body">
                <a href="{{ route('blogs.details', $blog->slug) }}">
                    <h3>{{ Str::limit($blog->name, 35) }}</h3>
                </a>
                <p>
                    @if (\Carbon\Carbon::parse($blog->created_at)->gt(\Carbon\Carbon::now()->subMonth()))
                        {{ \Carbon\Carbon::parse($blog->created_at)->diffForHumans() }}
                    @else
                        {{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}
                    @endif
                </p>
            </div>
        </div>
    @empty
        <p>No suggested posts available.</p>
    @endforelse
    <div class="suggested-pagination justify-content-center d-flex mt-3">
        <nav aria-label="Suggested blogs pagination">
            <ul class="pagination">
                @if ($suggestedBlogs->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link" style="background-color: #ccc; color: #fff;">  &laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link suggested-page-link" href="{{ $suggestedBlogs->previousPageUrl() }}&section=suggested_blogs" style="background-color: #71cd14; color: #fff;">&laquo;</a>
                    </li>
                @endif
                @foreach ($suggestedBlogs->getUrlRange(1, $suggestedBlogs->lastPage()) as $page => $url)
                    <li class="page-item {{ $suggestedBlogs->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link suggested-page-link" href="{{ $url }}&section=suggested_blogs" style="{{ $suggestedBlogs->currentPage() == $page ? 'background-color: #71cd14; color: #fff;' : 'color: #71cd14;' }}">{{ $page }}</a>
                    </li>
                @endforeach
                @if ($suggestedBlogs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link suggested-page-link" href="{{ $suggestedBlogs->nextPageUrl() }}&section=suggested_blogs" style="background-color: #71cd14; color: #fff;"> &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" style="background-color: #ccc; color: #fff;"> &raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>