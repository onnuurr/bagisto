<?php

namespace Webkul\Admin\Http\Controllers\SMS;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\SMS\SmsLogDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\SMS\Repositories\SmsLogRepository;

class SmsLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SmsLogRepository $smsLogRepository) {}

    /**
     * Loads the index page showing the SMS logs.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(SmsLogDataGrid::class)->process();
        }

        return view('admin::sms.index');
    }

    /**
     * Remove the specified SMS log from storage.
     */
    public function delete(int $id): JsonResponse
    {
        $this->smsLogRepository->delete($id);

        return new JsonResponse([
            'message' => trans('admin::app.sms.messages.delete-success'),
        ]);
    }

    /**
     * Remove the specified SMS logs from storage.
     */
    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        foreach ($massDestroyRequest->input('indices') as $index) {
            $this->smsLogRepository->delete($index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.sms.messages.delete-success'),
        ]);
    }
}
