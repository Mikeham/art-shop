<?php

namespace App\Lunar\Pages;

use Filament\Forms\Form;
use Lunar\Admin\Filament\Resources\ProductVariantResource\Pages\ManageVariantIdentifiers as BaseManageVariantIdentifiers;

class ManageVariantIdentifiers extends BaseManageVariantIdentifiers
{
    public function form(Form $form): Form
    {
        $form = parent::form($form);

        return $this->callLunarHook('extendForm', $form);
    }
}
