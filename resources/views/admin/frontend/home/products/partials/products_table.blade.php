@foreach($products as $index => $product)
<tr>
  <td>{{ $loop->iteration }}</td>
  <td>{{ $product->name }}</td>
  <td>${{ $product->price }}</td>
  <td>${{ $product->discount_price }}</td>
  <td>
  @if($product->galleries && $product->galleries->isNotEmpty())
    <img src="{{ asset('storage/' . $product->galleries->first()->image) }}" width="80" class="rounded">

@endif


  </td>
    <td class="text-center">
        <!-- ... other action buttons ... -->
        <button class="btn btn-info btn-sm view-reviews" 
                data-product-id="{{ $product->id }}" 
                data-bs-toggle="modal" 
                data-bs-target="#reviewsModal">
            <i class="bi bi-chat-left-text"></i> Reviews
        </button>
    </td>
  <td class="text-center">
    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
      @csrf
      <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
  </td>
</tr>
@endforeach
