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
  </div>

  {{-- search --}}
  <div class="content-search">
    <form action="{{ route('applications.index') }}" method="get">
      <label>id : <input type="number" name="id" value="{{ request()->get('id') ?? '' }}"></label>
      <label>name : <input type="text" name="name" value="{{ request()->get('name') ?? '' }}"></label>
      <a href="{{ route('applications.index') }}" class="btn">crear</a>
      <button type="submit">search</button>
    </form>
  </div>

  {{-- table --}}
  <div class="content-body">
    @if(!$applications->isEmpty())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($applications as $application)
        <tr>
          <td>{{ $application->id }}</td>
          <td>{{ $application->name }}</td>
          <td>
            <div class="btn-group">
              <a href="{{ route('applications.show', $application->id) }}" class="btn">detail</a>
              <a href="{{ route('applications.edit', $application->id) }}" class="btn">edit</a>
              <form action="{{ route('applications.destroy', $application->id) }}" method="POST">
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
