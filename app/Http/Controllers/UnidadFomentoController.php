<?php

namespace App\Http\Controllers;

use App\Models\UnidadFomento;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;


class UnidadFomentoController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::now()->toDateString();
            $ufs = UnidadFomento::whereYear('fecha', Carbon::now()->year)
                ->orderBy('fecha', 'desc')
                ->get();
            $ufToday = UnidadFomento::where('fecha', $today)->first();

            if (!$ufToday) {
                $response = Http::get('https://mindicador.cl/api/uf');
                $data = $response->json();
                foreach ($data['serie'] as $item) {
                    UnidadFomento::updateOrCreate(
                        ['fecha' =>  Carbon::parse($item['fecha'])->toDateString()],
                        ['valor' => $item['valor']]
                    );
                }
            }

            $ufs = UnidadFomento::whereYear('fecha', Carbon::now()->year)
                ->orderBy('fecha', 'desc')
                ->get();
            $ufToday = UnidadFomento::where('fecha', $today)->first();

            return Inertia::render('Home/Show', compact('ufs', 'ufToday'));
        } catch (\Exception $e) {
            Log::info("message: {$e->getMessage()}");
        }
    }

    public function getUfFromYear($year)
    {
        $ufs = UnidadFomento::whereYear('fecha', $year)
            ->orderBy('fecha', 'desc')
            ->get();

        if ($ufs->isEmpty()) {
            $response = Http::get('https://mindicador.cl/api/uf/' . $year);
            $data = $response->json();
            foreach ($data['serie'] as $item) {
                UnidadFomento::updateOrCreate(
                    ['fecha' =>  Carbon::parse($item['fecha'])->toDateString()],
                    ['valor' => $item['valor']]
                );
            }

            $ufs = UnidadFomento::whereYear('fecha', $year)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        return response()->json($ufs);
    }
}
