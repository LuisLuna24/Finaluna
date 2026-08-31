<?php

namespace App\Livewire\Modules\Pockets;

use App\Livewire\Forms\Pockets\PocketItemForm;
use App\Models\PaymentMethod;
use App\Models\Pocket;
use App\Models\PocketItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public ?int $editId = null;

    public array $pocket = [
        'name' => '',
        'notes' => '',
        'fecha_inicio' => '',
        'fecha_fin' => '',
        'monto' => '',
    ];

    public PocketItemForm $pocketItemForm;

    // Pocket Items step
    public array $pocketItems = [];

    public function mount(?int $editId = null)
    {
        $this->editId = $editId;

        if ($this->editId) {
            $pocket = Pocket::with('pocketItems.paymentMethod')->findOrFail($this->editId);

            $this->pocket = [
                'name' => $pocket->nombre ?? '',
                'notes' => '',
                'fecha_inicio' => $pocket->fecha_inicio,
                'fecha_fin' => $pocket->fecha_fin,
                'monto' => (string) ($pocket->meta_apartado ?? ''),
            ];

            $this->pocketItems = $pocket->pocketItems->map(fn (PocketItem $item) => [
                'method_id' => $item->payment_method_id,
                'method' => $item->paymentMethod?->nombre ?? 'Desconocido',
                'descripcion' => $item->descripcion ?? '',
                'fecha' => $item->fecha,
                'monto' => (float) $item->monto,
            ])->values()->all();
        }
    }

    public function newPocketItem(): void
    {
        $this->pocketItemForm->openNew();
    }

    public function savePocketItem(): void
    {
        $this->pocketItemForm->validate([
            'pocketItemMethod' => 'required|exists:payment_methods,id',
            'pocketItemAmount' => 'required|numeric|min:0.01',
            'pocketItemDate' => 'required|date',
            'pocketItemDescription' => 'nullable|string|max:255',
        ], [
            'pocketItemMethod.required' => 'El método de pago es obligatorio.',
            'pocketItemAmount.required' => 'El monto es obligatorio.',
            'pocketItemDate.required' => 'La fecha es obligatoria.',
        ]);

        $method = PaymentMethod::find($this->pocketItemForm->pocketItemMethod);

        $itemData = [
            'method_id' => $this->pocketItemForm->pocketItemMethod,
            'method' => $method ? $method->nombre : 'Desconocido',
            'descripcion' => $this->pocketItemForm->pocketItemDescription,
            'fecha' => $this->pocketItemForm->pocketItemDate,
            'monto' => (float) $this->pocketItemForm->pocketItemAmount,
        ];

        if ($this->pocketItemForm->editingId !== null) {
            $this->pocketItems[$this->pocketItemForm->editingId] = $itemData;
        } else {
            $this->pocketItems[] = $itemData;
        }

        $this->pocketItemForm->reset();
    }

    public function editPocketItem($index): void
    {
        $item = $this->pocketItems[$index];

        $this->pocketItemForm->reset();
        $this->pocketItemForm->editingId = $index;
        $this->pocketItemForm->pocketItemMethod = (string) $item['method_id'];
        $this->pocketItemForm->pocketItemAmount = (string) $item['monto'];
        $this->pocketItemForm->pocketItemDate = $item['fecha'];
        $this->pocketItemForm->pocketItemDescription = $item['descripcion'];
        $this->pocketItemForm->modal = true;
    }

    public function removePocketItem($index): void
    {
        unset($this->pocketItems[$index]);
        $this->pocketItems = array_values($this->pocketItems);
    }

    public function savePocket()
    {
        $this->validate([
            'pocket.name' => 'required|string|max:255',
            'pocket.fecha_inicio' => 'required|date',
            'pocket.fecha_fin' => 'required|date|after_or_equal:pocket.fecha_inicio',
            'pocket.monto' => 'required|numeric|min:0.01',
        ], [
            'pocket.name.required' => 'El nombre es obligatorio.',
            'pocket.fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'pocket.fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'pocket.monto.required' => 'El monto es obligatorio.',
            'pocket.monto.min' => 'El monto debe ser mayor a 0.',
            'pocket.fecha_fin.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio.',
        ]);

        DB::transaction(function () {
            if ($this->editId) {
                $pocket = Pocket::findOrFail($this->editId);
                $pocket->update([
                    'nombre' => $this->pocket['name'],
                    'fecha_inicio' => $this->pocket['fecha_inicio'],
                    'fecha_fin' => $this->pocket['fecha_fin'],
                    'meta_apartado' => $this->pocket['monto'],
                ]);

                $pocket->pocketItems()->delete();
            } else {
                $pocket = Pocket::create([
                    'user_id' => Auth::user()->id ?? 1,
                    'nombre' => $this->pocket['name'],
                    'fecha_inicio' => $this->pocket['fecha_inicio'],
                    'fecha_fin' => $this->pocket['fecha_fin'],
                    'meta_apartado' => $this->pocket['monto'],
                    'is_active' => true,
                ]);
            }

            foreach ($this->pocketItems as $itemData) {
                PocketItem::create([
                    'pocket_id' => $pocket->id,
                    'payment_method_id' => $itemData['method_id'],
                    'descripcion' => $itemData['descripcion'] ?: null,
                    'fecha' => $itemData['fecha'],
                    'monto' => $itemData['monto'],
                ]);
            }
        });

        $this->redirectRoute('pockets.index');
    }

    public function render()
    {
        return view('livewire.modules.pockets.form');
    }
}
