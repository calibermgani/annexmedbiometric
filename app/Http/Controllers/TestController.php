<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        // try {
        //     DB::connection('mysql')->getPDO();
        //     echo DB::connection()->getDatabaseName();
        //     } catch (Exception $e) {
        //     echo 'None' . $e->getMessage();
        // }

        try {
            // Specify the connection name in the connection() method
            DB::connection('mysql')->getPDO();

            // Connection successful, perform operations here

            echo 'Connected to the second database successfully!';
        } catch (Exception $e) {
            // Connection failed, handle the exception
            echo 'Connection to the second database failed: <pre>' . $e . '</pre>';
        }

        // try {
        //     $mssqlData = DB::connection('sqlsrv')->table('netXs.dbo.Trans')->take(1)->get();

        //     foreach ($mssqlData as $data) {
        //         DB::connection('mysql')->table('trans')->insert((array) $data);
        //     }

        //     $this->info('Data migrated successfully.');

        // } catch (Exception $e) {
        //     echo 'None' . $e->getMessage();
        // }

    }
}
