<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $tickets = $this->getProcessedTickets($validatedData);
            $contract = $this->getConstractsInfo($validatedData['customer_id']);

            $usedHours = collect($tickets)->sum('response_time_hours');
            $contractHours = $contract?->cost;

            $pdf = Pdf::loadView('reports.tickets', [
                'tickets' => $tickets,
                'customer_id' => $validatedData['customer_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'customer_name' => $contract?->contract?->name ?? 'Cliente Desconhecido',
                'contract_start' => $contract?->begin_date,
                'contract_end' => $contract?->end_date,
                'contract_cost' => $contract?->cost,
                'used_hours' => $usedHours,
                'contract_hours' => $contractHours,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('report.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getProcessedTickets(array $filters): \Illuminate\Support\Collection
    {
        return collect([
            [
                'id' => 5025,
                'description' => 'Chamado de exemplo 1',
                'solution_date' => '2024-03-15',
                'raw_response_time' => '01:30',
                'response_time_hours' => 1.5,
            ],
            [
                'id' => 3611,
                'description' => 'Chamado de exemplo 2',
                'solution_date' => '2024-03-16',
                'raw_response_time' => '02:15',
                'response_time_hours' => 2.25,
            ],
            [
                'id' => 2435,
                'description' => 'Chamado de exemplo 3',
                'solution_date' => '2024-03-17',
                'raw_response_time' => '00:45',
                'response_time_hours' => 0.75,
            ],
        ]);

    }

    private function getConstractsInfo(int $customerId)
    {

        return (object)[
            'begin_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'cost' => 120,
            'contract' => (object)[
                'name' => 'Buona Vita'
            ]
        ];
        /*
         *
         *
         *
         *
        return ContractCost::with('contract')
            ->where('entities_id', $customerId)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now()->subMonth())
            ->first();
        */
    }

/**
    private function getProcessedTickets(array $filters)
    {
        return Ticket::with('vnGroup.responseTime')
            ->where('entities_id', $filters['customer_id'])
            ->whereBetween('solvedate', [$filters['start_date'], $filters['end_date']])
            ->get()
            ->map(fn ($ticket) => [
                'id' => $ticket->id,
                'description' => $ticket->name,
                'solution_date' => $ticket->solvedate,
                'raw_response_time' => $ticket->vnGroup?->responseTime?->name,
                'response_time_hours' => $ticket->response_time_hours,
            ]);
    }
 * */
}
