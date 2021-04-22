<?php

namespace App\Http\Controllers;

use App\Black;
use App\Brown;
use App\Resource;
use App\Transaction;
use App\TransactionBuy;
use App\TransactionSell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.user');
        $this->middleware('verified');
    }

    protected function index()
    {
        return view('chart');
    }

    public function sell(Request $request)
    {

        switch ($request->input('form')) {
            case 'buy':

                $this->validate(
                    $request,
                    [
                        'buy'             => 'required|integer',
                    ],
                    [
                        'buy.required'    => 'Wymagane jest podanie ilości kupowanego węgla',
                        'buy.integer'    => 'Podana wartość musi być dodatnią liczbą całkowitą',
                    ]
                );



                    if($request->type=='black')
                    {

                        $value=Black::latest()->take(1)->first();

                        $ability = round(auth()->user()->resource->money/$value->value,0);


                        if($request->buy*$value->value<auth()->user()->resource->money)
                        {
                            TransactionBuy::create([
                                'quantity'=>$request->buy,
                                'coal'=>$request->type
                            ]);

                            Resource::where('user_id', auth()->user()->id)
                                ->update([
                                    'money' => DB::raw('money-' . $value->value . '*' . $request->buy),
                                    'black_coal' => DB::raw('black_coal+' . $request->buy)
                                ]);
                            return redirect("/chart/");
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Nie posiadasz wystarczającej ilości gotówki aby zakupić taką ilość węgla, możesz zakupić maksymalnie '.$ability.' ton');
                        }
                    }
                   else
                   {
                       $value=Brown::latest()->take(1)->first();

                       $ability = auth()->user()->resource->money/$value->value;

                       if($request->buy*$value->value<auth()->user()->resource->money)
                       {
                           TransactionBuy::create([
                               'quantity'=>$request->buy,
                               'coal'=>$request->type
                           ]);

                           Resource::where('user_id', auth()->user()->id)
                               ->update([
                                   'money' => DB::raw('money-'.$value->value . '*' . $request->buy),
                                   'brown_coal' => DB::raw('brown_coal+' . $request->buy)
                               ]);
                           return redirect("/chart/");
                       }
                       else
                       {
                           return redirect()->back()->with('error', 'Nie posiadasz wystarczającej ilości gotówki aby zakupić taką ilość węgla, możesz zakupić maksymalnie '.$ability.' ton');
                       }
                   }

                break;

            case 'sell':

                $this->validate(
                    $request,
                    [
                        'sell'             => 'required|integer',
                    ],
                    [
                        'sell.required'    => 'Wymagane jest podanie ilości kupowanego węgla',
                        'sell.integer'    => 'Podana wartość musi być dodatnią liczbą całkowitą',
                    ]
                );

                    if($request->type=='black')
                    {
                        $value=Black::latest()->take(1)->first();

                        if($request->sell<auth()->user()->resource->black_coal)
                        {
                            TransactionSell::create([
                                'quantity'=>$request->sell,
                                'coal'=>$request->type
                            ]);

                        Resource::where('user_id',auth()->user()->id)
                            ->update([
                                'money'=> DB::raw('money+'.$value->value . '*' . $request->sell),
                                'black_coal' => DB::raw('black_coal-'.$request->sell)
                            ]);
                            return redirect("/chart/");

                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Nie posiadasz wystarczającej ilości węgla kamiennego');

                        }
                    }
                    else
                    {
                        $value=Brown::latest()->take(1)->first();

                        if($request->sell<auth()->user()->resource->brown_coal)
                        {
                            TransactionSell::create([
                                'quantity'=>$request->sell,
                                'coal'=>$request->type
                            ]);

                        Resource::where('user_id',auth()->user()->id)
                            ->update([
                                'money'=> DB::raw('money+'.$value->value . '*' . $request->sell),
                                'brown_coal' => DB::raw('brown_coal-'.$request->sell)
                            ]);
                            return redirect("/chart/");

                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Nie posiadasz wystarczającej ilości węgla brunatnego');

                        }
                    }

                break;

        }
    }
}
