
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchoolLabController extends Controller
{
    private function modules()
    {
        return [
            'laboratories' => [
                'table' => 'laboratories',
                'title' => 'Laboratories',
                'fields' => ['name', 'location', 'capacity', 'status'],
            ],
            'equipment' => [
                'table' => 'equipment',
                'title' => 'Equipment',
                'fields' => ['name', 'laboratory_id', 'quantity', 'status'],
            ],
            'students' => [
                'table' => 'students',
                'title' => 'Students',
                'fields' => ['student_id', 'name', 'course', 'year_level'],
            ],
            'reservations' => [
                'table' => 'reservations',
                'title' => 'Reservations',
                'fields' => ['student_id', 'laboratory_id', 'reservation_date', 'purpose', 'status'],
            ],
            'maintenance' => [
                'table' => 'maintenance',
                'title' => 'Maintenance',
                'fields' => ['equipment_id', 'description', 'maintenance_date', 'status'],
            ],
        ];
    }

    private function module($module)
    {
        abort_unless(isset($this->modules()[$module]), 404);

        return $this->modules()[$module];
    }

    public function index($module)
    {
        $config = $this->module($module);
        $records = DB::table($config['table'])->orderByDesc('id')->get();

        return view('schoollab.index', compact('module', 'config', 'records'));
    }

    public function create($module)
    {
        $config = $this->module($module);
        $record = null;

        return view('schoollab.form', compact('module', 'config', 'record'));
    }

    public function store(Request $request, $module)
    {
        $config = $this->module($module);
        $data = $request->validate(
            $this->rules($config['fields'])
        );

        DB::table($config['table'])->insert($data);

        return redirect()->route('schoollab.index', $module)
            ->with('success', 'Record added successfully!');
    }

    public function edit($module, $id)
    {
        $config = $this->module($module);
        $record = DB::table($config['table'])->find($id);

        abort_if(!$record, 404);

        return view('schoollab.form', compact('module', 'config', 'record'));
    }

    public function update(Request $request, $module, $id)
    {
        $config = $this->module($module);
        $record = DB::table($config['table'])->find($id);

        abort_if(!$record, 404);

        $data = $request->validate(
            $this->rules($config['fields'])
        );

        DB::table($config['table'])->where('id', $id)->update($data);

        return redirect()->route('schoollab.index', $module)
            ->with('success', 'Record updated successfully!');
    }

    public function destroy($module, $id)
    {
        $config = $this->module($module);

        abort_if(!DB::table($config['table'])->where('id', $id)->exists(), 404);

        DB::table($config['table'])->where('id', $id)->delete();

        return redirect()->route('schoollab.index', $module)
            ->with('success', 'Record deleted successfully!');
    }

    private function rules($fields)
    {
        $rules = [];

        foreach ($fields as $field) {
            $rules[$field] = 'required|string|max:255';
        }

        foreach (['capacity', 'quantity', 'year_level',
                  'laboratory_id', 'student_id', 'equipment_id'] as $field) {
            if (in_array($field, $fields)) {
                $rules[$field] = 'required|integer|min:1';
            }
        }

        foreach (['reservation_date', 'maintenance_date'] as $field) {
            if (in_array($field, $fields)) {
                $rules[$field] = 'required|date';
            }
        }

        return $rules;
    }
}
