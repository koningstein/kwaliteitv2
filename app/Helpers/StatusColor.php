<?php

namespace App\Helpers;

class StatusColor
{
    // Centrale kleurenmap — één bron van waarheid
    // Op schema = blue, Afgerond = emerald
    private static array $map = [
        'Niet gestart' => [
            'badge'  => 'bg-slate-100 text-slate-700 border border-slate-300',
            'card'   => ['text' => 'text-slate-600', 'bg' => 'bg-white border-slate-300', 'hover' => 'hover:border-slate-400', 'icon' => 'text-slate-400', 'detail' => 'text-slate-400'],
            'filter' => ['active' => 'bg-slate-600 text-white', 'idle' => 'border-slate-200 text-slate-600 hover:bg-slate-50'],
            'simple' => 'text-slate-500',
        ],
        'Op schema' => [
            'badge'  => 'bg-blue-100 text-blue-700 border border-blue-300',
            'card'   => ['text' => 'text-blue-700', 'bg' => 'bg-white border-blue-300', 'hover' => 'hover:border-blue-400', 'icon' => 'text-blue-400', 'detail' => 'text-blue-400'],
            'filter' => ['active' => 'bg-blue-600 text-white', 'idle' => 'border-blue-200 text-blue-700 hover:bg-blue-50'],
            'simple' => 'text-blue-600',
        ],
        'Loopt achter' => [
            'badge'  => 'bg-amber-100 text-amber-700 border border-amber-300',
            'card'   => ['text' => 'text-amber-700', 'bg' => 'bg-white border-amber-300', 'hover' => 'hover:border-amber-400', 'icon' => 'text-amber-400', 'detail' => 'text-amber-400'],
            'filter' => ['active' => 'bg-amber-600 text-white', 'idle' => 'border-amber-200 text-amber-700 hover:bg-amber-50'],
            'simple' => 'text-amber-600',
        ],
        'Uitgesteld' => [
            'badge'  => 'bg-orange-100 text-orange-700 border border-orange-300',
            'card'   => ['text' => 'text-orange-700', 'bg' => 'bg-white border-orange-300', 'hover' => 'hover:border-orange-400', 'icon' => 'text-orange-400', 'detail' => 'text-orange-400'],
            'filter' => ['active' => 'bg-orange-500 text-white', 'idle' => 'border-orange-200 text-orange-700 hover:bg-orange-50'],
            'simple' => 'text-orange-600',
        ],
        'Afgerond' => [
            'badge'  => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
            'card'   => ['text' => 'text-emerald-700', 'bg' => 'bg-white border-emerald-300', 'hover' => 'hover:border-emerald-400', 'icon' => 'text-emerald-400', 'detail' => 'text-emerald-400'],
            'filter' => ['active' => 'bg-emerald-600 text-white', 'idle' => 'border-emerald-200 text-emerald-700 hover:bg-emerald-50'],
            'simple' => 'text-emerald-600',
        ],
    ];

    public static function badge(string $status): string
    {
        return self::$map[$status]['badge'] ?? self::$map['Niet gestart']['badge'];
    }

    public static function card(string $status): array
    {
        return self::$map[$status]['card'] ?? self::$map['Niet gestart']['card'];
    }

    public static function filter(string $status): array
    {
        return self::$map[$status]['filter'] ?? self::$map['Niet gestart']['filter'];
    }

    public static function simple(string $status): string
    {
        return self::$map[$status]['simple'] ?? self::$map['Niet gestart']['simple'];
    }
}
