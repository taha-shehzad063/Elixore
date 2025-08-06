<div class="row" id="product-skeleton">
    @for ($i = 0; $i < 6; $i++)
        <div class="product-col mb-4">
            <div class="single-product">
                <div class="product-img">
                    <div class="skeleton skeleton-img"></div>
                </div>
                <div class="product-btm">
                    <div class="skeleton skeleton-title mb-2"></div>
                    <div class="skeleton skeleton-desc mb-2"></div>
                    <div class="skeleton skeleton-btn mb-2"></div>
                    <div class="skeleton skeleton-price"></div>
                </div>
            </div>
        </div>
    @endfor
</div>