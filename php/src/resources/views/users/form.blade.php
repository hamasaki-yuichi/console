@extends('base/layout')

@section('content')

{{-- tab --}}
<div class="tab">
  <div class="tab-header">User</div>
  <div class="btn-group">
    <a href="{{ route('users.create') }}" class="btn">new</a>
  </div>
</div>

<div class="content">
    {{-- nav --}}
    <div class="content-nav">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('users.index') }}">< back</a>
    </div>

    {{-- form --}}
    <div class="content-body">
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="post">
            @csrf
            @isset($user)
                @method('put')
            @endisset

            <label>Name : <input type="text" name="name" value="{{ old('name', $user->name ?? null) }}"></label>
            <label>Email : <input type="text" name="email" value="{{ old('email', $user->email ?? null) }}"></label>
            <label>Password : <input type="password" name="password" value="{{ old('password') }}"></label>
            <button type="submit">submit ...</button>
        </form>
    </div>
</div>
@endsection

