@php
    $designData = $card->design_data;
    if (is_string($designData)) {
        $designData = json_decode($designData, true);
    }

    $layout = $designData['templateLayout']
        ?? $designData['layout']
        ?? $card->template?->design_config['layout']
        ?? 'standard';

    $layoutMap = [
        'standard' => 'default',
        'elegant'  => 'luxury',
        'modern'   => 'modern',
        'classic'  => 'classic',
        'minimal'  => 'minimal',
        'bold'     => 'professional',
    ];

    $layoutFile = $layoutMap[$layout] ?? 'default';
@endphp

@include("public.card-layouts.{$layoutFile}")
