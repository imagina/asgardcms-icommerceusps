<?php

namespace Modules\Icommerceusps\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Icommerceusps\Entities\Icommerceusps;
use Modules\Icommerceusps\Http\Requests\CreateIcommerceuspsRequest;
use Modules\Icommerceusps\Http\Requests\UpdateIcommerceuspsRequest;
use Modules\Icommerceusps\Repositories\IcommerceuspsRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class IcommerceuspsController extends AdminBaseController
{
    /**
     * @var IcommerceuspsRepository
     */
    private $icommerceusps;

    public function __construct(IcommerceuspsRepository $icommerceusps)
    {
        parent::__construct();

        $this->icommerceusps = $icommerceusps;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$icommerceusps = $this->icommerceusps->all();

        return view('icommerceusps::admin.icommerceusps.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('icommerceusps::admin.icommerceusps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateIcommerceuspsRequest $request
     * @return Response
     */
    public function store(CreateIcommerceuspsRequest $request)
    {
        $this->icommerceusps->create($request->all());

        return redirect()->route('admin.icommerceusps.icommerceusps.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('icommerceusps::icommerceusps.title.icommerceusps')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Icommerceusps $icommerceusps
     * @return Response
     */
    public function edit(Icommerceusps $icommerceusps)
    {
        return view('icommerceusps::admin.icommerceusps.edit', compact('icommerceusps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Icommerceusps $icommerceusps
     * @param  UpdateIcommerceuspsRequest $request
     * @return Response
     */
    public function update(Icommerceusps $icommerceusps, UpdateIcommerceuspsRequest $request)
    {
        $this->icommerceusps->update($icommerceusps, $request->all());

        return redirect()->route('admin.icommerceusps.icommerceusps.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('icommerceusps::icommerceusps.title.icommerceusps')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Icommerceusps $icommerceusps
     * @return Response
     */
    public function destroy(Icommerceusps $icommerceusps)
    {
        $this->icommerceusps->destroy($icommerceusps);

        return redirect()->route('admin.icommerceusps.icommerceusps.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('icommerceusps::icommerceusps.title.icommerceusps')]));
    }
}
