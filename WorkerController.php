<?php

namespace App\Http\Controllers;

use App\Mine;
use App\Resource;
use App\Trade;
use App\User;
use Illuminate\Http\Request;
use App\Worker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.user');
        $this->middleware('verified');
    }

    public function index()
    {
        $workers = Worker::where("user_id", "=", null)->get()->toArray();
        $workersSell = DB::table('trades as t')
            ->select('t.confirm', 't.id as tid', 't.user_id', 'w.name', 'w.id as wid', 'w.salary', 'w.experience', 'w.age', 't.deal', 't.dealer_id', 't.price', 'u.name as player', 'u.id')
            ->join('users as u', 't.user_id', 'u.id')
            ->join('workers as w', 't.worker_id', 'w.id')
            ->get();

        $user_workers = Worker::where("workers.user_id", "=", Auth::id())
            ->get()->toArray(); // można to zrobić jednym zapytanie niepotrzebne są dwa ZMIENI!!!!

        $user_age =
            [
                'age20' => Worker::where("user_id", "=", Auth::id())->whereBetween("age", [20, 30])->count(),
                'age30' => Worker::where("user_id", "=", Auth::id())->whereBetween("age", [31, 40])->count(),
                'age40' => Worker::where("user_id", "=", Auth::id())->whereBetween("age", [41, 50])->count(),
                'avg_age' => round(Worker::where("user_id", "=", Auth::id())->avg('age'), 1)

            ];
        $user_salary =
            [
                'salary30' => Worker::where("user_id", "=", Auth::id())->whereBetween("salary", [30, 35])->count(),
                'salary35' => Worker::where("user_id", "=", Auth::id())->whereBetween("salary", [36, 40])->count(),
                'salary40' => Worker::where("user_id", "=", Auth::id())->whereBetween("salary", [41, 45])->count(),
                'avg_sal' => round(Worker::where("user_id", "=", Auth::id())->avg('salary'), 1)

            ];
        $mine = Mine::select('name', 'id', 'workers')->where("user_id", "=", Auth::user()->id)->get();

        $correct = DB::table('workers')
            ->select(array(
                DB::raw('COUNT(*) as total'),
                DB::raw("sum(case when mines.type=1 AND workers.experience='Węgiel Kamienny' then 1 else 0 end) AS wkok"),
                DB::raw("sum(case when mines.type=2 AND workers.experience='Węgiel Brunatny ' then 1 else 0 end) AS wbok"),
                DB::raw("sum(case when (mines.type=1 OR mines.type=2) AND workers.experience='Ekspert' then 1 else 0 end) AS eok")
            ))
            ->join('mines', 'workers.mine_id', 'mines.id')
            ->where('workers.user_id', Auth::user()->id)
            ->where('workers.experience', '!=', 'brak')
            ->first();

        $date = Carbon::now();

        return view('workers', compact('workers', 'user_workers', 'user_age', 'user_salary', 'mine', 'correct', 'date', 'workersSell'));
    }

    public function hire($id)
    {
        $user = auth()->user()->id;

        $mytime = Carbon::now()->addDays(7);

        DB::table('workers')
            ->where('id', $id)
            ->update([
                'user_id' => $user,
                'agreement' => $mytime
            ]);

        $array = ['Adam', 'Tadek', 'Kamil', 'Patryk', 'Robert', 'Tomek', 'Darek', 'Marek', 'Arek', 'Karol',
            'Konrad', 'Konstanty', 'Krzysztof', 'Jakub', 'Marian', 'Marcin', 'Mirosław', 'Bronisław', 'Lech', 'Andrzej',
            'Damian', 'Daniel', 'Zdzisław', 'Zenon', 'Mateusz', 'Marcel', 'Rafał', 'Bartłomiej', 'Bożydar', 'Paweł',
            'Piotr', 'Łukasz', 'Maciej', 'Grzegorz', 'Włodzimierz', 'Miłosz', 'Maksymilian', 'Alojzy', 'Przemysław', 'Henryk',
            'Albert', 'Amadeusz', 'Nikodem', 'Jerzy', 'Sebastian'];

        $array2 = ['brak', 'Węgiel Kamienny', 'Węgiel Brunatny', 'Ekspert'];
        $k = array_rand($array);
        $v = $array[$k];

        $a = array_rand($array2);
        $b = $array2[$a];
        $paid = rand(30, 45);
        $age = rand(20, 50);

        $worker = new Worker();

        $worker->name = $v;
        $worker->salary = $paid;
        $worker->age = $age;
        $worker->experience = $b;
        $worker->save();

        return redirect("/workers/");


    }

    public function fire(Request $request)
    {
        switch ($request->input('form')) {
            case 'wypowiedz':
                Worker::where('id', $request->worker)
                    ->update([
                        'salary' => DB::raw('salary/3'),
                        'mine_id' => null,
                        'denunciation' => 1
                    ]);

                break;

            case 'zwolnij':
                Worker::where('id', $request->worker)->delete();
                break;

            case 'sprzedaj':
                $id = $request->worker;
                return view('workers_sell', compact('id'));
                break;


        }

        return redirect("/workers/");

    }


    public function workerSell(Request $request)
    {
        $this->validate(
            $request,
            [
                'workerPrice' => 'integer|gt:0',
            ],
            [
                'workerPrice.integer' => 'Zaproponowana cena musi być dodatnią liczbą całkowitą',
                'workerPrice.gt' => 'Zaproponowana cena musi być dodatnią liczbą całkowitą',
            ]
        );

        Trade::create([
            'user_id' => auth()->user()->id,
            'worker_id' => $request->id,
            'price' => $request->workerPrice,
        ]);


        return redirect("/workers")->withErrors('Twoja oferta została dodana');
    }

    public function buy($price, $buyer, $worker, $seller, $trade)
    {
        $money = auth()->user()->resource->money;

        if ($money < $price) {
            return redirect()->back()->with('error', 'Nie posiadasz wystarczającej ilości gotówki żeby odkupić pracownika');
        }

        Resource::where('user_id', $buyer)
            ->update([
                'money' => DB::raw('money-' . $price)
            ]);

        Resource::where('user_id', $seller)
            ->update([
                'money' => DB::raw('money+' . $price)
            ]);

        Worker::where('id', $worker)
            ->update([
                'user_id' => $buyer,
                'mine_id' => null
            ]);

        Trade::where('id', $trade)
            ->delete();


        return redirect("/workers/");
    }

    public function add(Request $request)
    {
        auth()->user()->worker()->where('id', $request->worker)
            ->update(['mine_id' => $request->mine]);

        return redirect("/workers/");

    }

    public function trade(Request $request)
    {
        $this->validate(
            $request,
            [
                'price' => 'integer|gt:0',
            ],
            [
                'price.integer' => 'Zaproponowana cena musi być dodatnią liczbą całkowitą',
                'price.gt' => 'Zaproponowana cena musi być dodatnią liczbą całkowitą',
            ]
        );

        $high = Trade::select('deal', 'price')
            ->where('worker_id', $request->worker)
            ->first();

        if ($request->price > $high->deal) {
            Trade::where('worker_id', $request->worker)
                ->update([
                    'deal' => $request->price,
                    'dealer_id' => $request->id
                ]);
        }

        return redirect("/workers/")->with('error', 'Twoja oferta została przesłana');

    }

    public function confirm($trade)
    {
        Trade::where('id', $trade)
            ->update([
                'confirm' => true,
            ]);
        return redirect("/workers/")->with('error', 'Gracz otrzymał potwierdzenie złożonej przez niego oferty');
    }

    public function decline($trade)
    {
        Trade::where('id', $trade)
            ->update([
                'confirm' => false,
            ]);
        return redirect("/workers/")->with('error', 'Gracz otrzymał odmowę złożonej przez niego oferty');
    }

    public function delete($trade)
    {
        Trade::where('id', $trade)
            ->delete();
        return redirect("/workers/")->with('error', 'Twoja ogłoszenie zostało usunięte');
    }
}
