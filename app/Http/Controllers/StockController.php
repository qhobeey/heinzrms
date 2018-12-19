<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\IssueStock;
use App\EnumGcr;
use App\Accountant;
use App\Collector;
use App\Cashier;

class StockController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::latest()->get();
        return view('console.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('console.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'stock_type' => 'required',
            'voucher' => 'required',
            'min_serial' => 'required',
            'max_serial' => 'required',
            'date' => 'required',
            'quantity' => 'required'
        ]);
        //change accountant id to auth()->user()->id
        // $accountant = Accountant::latest()->first();
        // dd($accountant);
        $data = array_merge($data, ['accountant_id' => auth()->user()->id]);
        $stock = Stock::create($data);
        for($i = intval($data['min_serial']); $i <= intval($data['max_serial']); $i++):
            $enumGcr = [];
            $enumGcr = array_merge($enumGcr, ['stock_id' => $stock->id, 'gcr_number' => $i]);
            EnumGcr::create($enumGcr);
        endfor;
        return redirect()->route('stock.create');
    }

    public function createIssue()
    {
        return view('console.stock.issue');
    }

    public function storeIssue(Request $request)
    {
        $data = $request->validate([
            'from_name' => 'required',
            'to_name' => 'required',
            'from_id' => '',
            'to_id' => 'required',
            'date' => 'required'
        ]);
        // dd($request->stock_id);
        if($data['from_name'] === 'stock'):
            foreach($request->stock_id as $id):
                $data = array_merge($data, [
                    'from_id' => auth()->user()->id,
                    'stock_id' => $id
                ]);
                // dd($data);
                $issue = IssueStock::create($data);
                if($issue):
                    $stock = Stock::whereNotNull('accountant_id')->where('accountant_id', $data['from_id'])->first();
                    if($data['to_name'] === 'supervisor'):
                        $stock->update([
                            'accountant_id' => null,
                            'collector_id' => null,
                            'supervisor_id' => $data['to_id'],
                            'status' => 'issued'
                        ]);
                    endif;
                    if($data['to_name'] === 'collector'):
                      $collector = Collector::where('id', $data['to_id'])->first();
                        $stock->update([
                            'accountant_id' => null,
                            'supervisor_id' => null,
                            'collector_id' => $collector->collector_id,
                            'status' => 'issued'
                        ]);
                        $enumGcrs = EnumGcr::where('stock_id', $data['stock_id'])->get();
                        foreach($enumGcrs as $enumGcr):
                            $enumGcr->id_collector = $collector->collector_id;
                            $enumGcr->save();
                        endforeach;
                        // dd($enumGcr);
                    endif;
                    if($data['to_name'] === 'cashier'):
                      $cashier = Cashier::where('id', $data['to_id'])->first();
                        $stock->update([
                            'accountant_id' => null,
                            'supervisor_id' => null,
                            'collector_id' => null,
                            'cashier_id' => $cashier->cashier_id,
                            'status' => 'issued'
                        ]);
                        $enumGcrs = EnumGcr::where('stock_id', $data['stock_id'])->get();
                        foreach($enumGcrs as $enumGcr):
                            $enumGcr->id_cashier = $cashier->cashier_id;
                            $enumGcr->save();
                        endforeach;
                        // dd($enumGcr);
                    endif;
                endif;
            endforeach;
        endif;

        if($data['from_name'] === 'supervisor'):
            foreach($request->stock_id as $id):
                $data = array_merge($data, [
                    'from_id' => $request->from_id,
                    'stock_id' => $id
                ]);
                // dd($data);
                $issue = IssueStock::create($data);
                if($issue):
                    $stock = Stock::whereNotNull('supervisor_id')->where('supervisor_id', $data['from_id'])->first();
                    if($data['to_name'] === 'supervisor'):
                        $stock->update([
                            'accountant_id' => null,
                            'collector_id' => null,
                            'supervisor_id' => $data['to_id'],
                            'status' => 'issued'
                        ]);
                    endif;
                    if($data['to_name'] === 'collector'):
                      $collector = Collector::where('id', $data['to_id'])->first();
                        $stock->update([
                            'accountant_id' => null,
                            'supervisor_id' => null,
                            'collector_id' => $collector->collector_id,
                            'status' => 'issued'
                        ]);
                        $enumGcrs = EnumGcr::where('stock_id', $data['stock_id'])->get();
                        foreach($enumGcrs as $enumGcr):
                            $enumGcr->id_collector = $collector->collector_id;
                            $enumGcr->save();
                        endforeach;
                    endif;
                endif;
            endforeach;
        endif;

        return redirect()->route('stock.issue');
    }

    public function findStock()
    {
        return view('console.stock.find');
    }

    public function returnStock()
    {
        return view('console.stock.return');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}