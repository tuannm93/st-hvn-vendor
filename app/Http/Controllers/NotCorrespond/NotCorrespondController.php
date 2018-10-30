<?php

namespace App\Http\Controllers\NotCorrespond;

use App\Http\Requests\NotCorrespondItemRequest;
use App\Services\NotCorrespondItemService;
use App\Http\Controllers\Controller;

class NotCorrespondController extends Controller
{
    /**
     * @var mixed
     */
    protected $notCorrespondItemsRepository;

    /**
     * NotCorrespondController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param NotCorrespondItemService $service
     * @return $this
     */
    public function index(NotCorrespondItemService $service)
    {
        $item = $service->getCorrespondItem();
        if ($item !== null) {
            return view('not_correspond.index')->with(compact('item'));
        } else {
            abort(404);
        }
    }

    /**
     * @param NotCorrespondItemRequest $request
     * @param NotCorrespondItemService $service
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NotCorrespondItemRequest $request, NotCorrespondItemService $service, $id)
    {
        $data = $request->all();
        $result = $service->update($id, $data);
        if ($result == true) {
            $message['type'] = 'success';
            $message['text'] = trans('not_correspond.message_successfully');
        } else {
            $message['type'] = 'error';
            $message['text'] = trans('not_correspond.message_error');
        }
        return redirect()->back()->with('flash_message', $message);
    }
}
