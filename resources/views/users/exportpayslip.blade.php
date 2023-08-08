<style>
.payslip-data
{
    text-align: right;
}

* {
  font-family: Arial, Helvetica, sans-serif;
  font-size:0.95em;
}

</style>

<table class="table" style="padding:1px;border: 1px solid black;width:600px;" cellspacing="0">

    <tr>
        <td colspan="9" style="border-bottom: 1px solid black;">
            <center>
            <p>REPUBLIC OF THE PHILIPPINES<br>
            Department of Labor and Employment<br>
            <b>INSTITUTE FOR LABOR STUDIES<br>
            PAYSLIP</b>
            </p>
            </center>
        </td>
    </tr >
    
    <tr>
        <td style="width:250px;">NAME</td>
        <td>    </td>
        <td>    </td>
        
        <td  colspan="2" style="border-left: 1px solid black;">Ref No.:</td> 
        <td>  </td>
      
        <td colspan="3">{{$payslips->ref_no}}</td>
       
    </tr>

    <tr>
        <td colspan="3" style="border-bottom: 1px solid black;"><center><b>{{Str::upper($employees->lastname)}} {{Str::upper($employees->extname)}}, {{Str::upper($employees->firstname)}} {{substr($employees->middlename, 0, 1)}}</b></center></td>   
        <td colspan="3" style="border-left: 1px solid black;border-bottom: 1px solid black;">PAY-PERIOD:</td> 
        <td style="border-bottom: 1px solid black;" colspan="3">{{$payslips->pay_period}}</td>
    </tr>

    <tr>
        <td style="padding-left:25px;" colspan="3"><b>BASIC PAY</b></td>
        <td> </td>
        <td  style="width:30px;"> </td>
        <td> </td> 
        <td>&nbsp;&nbsp;&nbsp;&nbsp;P</td>
        <td class="payslip-data"><b>{{ number_format($compensations->basic_pay,2) }}</b></td>
        <td style="width:50px;"> </td> 
    </tr>

    <tr>
        <td style="padding-left:25px;" colspan="3">Add</td>
        <td> </td>
        <td> </td>
        <td> </td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Representation</td>
        <td> </td>
        <td>P</td>
        <td class="payslip-data">{{ number_format($compensations->representation,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>


    <tr>
        <td style="padding-left:50px;" colspan="3">Transportation</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data" style="border-bottom: 1px solid black;">{{ number_format($compensations->transportation,2) }}</td> 
        <td> </td>
        <td class="payslip-data" style="border-bottom: 1px solid black;">{{ number_format($compensations->rep_trans_sum,2) }}</td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:150px;" colspan="3"><b>Gross Pay</b></td>
        <td> </td>
        <td> </td>
        <td> </td> 
        <td>&nbsp;&nbsp;&nbsp;&nbsp;P</td>
        <td class="payslip-data">{{ number_format($compensations->gross_pay,2) }}</td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:25px;" colspan="3">Add PERA/A.O. 53</td>
        <td> </td>
        <td>P</td>
        <td class="payslip-data">{{ number_format($compensations->pera,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Salary Differential</td>
        <td> </td>
        <td></td>
        <td class="payslip-data">{{ number_format($compensations->salary_differential,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
        
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">MYB Adjustments</td>
        <td> </td>
        <td></td>
        <td class="payslip-data">{{ number_format($compensations->myb_adjustments,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Less: Lates/Undertimes</td>
        <td> </td>
        <td></td>
        <td style="border-bottom: 1px solid black;" class="payslip-data">{{ number_format($compensations->lates_undertime,2) }}</td> 
        <td> </td>
        <td style="border-bottom: 1px solid black;" class="payslip-data">{{ number_format($compensations->pera_under_diff,2) }}</td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:150px;" colspan="3"><b>Gross Income</b></td>
        <td> </td>
        <td> </td>
        <td> </td> 
        <td>&nbsp;&nbsp;&nbsp;&nbsp;P</td>
        <td class="payslip-data">{{ number_format($compensations->gross_income,2) }}</td>
        <td> </td>
        
    </tr>

    <tr>
        <td style="padding-left:25px;" colspan="3"><b>DEDUCTIONS</b></td>
        <td> </td>
        <td> </td>
        <td> </td> 
        <td> </td>
        <td> </td>
        <td> </td> 
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Life & Retirement Ins. Prem.</td>
        <td> </td>
        <td>P</td>
        <td class="payslip-data">{{ number_format($deductions->gsis_insurance,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Policy Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_policy_loan,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">W/Holding Tax</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->tax,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Philhealth Contributions</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->philhealth_contri,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Philhealth Contributions-Differential</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->philhealth_diff,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Consolidated Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_conso,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Emergency Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_emergency,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Computer Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_computer,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Life and Retirement Differential</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_ins_diff,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">PAG IBIG Contributions</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->pagibig_contri,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">PAG IBIG Multi Purpose Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->pagibig_mp,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">PAG IBIG Calamity Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->pagibig_cal,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">PAG IBIG MP2</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->pagibig_mp2,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GSIS Educational Assistance</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gsis_educ,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">GFAL</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->gfal,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Union Dues</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->union_dues,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Paluwagan Shares</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->paluwagan_shares,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">ILSEA Short-term Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->ilsea_loan,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">Paluwagan Loan</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data" style="border-bottom: 1px solid black;">{{ number_format($deductions->paluwagan_loan,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:150px;" colspan="3"><b>Total Deductions</b></td>
        <td> </td>
        <td> </td>
        <td> </td> 
        <td> </td>
        <td class="payslip-data">{{ number_format($deductions->total_deduction,2) }}</td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">7th NET PAY</td>
        <td> </td>
        <td>P</td>
        <td class="payslip-data">{{ number_format($netpays->netpay7,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">15th NET PAY</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($netpays->netpay15,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">22nd NET PAY</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($netpays->netpay22,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>

    <tr>
        <td style="padding-left:50px;" colspan="3">30th NET PAY</td>
        <td> </td>
        <td> </td>
        <td class="payslip-data">{{ number_format($netpays->netpay30,2) }}</td> 
        <td> </td>
        <td> </td>
        <td> </td>
    </tr> 
    
    <tr>
        <td style="padding-left:25px;border-top: 1px solid black;border-bottom: 1px solid black;" colspan="3"><b>TOTAL NET PAY</b></td>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;"> </td>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;"> </td>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;"> </td> 
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;P</td>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;" class="payslip-data"><b>{{ number_format($netpays->total_netpay,2) }}</b></td>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;"> </td> 
    </tr>

    <tr>
        <td colspan="3" style="padding-left:25px;">RECEIVED BY/DATE :</td>
        <td colspan="6" style="border-left: 1px solid black;">AUTHENTICATED BY:</td> 
        
    </tr>

    <tr>
        <td colspan="3" > </td>
        <td colspan="6" style="border-left: 1px solid black;text-align: center;">
        <img src="{{ public_path('images/signature.jpg') }}" height="50px">
        </td> 
        
    </tr>

    <tr>
        <td colspan="3" > </td>
        <td colspan="6" style="border-left: 1px solid black;text-align: center;">RENITA JOAN M. ROBLES  </td> 
        
    </tr>

    <tr>
        <td colspan="3" > </td>
        <td colspan="6" style="border-left: 1px solid black;text-align: center;"><i>Administrative Officer III</i></td> 
        
    </tr>


    
    </table>