<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ReceiveService;
use App\Services\ServiceService;
use App\Traits\Alert;
use App\Traits\Redirect;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use Alert, Redirect;

    private ServiceService $service;
    public function __construct()
    {
        $this->service = new ServiceService();
    }
    public function index()
    {
        $pagination = intval(request()->pagination) ?: get_user_pagination();
        $services   = Service::query()->latest()->paginate($pagination);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $formAttributes = $this->service->formAttributes();
        return view('admin.services.create', compact('formAttributes'));
    }

    public function store(Request $request)
    {
        Service::create(['name' => $request->name]);
        $this->successAlert();
        return $this->redirect(route('services.index'));
    }

    public function edit(Service $service, Request $request)
    {
        $formAttributes = $this->service->formAttributes($service);
        return view('admin.services.create', compact('formAttributes', 'service'));
    }

    public function update(Service $service, Request $request)
    {
        $service->update(['name' => $request->name]);
        $this->successAlert();
        return $this->redirect(route('services.index'));
    }

    public function show(Service $service, ReceiveService $receiveService)
    {
        $pagination = intval(request()->pagination) ?: get_user_pagination();

        $contracts  = $service->contracts()->latest()->paginate($pagination);

        return view('admin.services.contract-by-service', compact('service', 'contracts', 'receiveService'));
    }
    public function destroy(string $id)
    {
        $service = Service::query()->findOrFail($id);
        $service->delete();
        $this->successAlert();
        return back();
    }
}
