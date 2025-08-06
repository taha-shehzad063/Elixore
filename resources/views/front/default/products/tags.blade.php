<ul>
@foreach($tags as $tag)
    <li>
        <label>
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="filter-tag"
                {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}>
            {{ $tag->name }}
        </label>
    </li>
@endforeach
</ul>
