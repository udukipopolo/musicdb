<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageBulkRegistrationController extends Controller
{
    public function index()
    {
        $params = [];

        return view('manage.bulk.regist.index', $params);
    }

    public function csv(Request $request)
    {

    }
}
