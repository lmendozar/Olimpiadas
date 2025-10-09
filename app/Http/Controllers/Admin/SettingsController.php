<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show system settings form
     */
    public function index()
    {
        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'system_title' => 'required|string|max:255',
            'system_logo' => 'nullable|url|max:500',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        foreach ($validated as $key => $value) {
            $type = str_contains($key, 'color') ? 'color' : 
                   (str_contains($key, 'logo') ? 'url' : 'string');
            
            SystemSetting::set($key, $value, $type);
        }

        SystemSetting::clearCache();

        return back()->with('success', 'Configuración del sistema actualizada exitosamente.');
    }

    /**
     * Reset to defaults
     */
    public function reset()
    {
        SystemSetting::set('system_title', 'Sistema de Gestión de Olimpiadas', 'string');
        SystemSetting::set('system_logo', '', 'url');
        SystemSetting::set('primary_color', '#2563eb', 'color');
        SystemSetting::set('secondary_color', '#059669', 'color');
        SystemSetting::set('accent_color', '#dc2626', 'color');

        SystemSetting::clearCache();

        return back()->with('success', 'Configuración restaurada a valores por defecto.');
    }
}

