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
  </div>

  {{-- search --}}
  <div class="content-search">
    <form action="{{ route('users.index') }}" method="get">
      <label>id : <input type="number" name="id" value="{{ request()->get('id') ?? '' }}"></label>
      <label>name : <input type="text" name="name" value="{{ request()->get('name') ?? '' }}"></label>
      <label>email : <input type="text" name="email" value="{{ request()->get('email') ?? '' }}"></label>
      <a href="{{ route('users.index') }}" class="btn">crear</a>
      <button type="submit">search</button>
    </form>
  </div>

  {{-- table --}}
  <div class="content-body">
    @if(!$users->isEmpty())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            <div class="btn-group">
              <a href="{{ route('users.show', $user->id) }}" class="btn">detail</a>
              <a href="{{ route('users.edit', $user->id) }}" class="btn">edit</a>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('delete')
                <button type="submit" onclick="return confirm('Are you sure you want to remove?')">delete</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <span>no contents ...</span>
    @endif

  </div>
</div>
@endsection
