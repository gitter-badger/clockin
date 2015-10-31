<?php namespace App\Http\Controllers\JsonApi;

use \App\Workday;
use \Symfony\Component\HttpFoundation\Response;
use \Illuminate\Contracts\Validation\ValidationException;

/**
 * @package Neomerx\LimoncelloShot
 */
class WorkdayController extends JsonApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->checkParametersEmpty();

        return $this->getResponse(Workday::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->checkParametersEmpty();

        $attributes = array_get($this->getDocument(), 'data.attributes', []);

        /** @var \Illuminate\Validation\Validator $validator */
        $rules     = [
            'date'     => 'required',
        ];
        /** @noinspection PhpUndefinedClassInspection */
        $validator = \Validator::make($attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $workday = new Workday($attributes);
        $workday->save();

        return $this->getCreatedResponse($workday);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->checkParametersEmpty();

        return $this->getResponse(Workday::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $this->checkParametersEmpty();

        $attributes = array_get($this->getDocument(), 'data.attributes', []);
        $attributes = array_filter($attributes, function ($value) {
            return $value !== null;
        });

        /** @var \Illuminate\Validation\Validator $validator */
        $rules     = [
            'name' => 'required'
        ];
        /** @noinspection PhpUndefinedClassInspection */
        $validator = \Validator::make($attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $workday = Workday::findOrFail($id);
        $workday->fill($attributes);
        $workday->save();

        return $this->getCodeResponse(Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->checkParametersEmpty();

        $workday = Workday::findOrFail($id);
        $workday->delete();

        return $this->getCodeResponse(Response::HTTP_NO_CONTENT);
    }
}
