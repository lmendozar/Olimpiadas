<?php

namespace App\View\Composers;

use App\Models\SystemSetting;
use Illuminate\View\View;

class SettingsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $systemSettings = [
            'title' => SystemSetting::get('system_title', 'Sistema de Gestión de Olimpiadas'),
            'logo' => SystemSetting::get('system_logo', ''),
            'primary_color' => SystemSetting::get('primary_color', '#2563eb'),
            'secondary_color' => SystemSetting::get('secondary_color', '#059669'),
            'accent_color' => SystemSetting::get('accent_color', '#dc2626'),
        ];

        $view->with('systemSettings', $systemSettings);
    }
}

