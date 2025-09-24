@extends('admin.frontend.partials.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">View Message</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $message->name }}</p>
            <p><strong>Email:</strong> {{ $message->email }}</p>
            <p><strong>Subject:</strong> {{ $message->subject ?? '—' }}</p>
            <p><strong>Message:</strong> {{ $message->message }}</p>
            <p><strong>Received At:</strong> {{ $message->created_at->format('d M Y h:i A') }}</p>

            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
