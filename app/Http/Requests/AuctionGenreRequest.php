<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionGenreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.AuctionGenre.genre_id' => 'required',
            'data.AuctionGenre.exclusion_pattern' => 'required|numeric',
            'data.AuctionGenre.limit_asap' => 'required|numeric',
            'data.AuctionGenre.limit_immediately' => 'required|numeric',
            'data.AuctionGenre.asap' => 'required|numeric',
            'data.AuctionGenre.immediately' => 'required|numeric',
            'data.AuctionGenre.normal1' => 'numeric',
            'data.AuctionGenre.normal2' => 'numeric',
            'data.AuctionGenre.normal3' => 'required|numeric',
            'data.AuctionGenre.open_rank_a' => 'required|numeric|max:100',
            'data.AuctionGenre.open_rank_b' => 'required|numeric|max:100',
            'data.AuctionGenre.open_rank_c' => 'required|numeric|max:100',
            'data.AuctionGenre.open_rank_d' => 'required|numeric|max:100',
            'data.AuctionGenre.open_rank_z' => 'required|numeric|max:100',
            'data.AuctionGenre.tel_hope_a' => 'required|numeric|max:100',
            'data.AuctionGenre.tel_hope_b' => 'required|numeric|max:100',
            'data.AuctionGenre.tel_hope_c' => 'required|numeric|max:100',
            'data.AuctionGenre.tel_hope_d' => 'required|numeric|max:100',
            'data.AuctionGenre.tel_hope_z' => 'required|numeric|max:100',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => trans('genre_detail.validate_required'),
            'numeric' => trans('genre_detail.validate_number'),
            'max' => trans('genre_detail.validate_max'),
        ];
    }
}
