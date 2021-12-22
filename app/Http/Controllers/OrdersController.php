<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Response;
use App\Orders;
use DB;
use Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class OrdersController extends Controller
{

	function getOrdersListData(Request $request){
		$search = $_POST['search']['value'];

		$rowTotalObj = DB::table('t_orders')
            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
            ->join('t_products', 't_ordersitems.ProductId', '=', 't_products.ProductId')
            ->select(DB::raw('count(*) as rcount'))
	        ->where(function($query) use ($search)
	          {
	            if(!empty($search)):
	               $query->Where('ProductName','like', '%' . $search . '%');
	               $query->orWhere('Qty','like', '%' . $search . '%');
	               $query->orWhere('t_ordersitems.TotalPrice','like', '%' . $search . '%');
	               $query->orWhere('BuyerName','like', '%' . $search . '%');
	               $query->orWhere('Phone','like', '%' . $search . '%');
	               $query->orWhere('Address','like', '%' . $search . '%');
	               $query->orWhere('Status','like', '%' . $search . '%');
	            endif;
	          })
            ->get();

		$totalData = $rowTotalObj[0]->rcount;

		$limit = $_POST['length'];
		$start = $_POST['start'];

		$posts = DB::table('t_orders')
            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
            ->join('t_products', 't_ordersitems.ProductId', '=', 't_products.ProductId')
            ->select('t_orders.OrdersId', 't_orders.OrderDate', 't_orders.BuyerName', 't_orders.Phone', 't_orders.Address', 't_orders.Status', 't_products.ProductName', 't_ordersitems.Qty', 't_ordersitems.UnitPrice', 't_ordersitems.TotalPrice')
	        ->where(function($query) use ($search)
	          {
	            if(!empty($search)):
	               $query->Where('ProductName','like', '%' . $search . '%');
	               $query->orWhere('Qty','like', '%' . $search . '%');
	               $query->orWhere('t_ordersitems.TotalPrice','like', '%' . $search . '%');
	               $query->orWhere('BuyerName','like', '%' . $search . '%');
	               $query->orWhere('Phone','like', '%' . $search . '%');
	               $query->orWhere('Address','like', '%' . $search . '%');
	               $query->orWhere('Status','like', '%' . $search . '%');
	            endif;
	          })
	        ->offset($start)
			->limit($limit)
			->orderByRaw("OrderDate desc,ProductName asc")
            ->get();

		$data = array();

		if($posts){

			$accept = "<a class='task-del itemApprove' style='margin-left:4px' href='javascript:void(0);'><span class='label label-primary'>Accept</span></a>";
			$delivered = "<a class='task-del itemDelivery' style='margin-left:4px' href='javascript:void(0);'><span class='label label-primary'>Delivery</span></a>";
			$cancel = "<a class='task-del itemCancel' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>Cancel</span></a>";
	
			$serial = $_POST['start'] + 1;
			foreach($posts as $r){
				$arr['OrdersId'] = $r->OrdersId;
				$arr['Serial'] = $serial++;
				$arr['OrderDate'] = $r->OrderDate;
				$arr['ProductName'] = $r->ProductName;
				$arr['Qty'] = $r->Qty;
				$arr['TotalPrice'] = $r->TotalPrice;
				$arr['BuyerName'] = $r->BuyerName;
				$arr['Phone'] = $r->Phone;
				$arr['Address'] = $r->Address;
				
			
				$arr['Status'] = $r->Status;

				if($r->Status == "Order"){
					$arr['action'] = $accept . $cancel;
				}else if($r->Status == "Accept"){
					$arr['action'] = $delivered;
				}else if($r->Status == "Delivered"){
					$arr['action'] =  "<span class='font-green'>Delivered</span>";
				}else if($r->Status == "Cancel"){
					$arr['action'] =  "<span class='font-red'>Cancel</span>";
				}
				
				$data[] = $arr;
			}
			/*1.Order, 2.Accept, 3.Delivered, 4.Cancel*/ 
			$json_data = array(
				"iTotalRecords"=> intval($totalData),
				"iTotalDisplayRecords"=> intval($totalData),
				"draw"=>intval($request->input('draw')),
				"recordsTotal"=> intval($totalData),
				"data"=>$data
			);

			echo json_encode($json_data);
		}
	}


    function getAllProductsData(Request $request){ 

    	$curDateTime = date ( 'Y-m-d' );

		$posts = DB::table('t_products')
				->select('t_products.*')
				->orderByRaw("ProductName asc")
				->get();

		$data = array();
		if($posts){
			foreach($posts as $r){
				$arr['ProductId'] = $r->ProductId;
				$arr['ProdCatId'] = $r->ProdCatId;
				$arr['ProductName'] = $r->ProductName;
				$arr['Price'] = $r->Price;
				$arr['ImageURL'] = $r->ImageURL;
				$arr['Remarks'] = $r->Remarks;

//Order, 2.Accept
				$search = "";

				 $ordercheck = DB::table('t_orders')
		            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
		            ->select(DB::raw('sum(Qty) as orderQty')) 
			        ->where('ProductId','=',$r->ProductId) 
			        ->where('OrderDate','>', $curDateTime)

			        ->where(function($ordercheck) use ($search)
			          {
			               $ordercheck->Where('Status','=', 'Order');
			               $ordercheck->orWhere('Status','=', 'Accept');
			          })

			        ->get();

				$orderQtyToday = 0;

				if($ordercheck){
					foreach($ordercheck as $or){
						$orderQtyToday = $or->orderQty;
					}
				}

				$availableStock = $r->Availability - $orderQtyToday;
				$arr['Availability'] = $availableStock;

				//$arr['Availability'] = $r->Availability;

				$data[] = $arr;
			}
		}

		return $data;
 
	}


	function getSingleProductForPlaceOrder(Request $request){ 
		$ProductId = $_POST['ProductId'];

		$posts = DB::table('t_products')
				->select('t_products.*')
				->where(function($query) use ($ProductId)
		          {
		            if($ProductId != 0):
		               $query->Where('t_products.ProductId','=', $ProductId);
		            endif;
		          })
				->get();

		return $posts;

	}

    public function submitOrder(Request $request){

		$currDateTime = date ( 'Y-m-d H:i:s' );

		$ProductId = $_POST['ProductId'];

		$UnitPrice = $_POST['UnitPrice'];
		$Quantity = $_POST['Quantity'];
		$TotalPrice = $_POST['TotalPrice'];

		$BuyerName = $_POST['BuyerName'];
		$Phone = $_POST['Phone'];
		$Address = $_POST['Address'];

		/*when PK already exist then  update otherwise insert*/
		//Status = Order, Accept, Delivered, Cancel 
		DB::table('t_orders')
		    ->Insert(

		        ['OrderDate' => $currDateTime, 
		        'TotalPrice' => $TotalPrice, 
		        'BuyerName' =>$BuyerName, 
		        'Phone' => $Phone, 
		        'Address' => $Address, 
		        'Status' => 'Order']		       
		    );


	    //get mysqli_insert_id
		$rowTotalObj = DB::table('t_orders')
                     ->select(DB::raw('max(OrdersId) as OrdersId'))
                     ->get();

		$OrdersId = $rowTotalObj[0]->OrdersId;

		DB::table('t_ordersitems')->insert(
					    ['OrdersId' => $OrdersId, 'ProductId' => $ProductId, 'Qty' => $Quantity, 'UnitPrice' => $UnitPrice, 'TotalPrice' => $TotalPrice]
					);

		echo $OrdersId;
    }

	
    public function confirmOrder(Request $request){

		$OrdersId = $request->input("OrdersId");

		$obj = DB::table('t_orders')
              ->where('OrdersId', $OrdersId)
              ->update(['IsPayment' =>1]);

		return Response::json($obj);

    }

	







	/* / Admin / Order List	*/
	public function orderRequestAccept(Request $request){
	 	$id = $request->input("id");
	 	$currDateTime = date ( 'Y-m-d H:i:s' );

		$obj = DB::table('t_orders')
              ->where('OrdersId', $id)
              ->update(['Status' => 'Accept', 'ReadyOrCancellDate' => $currDateTime]);

		return Response::json($obj);
    }

    public function orderRequestDelivery(Request $request){
	 	$id = $request->input("id");
	 	$currDateTime = date ( 'Y-m-d H:i:s' );

		$obj = DB::table('t_orders')
              ->where('OrdersId', $id)
              ->update(['Status' => 'Delivered', 'ReadyOrCancellDate' => $currDateTime]);

		return Response::json($obj);
    }

	public function orderRequestCancel(Request $request){
	 	$id = $request->input("id");

		$obj = DB::table('t_orders')
              ->where('OrdersId', $id)
              ->update(['Status' => 'Cancel', 'ReadyOrCancellDate' => NULL]);

		return Response::json($obj);
    }






	function payment(Request $request)
	{

		$OrdersId = $request->input("OrdersId");


		//Get order info
		$BuyerName = "Na";
		$Phone = "Na";
		$TotalPrice = 0;
/*
		$posts = DB::table('t_orders')
            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
            ->select('t_orders.BuyerName', 't_orders.Phone', 't_ordersitems.TotalPrice')
            ->select(DB::raw("t_orders.BuyerName,t_orders.Phone,sum(t_ordersitems.TotalPrice) as TotalPrice"))
	        ->Where('t_orders.OrdersId','=', $OrdersId)
	        ->groupByRaw("t_orders.BuyerName,t_orders.Phone")
            ->get();

		

		if($posts){
			foreach($posts as $r){
				$BuyerName = $r->BuyerName;
				$Phone = $r->Phone;
				$TotalPrice = $r->TotalPrice;
			}
		}


		//update payment status
		$obj = DB::table('t_orders')
              ->where('OrdersId', $OrdersId)
              ->update(['IsPayment' =>1]);*/



		$name   = $BuyerName;//"Rubel";// $request->input('name');
		$phone  = $Phone;//"01538198763";//$request->input('contact');
		$amount = $TotalPrice;//10;// $request->input('amount');
		$trnxId = 'trnx_' . Str::uuid();     // must be unique

		$url = "https://api.sheba.xyz";
		$PL_CLIENT_ID = "568027661";
		$PL_CLIENT_SECRET = "hg98Z1ZwB7uoLfItEWo7Uso2ttDzR0As5OkMV7GiXxXqRrpW2PpSUIQZcVcHhCqZ0FHOFVCrAqyuObhiIdSebpuKK4pYngPoXRfBLZB5tlH4uLS8Z7h8ikaT";
		
		try {
			$responsejSON = Http::withHeaders([
				'client-id'     => $PL_CLIENT_ID,
				'client-secret' => $PL_CLIENT_SECRET
			])->post($url . '/v1/ecom-payment/initiate', [
				'customer_name'   => $name,
				'customer_mobile' => $phone,
				'amount'          => $amount,
				'transaction_id'  => $trnxId,
				'success_url'     => 'https://myurl.com/success',  // success url
				'fail_url'        => 'https://myurl.com/failed'    // failed url
			])->json();

			$code    = $responsejSON['code'];
			$message = $responsejSON['message'];

			//return $responsejSON;
			
			if ($code !== 200) {
				return Redirect::back()
	                ->withErrors([$message]);
			}else{
				return redirect()->to($responsejSON['data']['link'])->send();
			}

			//$response['plInitiateUrl'] = $responsejSON['data']['link'];
			
			//$response = Http::get($response['plInitiateUrl']);
		} catch (\Exception $ex) {
			return Redirect::back()
	                ->withErrors([$ex->getMessage()]);
		}
	}




/*Report*/

function getOrdersReportData(Request $request){ 

		$search = $_POST['search']['value'];

		$StartDate = $_POST['StartDate'];
		$EndDate = $_POST['EndDate'];

		$rowTotalObj = DB::table('t_orders')
            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
            ->join('t_products', 't_ordersitems.ProductId', '=', 't_products.ProductId')
            ->select(DB::raw('count(*) as rcount'))
            ->Where('OrderDate','>=', $StartDate)
            ->Where('OrderDate','<=', $EndDate)

	        ->where(function($query) use ($search)
	          {
	            if(!empty($search)):
	               $query->Where('ProductName','like', '%' . $search . '%');
	               $query->orWhere('Qty','like', '%' . $search . '%');
	               $query->orWhere('t_ordersitems.TotalPrice','like', '%' . $search . '%');
	               $query->orWhere('BuyerName','like', '%' . $search . '%');
	               $query->orWhere('Phone','like', '%' . $search . '%');
	               $query->orWhere('Address','like', '%' . $search . '%');
	               $query->orWhere('Status','like', '%' . $search . '%');
	            endif;
	          })
            ->get();

		$totalData = $rowTotalObj[0]->rcount;

		$limit = $_POST['length'];
		$start = $_POST['start'];

		$posts = DB::table('t_orders')
            ->join('t_ordersitems', 't_orders.OrdersId', '=', 't_ordersitems.OrdersId')
            ->join('t_products', 't_ordersitems.ProductId', '=', 't_products.ProductId')
            ->select('t_orders.OrdersId', 't_orders.OrderDate', 't_orders.BuyerName', 't_orders.Phone', 't_orders.Address', 't_orders.Status', 't_products.ProductName', 't_ordersitems.Qty', 't_ordersitems.UnitPrice', 't_ordersitems.TotalPrice')

            ->Where('OrderDate','>=', $StartDate)
            ->Where('OrderDate','<=', $EndDate)
	        ->where(function($query) use ($search)
	          {
	            if(!empty($search)):
	               $query->Where('ProductName','like', '%' . $search . '%');
	               $query->orWhere('Qty','like', '%' . $search . '%');
	               $query->orWhere('t_ordersitems.TotalPrice','like', '%' . $search . '%');
	               $query->orWhere('BuyerName','like', '%' . $search . '%');
	               $query->orWhere('Phone','like', '%' . $search . '%');
	               $query->orWhere('Address','like', '%' . $search . '%');
	               $query->orWhere('Status','like', '%' . $search . '%');
	            endif;
	          })
	        ->offset($start)
			->limit($limit)
			->orderByRaw("OrderDate desc,ProductName asc")
            ->get();

		$data = array();

		if($posts){

			$serial = $_POST['start'] + 1;
			foreach($posts as $r){
				$arr['Serial'] = $serial++;
				$arr['OrderDate'] = $r->OrderDate;
				$arr['ProductName'] = $r->ProductName;
				$arr['Qty'] = $r->Qty;
				$arr['TotalPrice'] = $r->TotalPrice;
				$arr['BuyerName'] = $r->BuyerName;
				$arr['Phone'] = $r->Phone;
				$arr['Address'] = $r->Address;
			
				$arr['Status'] = $r->Status;

				
				$data[] = $arr;
			}
			/*1.Order, 2.Accept, 3.Delivered, 4.Cancel*/ 
			$json_data = array(
				"iTotalRecords"=> intval($totalData),
				"iTotalDisplayRecords"=> intval($totalData),
				"draw"=>intval($request->input('draw')),
				"recordsTotal"=> intval($totalData),
				"data"=>$data
			);

			echo json_encode($json_data);
		}
	}

}
