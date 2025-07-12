<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class HomeService
{
    public function getTimeIntervals(string $startTime, string $endTime): array
    {
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);

        $timeIntervals = [];

        while ($endTimestamp >= $startTimestamp) {
            $timeIntervals[] = date('H:i', $startTimestamp);
            $startTimestamp = strtotime('+1 hour', $startTimestamp);
        }

        return $timeIntervals;
    }

    public function getBookStaffData(array $bookStaff, array $visits, string $startTime, int $multiplier): array
    {
        $recordsIntervals = [];

        foreach ($visits as $index => $visit) {
            if (!count($visit['services'])) {
                continue;
            }

            $staffId = $visit['services'][0]['staff']['id'] ?? null;
            if (!$staffId) {
                continue;
            }

            $startDate = null;
            $endDate = null;
            $startHours = null;
            $endHours = null;
            $servicesAsString = '';

            foreach ($visit['services'] as $serviceIndex => $service) {
                $servicesAsString .= $service['service']['title'];

                if (count($visit['services']) - 1 !== $serviceIndex) {
                    $servicesAsString .= '.' . PHP_EOL;
                } else {
                    $servicesAsString .= '.';
                }

                if (count($visit['services']) === 1) {
                    $startDate = $service['start_date'];
                    $startHours = Carbon::parse($service['start_date'])->format('H:i');
                    $endDate = $service['end_date'];
                    $endHours = Carbon::parse($service['end_date'])->format('H:i');
                } elseif ($serviceIndex === 0) {
                    $startDate = $service['start_date'];
                    $startHours = Carbon::parse($service['start_date'])->format('H:i');
                } elseif ($serviceIndex === count($visit['services']) - 1) {
                    $endDate = $service['end_date'];
                    $endHours = Carbon::parse($service['end_date'])->format('H:i');
                }
            }

            $visits[$index]['services_as_string'] = $servicesAsString;
            $visits[$index]['height'] = Carbon::parse($startDate)->diffInMinutes(Carbon::parse($endDate), true) * $multiplier;
            $visits[$index]['top'] = Carbon::parse($startDate)->diffInMinutes(Carbon::parse($startTime), true) * $multiplier;
            $visits[$index]['start_hours'] = $startHours;
            $visits[$index]['end_hours'] = $endHours;
            $visits[$index]['nesting'] = 0;

            $bookStaffIndex = array_find_key($bookStaff, fn($staff) => $staff['id'] === $staffId);
            if (!is_null($bookStaffIndex)) {
                $bookStaff[$bookStaffIndex]['visits'][] = $visits[$index];
            }

            $recordsIntervals[$staffId][] = [
                'visit_id' => $visit['id'],
                'start_hours' => $startHours,
                'end_hours' => $endHours,
            ];
        }

        foreach ($bookStaff as $staffIndex => $staff) {
            if (isset($staff['visits']) && count($staff['visits'])) {
                foreach ($staff['visits'] as $visitIndex => $visit) {
                    foreach ($recordsIntervals[$staff['id']] as $lastInterval) {
                        if (
                            $visit['id'] != $lastInterval['visit_id']
                            && strtotime($visit['start_hours']) >= strtotime($lastInterval['start_hours'])
                            && strtotime($visit['end_hours']) <= strtotime($lastInterval['end_hours'])
                        ) {
                            $bookStaff[$staffIndex]['visits'][$visitIndex]['nesting'] += 10;
                        }
                    }
                }
            }
        }

        return $bookStaff;
    }
}
