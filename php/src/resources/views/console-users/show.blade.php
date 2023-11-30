@extends('base/layout')

@section('content')

{{-- tab --}}
<div class="tab">
  <div class="tab-header">User</div>
  <div class="btn-group">
    <a href="{{ route('console-users.edit', $consoleUser->id) }}" class="btn">edit</a>
    <a href="{{ route('console-users.create') }}" class="btn">new</a>
  </div>
</div>

<div class="content">
    {{-- nav --}}
    <div class="content-nav">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('console-users.index') }}">< back</a>
    </div>

    {{-- detail --}}
    <div class="content-body detail">
      <div class="item"><span>ID : </span>{{ $consoleUser->id }}</div>
      <div class="item"><span>Name : </span>{{ $consoleUser->name }}</div>
      <div class="item"><span>Email : </span>{{ $consoleUser->email }}</div>
      <div class="item"><span>Password : </span>****</div>
    </div>
</div>
@endsection

