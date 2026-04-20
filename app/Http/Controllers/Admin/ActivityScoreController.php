<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityScore;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityScoreController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Activity Scores';
    private $module = 'activity.score';
    private $url = 'activity.score.index';
    public function index(Request $request)
    {
        $data['tabel_fields'] = array(
            'id',
            'name',
            'score',
        );

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Activity Score', $this->url, null);

        //Get Question data
        $data['list'] = ActivityScore::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);

    }
    public function create()
    {
        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Create Activity Score', $this->url, null);

        //Form Builder
        $data['form'] = $this->scoreForm('activity.score.store', null);

        return view('admin.general.form', $data);
    }
    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'score' => 'required'
            ],
            [
                'name.required' => 'Name field is required',
                'score.required' => 'Score field is required'

            ]
        );
        $terms = new ActivityScore();
        $terms->name = $data['name'];
        $terms->slug = Str::slug($data['name']);
        $terms->score = $data['score'];
        $terms->save();

        return redirect()->route('activity.score.index')->with('success', 'Activity Score "' . $data['name'] . '" created successfully!');
    }
    public function edit($id)
    {
        $group = ActivityScore::findOrFail($id);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Activity Score Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->scoreForm('activity.score.update', $group);


        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $request->validate(
            [
                'name' => 'required|max:255',
                'score' => 'required'
            ],
            [
                'name.required' => 'Name field is required',
                'score.required' => 'Score field is required'

            ]
        );

        $terms = ActivityScore::findOrFail($id);
        $terms->name = $data['name'];
        $terms->slug = Str::slug($data['name']);
        $terms->score = $data['score'];
        $terms->save();

        return redirect()->route('activity.score.index')->with('success', 'Activity Score "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $term = ActivityScore::findOrFail($id);

        if (!$term) {
            return redirect()->route('activity.score.index')->with('error', 'Something went wrong');
        }

        $term->delete();

        return redirect()->route('activity.score.index')->with('success', '"' . $term->name . '" deleted successfully!');
    }
}
