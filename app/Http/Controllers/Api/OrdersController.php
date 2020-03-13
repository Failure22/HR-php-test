<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderSaveRequest;
use App\Http\Resources\OrderListResource;
use App\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    const DEFAULT_TAB = 'current';

    private $tabs;
    private $current_tab;

    /**
     * @return array
     */
    public function tabsConfig()
    {
        return [
            'outdated' => [
                'delivery_dt' => [
                    ['<', new Carbon()]
                ],
                'status'    => 10,
                'sort'      => ['delivery_dt', 'asc'],
                'page_size' => 50
            ],

            'current' => [
                'delivery_dt' => [
                    ['>=', new Carbon()],
                    ['<=', (new Carbon())->addHours(24)]
                ],
                'status'    => 10,
                'sort'      => ['delivery_dt', 'asc'],
                'page_size' => 20
            ],

            'new' => [
                'delivery_dt' => [
                    ['>', new Carbon()]
                ],
                'status'    => 0,
                'sort'      => ['delivery_dt', 'asc'],
                'page_size' => 50
            ],

            'completed' => [
                'delivery_dt' => [
                    ['>=', Carbon::today()],
                    ['<',  Carbon::tomorrow()]
                ],
                'status'    => 20,
                'sort'      => ['delivery_dt', 'asc'],
                'page_size' => 50
            ]
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getList(Request $request)
    {
        $this->tabs = $this->tabsConfig();

        $page = !empty($request->get('page')) ? $request->get('page') - 1 : 0;

        $this->current_tab = empty($request->get('tab')) || !array_key_exists($request->get('tab'), $this->tabs)
            ? static::DEFAULT_TAB
            : $request->get('tab');

        return [
            'page_count' => ceil($this->requestedOrders($page)->count() / $this->getTabData('page_size')),
            'page_size'  => $this->getTabData('page_size'),
            'list' => new OrderListResource($this->requestedOrders($page)
                ->limit($this->getTabData('page_size'))
                ->offset($page * $this->getTabData('page_size'))
                ->get()
            )
        ];
    }

    /**
     * @return array
     */
    public function getStatusList()
    {
        return Order::STATUSES;
    }

    /**
     * @param Request $request
     */
    public function save(OrderSaveRequest $request)
    {
        /**
         * @var Order $order
         */
        $order = Order::find($request->get('id'));

        $order->client_email = $request->get('client_email');
        $order->partner_id   = $request->get('partner_id');
        $order->status       = $request->get('status');

        $order->save();
    }

    /**
     * @param string $parameter
     *
     * @return mixed
     */
    private function getTabData(string $parameter)
    {
        return $this->tabs[$this->current_tab][$parameter] ?? null;
    }

    /**
     * @param int $page
     *
     * @return Builder
     */
    private function requestedOrders(int $page)
    {
        $orderQuery = Order::with([
            'partner',
            'products.product'
        ])
            ->where('status', $this->getTabData('status'))
            ->orderBy(...$this->getTabData('sort'));

        if (!empty($this->getTabData('delivery_dt')))
        {
            foreach ($this->getTabData('delivery_dt') as $time_condition)
            {
                $orderQuery->where('delivery_dt', $time_condition[0], $time_condition[1]);
            }
        }

        return $orderQuery;
    }
}
