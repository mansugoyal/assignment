<?php

namespace App\Http\Controllers;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function __construct(){
        $this->middleware('check_seret');
    }

    public function add_emp(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'salary' => 'required',
            'bonus' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['result' => "0", 'message' => $errors[0]]);
        }

        $employee = Employee::create([
            'name'=> $request->name,
            'email' => $request->email,
            'salary' => $request->salary,
            'bonus' => $request->bonus,
        ]);

        return response()->json(['result' => "1", 'message' => 'Your Employee Successfully Created!!']);

    }

    public function monthly_data(){
        $total_emp_slry = Employee::pluck('salary')->sum();
        $all_emp = Employee::all();
        $bonus = 0;
        foreach ($all_emp as $all){
            $emp_bonus = ($all->salary*$all->bonus)/100;
            $bonus +=  $emp_bonus;
        }
        return $re = array(
            'Month' =>date('M'),
            'Salaries_payment_day' => date("d", strtotime($this->calculate_salary())),
            'Bonus_payment_day' => date("d",strtotime($this->bonus())),
            'Salaries_total' => $total_emp_slry,
            'Bonus_total' => $bonus,
            'Payment_total' => $total_emp_slry+$bonus,
        );
    }

    public function calculate_salary(){
        $month = date('M');
        $last_date = $this->get_last_date(date('d-m-Y'));
        $salary_date = ($this->is_friday($last_date) || $this->is_weekend($last_date)) ? $this->month_last_saturday() : $last_date;
        return $salary_date;
    }

    public function bonus(){
        $date = "15-".date('m-Y');
        return $bonus_date = $this->is_weekend($date) ? $this->specific_date_next_thursday($date)  : $date;
    }

    public function specific_date_next_thursday($date){
        return $next_monday = date('d-m-Y', strtotime("next thursday", strtotime($date)));
    }

    public function month_last_saturday(){
        $date = strtotime('last thu of this month');
        return date('d-m-Y', $date);
    }

    public function get_last_date($date){
        return date("t-m-Y", strtotime($date));
    }

    public function is_friday($date){
        $unixTimestamp = strtotime($date);
        $day = date("l", $unixTimestamp);
        if($day != 'Friday'){
            return false;
        }
        return true;
    }

    public function is_weekend($date){
        $unixTimestamp = strtotime($date);
        $day = date("l", $unixTimestamp);
        if($day == 'Saturday' || $day == 'Sunday'){
            return true;
        }
        return false;
    }
}
