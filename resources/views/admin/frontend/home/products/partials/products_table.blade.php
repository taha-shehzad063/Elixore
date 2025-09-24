@foreach($products as $index => $product)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->category->name ?? 'N/A' }}</td>
        <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
        <td>{{ number_format($product->price, 2) }}</td>
        <td>{{ $product->discount_price ? number_format($product->discount_price, 2) : 'N/A' }}</td>
        <td>
            
            @if($product->galleries && $product->galleries->isNotEmpty())
                @php
                    $imagePath = $product->galleries->first()->image;

                    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                        // ✅ External URL
                        $finalImage = $imagePath;
                    }  else {
                            $finalImage = asset($imagePath); // fallback for public/
                        }
                    
                @endphp

                <img src="{{ $finalImage }}" width="80" class="rounded" alt="{{ $product->name }}">
            @else
                No Image
            @endif
        </td>
        <td class="text-center">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <button class="btn btn-sm btn-info me-1 view-reviews" 
                    data-product-id="{{ $product->id }}" 
                    data-bs-toggle="modal" 
                    data-bs-target="#reviewsModal" 
                    title="View Reviews">
                <i class="bi bi-chat-left-text"></i> Reviews
            </button>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger delete-product" title="Delete" data-product-id="{{ $product->id }}">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </td>
    </tr>
@endforeach
