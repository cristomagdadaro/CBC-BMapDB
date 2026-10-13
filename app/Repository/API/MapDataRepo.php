<?php

namespace App\Repository\API;

use Illuminate\Support\Facades\DB;

class MapDataRepo
{
    /**
     * Get orbit items by city ID with essential public information.
     */
    public function getOrbitItems(string $type, array $cityIds, int $limit, bool $isAdmin): array
    {
        $query = null;

        if ($type === 'commodities') {
            $query = DB::table('commodities')
                ->leftJoin('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                ->join('loc_cities', function ($join) {
                    $join->on(DB::raw('COALESCE(commodities.geolocation, breeders.geolocation)'), '=', 'loc_cities.id');
                })
                ->whereIn('loc_cities.id', $cityIds)
                ->whereNull('commodities.deleted_at')
                ->when(!$isAdmin, function ($q) {
                    $q->whereNotNull('commodities.approved_at');
                })
                ->select([
                    'loc_cities.id as city_id',
                    'commodities.id',
                    'commodities.name',
                    'commodities.name as label',
                    'commodities.scientific_name',
                    'commodities.variety',
                    'commodities.accession',
                    'commodities.yield',
                    'commodities.description as unique_traits',
                    DB::raw("TRIM(CONCAT(IFNULL(breeders.fname,''),' ',IFNULL(breeders.mname,''),' ',IFNULL(breeders.lname,''),' ',IFNULL(breeders.suffix,''))) as breeder_name"),
                    DB::raw("TRIM(CONCAT(IFNULL(loc_cities.cityDesc,''), CASE WHEN loc_cities.provDesc IS NOT NULL AND loc_cities.provDesc != '' THEN CONCAT(', ', loc_cities.provDesc) ELSE '' END)) as location"),
                    'commodities.photo as photo',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY commodities.updated_at DESC) as rn')
                ]);
        } else {
            $query = DB::table('breeders')
                ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
                ->leftJoin('institutes', 'breeders.affiliation', '=', 'institutes.id')
                ->whereIn('loc_cities.id', $cityIds)
                ->whereNull('breeders.deleted_at')
                ->select([
                    'loc_cities.id as city_id',
                    'breeders.id',
                    DB::raw("TRIM(CONCAT(breeders.fname,' ',IFNULL(breeders.mname,''),' ',breeders.lname,' ',IFNULL(breeders.suffix,''))) as name"),
                    DB::raw("TRIM(CONCAT(breeders.fname,' ',IFNULL(breeders.mname,''),' ',breeders.lname,' ',IFNULL(breeders.suffix,''))) as label"),
                    'institutes.name as affiliation',
                    'breeders.expertise',
                    'breeders.research_interest',
                    DB::raw("TRIM(CONCAT(IFNULL(loc_cities.cityDesc,''), CASE WHEN loc_cities.provDesc IS NOT NULL AND loc_cities.provDesc != '' THEN CONCAT(', ', loc_cities.provDesc) ELSE '' END)) as location"),
                    'breeders.photo as photo',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY breeders.updated_at DESC) as rn')
                ]);
        }

        $rankedRows = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->where('rn', '<=', $limit)
            ->get();

        $groupedData = $rankedRows->groupBy('city_id')->map(function ($rows) use ($type) {
            return $rows->map(function ($r) use ($type) {
                $photoUrl = $r->photo ? asset($r->photo) : asset('img/logo_cbc.webp');

                if ($type === 'commodities') {
                    return [
                        'id' => $r->id,
                        'name' => $r->name,
                        'label' => $r->label,
                        'scientific_name' => $r->scientific_name ?: 'N/A',
                        'breeder' => $r->breeder_name ?: 'N/A',
                        'variety' => $r->variety ?: ($r->accession ?: 'N/A'),
                        'accession' => $r->accession,
                        'yield' => $r->yield !== null ? $r->yield : null,
                        'location' => $r->location ?: 'N/A',
                        'unique_traits' => $r->unique_traits ?: 'N/A',
                        'image' => $photoUrl,
                        'data_type' => 'commodities',
                    ];
                }

                return [
                    'id' => $r->id,
                    'name' => $r->name,
                    'label' => $r->label,
                    'affiliation' => $r->affiliation ?: 'N/A',
                    'expertise' => $r->expertise ?: 'N/A',
                    'research_interest' => $r->research_interest ?: 'N/A',
                    'location' => $r->location ?: 'N/A',
                    'image' => $photoUrl,
                    'data_type' => 'breeders',
                ];
            });
        });

        return $groupedData->toArray();
    }

    /**
     * Get basic publicly-viewable details for a single breeder or commodity.
     */
    public function getBasicInfo(string $type, int $id): ?array
    {
        if ($type === 'commodities') {
            $row = DB::table('commodities')
                ->leftJoin('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                ->join('loc_cities', function ($join) {
                    $join->on(DB::raw('COALESCE(commodities.geolocation, breeders.geolocation)'), '=', 'loc_cities.id');
                })
                ->where('commodities.id', $id)
                ->whereNull('commodities.deleted_at')
                ->select([
                    'commodities.id',
                    'commodities.name',
                    'commodities.scientific_name',
                    'commodities.variety',
                    'commodities.accession',
                    'commodities.yield',
                    'commodities.description as unique_traits',
                    DB::raw("TRIM(CONCAT(IFNULL(breeders.fname,''),' ',IFNULL(breeders.mname,''),' ',IFNULL(breeders.lname,''),' ',IFNULL(breeders.suffix,''))) as breeder_name"),
                    DB::raw("TRIM(CONCAT(IFNULL(loc_cities.cityDesc,''), CASE WHEN loc_cities.provDesc IS NOT NULL AND loc_cities.provDesc != '' THEN CONCAT(', ', loc_cities.provDesc) ELSE '' END)) as location"),
                    'commodities.photo as photo',
                ])
                ->first();

            if (!$row) return null;

            return [
                'id' => $row->id,
                'name' => $row->name,
                'label' => $row->name,
                'scientific_name' => $row->scientific_name ?: 'N/A',
                'breeder' => $row->breeder_name ?: 'N/A',
                'variety' => $row->variety ?: ($row->accession ?: 'N/A'),
                'accession' => $row->accession ?: 'N/A',
                'yield' => $row->yield !== null ? $row->yield : 'N/A',
                'location' => $row->location ?: 'N/A',
                'unique_traits' => $row->unique_traits ?: 'N/A',
                'image' => $row->photo ? asset($row->photo) : asset('img/logo_cbc.webp'),
                'data_type' => 'commodities',
            ];
        }

        $row = DB::table('breeders')
            ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->leftJoin('institutes', 'breeders.affiliation', '=', 'institutes.id')
            ->where('breeders.id', $id)
            ->whereNull('breeders.deleted_at')
            ->select([
                'breeders.id',
                DB::raw("TRIM(CONCAT(breeders.fname,' ',IFNULL(breeders.mname,''),' ',breeders.lname,' ',IFNULL(breeders.suffix,''))) as name"),
                'institutes.name as affiliation',
                'breeders.expertise',
                'breeders.research_interest',
                DB::raw("TRIM(CONCAT(IFNULL(loc_cities.cityDesc,''), CASE WHEN loc_cities.provDesc IS NOT NULL AND loc_cities.provDesc != '' THEN CONCAT(', ', loc_cities.provDesc) ELSE '' END)) as location"),
                'breeders.photo as photo',
            ])
            ->first();

        if (!$row) return null;

        return [
            'id' => $row->id,
            'name' => $row->name,
            'label' => $row->name,
            'affiliation' => $row->affiliation ?: 'N/A',
            'expertise' => $row->expertise ?: 'N/A',
            'research_interest' => $row->research_interest ?: 'N/A',
            'location' => $row->location ?: 'N/A',
            'image' => $row->photo ? asset($row->photo) : asset('img/logo_cbc.webp'),
            'data_type' => 'breeders',
        ];
    }
}
