@extends('front.default.partials.app')

@section('content')
<style>
    .policy-wrapper {
        background-color: #f9f9f9;
        padding: 50px 0;
    }

    .policy-card {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
    }

    .policy-card:hover {
        box-shadow: 0 10px 35px rgba(113, 205, 20, 0.15);
    }

    .policy-title {
        font-size: 2rem;
        font-weight: 600;
        color: #222;
        border-bottom: 2px solid #71cd14;
        padding-bottom: 10px;
    }

    .policy-content {
        margin-top: 25px;
        line-height: 1.8;
        font-size: 1.05rem;
        color: #444;
    }

    .policy-content img,
    .policy-content video {
        max-width: auto;
        height: auto;
        max-height: auto;
        margin: 15px 0;
        border-radius: 8px;
        border: 1px solid #e2e2e2;
    }

    .policy-date {
        font-size: 0.9rem;
        color: #999;
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    @media (max-width: 768px) {
        .policy-title {
            font-size: 1.5rem;
        }
        .policy-content {
            font-size: 1rem;
        }
         .policy-content img,
    .policy-content video {
        max-width: 300px;
        height: auto;
        max-height: auto;
        margin: 15px 0;
        border-radius: 8px;
        border: 1px solid #e2e2e2;
    }
    }
</style>

<div class="policy-wrapper">
    <div class="container">
        <div class="policy-card">
            <h2 class="policy-title">{{ $policy->title }}</h2>

            <div class="policy-content">
                {!! $policy->content !!}
            </div>

           
        </div>
    </div>
</div>
@endsection
