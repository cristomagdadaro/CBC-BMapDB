<?php

namespace App\Repository\API;

use Illuminate\Support\Facades\DB;

class MapDataRepo
{
    public function getOrbitItems(string $type, array $cityIds, int $limit, bool $isAdmin): array
    {
        $query = null;

        if ($type === 'commodities') {
            $query = DB::table('commodities')
                ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
                ->whereIn('loc_cities.id', $cityIds)
                ->when(!$isAdmin, function ($q) {
                    $q->whereNotNull('commodities.approved_at');
                })
                ->select([
                    'loc_cities.id as city_id',
                    'commodities.id',
                    'commodities.name as label',
                    'commodities.photo as photo',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY commodities.updated_at DESC) as rn')
                ]);
        } else {
            $query = DB::table('breeders')
                ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
                ->whereIn('loc_cities.id', $cityIds)
                ->select([
                    'loc_cities.id as city_id',
                    'breeders.id',
                    DB::raw("TRIM(CONCAT(breeders.fname,' ',IFNULL(breeders.mname,''),' ',breeders.lname,' ',IFNULL(breeders.suffix,''))) as label"),
                    'breeders.photo as photo',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY breeders.updated_at DESC) as rn')
                ]);
        }

        $rankedRows = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->where('rn', '<=', $limit)
            ->get();

        $groupedData = $rankedRows->groupBy('city_id')->map(function ($rows) {
            return $rows->map(function ($r) {
                return [
                    'id' => $r->id,
                    'label' => $r->label,
                    'image' => $r->photo ? asset($r->photo) : asset('img/logos/pin.webp'),
                ];
            });
        });

        return $groupedData->toArray();
    }
}
