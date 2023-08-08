<?php

namespace App\Exports;
use DB;
use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceExportOther implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    private $to;
    private $from;
    private $division;
    private $table_name;

    public function __construct($division,$from,$to,$table_name)
    {
        $this->division = $division;
        $this->from = $from;
        $this->to = $to;
        $this->table_name = $table_name;
    }

    public function sheets(): array
    {

        $employees=DB::table('employees')
        ->join('attendances','attendances.employee_id','=','employees.employee_id')
        ->join('users','users.id','=','employees.employee_id')
        ->where('users.is_disabled','0')
        ->where('employees.division','=',$this->division)
        ->select('employees.employee_id')
        ->distinct()
        ->get()->toArray();

    $sheets = [];    
    if(empty($employees))
    {
        $sheets[] = new AttendanceOtherSheets("No records found", $this->from,$this->to);
    }
    
    else
    {
        for ($i = 0; $i <= sizeof($employees)-1; $i++) {
            $sheets[] = new AttendanceOtherSheets($employees[$i]->employee_id, $this->from,$this->to,$this->table_name);
        }
    }

    return $sheets;
        

    }

}
