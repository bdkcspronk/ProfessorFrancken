<?php

declare(strict_types=1);

namespace Francken\Association\Activities;

use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ActivitiesSidebarComposer
{
    private const FIRST_MONTH_OF_ACADEMIC_YEAR = 8;

    private DateTimeImmutable $today;

    public function __construct()
    {
        $this->today = new DateTimeImmutable();
    }

    public function compose(View $view) : void
    {
        $viewData = $view->getData();
        $year = (int) ($viewData['selectedYear'] ?? $this->today->format('Y'));
        $month = (int) ($viewData['selectedMonth'] ?? $this->today->format('m'));

        $academicYear = $this->academicYear($year, $month);

        $view->with([
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedDate' => DateTimeImmutable::createFromFormat(
                'Y-m',
                $year . '-' . $month
            ),
            'months' => $this->monthNames($academicYear),
            'visibleYears' => $this->visibleYears($academicYear, $month),
            'activitiesPerMonth' => $this->activitiesPerMonth($academicYear),
            'currentMonth' => $this->today->format('Y-m'),
        ]);
    }

    /**
     * The number of activities in each month of an academic year, keyed by "Y-m", so that
     * the calendar can show which months have activities
     */
    private function activitiesPerMonth(int $academicYear) : array
    {
        $start = sprintf('%d-%02d-01', $academicYear, self::FIRST_MONTH_OF_ACADEMIC_YEAR);
        $end = sprintf('%d-%02d-01', $academicYear + 1, self::FIRST_MONTH_OF_ACADEMIC_YEAR);

        return Activity::query()
            ->where('start_date', '>=', $start)
            ->where('start_date', '<', $end)
            ->pluck('start_date')
            // The dates are formatted as "Y-m-d H:i:s", so the first 7 characters are "Y-m"
            ->countBy(fn ($date) : string => $date instanceof \DateTimeInterface
                ? $date->format('Y-m')
                : substr(is_string($date) ? $date : '', 0, 7))
            ->all();
    }

    /**
     * The calendar shows academic years, which start in August: the year in which the
     * academic year of the given month starts
     */
    private function academicYear(int $year, int $month) : int
    {
        return $month >= self::FIRST_MONTH_OF_ACADEMIC_YEAR ? $year : $year - 1;
    }

    /**
     * The previous, current and next academic year, each linking to the selected month
     * in that academic year
     */
    private function visibleYears(int $academicYear, int $month) : array
    {
        $years = [];

        // Previous academic year
        if ($academicYear > 2006) {
            $years[] = $academicYear - 1;
        }

        // Current academic year
        $years[] = $academicYear;

        // Next academic year
        $currentAcademicYear = $this->academicYear(
            (int) $this->today->format('Y'),
            (int) $this->today->format('m')
        );
        if ($academicYear <= $currentAcademicYear) {
            $years[] = $academicYear + 1;
        }

        return array_map(fn (int $start) : array => [
            'label' => $start . '–' . substr((string) ($start + 1), -2),
            'year' => $month >= self::FIRST_MONTH_OF_ACADEMIC_YEAR ? $start : $start + 1,
            'isSelected' => $start === $academicYear,
        ], $years);
    }

    /**
     * The months of an academic year, from August until July, with the calendar year
     * that each month is in
     */
    private function monthNames(int $academicYear) : Collection
    {
        $months = array_merge(
            range(self::FIRST_MONTH_OF_ACADEMIC_YEAR, 12),
            range(1, self::FIRST_MONTH_OF_ACADEMIC_YEAR - 1)
        );

        return collect($months)->map(function (int $month) use ($academicYear) : array {
            /** @var int $time */
            $time = mktime(0, 0, 0, $month, 1);
            return [
                'number' => date('m', $time),
                'name' => date('F', $time),
                'year' => $month >= self::FIRST_MONTH_OF_ACADEMIC_YEAR ? $academicYear : $academicYear + 1,
            ];
        });
    }
}
