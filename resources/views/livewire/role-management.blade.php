{{-- resources/views/livewire/role-management.blade.php --}}
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Role Management</h2>

        @can('role.create')
            <button wire:click="create" 
                class="btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Role
            </button>
        @endcan
    </div>

    <!-- Flash message -->
    @if (session()->has('message'))
        <div class="alert bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Permissions</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Users</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($roles as $role)
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex px-2 py-1 text-xs bg-green-100 dark:bg-green-700 text-green-700 dark:text-green-200 rounded-full">
                                {{ $role->permissions->count() }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                            {{ $role->users->count() }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            @can('role.edit')
                                <button wire:click="edit({{ $role->id }})" class="text-blue-600 hover:underline">
                                    Edit
                                </button>
                            @endcan
                            @can('role.delete')
                                @if($role->users->count() == 0)
                                    <button wire:click="delete({{ $role->id }})" class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $roles->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    {{ $isEditing ? 'Edit Role' : 'Add Role' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-6">
                    <!-- Role name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
                        <input type="text" wire:model="name"
                               class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-white">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Permissions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="border border-gray-200 dark:border-gray-700 rounded p-3">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2 capitalize">{{ $group }}</h4>
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center space-x-2 mb-1">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
