<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BrandManagement extends Component
{
    use WithPagination;

    public $showModal = false;    
    public $name;
    public $brandId;    
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:brands,name',        
    ];
    public function create()
    {
        $this->authorize('brand.create');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('brand.edit');
        $brand = Brand::findOrFail($id);
        $this->brandId = $brand->id;
        $this->name = $brand->name; 
        
        $this->isEditing = true;   // ✅ so save() knows it’s an update
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('brand.edit');
            $this->rules['name'] = 'required|string|max:255|unique:brands,name,' . $this->brandId;
        } else {
            $this->authorize('brand.create');
        }

        $this->validate();

        if ($this->isEditing) {
            $brand = Brand::findOrFail($this->brandId);
            $brand->update(['name' => $this->name]);
        } else {
            $brand = Brand::create(['name' => $this->name]);
        }
        

        $this->resetForm();
        $this->showModal = false;

        session()->flash('message', $this->isEditing ? 'Brand updated successfully.' : 'Brand created successfully.');
        
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->selectedPermissions = [];
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $this->authorize('brand.delete', Brand::class);
        Brand::findOrFail($id)->delete();
        session()->flash('message', 'Brand deleted successfully.');
    }

    public function render()
    {
        $this->authorize('role.view');
        return view('livewire.brand-management', [
            'brands' => Brand::paginate(10),            
        ]);
    }
}
