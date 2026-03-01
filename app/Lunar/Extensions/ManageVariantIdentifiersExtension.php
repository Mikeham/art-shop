<?php

namespace App\Lunar\Extensions;

use App\Models\PrintfulVariantMapping;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Lunar\Admin\Support\Extending\EditPageExtension;

class ManageVariantIdentifiersExtension extends EditPageExtension
{
    private ?string $pendingPrintfulId = null;

    public function extendForm(Form $form): Form
    {
        $section = $form->getComponents(withHidden: true)[0];

        $section->schema([
            ...$section->getChildComponents(),
            TextInput::make('printful_catalog_variant_id')
                ->label('Printful Catalog Variant ID')
                ->helperText('Enter the Printful catalog variant ID for drop-shipping. Leave blank if self-fulfilled.')
                ->nullable(),
        ]);

        return $form;
    }

    public function beforeFill(array $data): array
    {
        $record = $this->caller->getRecord();

        $mapping = PrintfulVariantMapping::where('product_variant_id', $record->id)->first();

        $data['printful_catalog_variant_id'] = $mapping?->printful_catalog_variant_id;

        return $data;
    }

    public function beforeSave(array $data): array
    {
        $this->pendingPrintfulId = $data['printful_catalog_variant_id'] ?? null;

        unset($data['printful_catalog_variant_id']);

        return $data;
    }

    public function afterUpdate(Model $record, array $data): Model
    {
        if (! empty($this->pendingPrintfulId)) {
            PrintfulVariantMapping::upsert(
                [
                    'product_variant_id'          => $record->id,
                    'printful_catalog_variant_id' => $this->pendingPrintfulId,
                ],
                uniqueBy: ['product_variant_id'],
                update: ['printful_catalog_variant_id'],
            );
        } else {
            PrintfulVariantMapping::where('product_variant_id', $record->id)->delete();
        }

        return $record;
    }
}
