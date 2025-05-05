<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorNetworkSearchRequest;
use App\Services\DoctorNetworkService;

class DoctorNetworkController extends Controller
{
    protected DoctorNetworkService $networkService;

    public function __construct(DoctorNetworkService $networkService)
    {
        $this->networkService = $networkService;
    }

    public function aggregates(DoctorNetworkSearchRequest $request, int $id)
    {
        $minYoe = $request->query('min_yoe');
        $maxYoe = $request->query('max_yoe');

        // Get surgeons that have the same specialization as the one in the request
        $surgeonIds = $this->networkService->getSurgeonIds($request->query('specialization'));
        $reachableSurgeons = $this->networkService->findReachableSurgeons($id, $surgeonIds);

        // Filter by years of experience if provided
        $filteredDoctors = $this->networkService->filterDoctorsByExperience($reachableSurgeons, $minYoe, $maxYoe);
        $filteredDoctorIds = $filteredDoctors->pluck('id')->toArray();

        // Get total specialization counts
        $specializationCounts = $this->networkService->countSpecializations($filteredDoctorIds);

        $response = [
            'specializations_aggregrates' => $specializationCounts,
        ];

        // If there are years of experience filters, include them in the response
        if ($minYoe || $maxYoe) {
            $yearsOfExperience = $filteredDoctors->groupBy('years_of_experience')->map->count();
            $response['years_of_experience_aggregates'] = $yearsOfExperience;
        }

        return response()->json($response);
    }
}
