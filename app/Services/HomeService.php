<?php

namespace App\Services;

use App\Enums\VisitStatus;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

    public function getBookStaffData(
        array  $bookStaff,
        array  $visits,
        Carbon $date,
        string $startTime,
        int    $multiplier,
    ): array
    {
        $startTime = $date->setTimeFromTimeString($startTime);

        $visitIntervals = [];

        foreach ($visits as $visit) {
            if (!count($visit['services'])) {
                continue;
            }

            $status = $visit['status'];

            if ($status === VisitStatus::Canceled->name) {
                continue;
            }

            $visitServices = [];

            foreach ($visit['services'] as $visitService) {
                $startDate = $visitService['start_date'];
                $endDate = $visitService['end_date'];
                $startHours = Carbon::parse($visitService['start_date'])->format('H:i');
                $endHours = Carbon::parse($visitService['end_date'])->format('H:i');

                $lastServiceKey = array_key_last($visitServices);

                if (
                    count($visitServices)
                    && $visitServices[$lastServiceKey]
                    && $visitServices[$lastServiceKey]['end_date'] === $startDate
                    && $visitServices[$lastServiceKey]['staff_id'] === $visitService['staff']['id']
                ) {
                    $oldStartDate = $visitServices[$lastServiceKey]['start_date'];

                    $visitServices[$lastServiceKey]['services'][] = $visitService['service'];
                    $visitServices[$lastServiceKey]['end_date'] = $endDate;
                    $visitServices[$lastServiceKey]['end_hours'] = $endHours;
                    $visitServices[$lastServiceKey]['height'] = Carbon::parse($oldStartDate)
                            ->diffInMinutes(Carbon::parse($endDate), true) * $multiplier;
                    $visitServices[$lastServiceKey]['top'] = Carbon::parse($oldStartDate)
                            ->diffInMinutes(Carbon::parse($startTime), true) * $multiplier;
                } else {
                    $visitServices[] = [
                        'custom_id' => Str::uuid(),
                        'visit_id' => $visit['id'],
                        'staff_id' => $visitService['staff']['id'],
                        'client' => $visit['client'],
                        'services' => [$visitService['service']],
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'start_hours' => $startHours,
                        'end_hours' => $endHours,
                        'height' => Carbon::parse($startDate)
                                ->diffInMinutes(Carbon::parse($endDate), true) * $multiplier,
                        'top' => Carbon::parse($startDate)
                                ->diffInMinutes(Carbon::parse($startTime), true) * $multiplier,
                        'nesting' => 0,
                        'status' => $status,
                    ];
                }
            }

            foreach ($visitServices as $visitService) {
                $bookStaffIndex = array_find_key($bookStaff, fn($staff) => $staff['id'] === $visitService['staff_id']);
                if (!is_null($bookStaffIndex)) {
                    $bookStaff[$bookStaffIndex]['visits'][] = $visitService;
                }

                $visitIntervals[$visitService['staff_id']][] = [
                    'custom_id' => $visitService['custom_id'],
                    'start_hours' => $visitService['start_hours'],
                    'end_hours' => $visitService['end_hours'],
                ];
            }
        }

        foreach ($bookStaff as $staffIndex => $staff) {
            if (isset($staff['visits']) && count($staff['visits'])) {
                foreach ($staff['visits'] as $visitIndex => $visit) {
                    foreach ($visitIntervals[$staff['id']] as $lastInterval) {
                        if (
                            $visit['custom_id'] != $lastInterval['custom_id']
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
