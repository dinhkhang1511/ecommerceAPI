<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->has('order_no'))
        {
            $order = Order::where('order_no', request()->order_no)->first();
            return $order ? new OrderResource($order->load('details')) : error('Order not found',404);
        }

        $limit = request('limit',10);
        $query = Order::query();
        if(request()->has('status'))
        {
            $status = strtolower(request()->status);
            $query = $query->where("status", $status);
        }
        if( $limit == 'all')
            return OrderResource::collection($query->get());
        else
            return OrderResource::collection($query->paginate($limit));

    }

    /**
     * Display a listing of the resource by month.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderByMonth()
    {
        $month = request('m', date('m'));
        $query = Order::query();
        $query = $query->whereMonth('created_at', $month)
        ->whereYear('created_at', date('Y'));
        if(request()->has('limit'))
            $query->limit(request('limit'));

        return OrderResource::collection($query->latest()->get());
    }

    /**
     * Display a listing of the resource by range.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderByrange(Request $request)
    {
        $request->validate([
            'from' => 'date',
            'to'   => 'date'
        ]);

        $query = Order::query();

        $query->where('status', 'Delivered')->select(DB::raw('DATE(created_at) as date') ,DB::raw('sum(price) as total'), DB::raw('count(*) as orders'));

        if($request->from)
            $query->whereDate('created_at', '>=', $request->from);
        if($request->to)
            $query->whereDate('created_at', '<=', $request->to);

        $query->groupBy('date');

        return response()->json($query->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        return new OrderResource($order->load('details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update(['status' => request('status')]);
        return success();
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
