@extends('base/layout')

@section('content')

{{-- tab --}}
<div class="tab">
  <div class="tab-header">Application</div>
  <div class="btn-group">
    <a href="{{ route('applications.edit', $application->id) }}" class="btn">edit</a>
    <a href="{{ route('applications.create') }}" class="btn">new</a>
  </div>
</div>

<div class="content">
    {{-- nav --}}
    <div class="content-nav">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('applications.index') }}">< back</a>
    </div>

    {{-- detail --}}
    <div class="content-body detail">
      <div class="item"><span>ID : </span>{{ $application->id }}</div>
      <div class="item"><span>Name : </span>{{ $application->name }}</div>
    </div>
</div>
@endsection

