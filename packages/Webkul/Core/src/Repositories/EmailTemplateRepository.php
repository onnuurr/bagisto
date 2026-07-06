<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Contracts\EmailTemplate;
use Webkul\Core\Eloquent\Repository;

class EmailTemplateRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Core\Contracts\EmailTemplate';
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('core.email_template.update.before', $id);

        $emailTemplate = parent::update($attributes, $id);

        Event::dispatch('core.email_template.update.after', $emailTemplate);

        return $emailTemplate;
    }

    /**
     * Find an active, customized template by its unique code.
     *
     * @param  string  $code
     * @return EmailTemplate|null
     */
    public function findActiveByCode($code)
    {
        return $this->model
            ->where('code', $code)
            ->where('status', 1)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->first();
    }

    /**
     * Find a template by its unique code, regardless of status.
     *
     * @param  string  $code
     * @return EmailTemplate|null
     */
    public function findByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }
}
