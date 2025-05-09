<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Validation\Exception;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendOrderCreatedEmail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use App\Http\Collections\OrderCollection;
use App\Http\Resources\OrderResource;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Documentación de la API de Challenge Zipnova Ecommerce",
 *     description="Esta es la documentación Swagger de la API del sistema de gestión de órdenes."
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 *     description="Versión 1 del API"
 * )
 *
 *  @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT"
 *     )
 */
class OrderController extends Controller
{
    use AuthorizesRequests;

    protected OrderService $orderService;

    public function __construct(OrderService $_orderService){
        $this->orderService = $_orderService;
    }

   /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Listar órdenes paginadas",
     *     security={{"bearerAuth":{}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de órdenes"
     *     )
     * )
     */
    public function index()
    {
        //Usamos cache para guardar durante 5 minutos el listado de ordenes

        $page = request()->get('page', 1);
        $cacheKey = "orders_page_{$page}";

        $orders = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return Order::with([
                'user',
                'shippingAddress',
                'billingInfo.address',
                'items.product',
            ])->paginate(10);
        });

        return new OrderCollection($orders);
    }


    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Crear una nueva orden",
     *     security={{"bearerAuth":{}}},
     *     tags={"Orders"},
     *     operationId="createOrder",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "shipping_address", "billing_info", "items"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *
     *             @OA\Property(
     *                 property="shipping_address",
     *                 type="object",
     *                 required={"street", "city", "province", "postal_code", "country"},
     *                 @OA\Property(property="street", type="string", example="Av. Siempre Viva 742"),
     *                 @OA\Property(property="city", type="string", example="Springfield"),
     *                 @OA\Property(property="province", type="string", example="Buenos Aires"),
     *                 @OA\Property(property="postal_code", type="string", example="1234"),
     *                 @OA\Property(property="country", type="string", example="Argentina")
     *             ),
     *
    *             @OA\Property(
    *                 property="billing_info",
    *                 type="object",
    *                 required={"tax_id", "company_name", "address"},
    *                 @OA\Property(property="tax_id", type="string", example="20-12345678-9"),
    *                 @OA\Property(property="company_name", type="string", example="ACME Corp"),
    *                 @OA\Property(
    *                     property="address",
    *                     type="object",
    *                     required={"street", "city", "province", "postal_code", "country"},
    *                     @OA\Property(property="street", type="string", example="Calle Falsa 123"),
    *                     @OA\Property(property="city", type="string", example="Buenos Aires"),
    *                     @OA\Property(property="province", type="string", example="Buenos Aires"),
    *                     @OA\Property(property="postal_code", type="string", example="1000"),
    *                     @OA\Property(property="country", type="string", example="Argentina")
    *                 )
    *             ),

    *             @OA\Property(
    *                 property="items",
    *                 type="array",
    *                 @OA\Items(
    *                     type="object",
    *                     required={"product_id", "quantity"},
    *                     @OA\Property(property="product_id", type="integer", example=1),
    *                     @OA\Property(property="quantity", type="integer", example=2)
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Orden creada exitosamente"
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Error de validación"
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error del servidor"
    *     )
    * )
    */

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->store($request->validated());

        dispatch(new SendOrderCreatedEmail($order));

        return response()->json(['order_id' => $order->id], 201);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     operationId="getOrderById",
     *     tags={"Orders"},
     *     summary="Obtener detalle de una orden",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la orden",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de la orden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada"
     *     )
     * )
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'shippingAddress',
            'billingInfo.address',
            'items.product',
        ]);

        return new OrderResource($order);
    }
}
