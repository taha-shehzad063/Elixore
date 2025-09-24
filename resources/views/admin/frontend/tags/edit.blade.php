@extends('admin.frontend.partials.app')

@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        @include('admin.frontend.partials.header')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Edit Tag</h5>

                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('admin.tags.update', $tags->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') {{-- Required for update routes --}}

                                <!-- Tag Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tag Name</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name', $tags->name) }}">
                                </div>

                                <!-- Category Dropdown -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Select Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Choose Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $tags->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category_id" class="form-label">Select Sub Category</label>
                                    <select name="sub_category_id" class="form-select" required>
                                        <option value="">-- Choose Sub Category --</option>
                                        @foreach($subcategories as $subcategories)
                                            <option value="{{ $subcategories->id }}" {{ $tags->sub_category_id == $subcategories->id ? 'selected' : '' }}>
                                                {{ $subcategories->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Update Tag</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Design and Developed by
                    <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore.com</a>
                    Distributed by
                    <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
