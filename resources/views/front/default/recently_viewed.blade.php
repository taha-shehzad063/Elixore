@php
    $recentlyViewed = session('recently_viewed', []);
@endphp

<div class="recently-viewed-widget right-side" id="recentlyViewedWidget">
    @if(!empty($recentlyViewed))
        <a href="#" class="rv-header no-dark" id="toggleRecentlyViewed">
            <span>RECENTLY VIEWED</span>
            <i class="fas fa-clock"></i>
        </a>
        <div class="rv-content" id="recentlyViewedContent">
            @foreach($recentlyViewed as $item)
                @if(!empty($item['image']))
                    <div class="rv-item">
                       @php
    $imageUrl = filter_var($item['image'], FILTER_VALIDATE_URL) 
                ? $item['image'] 
                : asset($item['image']);
@endphp

<a href="{{ route('product.details', $item['slug']) }}">
    <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}">
    <p class="no-dark">{{ Str::limit($item['name'], 20) }}</p>
</a>

                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="rv-icons">
        <a href="#" id="scrollToTop" title="Back to Top"><i class="fas fa-arrow-up"></i></a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('recentlyViewedWidget');
    const toggleBtn = document.getElementById('toggleRecentlyViewed');
    const content = document.getElementById('recentlyViewedContent');
    const scrollToTopBtn = document.getElementById('scrollToTop');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 200) {
            widget.classList.add('visible');
        } else {
            widget.classList.remove('visible');
            if (content) content.classList.remove('visible');
        }
    });

    if (toggleBtn && content) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            content.classList.toggle('visible');
        });
    }

    if (scrollToTopBtn) {
        scrollToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
