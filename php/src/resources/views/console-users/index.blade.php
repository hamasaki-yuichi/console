@extends('base/layout')

@section('content')

{{-- tab --}}
<div class="tab">
  <div class="tab-header">Console User</div>
  <div class="btn-group">
    <a href="{{ route('console-users.create') }}" class="btn">new</a>
  </div>
</div>

<div class="content">
  {{-- nav --}}
  <div class="content-nav">
  </div>

  {{-- search --}}
  <div class="content-search">
    <form action="{{ route('console-users.index') }}" method="get">
      <label>id : <input type="number" name="id" value="{{ request()->get('id') ?? '' }}"></label>
      <label>name : <input type="text" name="name" value="{{ request()->get('name') ?? '' }}"></label>
      <label>email : <input type="text" name="email" value="{{ request()->get('email') ?? '' }}"></label>
      <a href="{{ route('console-users.index') }}" class="btn">crear</a>
      <button type="submit">search</button>
    </form>
  </div>

  {{-- table --}}
  <div class="content-body">
    @if(!$consoleUsers->isEmpty())
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
        @foreach ($consoleUsers as $consoleUser)
        <tr>
          <td>{{ $consoleUser->id }}</td>
          <td>{{ $consoleUser->name }}</td>
          <td>{{ $consoleUser->email }}</td>
          <td>
            <div class="btn-group">
              <a href="{{ route('console-users.show', $consoleUser->id) }}" class="btn">detail</a>
              <a href="{{ route('console-users.edit', $consoleUser->id) }}" class="btn">edit</a>
              <form action="{{ route('console-users.destroy', $consoleUser->id) }}" method="POST">
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
