<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DoctorNetworkService
{
    public function getSurgeonIds(string $specialization): array
    {
        return DB::table('doctors_specializations')
            ->join('specializations', 'specializations.id', '=', 'doctors_specializations.specialization_id')
            ->where('specializations.specialization', $specialization)
            ->pluck('doctors_specializations.doctor_id')
            ->toArray();
    }

    public function getConnections(int $doctorId): array
    {
        return array_merge(
            DB::table('doctors_network')->where('doctor_1_id', $doctorId)->pluck('doctor_2_id')->toArray(),
            DB::table('doctors_network')->where('doctor_2_id', $doctorId)->pluck('doctor_1_id')->toArray()
        );
    }


    public function findReachableSurgeons(int $startDoctorId, array $surgeonIds): array
    {
        $reachableSurgeons  = [];
        $queue = [$startDoctorId];

        while (!empty($queue)) {
            $currentDoctor = array_shift($queue);

            // If we've already reached this surgeon, or they’re not a surgeon specialist — skip them.
            if (in_array($currentDoctor, $reachableSurgeons) || !in_array($currentDoctor, $surgeonIds)) continue;

            $reachableSurgeons[] = $currentDoctor;

            foreach ($this->getConnections($currentDoctor) as $doctor) {
                if (!in_array($doctor, $reachableSurgeons)) {
                    $queue[] = $doctor;
                }
            }
        }

        return $reachableSurgeons;
    }

    public function filterDoctorsByExperience(array $doctorIds, ?int $minYoe, ?int $maxYoe): Collection
    {
        return DB::table('doctors')
            ->whereIn('id', $doctorIds)
            ->when($minYoe, fn($q) => $q->where('years_of_experience', '>=', $minYoe))
            ->when($maxYoe, fn($q) => $q->where('years_of_experience', '<=', $maxYoe))
            ->get();
    }

    public function countSpecializations(array $doctorIds): Collection
    {
        return DB::table('doctors_specializations')
            ->join('specializations', 'specializations.id', '=', 'doctors_specializations.specialization_id')
            ->whereIn('doctors_specializations.doctor_id', $doctorIds)
            ->select('specializations.specialization', DB::raw('count(*) as total'))
            ->groupBy('specializations.specialization')
            ->pluck('total', 'specializations.specialization');
    }
}
