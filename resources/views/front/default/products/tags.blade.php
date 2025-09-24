<ul>
@foreach($tags as $tag)
    <li class="types">
        <label>
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="filter-tag"
                {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}>
            {{ $tag->name }}
        </label>
    </li>
@endforeach
</ul>
