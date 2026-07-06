<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\EmailTemplateDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\EmailTemplateRepository;

class EmailTemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EmailTemplateRepository $emailTemplateRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(EmailTemplateDataGrid::class)->process();
        }

        return view('admin::settings.email-templates.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);

        $variables = config('email-template-variables')[$emailTemplate->code] ?? [];

        return view('admin::settings.email-templates.edit', compact('emailTemplate', 'variables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update($id): RedirectResponse
    {
        $this->validate(request(), [
            'name' => 'required',
            'subject' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $data = request()->only(['name', 'subject', 'content', 'status']);

        $data['content'] = $data['content'] ? clean_content($data['content']) : null;

        $this->emailTemplateRepository->update($data, $id);

        session()->flash('success', trans('admin::app.settings.email-templates.update-success'));

        return redirect()->route('admin.settings.email_templates.index');
    }
}
