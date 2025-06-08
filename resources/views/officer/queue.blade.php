@extends('layouts.app')

@section('title', 'Review Queue')

@section('content')
<div class="container py-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Customer Verification Queue</h1>
        <a href="{{ route('officer.reports') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-chart-bar fa-sm text-white-50 me-1"></i> View Reports
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @livewire('officer-review-queue')
        </div>
    </div>
</div>


@endsection