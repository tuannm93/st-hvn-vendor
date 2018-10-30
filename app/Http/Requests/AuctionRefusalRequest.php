<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionRefusalRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'other_contents' => 'max:1000',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'other_contents' => trans('auction_refusal.label_other_reason'),
        ];
    }
}
