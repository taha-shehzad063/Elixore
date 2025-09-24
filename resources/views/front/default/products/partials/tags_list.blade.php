@foreach($tags as $tag)
    <li class="types">
        <label class="filter-tag-label {{ in_array($tag->id, request('tags', [])) ? 'active' : '' }}">
            <input type="checkbox" name="tags[]" class="filter-tag" value="{{ $tag->id }}" {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}>
            {{ $tag->name }}
        </label>
    </li>
@endforeach