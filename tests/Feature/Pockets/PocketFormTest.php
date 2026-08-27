<?php

use App\Livewire\Modules\Pockets\Form as PocketForm;
use App\Livewire\Modules\Pockets\Index as PocketIndex;
use App\Models\PaymentMethod;
use App\Models\Pocket;
use App\Models\PocketItem;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->paymentMethod = PaymentMethod::create(['nombre' => 'Efectivo']);
});

it('creates a pocket with pocket items', function () {
    Livewire::test(PocketForm::class)
        ->set('pocket.name', 'Vacaciones')
        ->set('pocket.fecha_inicio', '2026-09-01')
        ->set('pocket.fecha_fin', '2026-12-31')
        ->set('pocket.monto', '1000.00')
        ->call('newPocketItem')
        ->set('pocketItemForm.pocketItemMethod', (string) $this->paymentMethod->id)
        ->set('pocketItemForm.pocketItemAmount', '500.00')
        ->set('pocketItemForm.pocketItemDate', '2026-09-01')
        ->set('pocketItemForm.pocketItemDescription', 'Abono de septiembre')
        ->call('savePocketItem')
        ->assertSet('pocketItems', fn ($items) => count($items) === 1)
        ->call('savePocket')
        ->assertRedirect(route('pockets.index'));

    $pocket = Pocket::where('nombre', 'Vacaciones')->first();

    expect($pocket)->not->toBeNull()
        ->and((float) $pocket->meta_apartado)->toBe(1000.0)
        ->and($pocket->pocketItems)->toHaveCount(1);

    $item = $pocket->pocketItems->first();
    expect((float) $item->monto)->toBe(500.0)
        ->and($item->payment_method_id)->toBe($this->paymentMethod->id)
        ->and($item->descripcion)->toBe('Abono de septiembre');
});

it('edits and removes pocket items inside the wizard', function () {
    Livewire::test(PocketForm::class)
        ->set('pocket.name', 'Vacaciones')
        ->set('pocket.fecha_inicio', '2026-09-01')
        ->set('pocket.fecha_fin', '2026-12-31')
        ->set('pocket.monto', '1000.00')
        ->call('newPocketItem')
        ->set('pocketItemForm.pocketItemMethod', (string) $this->paymentMethod->id)
        ->set('pocketItemForm.pocketItemAmount', '500.00')
        ->set('pocketItemForm.pocketItemDate', '2026-09-01')
        ->set('pocketItemForm.pocketItemDescription', 'Abono de septiembre')
        ->call('savePocketItem')
        ->call('editPocketItem', 0)
        ->set('pocketItemForm.pocketItemAmount', '700.00')
        ->set('pocketItemForm.pocketItemDescription', 'Abono actualizado')
        ->call('savePocketItem')
        ->assertSet('pocketItems.0', fn ($item) => $item['monto'] === 700.0)
        ->call('newPocketItem')
        ->set('pocketItemForm.pocketItemMethod', (string) $this->paymentMethod->id)
        ->set('pocketItemForm.pocketItemAmount', '300.00')
        ->set('pocketItemForm.pocketItemDate', '2026-10-01')
        ->call('savePocketItem')
        ->assertSet('pocketItems', fn ($items) => count($items) === 2)
        ->call('removePocketItem', 1)
        ->assertSet('pocketItems', fn ($items) => count($items) === 1)
        ->call('savePocket')
        ->assertRedirect(route('pockets.index'));

    $pocket = Pocket::where('nombre', 'Vacaciones')->first();

    expect($pocket)->not->toBeNull()
        ->and($pocket->pocketItems)->toHaveCount(1)
        ->and((float) $pocket->pocketItems->first()->monto)->toBe(700.0);
});

it('updates an existing pocket', function () {
    $pocket = Pocket::create([
        'user_id' => $this->user->id,
        'nombre' => 'Apartado viejo',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-12-31',
        'meta_apartado' => 1000.00,
        'is_active' => true,
    ]);

    PocketItem::create([
        'pocket_id' => $pocket->id,
        'payment_method_id' => $this->paymentMethod->id,
        'descripcion' => 'Abono inicial',
        'fecha' => '2026-09-01',
        'monto' => 500.00,
    ]);

    Livewire::test(PocketForm::class, ['editId' => $pocket->id])
        ->assertSet('pocket.name', 'Apartado viejo')
        ->assertSet('pocketItems', fn ($items) => count($items) === 1)
        ->call('editPocketItem', 0)
        ->assertSet('pocketItemForm.pocketItemAmount', '500')
        ->call('savePocketItem')
        ->set('pocket.name', 'Apartado actualizado')
        ->call('savePocket')
        ->assertRedirect(route('pockets.index'));

    expect($pocket->fresh()->nombre)->toBe('Apartado actualizado')
        ->and($pocket->fresh()->pocketItems)->toHaveCount(1);
});

it('lists pockets and sums abonos from the monto column', function () {
    $pocket = Pocket::create([
        'user_id' => $this->user->id,
        'nombre' => 'Emergencias',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-12-31',
        'meta_apartado' => 1000.00,
        'is_active' => true,
    ]);

    PocketItem::create([
        'pocket_id' => $pocket->id,
        'payment_method_id' => $this->paymentMethod->id,
        'descripcion' => 'Abono 1',
        'fecha' => '2026-09-01',
        'monto' => 400.00,
    ]);

    PocketItem::create([
        'pocket_id' => $pocket->id,
        'payment_method_id' => $this->paymentMethod->id,
        'descripcion' => 'Abono 2',
        'fecha' => '2026-09-15',
        'monto' => 250.00,
    ]);

    Livewire::test(PocketIndex::class)
        ->assertSee('Emergencias')
        ->assertSee('$650.00')
        ->assertSee('$1,000.00')
        ->assertSee('65%');
});

it('deletes a pocket from the index', function () {
    $pocket = Pocket::create([
        'user_id' => $this->user->id,
        'nombre' => 'A eliminar',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-12-31',
        'meta_apartado' => 100.00,
        'is_active' => true,
    ]);

    Livewire::test(PocketIndex::class)
        ->call('deletePocket', $pocket->id)
        ->assertNoRedirect();

    expect(Pocket::find($pocket->id))->toBeNull();
});
