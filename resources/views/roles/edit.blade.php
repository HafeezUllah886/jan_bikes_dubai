@extends('layout.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit Role: {{ $role->name }}</h3>
                    <a href="{{ route('roles.index') }}" class="btn btn-danger">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name">Role Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $role->name) }}" {{ $role->name == 'Admin' ? 'readonly' : '' }}>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                <label class="form-check-label fw-bold" for="selectAllPermissions">
                                    All Permissions
                                </label>
                            </div>
                            
                            <div class="row">
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="col-md-3 mb-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-select" type="checkbox" id="group_{{ Str::slug($group) }}" data-group="{{ Str::slug($group) }}">
                                            <label class="form-check-label fw-bold" for="group_{{ Str::slug($group) }}">
                                                {{ $group }}
                                            </label>
                                        </div>
                                        <div class="ps-3">
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox group-{{ Str::slug($group) }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllPermissions = document.getElementById('selectAllPermissions');
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        const groupCheckboxes = document.querySelectorAll('.group-select');

        // Toggle all permissions
        selectAllPermissions.addEventListener('change', function() {
            allCheckboxes.forEach(cb => cb.checked = selectAllPermissions.checked);
            groupCheckboxes.forEach(cb => cb.checked = selectAllPermissions.checked);
        });

        // Toggle group permissions
        groupCheckboxes.forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function() {
                const groupId = this.dataset.group;
                const permissionsInGroup = document.querySelectorAll('.group-' + groupId);
                permissionsInGroup.forEach(cb => cb.checked = this.checked);
                updateSelectAllState();
            });
        });

        // Update group and select all checkboxes based on individual permissions
        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const groupClass = Array.from(this.classList).find(c => c.startsWith('group-'));
                if (groupClass) {
                    const groupId = groupClass.replace('group-', '');
                    const groupCheckbox = document.getElementById('group_' + groupId);
                    const permissionsInGroup = document.querySelectorAll('.' + groupClass);
                    
                    const allChecked = Array.from(permissionsInGroup).every(cb => cb.checked);
                    const someChecked = Array.from(permissionsInGroup).some(cb => cb.checked);
                    
                    groupCheckbox.checked = allChecked;
                    groupCheckbox.indeterminate = someChecked && !allChecked;
                }
                updateSelectAllState();
            });
        });

        function updateSelectAllState() {
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(allCheckboxes).some(cb => cb.checked);
            selectAllPermissions.checked = allChecked;
            selectAllPermissions.indeterminate = someChecked && !allChecked;
        }

        // Initialize state on load
        allCheckboxes.forEach(checkbox => {
            const groupClass = Array.from(checkbox.classList).find(c => c.startsWith('group-'));
            if (groupClass) {
                const groupId = groupClass.replace('group-', '');
                const groupCheckbox = document.getElementById('group_' + groupId);
                const permissionsInGroup = document.querySelectorAll('.' + groupClass);
                
                const allChecked = Array.from(permissionsInGroup).every(cb => cb.checked);
                const someChecked = Array.from(permissionsInGroup).some(cb => cb.checked);
                
                groupCheckbox.checked = allChecked;
                groupCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
        updateSelectAllState();
    });
</script>
@endsection
