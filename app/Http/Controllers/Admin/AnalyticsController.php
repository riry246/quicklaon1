<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpensesAnalysis;
use App\Models\Factor;
use App\Models\SaccLoanAnalysis;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    use GeneralTrait;

    private $module_name = 'Analytics';
    private $module = 'analytics';
    private $url = 'analytics.index';


    public function index($slug = null)
    {
        $this->module_name = ucfirst($slug) . ' Factor';
        $data['topic'] = $slug;
        // Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, ucfirst($slug), $this->url, null);

        $list = Factor::where('type', $slug)->get();

        $data['list'] = $list;

        return view('admin.general.anyltic', $data);
    }

    public function store(Request $request)
    {

        $slug = $request->input('factor');

        $factor = new Factor();
        $factor->value = $request->input('value');
        $factor->type = $slug;
        $factor->group_name = $request->input('group_name') ?? null;
        $factor->save();

        return redirect()->back()->with('success', ucfirst($slug) . ' factor has been saved successfully.');

    }

    public function delete($slug, $id)
    {
        $model = Factor::find($id);

        // Check if the model exists
        if (!$model) {
            return redirect()->back()->with('error', ucfirst($slug) . ' factor not found.');
        }

        // Delete the model
        $model->delete();

        return redirect()->back()->with('success', ucfirst($slug) . ' factor has been deleted successfully.');
    }
}
