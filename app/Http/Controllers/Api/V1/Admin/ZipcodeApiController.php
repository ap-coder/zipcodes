<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreZipcodeRequest;
use App\Http\Requests\UpdateZipcodeRequest;
use App\Http\Resources\Admin\ZipcodeResource;
use App\Zipcode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZipcodeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('zipcode_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZipcodeResource(Zipcode::all());
    }

    public function store(StoreZipcodeRequest $request)
    {
        $zipcode = Zipcode::create($request->all());

        return (new ZipcodeResource($zipcode))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Zipcode $zipcode)
    {
        abort_if(Gate::denies('zipcode_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZipcodeResource($zipcode);
    }

    public function update(UpdateZipcodeRequest $request, Zipcode $zipcode)
    {
        $zipcode->update($request->all());

        return (new ZipcodeResource($zipcode))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Zipcode $zipcode)
    {
        abort_if(Gate::denies('zipcode_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zipcode->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
