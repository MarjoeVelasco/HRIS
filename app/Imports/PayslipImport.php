<?php
  
namespace App\Imports;
  
use App\Payslip;
use App\Compensations;
use App\Deductions;
use App\NetPays;
use App\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class PayslipImport implements ToModel, WithValidation , WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $month_year;

    public function __construct($month_year)
    {
        $this->month_year = $month_year;
    }

    //Get List of Employee names and ID's

    public function model(array $row)
    {

        $employee_id=$this->getEmployeeId($row['name']);
        
        if($employee_id!="error has occured")
        {
            //Add new payslip record
            $payslip = new Payslip();
            $payslip->employee_id   =  $employee_id;
            $payslip->ref_no        =  $row['ref_no'];
            $payslip->pay_period    =  $this->month_year;
            $payslip->save();

            //Add new compensation record
            $compensation = new Compensations();
            $compensation->payslip_id       = $payslip->id;
            $compensation->basic_pay        = $row['basic_pay'];
            $compensation->representation   = $row['representation'];
            $compensation->transportation   = $row['transportation'];
            $compensation->rep_trans_sum    = $row['rep_trans_sum'];
            $compensation->gross_pay        = $row['gross_pay'];
            $compensation->pera             = $row['pera'];
            $compensation->salary_differential = $row['salary_differential'];
            $compensation->myb_adjustments  = $row['myb_adjustments'];
            $compensation->lates_undertime  = $row['lates_undertime'];
            $compensation->pera_under_diff  = $row['pera_under_diff'];
            $compensation->gross_income     = $row['gross_income'];
            $compensation->save();

            //Add new deduction record
            $deduction = new Deductions();
            $deduction->payslip_id          = $payslip->id;
            $deduction->gsis_insurance      = $row['gsis_insurance'];
            $deduction->gsis_policy_loan    = $row['gsis_policy_loan'];
            $deduction->tax                 = $row['tax'];
            $deduction->philhealth_contri   = $row['philhealth_contri'];
            $deduction->philhealth_diff     = $row['philhealth_diff'];
            $deduction->gsis_conso          = $row['gsis_conso'];
            $deduction->gsis_emergency      = $row['gsis_emergency'];
            $deduction->gsis_computer       = $row['gsis_computer'];
            $deduction->gsis_ins_diff       = $row['gsis_ins_diff'];
            $deduction->pagibig_contri      = $row['pagibig_contri'];
            $deduction->pagibig_mp          = $row['pagibig_mp'];
            $deduction->pagibig_cal         = $row['pagibig_cal'];
            $deduction->pagibig_mp2         = $row['pagibig_mp2'];
            $deduction->gsis_educ           = $row['gsis_educ'];
            $deduction->gfal                = $row['gfal'];
            $deduction->union_dues          = $row['union_dues'];
            $deduction->paluwagan_shares    = $row['paluwagan_shares'];
            $deduction->ilsea_loan          = $row['ilsea_loan'];
            $deduction->paluwagan_loan      = $row['paluwagan_loan'];
            $deduction->total_deduction     = $row['total_deduction'];
            $deduction->save();

            
            //Add new netpays record
            $netpay = new NetPays();
            $netpay->payslip_id    = $payslip->id;
            $netpay->netpay7       =  $row['netpay7'];
            $netpay->netpay15      =  $row['netpay15'];
            $netpay->netpay22      =  $row['netpay22'];
            $netpay->netpay30      =  $row['netpay30'];
            $netpay->total_netpay  =  $row['total_netpay'];
            $netpay->save();

        }

        
          
            


    }

   /*
 //import only data source
    public function sheets(): array
    {
        return [
            'Data Source' =>  $this,
        ];
    }*/

    public function getRowCount()
    {
        return $this->rows;
    }

    //set row validation
    public function rules(): array
    {
        return [
            'name' => 'required',
            'ref_no' => 'required',
            'pay_period' => 'required',   
            'basic_pay' => 'required',        
            'representation' => 'required',
            'transportation' => 'required',
            'rep_trans_sum' => 'required',
            'gross_pay' => 'required',
            'pera' => 'required',
            'salary_differential' => 'required',
            'myb_adjustments' => 'required',
            'lates_undertime' => 'required',
            'pera_under_diff' => 'required',
            'gross_income' => 'required',
            'gsis_insurance' => 'required',
            'gsis_policy_loan' => 'required',
            'tax' => 'required',
            'philhealth_contri' => 'required',
            'philhealth_diff' => 'required',
            'gsis_conso' => 'required',
            'gsis_emergency' => 'required',
            'gsis_computer' => 'required',
            'gsis_ins_diff' => 'required',
            'pagibig_contri' => 'required',
            'pagibig_mp' => 'required',
            'pagibig_cal' => 'required',
            'pagibig_mp2' => 'required',
            'gsis_educ' => 'required',
            'gfal' => 'required',
            'union_dues' => 'required',
            'paluwagan_shares' => 'required',
            'ilsea_loan' => 'required',
            'paluwagan_loan' => 'required',
            'total_deduction' => 'required',
        ];
    }

    

    //check if already exists


    //get Employee ID
    public function getEmployeeId($name)
    {

        //get list of employees with user id
        $temp_employees = Employee::select('lastname','firstname','middlename','extname','employee_id')
        ->get();

        //define empty collection
        $employees = collect([]);

        foreach($temp_employees as $emp)
        {

            $lastname=$emp->lastname;
            $extname=$emp->extname;
            $firstname=$emp->firstname;
            $middlename=$emp->middlename;
            
            if($emp->extname!=NULL || $emp->extname!="")
            {
                $lastname=$emp->lastname.' '.$extname;
            }

            if($emp->middlename!=NULL || $emp->middlename!="")
            {
                $initial = substr($emp->middlename, 0, 1);
                $firstname=$emp->firstname.' '.$initial.'.';
            }

            $fullname = $lastname.', '.$firstname;

            $item= (object) array(
                'name'=>strtoupper($fullname), 
                'employee_id'=>$emp->employee_id,
                );

            $employees->push($item);
        }


        $selected_employee=$employees->where('name',$name)->first();
        
        if($selected_employee!=null)
        {
           $flag = $this->checkPayslip($selected_employee->employee_id,$this->month_year);
            
            if($flag)
            {
                return $selected_employee->employee_id;
            }

            else if(!$flag)
            {
                return "error has occured";
            }
        
        }

        else
        {
            return "error has occured";
        }

        
            
    }

    public function checkPayslip($employee_id,$pay_period)
    {
        $flag= Payslip::select('id')
        ->where('employee_id',$employee_id)
        ->where('pay_period',$pay_period)
        ->first();

        //PAYSLIP ALREADY EXIST
        if($flag!=null)
        {
            return false;
        }
        
        //NO PAYSLIP RECORD YET
        else
        {
            return true;
        }

    }




}