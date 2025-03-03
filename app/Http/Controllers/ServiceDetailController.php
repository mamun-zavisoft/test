<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceChart;
use App\Models\ServiceDetail;
use App\Actions\FetchServiceDetail;
use App\Models\Vehicle;

class ServiceDetailController extends Controller
{
    public function index(Request $request)
    {
        $serviceDetails = (new FetchServiceDetail)->execute($request);

        $service = Service::with('vehicle')->select('id','service_type','discount','grand_total','total_amount','any_parts_purchase')->get();
        $serviceChart = ServiceChart::select('id','name','price','code')->get();

        if ($request->ajax())
        {
            return view('components.serviceDetails.table', ['entity'=>$serviceDetails])->render();
        }

        return view('backend.serviceDetails.index', compact('serviceDetails','service','serviceChart'));
    }
    
}
