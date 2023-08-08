<?php

namespace App\Exports;
use DB;
use App\Errorlog;
use App\Voters;
use App\Employee;
use App\User;
use App\Categories;
use App\Ballots;
use App\Forms;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormResponsesExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }


    public function query() {
        return Ballots::query()
                ->join('employees','ballots.user_id','=','employees.employee_id')
                ->join('forms','ballots.form_id','=','forms.id')
                ->join('categories','ballots.category_id','=','categories.id')
                ->select('ballots.ballot_number','forms.title as form_title','categories.title','employees.lastname','employees.firstname','ballots.created_at') 
                ->orderBy('categories.id', 'ASC')
                ->where('forms.id',$this->id);               

    }

    public function headings(): array
    {
        return [
            'Ballot Number',
            'Form',
            'Category',
            'Lastname',
            'Firstname',
            'Created_at',
        ];
    }
}
