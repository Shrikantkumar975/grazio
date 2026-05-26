<?php

namespace App\Services;

class GeofenceService
{
    /**
     * Determine if a point [lat, lng] is inside a polygon.
     * Uses the Ray-Casting algorithm.
     *
     * @param  float  $lat
     * @param  float  $lng
     * @param  array  $polygon  Array of [lat, lng] points
     * @return bool
     */
    public function pointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $count = count($polygon);
        if ($count < 3) {
            return false;
        }

        $inside = false;
        $j = $count - 1;

        for ($i = 0; $i < $count; $i++) {
            $xi = (float) $polygon[$i][0];
            $yi = (float) $polygon[$i][1];
            $xj = (float) $polygon[$j][0];
            $yj = (float) $polygon[$j][1];

            $intersect = (($yi > $lng) !== ($yj > $lng))
                && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }

            $j = $i;
        }

        return $inside;
    }

    /**
     * Check all active geofences for a given user against a GPS position.
     * Returns array of geofences that the point is OUTSIDE of (breaches).
     *
     * @param  float  $lat
     * @param  float  $lng
     * @param  int    $userId
     * @return \Illuminate\Support\Collection
     */
    public function checkBreaches(float $lat, float $lng, int $userId)
    {
        $geofences = \App\Models\Geofence::where('user_id', $userId)
            ->where('active', true)
            ->get();

        return $geofences->filter(function ($fence) use ($lat, $lng) {
            $coords = $fence->coordinates ?? [];
            return count($coords) >= 3 && !$this->pointInPolygon($lat, $lng, $coords);
        });
    }
}
