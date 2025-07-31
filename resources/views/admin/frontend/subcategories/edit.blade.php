@extends('admin.frontend.partials.app')

@section('content')

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        @include('admin.frontend.partials.header')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Edit Subcategory</h5>

                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('admin.subcategories.update', $subcategory->id) }}">
                                @csrf

                                <!-- Subcategory Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Subcategory Name</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $subcategory->name) }}" required>
                                </div>

                                <!-- Parent Category Select -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Parent Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Update Subcategory</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Design and Developed by
                    <a href="https://adminmart.com/" target="_blank"
                       class="pe-1 text-primary text-decoration-underline">Elixore.com</a>
                    Distributed by
                    <a target="_blank" class="pe-1 text-primary text-decoration-underline">Elixore</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
