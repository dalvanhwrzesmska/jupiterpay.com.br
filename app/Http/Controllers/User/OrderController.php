<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CheckoutBuild;
use App\Models\CheckoutOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $status = $request->input('status');
        
        $user_id = \Auth::user()->user_id;
        $permissao = \Auth::user()->permission;

        // Monta a query com join para buscar no checkout.name
        $ordersQuery = CheckoutOrders::join('checkout_build', 'checkout_orders.checkout_id', '=', 'checkout_build.id')
            ->join('users', 'checkout_build.user_id', '=', 'users.id')
            ->select('checkout_orders.*', 'checkout_build.produto_name as checkout_name');
            
        if($permissao != 3){
            $ordersQuery->where('checkout_build.user_id', $user_id);
        }

        // Busca case-insensitive nos campos pedidos e no checkout.name
        if ($buscar) {
            $searchTerm = strtolower($buscar);
            $ordersQuery->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(checkout_orders.name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(checkout_orders.telefone) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(checkout_orders.email) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(checkout_build.produto_name) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Filtro por status
        if ($status && $status != 'todos') {
            switch ($status) {
                case 'pagos':
                    $ordersQuery->where('checkout_orders.status', 'pago');
                    break;
                case 'pendentes':
                    $ordersQuery->where('checkout_orders.status', 'gerado');
                    break;
                case 'med':
                    $ordersQuery->where('checkout_orders.status', 'med');
                    break;
                case 'chargeback':
                    $ordersQuery->where('checkout_orders.status', 'cancelado');
                    break;
                case 'reembolso':
                    $ordersQuery->where('checkout_orders.status', 'reembolso');
                    break;
            }
        }

        // Pega resultados
        $orders = $ordersQuery->get();
        $ordersPage = $ordersQuery->paginate(2);

        // Opcional: busca os checkouts do usuário para a view (se precisar)
        $checkouts = CheckoutBuild::where('user_id', auth()->user()->id)->get();

        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.orders' : 'profile.orders';

        return view($viewName, compact('orders', 'checkouts', 'ordersPage'));
    }

}
