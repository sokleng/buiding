<?php

namespace CondominiumManagementBundle\Controller;

use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Income;
use CondoBundle\Entity\Expend;
use CondoBundle\Entity\Currency;
use DateTime;

/**
 * @Route("/{condominium}/report")
 */
class ReportController extends Controller
{
    use HasCondominiumManagementUtils;
    const CHART_COLUMN = 'column';
    const CHART_LINE = 'line';
    const CONDOMINIUM_REPORT_FILTER = 'condominium_report_filter';
    const CONDOMINIUM_REPORT_FILTER_STATUS = 'condominium_report_filter_status';
    const DAY = 'day';
    const MONTH = 'month';
    const YEAR = 'year';
    const DAY_FORMAT = 'd-M-Y';
    const MONTH_FORMAT = 'M-Y';
    const YEAR_FORMAT = 'Y';
    const DAY_FORMAT_JS = 'd-m-Y';
    const MONTH_FORMAT_JS = 'm-Y';
    const YEAR_FORMAT_JS = ' Y';
    const FROM_TIME = '00:00:00';
    const TO_TIME = '23:59:59';
    const USD = 'USD';
    const CREATION = 'creation';
    const PAID = 'paid';

    /**
     * @Route("/", name="condominium_report_client")
     * @Template("CondominiumManagementBundle:Report:client.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function clientAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $clients = $this->getClientUnitRepository()
            ->findAllUsersForCondominiumAndDate(
                $condominium,
                $from,
                $to
            )
            ->getQuery()
            ->getResult();

        $clientsDateGroup = [];
        foreach ($clients as $key => $value) {
            $date = $value
                ->getStartDate()
                ->format($this->getDMYFormat($showBy));
            $clientsDateGroup[$date][$key] = $value;
        }

        $dates = [];
        $totals = [];
        $peoples = [];
        foreach ($clientsDateGroup as $key => $value) {
            $dates[] = $key;
            $totals[] = count($value);
            $people = 0;
            foreach ($value as $clientUnit) {
                $people += $clientUnit->getAmount();
            }
            $peoples[] = $people;
        }

        $arrClients = [
            'dates' => $dates,
            'totals' => $totals,
            'peoples' => $peoples,
        ];

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.client.clients'),
                'color' => '#337AEE',
                'data' => $totals,
            ],
            [
                'name' => $this->getTrans('condo.label.report.client.total.peoples'),
                'color' => '#d9534f',
                'data' => $peoples,
            ],
        ];
        $chartReport = $this->getChart(
            self::CHART_COLUMN,
            $this->getTrans('condo.label.report.client.title.chart'),
            $dates,
            $series
        );

        $form = $this->getFormFilterRender(
            $showBy,
            $from,
            $to
        );

        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrClients' => $arrClients,
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * @Route("/unit", name="condominium_report_unit")
     * @Template("CondominiumManagementBundle:Report:unit.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function unitAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $units = $this->getUnitRepository()
            ->findAllUnitsForCondominium($condominium)
            ->getQuery()
            ->getResult()
        ;

        $unitsDateGroup = [];
        foreach ($units as $key => $unit) {
            $date = $unit
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $unitsDateGroup[$date][$key] = $unit;
        }

        $arrUnits = $this->getDataForUnit(
            $unitsDateGroup,
            $from,
            $to
        );

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.unit.total.room'),
                'color' => '#337AEE',
                'data' => $arrUnits['totalRooms'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.unit.available'),
                'color' => '#5cb85c',
                'data' => $arrUnits['availables'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.unit.unavailable'),
                'color' => '#d9534f',
                'data' => $arrUnits['unAvailables'],
            ],
        ];

        $chartReport = $this->getChart(
            self::CHART_COLUMN,
            $this->getTrans('condo.report.unit.title.chart'),
            $arrUnits['dates'],
            $series
        );

        $form = $this->getFormFilterRender(
            $showBy,
            $from,
            $to
        );
        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrUnits' => $arrUnits,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * get Data Array for unit for given array unit having date as key.
     *
     * @param array    $unitsDateGroup
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return array
     */
    private function getDataForUnit(
        array $unitsDateGroup,
        DateTime $from,
        DateTime $to
    ) {
        $dates = [];
        $totalRooms = [];
        $availables = [];
        $unAvailables = [];
        foreach ($unitsDateGroup as $key => $units) {
            $dates[] = $key;
            $totalRooms[] = count($units);
            $arrNumAUUnit = $this->getAvailableUnavailableForGivenUnits(
                $units,
                $from,
                $to
            );
            $availables[] = $arrNumAUUnit['available'];
            $unAvailables[] = $arrNumAUUnit['unAvailable'];
        }

        return [
            'dates' => $dates,
            'totalRooms' => $totalRooms,
            'availables' => $availables,
            'unAvailables' => $unAvailables,
        ];
    }

    /**
     * Get number of available and unavailable for given units.
     *
     * @param array    $units
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return array
     */
    private function getAvailableUnavailableForGivenUnits(
        array $units,
        DateTime $from,
        DateTime $to
    ) {
        $available = 0;
        $unAvailable = 0;
        foreach ($units as $index => $unit) {
            $client = $unit->getClientUnits()[0];
            if ($client === null) {
                $available += 1;
            } else {
                $newUnit = $this->getClientUnitRepository()
                    ->findSpecificClientUnitForGivenUnitAndDate(
                        $unit,
                        $from,
                        $to
                    )
                    ->getQuery()
                    ->getResult();
                if (empty($newUnit)) {
                    $available += 1;
                } else {
                    $unAvailable += 1;
                }
            }
        }

        return [
            'available' => $available,
            'unAvailable' => $unAvailable,
        ];
    }

    /**
     * @Route("/issue", name="condominium_report_issue")
     * @Template("CondominiumManagementBundle:Report:issue.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function issueAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $issues = $this->getIssueRepository()
            ->findAllIssueByFromCreationDateToClosingDate(
                $condominium,
                $from,
                $to
        );

        $issuesDateGroup = [];
        foreach ($issues as $key => $issue) {
            $date = $issue
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $issuesDateGroup[$date][$key] = $issue;
        }

        $arrIssues = $this->getIssueValue(
            $issuesDateGroup,
            $from,
            $to
        );

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.issue.beginning'),
                'color' => '#f0ad4e',
                'data' => $arrIssues['begins'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.issue.new'),
                'color' => '#5cb85c',
                'data' => $arrIssues['news'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.issue.closed'),
                'color' => '#d9534f',
                'data' => $arrIssues['closed'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.issue.ending'),
                'color' => '#337AEE',
                'data' => $arrIssues['news'],
            ],
        ];

        $form = $this->getFormFilterRender(
            $showBy,
            $from,
            $to
        );

        $form->handleRequest($request);

        $chartReport = $this->getChart(
            self::CHART_COLUMN,
            $this->getTrans('condo.label.report.issue.title.chart'),
            $arrIssues['dates'],
            $series
        );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'queryData' => $queryData,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'arrIssues' => $arrIssues,
                'chartReport' => $chartReport,
            ]
        );
    }

    /* Get chart column type for given params
     *
     * @return HighChart $issue
     */
    private function getIssueValue(
        array $issuesDateGroup,
        DateTime $from,
        DateTime $to
    ) {
        $begins = [];
        $news = [];
        $closed = [];
        $endings = [];
        $dates = [];
        $begin = 0;

        foreach ($issuesDateGroup as $key => $issue) {
            $dates[] = $key;
            $begin = $this->getIssueRepository()
                ->getCountBegin(new DateTime($key));
            $begins[] = $begin;

            $issueNewAndResolved = $this->getIssueNewAndResolve($issue);

            $news[] = $issueNewAndResolved['new'];
            $closed[] = $issueNewAndResolved['close'];
            $endings[] = $begin + $issueNewAndResolved['new'] - $issueNewAndResolved['close'];
        }

        return [
            'begins' => $begins,
            'news' => $news,
            'closed' => $closed,
            'endings' => $endings,
            'dates' => $dates,
        ];
    }

    /* Get all issue New and Resolved
     *
     * @param $issue
     *
     * @return array
     */
    private function getIssueNewAndResolve($issue)
    {
        $new = 0;
        $close = 0;
        foreach ($issue as $status) {
            $issueDateGroup = $status->getCreationDate();
            $new += 1;

            if ($status->isClosed()) {
                $close += 1;
            }
        }

        return [
            'new' => $new,
            'close' => $close,
        ];
    }

    /**
     * @Route("/feedback", name="condominium_report_feedback")
     * @Template("CondominiumManagementBundle:Report:feedback.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function feedbackAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $feedbacks = $this
            ->getFeedbackRepository()
            ->findAllFeedbackCondominiumAndDate(
                $condominium,
                $from,
                $to
            )
            ->getQuery()
            ->getResult();

        $feedbacksDateGroup = [];
        foreach ($feedbacks as $key => $value) {
            $date = $value
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $feedbacksDateGroup[$date][$key] = $value;
        }

        $arrFeedbacks = $this->getArrayFeedbacks($feedbacksDateGroup);

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.feedback.one.star'),
                'color' => '#337AEE',
                'data' => $arrFeedbacks['oneStarA'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.feedback.two.stars'),
                'color' => '#d9534f',
                'data' => $arrFeedbacks['twoStarsA'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.feedback.three.stars'),
                'color' => '#5cb85c',
                'data' => $arrFeedbacks['threeStarsA'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.feedback.four.stars'),
                'color' => '#f0ad4e',
                'data' => $arrFeedbacks['fourStarsA'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.feedback.five.stars'),
                'color' => '#1aa3ff',
                'data' => $arrFeedbacks['fiveStarsA'],
            ],
        ];
        $chartReport = $this->getChart(
            self::CHART_LINE,
            $this->getTrans('condo.report.feedback.title.chart'),
            $arrFeedbacks['dates'],
            $series
        );

        $form = $this->getFormFilterRender(
            $showBy,
            $from,
            $to
        );
        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrFeedbacks' => $arrFeedbacks,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * @Route("/expend", name="condominium_report_expend")
     * @Template("CondominiumManagementBundle:Report:expend.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function expendAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER_STATUS);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $type = $queryData['type'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $expend = $this
            ->getExpendInvoiceRepository()
            ->findAllExpendCondominiumAndDate(
                $condominium,
                $type,
                $from,
                $to
            )
            ->getQuery()
            ->getResult();

        $expendsDateGroup = [];
        foreach ($expend as $key => $value) {
            $date = $value
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $expendsDateGroup[$date][$key] = $value;
        }

        $arrExpends = $this->getArrayExpends($expendsDateGroup, $condominium);

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.expense.vat'),
                'color' => '#337AEE',
                'data' => $arrExpends['vats'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.expense.sub.total'),
                'color' => '#d9534f',
                'data' => $arrExpends['subTotals'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.expense.grand.total'),
                'color' => '#5cb85c',
                'data' => $arrExpends['grandTotals'],
            ],
        ];
        $chartReport = $this->getChart(
            self::CHART_COLUMN,
            $this->getTrans('condo.report.expense.chart'),
            $arrExpends['dates'],
            $series,
            $condominium->getCurrency()
        );

        $form = $this->getFormFilterStatusRender(
            $showBy,
            $from,
            $to
        );
        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrExpends' => $arrExpends,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * Get array of expend for given expendsGroupByDate.
     *
     * @param array       $expendsDateGroup
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayExpends(
        array $expendsDateGroup,
        Condominium $condominium
    ) {
        $dates = [];
        $vats = [];
        $totalExpneds = [];
        $subTotals = [];
        $grandTotals = [];

        foreach ($expendsDateGroup as $key => $expend) {
            $dates[] = $key;
            $arrTotalExpend = $this->getArrayTotalExpends($expend, $condominium);
            $vats[] = $arrTotalExpend['vat'];
            $totalExpneds[] = count($expend);
            $subTotals[] = $arrTotalExpend['subTotal'];
            $grandTotals[] = $arrTotalExpend['grandTotal'];
        }

        return [
            'dates' => $dates,
            'vats' => $vats,
            'totalExpneds' => $totalExpneds,
            'subTotals' => $subTotals,
            'grandTotals' => $grandTotals,
        ];
    }

    /**
     * Get array of expend for given expend.
     *
     * @param array       $expends
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayTotalExpends(
        array $expends,
        Condominium $condominium
    ) {
        $vat = 0;
        $subTotal = 0;
        $grandTotal = 0;

        foreach ($expends as $expend) {
            $currency = $expend->getCurrency();
            $vatR = $expend->getGrandTotal() - $expend->getSubTotal();
            $subTotalR = $expend->getSubTotal();

            if ($condominium->getCurrency() !== $currency) {
                if ($currency->getCurrency() !== self::USD) {
                    $exchangeRate = $this->getExchangeRate($expend, $currency);
                    $vatR = $vatR / $exchangeRate;
                    $subTotalR = $subTotalR / $exchangeRate;
                }

                $exchangeRate = $this->getExchangeRate($expend, $condominium->getCurrency());
                $vatR = $vatR * $exchangeRate;
                $subTotalR = $subTotalR * $exchangeRate;
            }

            $vat += $vatR;
            $subTotal += $subTotalR;
            $grandTotal += $vatR + $subTotalR;
        }

        return [
            'vat' => $vat,
            'subTotal' => $subTotal,
            'grandTotal' => $grandTotal,
        ];
    }

    /**
     * Get array of feedbacks for given feedbacksGroupByDate.
     *
     * @param array $feedbacksDateGroup
     *
     * @return array
     */
    private function getArrayFeedbacks(array $feedbacksDateGroup)
    {
        $dates = [];
        $oneStarA = [];
        $twoStarsA = [];
        $threeStarsA = [];
        $fourStarsA = [];
        $fiveStarsA = [];

        foreach ($feedbacksDateGroup as $key => $feedbacks) {
            $dates[] = $key;
            $arrTotalFeedback = $this->getArrayTotalStarsFeedbacks($feedbacks);
            $oneStarA[] = $arrTotalFeedback['oneStar'];
            $twoStarsA[] = $arrTotalFeedback['twoStars'];
            $threeStarsA[] = $arrTotalFeedback['threeStars'];
            $fourStarsA[] = $arrTotalFeedback['fourStars'];
            $fiveStarsA[] = $arrTotalFeedback['fiveStars'];
        }

        return [
            'dates' => $dates,
            'oneStarA' => $oneStarA,
            'twoStarsA' => $twoStarsA,
            'threeStarsA' => $threeStarsA,
            'fourStarsA' => $fourStarsA,
            'fiveStarsA' => $fiveStarsA,
        ];
    }

    /**
     * Get array of feedbacks for given feedbacks.
     *
     * @param array $feedbacks
     *
     * @return array
     */
    private function getArrayTotalStarsFeedbacks(array $feedbacks)
    {
        $oneStar = 0;
        $twoStars = 0;
        $threeStars = 0;
        $fourStars = 0;
        $fiveStars = 0;
        foreach ($feedbacks as $feedback) {
            if ($feedback->isVeryLow()) {
                $oneStar += 1;
            }
            if ($feedback->isLow()) {
                $twoStars += 1;
            }
            if ($feedback->isAverage()) {
                $threeStars += 1;
            }
            if ($feedback->isHigh()) {
                $fourStars += 1;
            }
            if ($feedback->isVeryHigh()) {
                $fiveStars += 1;
            }
        }

        return [
            'oneStar' => $oneStar,
            'twoStars' => $twoStars,
            'threeStars' => $threeStars,
            'fourStars' => $fourStars,
            'fiveStars' => $fiveStars,
        ];
    }

    /**
     * @Route("/income", name="condominium_report_income")
     * @Template("CondominiumManagementBundle:Report:income.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function incomeAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER_STATUS);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $type = $queryData['type'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $incomes = $this
            ->getIncomeRepository()
            ->findIncomeByCondominiumAndDate(
                $condominium,
                $type,
                $from,
                $to
            )
            ->getQuery()
            ->getResult();

        $incomesDateGroup = [];
        foreach ($incomes as $key => $value) {
            $date = $value
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $incomesDateGroup[$date][$key] = $value;
        }

        $arrIncomes = $this->getArrayIncomes($incomesDateGroup, $condominium);

        $series = [
            [
                'name' => $this->getTrans('condo.label.report.income.vat'),
                'color' => '#337AEE',
                'data' => $arrIncomes['vat'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.income.sub.total'),
                'color' => '#d9534f',
                'data' => $arrIncomes['subTotal'],
            ],
            [
                'name' => $this->getTrans('condo.label.report.income.grand.total'),
                'color' => '#5cb85c',
                'data' => $arrIncomes['grandTotal'],
            ],
        ];
        $chartReport = $this->getChart(
            self::CHART_COLUMN,
            $this->getTrans('condo.report.income.title.chart'),
            $arrIncomes['dates'],
            $series,
            $condominium->getCurrency()
        );

        $form = $this->getFormFilterStatusRender(
            $showBy,
            $from,
            $to
        );
        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrIncomes' => $arrIncomes,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * Get array of incomes for given incomesGroupByDate.
     *
     * @param array       $incomesDateGroup
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayIncomes(
        array $incomesDateGroup,
        Condominium $condominium
    ) {
        $dates = [];
        $total = [];
        $vat = [];
        $subTotal = [];
        $grandTotal = [];

        foreach ($incomesDateGroup as $key => $incomes) {
            $dates[] = $key;
            $total[] = count($incomes);
            $arrTotalIncome = $this->getArrayTotalStarsIncome($incomes, $condominium);
            $vat[] = $arrTotalIncome['vat'];
            $subTotal[] = $arrTotalIncome['subTotal'];
            $grandTotal[] = $arrTotalIncome['grandTotal'];
        }

        return [
            'dates' => $dates,
            'total' => $total,
            'vat' => $vat,
            'subTotal' => $subTotal,
            'grandTotal' => $grandTotal,
        ];
    }

    /**
     * Get array of incomes for given incomes.
     *
     * @param array       $incomes
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayTotalStarsIncome(
        array $incomes,
        Condominium $condominium
    ) {
        $vat = 0;
        $subTotal = 0;
        $grandTotal = 0;
        foreach ($incomes as $income) {
            $currency = $income->getCurrency();
            $vatR = $income->getGrandTotal() - $income->getSubTotal();
            $subTotalR = $income->getSubTotal();

            if ($condominium->getCurrency() !== $currency) {
                if ($currency->getCurrency() !== self::USD) {
                    $exchangeRate = $this->getExchangeRate($income, $currency);
                    $vatR = $vatR / $exchangeRate;
                    $subTotalR = $subTotalR / $exchangeRate;
                }

                $exchangeRate = $this->getExchangeRate($income, $condominium->getCurrency());
                $vatR = $vatR * $exchangeRate;
                $subTotalR = $subTotalR * $exchangeRate;
            }

            $vat += $vatR;
            $subTotal += $subTotalR;
            $grandTotal += $vatR + $subTotalR;
        }

        return [
            'vat' => $vat,
            'subTotal' => $subTotal,
            'grandTotal' => $grandTotal,
        ];
    }

    /**
     * Get exchangeRate for given income and currency.
     *
     * @param $invoice
     * @param Currency $currency
     *
     * @return float
     */
    private function getExchangeRate(
        $invoice,
        Currency $currency
    ) {
        if ($invoice->getExchangeSetting() === null) {
            return 1;
        }

        return $invoice
            ->getExchangeSetting()
            ->getValue()[$currency->getId()];
    }

    /**
     * @Route("/profit", name="condominium_report_profit")
     * @Template("CondominiumManagementBundle:Report:profit.html.twig")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function profitAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $query = $request->query->get(self::CONDOMINIUM_REPORT_FILTER_STATUS);
        $queryData = $this->getQueryData($query);
        $showBy = $queryData['showBy'];
        $type = $queryData['type'];
        $from = $queryData['from'];
        $to = $queryData['to'];

        $invoices = $this
            ->getInvoiceRepository()
            ->findInvoicesByCondominiumAndDate(
                $condominium,
                $type,
                $from,
                $to
            )
            ->getQuery()
            ->getResult();

        $invoicesDateGroup = [];
        foreach ($invoices as $key => $value) {
            $date = $value
                ->getCreationDate()
                ->format($this->getDMYFormat($showBy));
            $invoicesDateGroup[$date][$key] = $value;
        }

        $arrInvoices = $this->getArrayInvoices($invoicesDateGroup, $condominium);

        $series = [
            [
                'name' => $this->getTrans('condo.label.invoice.income'),
                'color' => '#337AEE',
                'data' => $arrInvoices['income'],
            ],
            [
                'name' => $this->getTrans('condo.label.invoice.expense'),
                'color' => '#d9534f',
                'data' => $arrInvoices['expend'],
            ],
            [
                'name' => $this->getTrans('condo.label.invoice.profit'),
                'color' => '#5cb85c',
                'data' => $arrInvoices['profit'],
            ],
        ];
        $chartReport = $this->getChart(
            self::CHART_LINE,
            $this->getTrans('condo.invoice.profit.title.chart'),
            $arrInvoices['dates'],
            $series,
            $condominium->getCurrency()
        );

        $form = $this->getFormFilterStatusRender(
            $showBy,
            $from,
            $to
        );
        $form->handleRequest($request);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
                'arrInvoices' => $arrInvoices,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
                'queryData' => $queryData,
                'chartReport' => $chartReport,
            ]
        );
    }

    /**
     * Get array of invoices for given invoicesGroupByDate.
     *
     * @param array       $invoicesDateGroup
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayInvoices(
        array $invoicesDateGroup,
         Condominium $condominium
    ) {
        $dates = [];
        $income = [];
        $expend = [];
        $profit = [];

        foreach ($invoicesDateGroup as $key => $invoices) {
            $dates[] = $key;
            $arrTotalInvoice = $this->getArrayTotalInvoice($invoices, $condominium);
            $income[] = $arrTotalInvoice['income'];
            $expend[] = $arrTotalInvoice['expend'];
            $profit[] = $arrTotalInvoice['profit'];
        }

        return [
            'dates' => $dates,
            'income' => $income,
            'expend' => $expend,
            'profit' => $profit,
        ];
    }

    /**
     * Get array of invoices for given invoices.
     *
     * @param array       $invoices
     * @param Condominium $condominium
     *
     * @return array
     */
    private function getArrayTotalInvoice(
        array $invoices,
        Condominium $condominium
    ) {
        $income = 0;
        $expend = 0;
        foreach ($invoices as $invoice) {
            $currency = $invoice->getCurrency();
            $grandTotal = $invoice->getGrandTotal();

            if ($condominium->getCurrency() !== $currency) {
                if ($currency->getCurrency() !== self::USD) {
                    $exchangeRate = $this->getExchangeRate($invoice, $currency);
                    $grandTotal = $grandTotal / $exchangeRate;
                }
                $exchangeRate = $this->getExchangeRate($invoice, $condominium->getCurrency());
                $grandTotal = $grandTotal * $exchangeRate;
            }

            if ($invoice instanceof Income) {
                $income += $grandTotal;
            }

            if ($invoice instanceof Expend) {
                $expend += $grandTotal;
            }
        }

        $profit = $income - $expend;

        return [
            'income' => $income,
            'expend' => $expend,
            'profit' => $profit,
        ];
    }

    /**
     * Split the query string from url to array.
     *
     * @param $query
     *
     * @return array
     */
    private function getQueryData($query)
    {
        $showBy = empty($query['showby']) ? self::DAY : $query['showby'];
        $type = empty($query['type']) ? self::CREATION : $query['type'];

        if ($showBy === self::DAY) {
            $fromTo = $this->getFromToDate(
                self::DAY_FORMAT,
                '-5 days',
                $query
            );
        }

        if ($showBy === self::MONTH) {
            $fromTo = $this->getFromToDate(
                self::MONTH_FORMAT,
                '-5 months',
                $query,
                '1-',
                '31-'
            );
        }

        if ($showBy === self::YEAR) {
            $fromTo = $this->getFromToDate(
                self::YEAR_FORMAT,
                '-5 years',
                $query,
                '1-1-',
                '31-12-'
            );
        }

        $from = new DateTime($fromTo['from']);
        $to = new DateTime($fromTo['to']);

        return [
            'showBy' => $showBy,
            'type' => $type,
            'from' => $from,
            'to' => $to,
        ];
    }

    /**
     * Get date format for given type ex: day, month , year.
     * return date format follow param $type ex: php, js.
     *
     * @param $showBy
     * @param $type
     *
     * @return string
     */
    private function getDMYFormat($showBy, $type = 'php')
    {

        // TODO: find a good way to put date format to view like this 10-oct-2016.
        if ($type === 'js') {
            if ($showBy === self::DAY) {
                return self::DAY_FORMAT_JS;
            }

            if ($showBy === self::MONTH) {
                return self::MONTH_FORMAT_JS;
            }

            if ($showBy === self::YEAR) {
                return self::YEAR_FORMAT_JS;
            }
        }

        if ($showBy === self::DAY) {
            return self::DAY_FORMAT;
        }

        if ($showBy === self::MONTH) {
            return self::MONTH_FORMAT;
        }

        if ($showBy === self::YEAR) {
            return self::YEAR_FORMAT;
        }
    }

    /**
     * Get from and to date for given params.
     *
     * @param $stringFormat
     * @param $stringDate
     * @param $query
     * @param $prefixFrom
     * @param $prefixTo
     *
     * @return array
     */
    private function getFromToDate(
        $stringFormat,
        $stringDate,
        $query,
        $prefixFrom = '',
        $prefixTo = ''
    ) {
        $date = new DateTime();
        $defaultToDate = $date->format($stringFormat);
        $defaultFromDate = $date
                            ->add(date_interval_create_from_date_string($stringDate))
                            ->format($stringFormat);
        $from = empty($query['from']) ? $defaultFromDate : trim($query['from']);
        $to = empty($query['to']) ? $defaultToDate : trim($query['to']);

        $from = $prefixFrom.$from.' '.self::FROM_TIME;
        $to = $prefixTo.$to.' '.self::TO_TIME;

        return [
            'from' => $from,
            'to' => $to,
        ];
    }

    /**
     * Get chart column type for given params.
     *
     * @param $chartTitle
     * @param $chartCategories
     * @param $chartSeries
     * @param Currency $currency
     *
     * @return HighChart $ob
     */
    private function getChart(
        $chartType,
        $chartTitle,
        $chartCategories,
        $chartSeries,
        Currency $currency = null
    ) {
        $ob = new Highchart();
        $ob->chart->type($chartType);
        $ob->chart->renderTo('report');
        $ob->title->text($chartTitle);
        if ($currency !== null) {
            $ob->tooltip->pointFormat('{series.name}: {point.y:.2f} '.$currency->getCurrency());
        }
        $ob->xAxis->categories($chartCategories);
        $ob->yAxis->title([
            'text' => null,
        ]);
        $ob->series($chartSeries);

        return $ob;
    }

    /**
     * Get translate for given translation key.
     *
     * @param string $key
     *
     * @return string
     */
    private function getTrans($key)
    {
        $tr = $this->get('translator');

        return $tr->trans($key);
    }

    /**
     * Get form filter render.
     *
     * @param $showBy
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return string
     */
    private function getFormFilterRender(
        $showBy,
        DateTime $from,
        DateTime $to
    ) {
        return $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumReportFilterType',
            [
                'showBy' => $showBy,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
            ]
        );
    }

    /**
     * Get form filter status render.
     *
     * @param $showBy
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return string
     */
    private function getFormFilterStatusRender(
        $showBy,
        DateTime $from,
        DateTime $to
    ) {
        return $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumReportFilterStatusType',
            [
                'showBy' => $showBy,
                'from' => $from->format($this->getDMYFormat($showBy, 'js')),
                'to' => $to->format($this->getDMYFormat($showBy, 'js')),
            ]
        );
    }
}
