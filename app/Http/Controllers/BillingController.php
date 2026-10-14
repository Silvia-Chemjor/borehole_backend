<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function createBilling(Request $request){
            $validated = $request->validate([
                'record_date'=>'required|date',
                'meter_reading'=>'required|numeric',
                'units_used'=>'required|numeric',
                'amount'=>'required|numeric',
                'plot_id'=>'required|integer|exists:plots,id',
            ]);

            $payment = new Billing();
            $payment->record_date = $validated['record_date'];
            $payment->meter_reading = $validated['meter_reading'];
            $payment->units_used = $validated['units_used'];
            $payment->amount = $validated['amount'];
            $payment->plot_id = $validated['plot_id'];

            try{
                $payment->save();
                return response()->json([
                    'message'=>'Billing saved successfully',
                    'payment'=>$payment
                ], 200);
            } catch(\Exception $e){
                return response()->json([
                    'message'=>'Error trying to save',
                    'error'=>$e->getMessage()
                ], 500);
            }
    }

    public function readAllBillings(){
        try{
            $payments = Billing::all();
            return response()->json([
                'payments' => $payments
            ], 200);
        } catch(\Exception $e){
            return response()->json([
            'message'=>'Error trying to read',
            'error'=>$e->getMessage()
            ], 500);
        }
    }

    public function readBilling($id){
        
        try{
            $payment = Billing::where('id',$id)->first();
            return response()->json([
                'payment' => $payment
            ], 200);
        } catch(\Exception $e){
            return response()->json([
            'message'=>'Error trying to read',
            'error'=>$e->getMessage()
            ], 500);
        }      
    }

    public function updateBilling($id, Request $request){
        
            $validated = $request->validate([
                'record_date'=>'required|date',
                'meter_reading'=>'required|numeric',
                'units_used'=>'required|numeric',
                'amount'=>'required|numeric',
                'plot_id'=>'required|integer|exists:plots,id',
            ]);

            $payment = Billing::where('id',$id)->first();
            $payment->record_date = $validated['record_date'];
            $payment->meter_reading = $validated['meter_reading'];
            $payment->units_used = $validated['units_used'];
            $payment->amount = $validated['amount'];
            $payment->plot_id = $validated['plot_id'];

            try{
                $payment->save();
                return response()->json([
                    'message'=>'Billing updated successfully',
                    'payment'=>$payment
                ], 200);
            } catch(\Exception $e){
                return response()->json([
                    'message'=>'Error trying to update',
                    'error'=>$e->getMessage()
                ], 500);
            }
    
    }

    public function deleteBilling($id){
        try{
            $payment =Billing::where('id',$id)->first();
            if(!$payment){
                return response()->json("Record not found");
            }
            $payment->delete();
            return response()->json("Record deleted successfully");
        } catch(\Exception $e){
            return response()->json([
                    'message'=>'Error trying to delete',
                    'error'=>$e->getMessage()
                ], 500);

        }
    }

    public function searchBilling($name){            
        try{
            $payments = Billing::where('record_date', 'LIKE',"%{$name}%")->get();
            return response()->json([
                'payments' => $payments
            ], 200);
        } catch(\Exception $e){
            return response()->json([
            'message'=>'Error trying to fetch',
            'error'=>$e->getMessage()
            ], 500);
        }      
    
    }
}
