<?php

namespace App\Http\Controllers;

use App\improvement_level;
use App\Mine;
use App\MineImprovement;
use App\Price;
use App\User;
use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.user');
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($user)
    {
        $user=User::findOrFail($user);

        $mine= Mine::select('mines.bonus','mines.workers','mines.name','mines.created_at','mines.description','mines.id','mines.type',DB::raw('COUNT(w.id) as correct'))
        ->where('mines.user_id', Auth::user()->id)
            ->leftJoin('workers as w',function ($join){
              $join->on('mines.id','w.mine_id')
                  ->on(function ($query) {
                  $query->where('w.experience', 'Ekspert')
                      ->where((function ($query) {
                          $query->where('mines.type', 2)
                              ->orwhere('mines.type', 1);
                      }))

                  ->orON(function ($query) {
                      $query->where('w.experience', 'Węgiel Kamienny')
                          ->where('mines.type', 1);
                  })
                  ->orOn(function ($query) {
                      $query->where('w.experience', 'Węgiel Brunatny')
                          ->Where('mines.type', 2);
                  });
                  });
            })
            ->groupBy('mines.id')
            ->get();

        foreach($mine as $min)
        {
            if($min->worker->avg('age')<=30)
            {
                $min->bonus=$min->bonus;
            }
            elseif($min->worker->avg('age')<=40)
            {
                $min->bonus=$min->bonus-5;
            }
            else
            {
                $min->bonus=$min->bonus-15;
            }
        }

        $minesId = $mine->pluck('id');

        $improvement=DB::table('mine_improvements')
            ->select('mine_improvements.improvement_id','mine_improvements.mine_id','improvements.name', 'improvement_levels.value', 'improvement_levels.price')
            ->join('improvements',function ($join) {
                $join->on('mine_improvements.improvement_id', '=', 'improvements.id');
            })
            ->join('improvement_levels',function ($join) {
                $join->on('mine_improvements.improvement_level_id', '=', 'improvement_levels.level')
                ->on('mine_improvements.improvement_id', '=', 'improvement_levels.improvement_id');
            })
            ->get();

        $improvement_more=DB::table('mine_improvements')
            ->select('mine_improvements.improvement_level_id as level','mine_improvements.id','mine_improvements.improvement_id','mine_improvements.mine_id','improvements.name', 'improvement_levels.value', 'improvement_levels.price')
            ->leftJoin('improvements',function ($join) {
                $join->on('mine_improvements.improvement_id', '=', 'improvements.id');
            })
            ->leftJoin('improvement_levels',function ($join) {
                $join->on(DB::raw('mine_improvements.improvement_level_id+1'), '=', 'improvement_levels.level')
                    ->on('mine_improvements.improvement_id', '=', 'improvement_levels.improvement_id');
            })
            ->get();


        $test=DB::table('improvement_levels')
        ->select(DB::raw('MAX(level) as max'),'improvement_id')
            ->groupBy('improvement_id')
        ->get();

        $test = $test->keyBy('improvement_id');

        foreach ($improvement_more as $imp)
        {
            $imp->max=($test->get($imp->improvement_id))->max;
        }



        $price =Price::where('id', Mine::where('user_id',Auth::user()->id)->count())->first();


        return view('profile', compact('mine','user', 'price','improvement','improvement_more'));
    }

    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'MineName'             => 'required|max:15',
                'MineDes'             => 'required|max:25',
            ],
            [
                'MineName.max'    => 'Nazwa kopalni może mieć maksymalnie 15 znaków',
                'MineDes.max'    => 'Opis kopalni może mieć maksymalnie 25 znaków',
            ]
        );

        Mine::where('id',$request->Hmine)
            ->update([
               'name'=>$request->MineName,
               'description'=>$request->MineDes
            ]);

        return redirect("/profile/".auth()->user()->id);

    }
}
