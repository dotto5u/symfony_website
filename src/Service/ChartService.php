<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    public function __construct(private ChartBuilderInterface $chartBuilder)
    {
    }

    public function createPieChart(array $labels, array $data, array $backgroundColor): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => 'rgb(229, 229, 229)',
                    'borderWidth' => 1,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'size' => 14,
                        ],
                        'color' => 'rgb(229, 229, 229)',
                    ],
                ],
            ],
        ]);

        return $chart;
    }

    public function createBarChart(array $labels, array $data, array $backgroundColor): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'ticks' => [
                        'align' => 'center',
                        'color' => 'rgb(229, 229, 229)',
                    ],
                    'grid' => [
                        'color' => 'rgb(64, 64, 64)',
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => 'rgb(229, 229, 229)',
                    ],
                    'grid' => [
                        'color' => 'rgb(64, 64, 64)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        return $chart;
    }
}
