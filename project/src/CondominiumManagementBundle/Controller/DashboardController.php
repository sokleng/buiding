<?php

namespace CondominiumManagementBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Feedback;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * @Route("/{condominium}/dashboard")
 */
class DashboardController extends Controller
{
    use HasCondominiumManagementUtils;

    /**
     * @Route("/", name="condominium_dashboard")
     * @Template("CondominiumManagementBundle:Dashboard:show.html.twig")
     * @Method("GET")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function chartAction(Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium);

        $chartReport = $this->chartReport($condominium);
        $chartReportIncomeExpenseProfit = $this->chartReportIncomeExpenseProfit($condominium);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'chartReport' => $chartReport,
                'chartReportIncomeExpenseProfit' => $chartReportIncomeExpenseProfit
            ]
        );
    }

    /**
     * @param Condominium $condominium
     *
     * @return Highchart
     */
    private function chartReportIncomeExpenseProfit(Condominium $condominium)
    {
        $ob = new Highchart();
        $ob->chart->type('pie');
        $tr = $this->get('translator');
        $ob->chart->renderTo('chart_income_expense_profit');
        $ob->title->text($tr->trans('condo.report.income.expense.profit'));

        if($condominium->getCurrency() !== null) {
            $condominiumCurrencySign = $condominium->getCurrency()->getCurrency();
        }

        $this->StypeLabelChartAction($ob);
        $ob->tooltip->pointFormat(
            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f} </b>'
            .$condominiumCurrencySign
        );

        $ob->plotOptions->series(
            [
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.name}: {point.y:.0f} '
                    .$condominiumCurrencySign
                ],
            ]
        );

        $date = new DateTime();
        $defaultFromDate = $date
            ->add(date_interval_create_from_date_string('-3 months'));

        $incomes = $this->getIncomeRepository()
            ->findAllIncomeLastThreeMonthCondominiumAndDate(
                $condominium,
                $date,
                $defaultFromDate
            )
            ->getQuery()
            ->getResult();

        $vatIncome = [];
        $grandTotalIncomes = [];

        foreach($incomes as $income) {
            $grandTotalIncome = 0;

            if($condominium->getCurrency() === 'USD'){
                $grandTotalIncome =  $income->getUsdAmount();
            } else {
                $grandTotalIncome =
                    $income->getUsdAmount() *
                    $income->getExchangeSetting()->getValue()[$condominium->getCurrency()->getId()];
            }

            array_push($grandTotalIncomes, $grandTotalIncome);
            array_push($vatIncome,$income->getVat());
        }

        $totalVatIncome = array_sum($vatIncome) * array_sum($grandTotalIncomes) / 100;

        $dataIncome = [
            [
                $tr->trans('condo.label.report.income.vat'),$totalVatIncome,
            ],
            [
                $tr->trans('condo.label.report.income.grand.total'), array_sum($grandTotalIncomes)
            ]
        ];

        $expenses = $this->getExpendInvoiceRepository()
            ->findAllExpenseLastThreeMonthCondominiumAndDate(
                $condominium,
                $defaultFromDate
            )
            ->getQuery()
            ->getResult();

        $vatExpense = [];
        $grandTotalExpenses = [];

        foreach($expenses as $expense) {
            $grandTotalExpense = 0;
            if($condominium->getCurrency() === 'USD'){
                $grandTotalExpense =  $expense->getUsdAmount();
            } else {
                $grandTotalExpense =
                    $expense->getUsdAmount() *
                    $expense->getExchangeSetting()->getValue()[$condominium->getCurrency()->getId()];
            }

            array_push($grandTotalExpenses, $grandTotalExpense);
            array_push($vatExpense, $expense->getVat());
        }

        $totalVatExpense = array_sum($vatExpense) * array_sum($grandTotalExpenses) / 100;
        $dataExpense = [
            [
                $tr->trans('condo.label.report.expense.vat'),$totalVatExpense
            ],
            [
                $tr->trans('condo.label.report.expense.grand.total'), array_sum($grandTotalExpenses)
            ]
        ];

        $totalProfit = array_sum($grandTotalIncomes) - array_sum($grandTotalExpenses);
        $dataProfit = [
            [
                $tr->trans('condo.label.report.income'), array_sum($grandTotalIncomes)
            ],
            [
                $tr->trans('condo.label.report.expense'), array_sum($grandTotalExpenses)
            ],
            [
                $tr->trans('condo.label.report.profit'), $totalProfit
            ]
        ];

        $ob->series(
            [
                [
                    'center' => ['20%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.income'),
                    'data' => $dataIncome,
                ],
                [
                    'center' => ['50%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.expense'),
                    'data' => $dataExpense,
                ],
                [
                    'center' => ['78%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.profit'),
                    'data' => $dataProfit,
                ]
            ]
        );

        return  $ob;
    }

    /**
     * @param Condominium $condominium
     *
     * @return Highchart
     */
    private function chartReport(Condominium $condominium)
    {
        $ob = new Highchart();
        $ob->chart->type('pie');
        $tr = $this->get('translator');
        $ob->chart->renderTo('report');
        $ob->title->text($tr->trans('condo.report.feedback.issue.room'));

        // access to get feedback
        $feedbacks = $this->getFeedbackRepository()
            ->findAllFeedbackCondominium($condominium);

        // access to get issue
        $issues = $this->getIssueRepository()
            ->findAllIssueCondominium($condominium);

        $availableUnits = [];
        $notAvailableUnits = [];

        // access to get Unit not available
        $notAvailableUnits = $this->getClientUnitRepository()
            ->countAllUsersForCondominium($condominium)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        // access to get Unit available
        $units = $this->getUnitRepository()
            ->countAllUnitsForCondominium($condominium)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $availableUnits = $units - $notAvailableUnits;

        $dataRoom = [
            [
                $tr->trans('condo.report.room.available'), $availableUnits,
            ],
            [
                $tr->trans('condo.report.room.not.available'), $notAvailableUnits,
            ],
        ];

        $this->StypeLabelChartAction($ob);

        $veryBad = [];
        $bad = [];
        $average = [];
        $good = [];
        $veryGood = [];

        foreach ($feedbacks as $rate) {
            if ($rate->isVeryLow()) {
                array_push($veryBad, 1);
            }
            if ($rate->isLow()) {
                array_push($bad, 2);
            }
            if ($rate->isAverage()) {
                array_push($average, 3);
            }
            if ($rate->isHigh()) {
                array_push($good, 4);
            }
            if ($rate->isVeryHigh()) {
                array_push($veryGood, 5);
            }
        }
        $dataFeedback = [
            [
                $tr->trans('condo.report.feedback.one.star'), count($veryBad),
            ],
            [
                $tr->trans('condo.report.feedback.two.star'), count($bad),
            ],
            [
                $tr->trans('condo.report.feedback.three.star'), count($average),
            ],
            [
                $tr->trans('condo.report.feedback.four.star'), count($good),
            ],
            [
                $tr->trans('condo.report.feedback.five.star'), count($veryGood),
            ],
        ];

        $new = [];
        $inProgress = [];
        $closed = [];

        foreach ($issues as $status) {
            if ($status->isNew()) {
                array_push($new, 1);
            }
            if ($status->isInProgress()) {
                array_push($inProgress, 2);
            }
            if ($status->isClosed()) {
                array_push($closed, 3);
            }
        }

        $dataIssues = [
            [
                $tr->trans('condo.report.room.new'), count($new),
            ],
            [
                $tr->trans('condo.report.room.inprogress'), count($inProgress),
            ],
            [
                $tr->trans('condo.report.room.closed'), count($closed),
            ],
        ];

        $ob->series(
            [
                [
                    'center' => ['20%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.feedback'),
                    'data' => $dataFeedback,
                ],
                [
                    'center' => ['50%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.issue'),
                    'data' => $dataIssues,
                ],
                [
                    'center' => ['78%'],
                    'size' => 150,
                    'name' => $tr->trans('condo.label.report.room'),
                    'data' => $dataRoom,
                ],
            ]
        );

        return  $ob;
    }

    /**
     * Add style to label chart.
     *
     * @param Highchart $ob
     *
     * @return Highchart
     */
    private function stypeLabelChartAction($ob)
    {
        $ob->plotOptions->series(
            [
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.name}: {point.y:.0f}',
                ],
            ]
        );

        $ob->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob->tooltip->pointFormat(
            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f} </b>'
        );

        return $ob;
    }
}
