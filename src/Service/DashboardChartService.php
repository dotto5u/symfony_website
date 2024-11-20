<?php

namespace App\Service;

use App\Service\ChartService;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardChartService
{
    public function __construct(private ChartService $chartService, private TranslatorInterface $translator) {}

    public function preparePieChart(array $availabilityRatio): Chart
    {
        $labels = array_keys($availabilityRatio);
        $translatedLabels = array_map(fn($label) => $this->translator->trans('label.product.'.$label), $labels);
        $data = array_values($availabilityRatio);
        $backgroundColors = ['#2E7D32', '#0288D1', '#C62828'];

        return $this->chartService->createPieChart($translatedLabels, $data, $backgroundColors);
    }

    public function prepareBarChart(array $salesOverLastTwelveMonths): Chart
    {
        $monthlySales = array_reduce($salesOverLastTwelveMonths, function ($sales, $sale) {
            $key = $this->translator->trans('label.month.'.$sale['month']).' '.$sale['year'];
            $sales[$key] = $sale['total'];
            return $sales;
        }, []);

        $labels = array_keys($monthlySales);
        $data = array_values($monthlySales);
        $backgroundColors = [
            '#A8D5BA', '#2E7D32', '#B3E5FC', '#0288D1', '#FFCDD2', '#C62828',
            '#00BCD4', '#A8D5BA', '#2E7D32', '#B3E5FC', '#0288D1', '#FFCDD2'
        ];

        return $this->chartService->createBarChart($labels, $data, $backgroundColors);
    }
}