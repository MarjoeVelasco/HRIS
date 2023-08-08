<?php

namespace App\Exports;
use DB;
use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;



class CommunicationUtilityExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    private $to;
    private $from;
    private $division;
    private $month_year;

    public function __construct($division,$from,$to,$month_year)
    {
        $this->division = $division;
        $this->from = $from;
        $this->to = $to;
        $this->month_year = $month_year;
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
        $sheets[] = new CommunicationUtilitySheets("No records found", $this->month_year,$this->from,$this->to);
    }
    
    else
    {
        for ($i = 0; $i <= sizeof($employees)-1; $i++) {
            $sheets[] = new CommunicationUtilitySheets($employees[$i]->employee_id, $this->month_year,$this->from,$this->to);
        }
    }



    return $sheets;
        

    }

    

    

}
