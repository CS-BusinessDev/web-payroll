<?php

namespace App\Http\Controllers;

use App\Exports\SalaryExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportSalary(Request $request)
    {
        $periode = $request['periode'];
        $filename = 'report_salary_' . $periode . '.xlsx';
        return Excel::download(new SalaryExport($periode), $filename);
        // return redirect()->back();
    }
}
