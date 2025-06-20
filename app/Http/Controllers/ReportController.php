<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\ContractCost;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function fetchTickets(ReportRequest $request): \Illuminate\Http\JsonResponse
    {
        $tickets = $this->getProcessedTickets($request->validated());
        return response()->json($tickets);
    }

    public function generatePdf(ReportRequest $request): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validated();

            //$startTime = microtime(true);
            $tickets = $this->getProcessedTickets($validatedData);
            //$duration = microtime(true) - $startTime;
            $yearlyTickets = $this->getYearlyTickets($validatedData['customer_id']);
            $monthlyHours = $this->getMonthlyHours($yearlyTickets);

            //Log::info("Tempo para buscar os tickets: {$duration} segundos");

            $contract = $this->getContractInfo($validatedData['customer_id']);

            $usedHours = collect($tickets)->sum('response_time_hours');
            $contractHours = $contract?->cost;

            $pdf = Pdf::loadView('reports.tickets', [
                'tickets' => $tickets,
                'customer_id' => $validatedData['customer_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'customer_name' => $contract?->contract?->entity?->name ?? 'Entidade Desconhecida',
                'contract_start' => $contract?->begin_date,
                'contract_end' => $contract?->end_date,
                'contract_cost' => $contract?->cost,
                'used_hours' => $usedHours,
                'contract_hours' => $contractHours,
                'monthly_hours' => $monthlyHours,
                'contract_id' => $contract?->id ?? null,
            ])->setPaper('a4', 'landscape');



            return $pdf->download('report.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getContractInfo(int $customerId)
    {
        return ContractCost::with('contract.entity')
            ->whereHas('contract', function ($query) use ($customerId) {
                $query->where('entities_id', $customerId);
            })
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now()->subMonth())
            ->first();
    }

    private function getProcessedTickets(array $filters)
    {
        return Ticket::with('vnGroup.responseTime', 'category')
            ->where('entities_id', $filters['customer_id'])
            ->whereBetween('solvedate', [$filters['start_date'], $filters['end_date']])
            ->get()
            ->map(function ($ticket) {
                $vnGroup = $ticket->vnGroup;
                $responseTime = $vnGroup?->responseTime?->name;

                $hours = 0;
                if ($responseTime && str_contains($responseTime, ':')) {
                    [$h, $m] = explode(':', $responseTime);
                    $hours = (int)$h + ((int)$m / 60);
                }


                return [
                    'id' => $ticket->id,
                    'description' => $ticket->name,
                    'date_creation' => $ticket->date_creation,
                    'solution_date' => $ticket->solvedate,
                    'raw_response_time' => $responseTime,
                    'response_time_hours' => round($hours, 2),
                    'category' => $ticket->category?->simplifiedName ?? 'Sem categoria',
                ];
            });
    }

    private function getMonthlyHours($tickets): \Illuminate\Support\Collection
    {
        $monthly = array_fill_keys(range(1, 12), 0);

        foreach ($tickets as $ticket) {
            $month = (int)date('n', strtotime($ticket['solution_date']));
            $monthly[$month] += $ticket['response_time_hours'];
        }

        return collect($monthly)->mapWithKeys(function ($value, $month) {
            return [str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . date('Y') => round($value, 2)];
        });
    }

    private function getYearlyTickets(int $customerId)
    {
        $startOfYear = now()->startOfYear()->toDateString();
        $endOfYear = now()->endOfYear()->toDateString();

        return Ticket::where('entities_id', $customerId)
            ->whereBetween('solvedate', [$startOfYear, $endOfYear])
            ->get()
            ->map(function ($ticket) {
                $vnGroup = $ticket->vnGroup;
                $responseTime = $vnGroup?->responseTime?->name;

                $hours = 0;
                if ($responseTime && str_contains($responseTime, ':')) {
                    [$h, $m] = explode(':', $responseTime);
                    $hours = (int)$h + ((int)$m / 60);
                }

                return [
                    'solution_date' => $ticket->solvedate,
                    'response_time_hours' => round($hours, 2),
                ];
            });
    }
}
