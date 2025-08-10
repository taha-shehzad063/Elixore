@extends('admin.frontend.partials.app')

@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="body-wrapper">
        @include('admin.frontend.partials.header')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Edit Policy</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.policy.update', $policy->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="title" class="form-label">Policy Title</label>
                                    <input type="text" name="title" class="form-control"
                                           value="{{ old('title', $policy->title) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Policy Content</label>
                                    <textarea name="content" id="summernote" class="form-control" rows="10" required>{{ old('content', $policy->content) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Policy</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .accordion-button:not(.collapsed) {
        color: #000;
        background-color: #f8f9fa;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    .note-editable .accordion {
        margin-bottom: 1rem;
    }
</style>
@endsection

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        placeholder: 'Write your policy content here...',
        tabsize: 2,
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video', 'accordion']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        buttons: {
            accordion: function(context) {
                var ui = $.summernote.ui;
                return ui.button({
                    contents: '<i class="fa fa-list"></i> Accordion',
                    tooltip: 'Insert Accordion',
                    click: function() {
                        var accordionId = 'accordion_' + Date.now();
                        var collapseId = 'collapse_' + Date.now();
                        
                        var html = `
                        <div class="accordion mb-3" id="${accordionId}">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#${collapseId}" 
                                            aria-expanded="false">
                                        Accordion Title (click to edit)
                                    </button>
                                </h2>
                                <div id="${collapseId}" class="accordion-collapse collapse" 
                                     data-bs-parent="#${accordionId}">
                                    <div class="accordion-body">
                                        Accordion content (click to edit)
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        context.invoke('editor.pasteHTML', html);
                    }
                }).render();
            }
        }
    });

    // Make sure accordions work in the editor preview
    $(document).on('click', '.note-editable [data-bs-toggle="collapse"]', function(e) {
        e.preventDefault();
        var target = $(this).attr('data-bs-target');
        $(target).collapse('toggle');
    });
});
</script>