<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JasperReportsController extends Controller
{
    static function read_filter_fields(){
        
        return self::seprate_daterange($_GET);
    }
    static function seprate_daterange($fields){
        $new_fields = [];
        foreach($fields as $key => $value){
            if($key == 'filter_date_range'){
                $range = explode(" - ", $value);
                if(isset($range[0]) && isset($range[1])){
                    $new_fields['start_date'] = $range[0];
                    $new_fields['end_date'] = $range[1];
                }
            }else{
                $new_fields[$key] = $value;
            }
        }
        return $new_fields;
    }
    //reports -----------------------------------------------------------
    static function hello_world($input,$output,$options,$template,$ext,$user){
        // $options["format"] =[ "pdf"];
        // $ext = "pdf";
        return JasperController::jasper($input,$output,$options,$template,$ext);
    }

    static function promotional($input,$output,$options,$template,$ext,$user){
        // $daterange = [
        //     "start_date" => isset($_GET["start_date"])?$_GET["start_date"]:date('Y-m-d'),
        //     "end_date" => isset($_GET["end_date"])?$_GET["end_date"]:date("Y-m-d")
        // ];
        $options["params"] = self::read_filter_fields();

        $options["format"] = [$ext];
        // dd($options);
        return JasperController::jasper($input,$output,$options,$template,$ext);
    }
    static function all_requests($input,$output,$options,$template,$ext,$user){
        // dd($_SERVER);
        // $daterange = [
        //     "start_date" => isset($_GET["start_date"])?$_GET["start_date"]:NULL,
        //     "end_date" => isset($_GET["end_date"])?$_GET["end_date"]:NULL
        // ];
        $options["params"] = self::read_filter_fields();

        // $options["params"] =[ 
        //     "request_no" => NULL,
        //     "category_id" =>NULL,
        //     "end_date" => $daterange["start_date"],
        //     "end_date" => $daterange["end_date"]
        // ];
        $options["format"] = [$ext];
        // dd($options);
        return JasperController::jasper($input,$output,$options,$template,$ext);

        // $P = '';
        // $query = `SELECT
        //     r.id,
        //     r.request_no,
        //     r.total_expense request_expense,
        //     r.approved_expense,
        //     r.status request_status,
        //     rc.name AS category,
        //     CONCAT(COALESCE(rb.employee_id, ''), ' ', rb.name, ' ', COALESCE(rb.lname, '')) AS requested_by,
        //     CONCAT(COALESCE(cb.employee_id, ''), ' ', cb.name, ' ', COALESCE(cb.lname, '')) AS created_by,
        //     CONCAT(COALESCE(ub.employee_id, ''), ' ', ub.name, ' ', COALESCE(ub.lname, '')) AS updated_by
        // FROM requests AS r
        //     LEFT JOIN 
        //     request_categories AS rc ON r.request_category_id = rc.id
        // LEFT JOIN 
        //     users AS rb ON r.requested_by = rb.id
        // LEFT JOIN 
        //     users AS cb ON r.created_by = cb.id
        // LEFT JOIN 
        //     users AS ub ON r.updated_by = ub.id
        // WHERE 
        //     (   $P{request_no} IS NULL  OR r.request_no = $P{request_no}) 
        //     AND
        //     (   $P{category_id} IS NULL   OR rc.id = $P{category_id}) 
        // ORDER BY id DESC`;
    }
    static function users($input,$output,$options,$template,$ext,$user){
       
        // $_GET
        $options["params"] =[ 
            // "business_id" => $user->business_id,
            // "start_date" => $daterange["start_date"],
            // "end_date" => $daterange["end_date"]
        ];
        $options["format"] = [$ext];
        // dd($options);
        return JasperController::jasper($input,$output,$options,$template,$ext);
    }
    // static function expense($input,$output,$options,$template,$ext,$user){
    //     $daterange = [
    //         "start_date" => isset($_GET["start_date"])?$_GET["start_date"]:date('Y-m-d'),
    //         "end_date" => isset($_GET["end_date"])?$_GET["end_date"]:date("Y-m-d")
    //     ];
    //     $options["params"] =[ 
    //         "business_id" => $user->business_id,
    //         "start_date" => $daterange["start_date"],
    //         "end_date" => $daterange["end_date"]
    //     ];
    //     $options["format"] = [$ext];
    //     // dd($options);
    //     return JasperController::jasper($input,$output,$options,$template,$ext);
    // }
}
