@extends('base/layout')

@section('content')

{{-- tab --}}
<div class="tab">
  <div class="tab-header">Application</div>
  <div class="btn-group">
    <a href="{{ route('applications.create') }}" class="btn">new</a>
  </div>
</div>

<div class="content">
    {{-- nav --}}
    <div class="content-nav">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('applications.index') }}">< back</a>
    </div>

    {{-- form --}}
    <div class="content-body">
        <form action="{{ isset($application) ? route('applications.update', $application->id) : route('applications.store') }}" method="post">
            @csrf
            @isset($application)
                @method('put')
            @endisset

            <label>Name : <input type="text" name="name" value="{{ old('name', $application->name ?? null) }}"></label>
            <button type="submit">submit ...</button>
        </form>
    </div>
</div>
@endsection

