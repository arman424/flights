<?php

namespace App\Http\Requests;

use App\DTO\CreateFlightDTO;
use App\Enums\CabinClassCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'legs'                               => ['required', 'array', 'min:1'],
            'legs.*.segments'                    => ['required', 'array', 'min:1'],
            'legs.*.segments.*.origin'           => ['required', 'string', 'size:3', 'alpha'],
            'legs.*.segments.*.destination'      => ['required', 'string', 'size:3', 'alpha'],
            'legs.*.segments.*.departure'        => ['required', 'date_format:Y-m-d\TH:i:s'],
            'legs.*.segments.*.arrival'          => ['required', 'date_format:Y-m-d\TH:i:s', 'after:legs.*.segments.*.departure'],
            'legs.*.segments.*.cabinClass'       => ['required', 'string', Rule::enum(CabinClassCode::class)],
            'legs.*.segments.*.airline'          => ['required', 'string', 'size:2', 'alpha', Rule::exists('airlines', 'code')],
            'legs.*.segments.*.flightNumber'     => ['required', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'legs.required'                              => 'A flight must have at least one leg.',
            'legs.*.segments.required'                   => 'Each leg must have at least one segment.',
            'legs.*.segments.*.origin.size'              => 'Origin must be a 3-letter IATA airport code.',
            'legs.*.segments.*.destination.size'         => 'Destination must be a 3-letter IATA airport code.',
            'legs.*.segments.*.departure.date_format'    => 'Departure must be in ISO 8601 format (Y-m-dTH:i:s).',
            'legs.*.segments.*.arrival.date_format'      => 'Arrival must be in ISO 8601 format (Y-m-dTH:i:s).',
            'legs.*.segments.*.arrival.after'            => 'Arrival must be after departure.',
            'legs.*.segments.*.cabinClass.enum'          => 'Cabin class must be one of: Y, W, J, F.',
            'legs.*.segments.*.airline.size'             => 'Airline code must be a 2-letter IATA code.',
            'legs.*.segments.*.airline.exists'           => 'Airline code is not recognised.',
        ];
    }

    public function toDto(): CreateFlightDTO
    {
        return CreateFlightDTO::fromValidated($this->validated());
    }
}
