<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Response;

define('source_path', 'app/report/source/MyReports/src');
define('compiled_path', 'app/report/compiled');
define('output_path', 'app/report/output');

class JasperController extends Controller
{
    static $reports = ["expense"=>"expense"];
    static function read_files($path){
        $files = [];
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isFile()) {
                $files[$file->getFilename()] = $path."/".$file->getFilename();
            }
        }
        return $files;
    }
    static function UpdateReports($file = ""){
        $path = storage_path(source_path);
        if($file != ""){
            $file .= ".jrxml";
            $file = $path."/".$file;
            if (file_exists($file)) {
                JasperController::compile($file);
                return "Build Successfully!";
            }else{
                return "Sorry! no Report file Found in ". $path;
            }
        }else{
            $reports = JasperController::read_files($path);
            foreach($reports as $key=>$report){
                JasperController::compile($report);
            }
            return "All Reports Build Successfully!";
        }
    }
    static function compile($input){
        $output = storage_path(compiled_path);    
        $jasper = new PHPJasper;
        $jasper->compile($input,$output)->execute();
    }
    static function dir_check($path){
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }
    static function pdf($path,$filename){
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }
    static function xlsx($path,$filename){
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }
    static function csv($path,$filename){
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }
    static function html($path,$filename){
        // dd(str_replace('.html', '', $filename));
        $content = file_get_contents($path);
        return view("report.jasper.preview")
        ->with('preview',$content)
        ->with('filter',str_replace('.html', '', $filename));
    }
    static function jasper($input,$output,$options,$template,$ext){
        $jasper = new PHPJasper;
        $resp = $jasper->process(
            $input,
            $output,
            $options
        )->execute();
        $filename = $template.'.'.$ext;
        if($resp == []){
            switch ($ext){
                case "pdf":
                    return JasperController::pdf($output.'/'.$filename,$filename);
                    break;
                case "xlsx":
                    return JasperController::xlsx($output.'/'.$filename,$filename);
                    break;
                case "csv":
                    return JasperController::csv($output.'/'.$filename,$filename);
                    break;
                case "html":
                    return JasperController::html($output.'/'.$filename,$filename);
                    break;

            }
        }
    }
    public static function report($report,$ext = "pdf"){
        $src = storage_path(source_path);
        $reports = JasperController::read_files($src);

        if(!isset($reports[$report.".jrxml"])){
            abort(404, 'Unauthorized action.');
            return false;
        }

        // dd($reports,$template);
        $user = auth()->user();
        $user_id = $user->id;
        // $business_id = $user->business_id;

        $input = storage_path(compiled_path).'/'.$report.'.jasper';  

        $output = JasperController::dir_check(storage_path(output_path)."/user_".$user_id);  
        $options = [
            'locale' => 'en',
            'db_connection' => [
                'driver' => env('JASPER_DRIVER'),
                'username' => env('DB_USERNAME'),
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'port' => env('DB_PORT')
            ]
        ];
        if(env('DB_PASSWORD') != null ||  env('DB_PASSWORD') != ""){
            $options["db_connection"]["password"] = env('DB_PASSWORD');
        }

        try {
            return JasperReportsController::{$report}($input,$output,$options,$report,$ext,$user);
        } catch (\Exception $e) {
            // return redirect()->route('home')->with('error',$e->getMessage());
            return redirect()->back()->with('error',$e->getMessage());
        }
        // dd($options);
        // switch($report){
        //     case "users":{
        //         return JasperReportsController::users($input,$output,$options,$report,$ext,$user);
        //     }break;
        //     case "promotional":{
        //         return JasperReportsController::promotional($input,$output,$options,$report,$ext,$user);
        //     }break;
        //     case "all_requests":{
        //         return JasperReportsController::all_requests($input,$output,$options,$report,$ext,$user);
        //     }break;
        //     case "hello_world":{
        //         return JasperReportsController::hello_world($input,$output,$options,$report,$ext,$user);
        //     }break;
        // }
        
    }
    

    
    //for report testing console
    static function console(){
        $in = "E:/xampp8.2\htdocs\UltimatePOS\storage\app/report\compiled/expense.jasper";
        $out = "E:/xampp8.2\htdocs\UltimatePOS\storage\app/report\output\business_1\user_1";
        $op = [
            "format" => ["pdf"],
            'locale' => 'en',
            'params' => ["business_id" => 1],
            'db_connection' => [
                'driver' => env('DB_CONNECTION'),
                'username' => env('DB_USERNAME'),
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'port' => env('DB_PORT')
            ]
        ];
        $tp = "expense";
        $jasper = new PHPJasper;
        return Response::json($jasper->process(
            $in,
            $out,
            $op
        )->execute());
    }
}

