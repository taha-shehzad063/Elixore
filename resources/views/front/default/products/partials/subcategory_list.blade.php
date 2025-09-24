<ul class="list" id="subcategory-ul">
    <li>
        <label class="filter-subcategory-label {{ !request('subcategory') ? 'active' : '' }}">
            <input type="radio" name="subcategory" class="filter-subcategory" value="" data-name="" {{ !request('subcategory') ? 'checked' : '' }}>
            All Sub Categories
        </label>
    </li>
    @foreach($subcategories as $subcategory)
        <li>
            <label class="filter-subcategory-label {{ request('subcategory') == str_replace(' ', '-', strtolower($subcategory->name)) ? 'active' : '' }}">
                <input type="radio" name="subcategory" class="filter-subcategory" value="{{ $subcategory->id }}" data-name="{{ str_replace(' ', '-', strtolower($subcategory->name)) }}" {{ request('subcategory') == str_replace(' ', '-', strtolower($subcategory->name)) ? 'checked' : '' }}>
                {{ $subcategory->name }}
            </label>
        </li>
    @endforeach
</ul>