<?php

namespace CodeDelivery\Http\Controllers\Api\Deliveryman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Repositories\ProductRepository;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Services\OrderService;
use Authorizer;
class DeliverymanCheckoutController extends Controller
{
	private $repository;
    private $service;
    private $with = ['client','cupom','items'];
	public function __construct(
        OrderRepository $repository, 
        UserRepository $userRepository,
        ProductRepository $productRepository,
        OrderService $orderService    
        ){
        $this->repository = $repository;
        $this->userRepository = $userRepository;
		$this->productRepository = $productRepository;
        $this->service = $orderService;
	}
    public function index(){
        $id = Authorizer::getResourceOwnerId();

        $orders = $this->repository
        ->skipPresenter(false)
        ->with($this->with)
        ->scopeQuery(function($query) use ($id){
            return $query->where('user_deliveryman_id','=',$id);
        })->paginate();
        return $orders;
    }

    public function store(Request $request){
       $data = $request->all();
        $id = Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $data['client_id'] = $clientId;
        $orderObj = $this->service->create($data);

       return $this->repository
           
            ->with('items')
            ->find($orderObj->id);
    }
    public function show($id){
        $idDeliveryman = Authorizer::getResourceOwnerId();
        return $this->repository->skipPresenter(false)->getByIdAndDeliveryman($id,$idDeliveryman);
       
    }
    public function updateStatus(Request $request,$id){
        $idDeliveryman = Authorizer::getResourceOwnerId();
        $order = $this->service->updateStatus($id,$idDeliveryman,$request->get('status'));
       
        if($order){
            return $this->repository->find($order->id);
        }
        abort(400, "Order não encontrada");
    }
   
}
