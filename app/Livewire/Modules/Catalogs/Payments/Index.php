<?php

namespace App\Livewire\Modules\Catalogs\Payments;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public $search = '';

    public $formModal = false;

    public $name = '';

    public $editId;

    public function create(): void
    {
        $this->resetForm();
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $this->resetForm();

        $payment = PaymentMethod::findOrFail($id);
        $this->editId = $payment->id;
        $this->name = $payment->nombre;
        $this->formModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($this->editId) {
                $icon = PaymentMethod::findOrFail($this->editId);
                $icon->update(['nombre' => $this->name]);
                $this->success(
                    'Método de pago actualizado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            } else {
                PaymentMethod::create(['nombre' => $this->name]);
                $this->success(
                    'Método de pago creado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            }

            $this->resetForm();
            $this->formModal = false;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'editId']);
    }

    public function delete($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        $this->success('Método de pago eliminado exitosamente.', timeout: 2000, position: 'toast-top toast-center');
    }

    public function toggleActive($id): void
    {
        DB::beginTransaction();
        try {
            $icon = PaymentMethod::findOrFail($id);
            $icon->update(['is_active' => ! $icon->is_active]);
            $this->success('Método de pago actualizado exitosamente.', timeout: 2000, position: 'toast-top toast-center');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $payments = PaymentMethod::query()->where('nombre', 'like', '%'.$this->search.'%')->paginate(15);

        return view('livewire.modules.catalogs.payments.index', compact('payments', 'headers'));
    }
}
