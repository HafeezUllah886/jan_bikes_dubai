@extends('layout.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit User: {{ $user->name }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-danger">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name', $user->name) }}">
                        </div>


                        <div class="form-group mb-3">
                            <label for="password">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="contact">Contact</label>
                            <input type="text" name="contact" class="form-control"
                                value="{{ old('contact', $user->contact) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Assign Roles</label>
                            <div class="row">
                                @foreach ($roles as $role)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                value="{{ $role->name }}" id="role_{{ $role->id }}"
                                                {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
