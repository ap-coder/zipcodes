<?php

namespace App\Http\Requests;

use App\Zipcode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreZipcodeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('zipcode_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
