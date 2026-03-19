<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateRecipeDetailsRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'menu_item_id'=>'required',
            //'outlet_id' => 'required',
            //'menu_title_id' => 'required',
            //'menu_item_id' => 'required',
        ];
    }

}
