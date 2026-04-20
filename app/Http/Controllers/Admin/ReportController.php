<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportType;
use App\Traits\CohortReportTrait;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\ReportDetailTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait, ReportDetailTrait, CohortReportTrait;

    private $module_name = 'Report';
    private $title = 'Report';
    private $module = 'report';
    private $url = 'report';

    public function index()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Generate Report', $this->url, null);

        $report_type = ReportType::get();

        //Form Builder
        $data['form'] = $this->reportForm('report.submit', null, $report_type);

        return view('admin.general.form', $data);
    }

    public function submit(Request $request)
    {

        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
            'report_type' => 'required',
        ], [
            'date_from.required' => 'Date form field is required',
            'date_to.required' => 'Date to field is required',
            'report_type.required' => 'Report Type field is required',
        ]);


        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $report_id = $request->report_type;

        $data['report_type'] = ReportType::find($report_id);
        $reportType = $data['report_type']->slug;

        $data['breadcrumb'] = $this->breadcrumb($data['report_type']->name, $this->module_name, $this->url, null);


        $data['report'] = $this->$reportType($date_from, $date_to);

        $data['date'] = array(
            'dateForm' => $date_from,
            'dateTo' => $date_to,
        );

        return view('admin.reports.detail', $data);

    }
    public function cohort()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb('Cohort Report', 'Generate Cohort Report', $this->url, null);

        //Form Builder
        $data['form'] = $this->cohortReportForm('report.cohort.submit', null);

        return view('admin.general.form', $data);
    }
    public function cohortSubmit(Request $request)
    {

        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
            'start_from' => 'required',
            'number_of_loans' => 'required',
        ], [
            'date_from.required' => 'Date form field is required',
            'date_to.required' => 'Date to field is required',
            'number_of_loans.required' => 'Numnber of loans field is required',
            'start_from.required' => 'Start From field is required',
        ]);


        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $start_from = $request->start_from;
        $number_of_loans = $request->number_of_loans;

        $this->module_name = 'Cohort Analysis for Bad Debts as on '. $date_from .' to ' .$date_to;
        $data['breadcrumb'] = $this->breadcrumb('Cohort Report', $this->module_name, $this->url, null);

        $data['report'] = $this->generateCohortReport($date_from, $date_to, $start_from, $number_of_loans);

        $data['date'] = array(
            'dateForm' => $date_from,
            'dateTo' => $date_to,
        );

        return view('admin.cohort.detail', $data);

    }

}