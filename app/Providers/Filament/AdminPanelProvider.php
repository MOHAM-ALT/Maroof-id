<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\CardResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\CouponResource;
use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\PayoutResource;
use App\Filament\Resources\ResellerResource;
use App\Filament\Resources\DesignerResource;
use App\Filament\Resources\AffiliateResource;
use App\Filament\Resources\TemplateResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Maroof ID')
            ->brandLogo(null)
            ->favicon(null)
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Cairo')
            ->navigationGroups([
                'إدارة المحتوى' => \Filament\Navigation\NavigationGroup::make()
                    ->label('إدارة المحتوى')
                    ->icon('heroicon-o-rectangle-stack'),
                'الشركاء' => \Filament\Navigation\NavigationGroup::make()
                    ->label('الشركاء')
                    ->icon('heroicon-o-user-group'),
                'الإعدادات' => \Filament\Navigation\NavigationGroup::make()
                    ->label('الإعدادات')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): HtmlString => new HtmlString('
                    <div class="flex items-center gap-3 px-4 py-3 mb-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/>
                            </svg>
                        </div>
                    </div>
                ')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.6.0/dist/css/jsvectormap.min.css" />
                    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.6.0/dist/js/jsvectormap.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.6.0/dist/maps/world.js"></script>
                    <style>
                        /* تحسين تباين الداشبورد */
                        .fi-body {
                            background-color: #f1f5f9 !important;
                        }
                        .dark .fi-body {
                            background-color: #0f172a !important;
                        }
                        .fi-wi-stats-overview-stat {
                            border: 1px solid #e2e8f0 !important;
                            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06) !important;
                        }
                        .fi-section {
                            border: 1px solid #e2e8f0 !important;
                            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06) !important;
                        }
                        .fi-wi-chart {
                            border: 1px solid #e2e8f0 !important;
                            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06) !important;
                        }
                        /* تحسين الشريط الجانبي */
                        .fi-sidebar {
                            border-left: 1px solid #e2e8f0 !important;
                        }
                        .fi-sidebar-nav-group-label {
                            font-weight: 700 !important;
                            color: #475569 !important;
                            font-size: 0.7rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.05em !important;
                        }
                    </style>
                ')
            )
            ->resources([
                UserResource::class,
                CardResource::class,
                OrderResource::class,
                RoleResource::class,
                CouponResource::class,
                PartnerResource::class,
                PayoutResource::class,
                ResellerResource::class,
                DesignerResource::class,
                AffiliateResource::class,
                TemplateResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
    }
}
