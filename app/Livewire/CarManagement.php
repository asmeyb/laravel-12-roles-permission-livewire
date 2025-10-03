<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Car;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class CarManagement extends Component
{
    use AuthorizesRequests;

    public $showModal = false;
    public $name;
    public $model;
    public $year;
    public $color;
    public $isEditing = false;
    public $carId;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'name')->ignore($this->carId),
            ],
            'model' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'model')->ignore($this->carId),
            ],
            'year' => [
                'required',
                'integer',
                'min:1886',
                'max:' . (date('Y') + 1),
                Rule::unique('cars', 'year')->ignore($this->carId),
            ],
            'color' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cars', 'color')->ignore($this->carId),
            ],
        ];
    }

    public function create()
    {
        $this->authorize('car.create');
        $this->resetForm();
        $this->showModal = true;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->model = '';
        $this->year = '';
        $this->color = '';
        $this->isEditing = false;
        $this->carId = null;
    }

    public function edit($id)
    {
        $this->authorize('car.edit');
        $car = Car::findOrFail($id);

        $this->carId = $car->id;   // ✅ keep track of which car is being edited
        $this->name = $car->name;
        $this->model = $car->model;
        $this->year = $car->year;
        $this->color = $car->color;

        $this->isEditing = true;   // ✅ so save() knows it’s an update
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        $carData = [
            'name' => $this->name,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
        ];

        if ($this->isEditing) {
            $this->authorize('car.edit');
            Car::findOrFail($this->carId)->update($carData);
        } else {
            $this->authorize('car.create');
            Car::create($carData);
        }

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', $this->isEditing ? 'Car updated successfully.' : 'Car created successfully.');
    }

    public function delete($id)
    {
        $this->authorize('car.delete', Car::class);
        Car::findOrFail($id)->delete();
        session()->flash('message', 'Car deleted successfully.');
    }

    public function render()
    {
        $this->authorize('car.view');
        return view('livewire.car-management', [
            'cars' => Car::paginate(10),
        ]);
    }
}
