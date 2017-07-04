<?php

namespace CodeDelivery\Http\Controllers;

use Illuminate\Http\Request;
use CodeDelivery\Repositories\ClientRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Requests\AdminClientRequest;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Services\ClientService;
class ClientsController extends Controller
{
	private $repository;
	public function __construct(ClientRepository $repository, ClientService $clientService){
		$this->repository = $repository;
        $this->clientService = $clientService;
	}
    public function index(){

    	$clients = $this->repository->paginate();
    	return view('admin.clients.index',compact('clients'));
    }
    public function create(){
       
    	return view('admin.clients.create',compact('clients'));
    }
    public function store(AdminClientRequest $request){
     	$data = $request->all();
        
     	$this->clientService->create($data);

     	return redirect()->route('admin.clients.index');	
    }
    public function edit($id){
    	$client = $this->repository->find($id);
       /* $client->name = $client->user->name;  VALIDAR METODO */
        /*$client->email = $client->user->email;  VALIDAR METODO */

    	return view('admin.clients.edit',compact('client'));	
    }
    public function update(AdminClientRequest $request,$id){
    	$data = $request->all();
     	$this->clientService->update($data,$id);
        
     	return redirect()->route('admin.clients.index');	
    }
     public function delete( $id){
        $data = $this->repository->find($id);
    	$client = $this->repository->delete($id);
        $user = $this->userRepository->delete($data->user_id);
       
    	return redirect()->route('admin.clients.index');	
    }
}
