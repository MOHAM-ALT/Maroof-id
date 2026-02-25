<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Partner;
use App\Enums\GeneralStatus;
use Illuminate\Support\Collection;

class PartnerMatchingService
{
    /**
     * Saudi cities with approximate coordinates for distance calculation
     */
    private array $cityCoordinates = [
        'الرياض' => ['lat' => 24.7136, 'lng' => 46.6753],
        'جدة' => ['lat' => 21.4858, 'lng' => 39.1925],
        'الدمام' => ['lat' => 26.3927, 'lng' => 49.9777],
        'مكة' => ['lat' => 21.3891, 'lng' => 39.8579],
        'المدينة' => ['lat' => 24.5247, 'lng' => 39.5692],
        'الخبر' => ['lat' => 26.2172, 'lng' => 50.1971],
        'تبوك' => ['lat' => 28.3835, 'lng' => 36.5662],
        'أبها' => ['lat' => 18.2164, 'lng' => 42.5053],
        'الطائف' => ['lat' => 21.2703, 'lng' => 40.4158],
        'بريدة' => ['lat' => 26.3260, 'lng' => 43.9750],
        'حائل' => ['lat' => 27.5114, 'lng' => 41.7208],
        'نجران' => ['lat' => 17.4924, 'lng' => 44.1277],
        'جازان' => ['lat' => 16.8892, 'lng' => 42.5511],
        'ينبع' => ['lat' => 24.0895, 'lng' => 38.0618],
        'الجبيل' => ['lat' => 26.9598, 'lng' => 49.5687],
        'الاحساء' => ['lat' => 25.3648, 'lng' => 49.5976],
        'القطيف' => ['lat' => 26.5196, 'lng' => 50.0115],
        'خميس مشيط' => ['lat' => 18.3007, 'lng' => 42.7353],
    ];

    /**
     * City regions for fallback matching
     */
    private array $cityRegions = [
        'central' => ['الرياض', 'بريدة', 'حائل'],
        'western' => ['جدة', 'مكة', 'المدينة', 'الطائف', 'ينبع'],
        'eastern' => ['الدمام', 'الخبر', 'الجبيل', 'الاحساء', 'القطيف'],
        'southern' => ['أبها', 'نجران', 'جازان', 'خميس مشيط'],
        'northern' => ['تبوك'],
    ];

    /**
     * Find the best matching partner for an order
     */
    public function findPartnerForOrder(Order $order): ?Partner
    {
        $shippingCity = $order->shipping_city;

        if (!$shippingCity) {
            return null;
        }

        $activePartners = Partner::where('status', GeneralStatus::Active)->get();

        if ($activePartners->isEmpty()) {
            return null;
        }

        // Step 1: Exact city match
        $sameCityPartners = $activePartners->where('city', $shippingCity);
        if ($sameCityPartners->isNotEmpty()) {
            return $this->selectBestPartner($sameCityPartners);
        }

        // Step 2: Nearest city by distance
        $nearestPartner = $this->findNearestPartner($shippingCity, $activePartners);
        if ($nearestPartner) {
            return $nearestPartner;
        }

        // Step 3: Same region fallback
        $region = $this->getCityRegion($shippingCity);
        if ($region) {
            $regionCities = $this->cityRegions[$region];
            $regionPartners = $activePartners->whereIn('city', $regionCities);
            if ($regionPartners->isNotEmpty()) {
                return $this->selectBestPartner($regionPartners);
            }
        }

        // Step 4: Any active partner (least orders first)
        return $this->selectBestPartner($activePartners);
    }

    /**
     * Assign a partner to an order
     */
    public function assignPartnerToOrder(Order $order): ?Partner
    {
        $partner = $this->findPartnerForOrder($order);

        if ($partner) {
            $order->update(['partner_id' => $partner->id]);
        }

        return $partner;
    }

    /**
     * Select the best partner from a collection (least orders = balanced workload)
     */
    private function selectBestPartner(Collection $partners): Partner
    {
        return $partners->sortBy(function ($partner) {
            return $partner->orders()
                ->whereIn('status', ['pending', 'confirmed', 'processing'])
                ->count();
        })->first();
    }

    /**
     * Find nearest partner by geographic distance
     */
    private function findNearestPartner(string $shippingCity, Collection $partners): ?Partner
    {
        $cityCoords = $this->cityCoordinates[$shippingCity] ?? null;

        if (!$cityCoords) {
            return null;
        }

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($partners as $partner) {
            $partnerCoords = $this->cityCoordinates[$partner->city] ?? null;
            if (!$partnerCoords) {
                continue;
            }

            $distance = $this->calculateDistance(
                $cityCoords['lat'], $cityCoords['lng'],
                $partnerCoords['lat'], $partnerCoords['lng']
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $partner;
            }
        }

        return $nearest;
    }

    /**
     * Calculate distance between two points (Haversine formula) in km
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get region for a city
     */
    private function getCityRegion(string $city): ?string
    {
        foreach ($this->cityRegions as $region => $cities) {
            if (in_array($city, $cities)) {
                return $region;
            }
        }

        return null;
    }

    /**
     * Get all partners with their distances from a city (for admin view)
     */
    public function getPartnersWithDistances(string $city): Collection
    {
        $partners = Partner::where('status', GeneralStatus::Active)->get();
        $cityCoords = $this->cityCoordinates[$city] ?? null;

        return $partners->map(function ($partner) use ($cityCoords, $city) {
            $partnerCoords = $this->cityCoordinates[$partner->city] ?? null;
            $distance = null;

            if ($cityCoords && $partnerCoords) {
                $distance = round($this->calculateDistance(
                    $cityCoords['lat'], $cityCoords['lng'],
                    $partnerCoords['lat'], $partnerCoords['lng']
                ), 1);
            }

            $partner->distance_km = $distance;
            $partner->same_city = $partner->city === $city;
            $partner->same_region = $this->getCityRegion($partner->city) === $this->getCityRegion($city);

            return $partner;
        })->sortBy('distance_km');
    }
}
