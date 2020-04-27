<?php

namespace App\Http\Controllers\BladesControllers;

use App\Http\Controllers\Controller;
use App\Imports\SuspiciousOrganizationExcelImport;
use App\Imports\TwoColumnExcelImport;
use App\Imports\XmlImport;
use App\Exports\SuspectsExport;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class XmlExcelImporterController extends Controller
{

    public function importExportView()
    {
        return view('import');
    }

    public function export()
    {
        return Excel::download(new SuspectsExport, 'suspects.xlsx');
    }

    public function import()
    {
        Excel::import(new ExcelImport,request()->file('file'));

        return back();
    }

    public function importOrganizations()
    {
        Excel::import(new SuspiciousOrganizationExcelImport,request()->file('file'));

        return back();
    }

    public function importOtherExcels()
    {
        Excel::import(new TwoColumnExcelImport,request()->file('file'));

        return back();
    }

    public function importXml(Request $request)
    {
        XmlImport::import($request);

        return back();
    }



}
