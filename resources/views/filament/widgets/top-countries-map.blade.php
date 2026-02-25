@php
    $mapData = $this->getMapData();
    $mapValues = $mapData['mapValues'];
    $tableData = $mapData['tableData'];
    $totalViews = $mapData['totalViews'];
    $mapId = 'world-map-' . uniqid();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            توزيع الزيارات حسب الدولة
        </x-slot>
        <x-slot name="description">
            خريطة تفاعلية لأكثر الدول زيارة (إجمالي: {{ number_format($totalViews) }} زيارة)
        </x-slot>

        <style>
            .jvm-tooltip {
                background: #1e293b !important;
                color: #fff !important;
                border: none !important;
                border-radius: 8px !important;
                padding: 8px 12px !important;
                font-size: 13px !important;
                direction: rtl;
                z-index: 9999;
            }
            #{{ $mapId }} {
                width: 100%;
                height: 400px;
                direction: ltr;
                position: relative;
            }
        </style>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Map --}}
            <div class="lg:col-span-2" wire:ignore>
                <div id="{{ $mapId }}"></div>
            </div>

            {{-- Table --}}
            <div class="lg:col-span-1">
                <div class="space-y-2">
                    @foreach($tableData as $index => $row)
                        <div class="flex items-center justify-between p-2 rounded-lg {{ $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-400 w-5">{{ $index + 1 }}</span>
                                <span class="text-sm font-medium">{{ $row['country'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ number_format($row['total']) }}</span>
                                @if($totalViews > 0)
                                    <span class="text-xs text-gray-500">({{ round(($row['total'] / $totalViews) * 100, 1) }}%)</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

@script
<script>
    var mapId = @js($mapId);
    var mapValues = @js($mapValues);
    var countryNames = @js(collect($tableData)->pluck('country', 'code')->filter());
    var totalViews = @js($totalViews ?: 1);
    var attempts = 0;

    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            var existing = document.querySelector('script[src="' + src + '"]');
            if (existing) { resolve(); return; }
            var s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    async function ensureLoaded() {
        if (typeof jsVectorMap === 'undefined') {
            await loadScript('https://cdn.jsdelivr.net/npm/jsvectormap@1.6.0/dist/js/jsvectormap.min.js');
        }
        // Wait a tick for jsVectorMap to be available
        await new Promise(r => setTimeout(r, 100));
        if (typeof jsVectorMap !== 'undefined' && (!jsVectorMap.maps || !jsVectorMap.maps['world'])) {
            await loadScript('https://cdn.jsdelivr.net/npm/jsvectormap@1.6.0/dist/maps/world.js');
            await new Promise(r => setTimeout(r, 100));
        }
    }

    async function initWorldMap() {
        attempts++;
        if (attempts > 20) { console.warn('World map: gave up after 20 attempts'); return; }

        try { await ensureLoaded(); } catch(e) {
            console.warn('World map: loading libraries...', e);
            setTimeout(initWorldMap, 500); return;
        }

        if (typeof jsVectorMap === 'undefined') { setTimeout(initWorldMap, 500); return; }

        var el = document.getElementById(mapId);
        if (!el || el.offsetWidth === 0) { setTimeout(initWorldMap, 500); return; }

        el.innerHTML = '';

        try {
            new jsVectorMap({
                selector: '#' + mapId,
                map: 'world',
                backgroundColor: 'transparent',
                zoomOnScroll: true,
                zoomButtons: true,
                showTooltip: true,
                focusOn: { x: 0.55, y: 0.45, scale: 1.8 },
                regionStyle: {
                    initial: {
                        fill: '#e2e8f0', fillOpacity: 1,
                        stroke: '#cbd5e1', strokeWidth: 0.5, strokeOpacity: 1
                    },
                    hover: { fillOpacity: 0.8, cursor: 'pointer' }
                },
                series: {
                    regions: [{
                        values: mapValues,
                        scale: ['#93c5fd', '#1d4ed8'],
                        normalizeFunction: 'polynomial'
                    }]
                },
                onRegionTooltipShow: function(event, tooltip, code) {
                    var value = mapValues[code];
                    var name = countryNames[code] || code;
                    if (value) {
                        var pct = ((value / totalViews) * 100).toFixed(1);
                        tooltip.text(
                            '<div style="text-align:center"><strong>' + name + '</strong><br><span>' + value.toLocaleString() + ' زيارة (' + pct + '%)</span></div>',
                            true
                        );
                    }
                }
            });
            console.log('World map initialized on #' + mapId);
        } catch(e) {
            console.error('World map error:', e);
            setTimeout(initWorldMap, 1000);
        }
    }

    initWorldMap();
</script>
@endscript
