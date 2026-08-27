<?php

namespace App\Livewire\Forms\Pockets;

use App\Models\PaymentMethod;
use App\Models\PocketItem;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class PocketItemForm extends Form
{
    public bool $modal = false;

    public ?int $editingId = null;

    public string $pocketItemMethod = '';

    public string $pocketItemAmount = '';

    public string $pocketItemDate = '';

    public string $pocketItemDescription = '';

    public ?int $pocketId = null;

    public function openNew(?int $pocketId = null): void
    {
        $this->reset();
        $this->pocketItemDate = now()->format('Y-m-d\TH:i');
        $this->pocketId = $pocketId ?? null;
        $this->modal = true;
    }

    public function openEdit(int $id): void
    {
        $pocketItem = PocketItem::findOrFail($id);

        $this->editingId = $id;
        $this->pocketItemMethod = (string) $pocketItem->payment_method_id;
        $this->pocketItemAmount = (string) $pocketItem->monto;
        $this->pocketItemDate = $pocketItem->fecha;
        $this->pocketItemDescription = $pocketItem->descripcion ?? '';
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'pocketItemMethod' => 'required|exists:payment_methods,id',
            'pocketItemAmount' => 'required|numeric|min:0.01',
            'pocketItemDate' => 'required|date',
            'pocketItemDescription' => 'nullable|string|max:255',
        ], [
            'pocketItemMethod.required' => 'El método de pago es obligatorio.',
            'pocketItemAmount.required' => 'El monto es obligatorio.',
            'pocketItemDate.required' => 'La fecha es obligatoria.',
        ]);

        PocketItem::create([
            'pocket_id' => $this->pocketId,
            'payment_method_id' => $this->pocketItemMethod,
            'descripcion' => $this->pocketItemDescription,
            'fecha' => $this->pocketItemDate,
            'monto' => $this->pocketItemAmount,
        ]);
    }

    public function getPaymentMethods(): array
    {
        return PaymentMethod::where('is_active', true)->get()->toArray();
    }
}
