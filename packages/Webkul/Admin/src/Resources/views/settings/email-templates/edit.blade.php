<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.email-templates.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.email_templates.edit.before', ['emailTemplate' => $emailTemplate]) !!}

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.settings.email_templates.update', $emailTemplate->id)"
        method="PUT"
    >
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                {{ $emailTemplate->name }}
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.email_templates.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.email-templates.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.email-templates.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- body content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.email_templates.edit.card.content.before', ['emailTemplate' => $emailTemplate]) !!}

                <!-- Subject & Content -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <div class="mb-2.5">
                        <!-- Subject -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.email-templates.edit.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                value="{{ old('subject') ?: $emailTemplate->subject }}"
                                :label="trans('admin::app.settings.email-templates.edit.subject')"
                                :placeholder="trans('admin::app.settings.email-templates.edit.subject')"
                            />

                            <x-admin::form.control-group.error control-name="subject" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="mb-2.5">
                        <!-- Content -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.email-templates.edit.content')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="content"
                                name="content"
                                value="{{ old('content') ?: $emailTemplate->content }}"
                                :label="trans('admin::app.settings.email-templates.edit.content')"
                                :placeholder="trans('admin::app.settings.email-templates.edit.content')"
                                :tinymce="true"
                            />

                            <x-admin::form.control-group.error control-name="content" />
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.email_templates.edit.card.content.after', ['emailTemplate' => $emailTemplate]) !!}

            </div>

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                <!-- General -->
                <div class="box-shadow rounded bg-white dark:bg-gray-900">

                    {!! view_render_event('bagisto.admin.settings.email_templates.edit.card.accordion.general.before', ['emailTemplate' => $emailTemplate]) !!}

                    <x-admin::accordion>
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.email-templates.edit.general')
                                </p>
                            </div>
                        </x-slot>

                        <x-slot:content>
                            <div class="mb-2.5 w-full">
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.email-templates.edit.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        value="{{ old('name') ?: $emailTemplate->name }}"
                                        :label="trans('admin::app.settings.email-templates.edit.name')"
                                        :placeholder="trans('admin::app.settings.email-templates.edit.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <!-- Code (read-only) -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.email-templates.edit.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code_display"
                                        disabled="true"
                                        value="{{ $emailTemplate->code }}"
                                        :label="trans('admin::app.settings.email-templates.edit.code')"
                                    />
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.email-templates.edit.status')
                                    </x-admin::form.control-group.label>

                                    @php $selectedStatus = old('status') ?: $emailTemplate->status; @endphp

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="status"
                                        value="0"
                                    />

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        value="1"
                                        :label="trans('admin::app.settings.email-templates.edit.status')"
                                        :placeholder="trans('admin::app.settings.email-templates.edit.status')"
                                        :checked="(bool) $selectedStatus"
                                    />

                                    <x-admin::form.control-group.error control-name="status" />
                                </x-admin::form.control-group>
                            </div>
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.settings.email_templates.edit.card.accordion.general.after', ['emailTemplate' => $emailTemplate]) !!}

                </div>

                @if (! empty($variables))
                    <!-- Available Variables -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.settings.email-templates.edit.variables')
                                    </p>
                                </div>
                            </x-slot>

                            <x-slot:content>
                                <div class="mb-2.5 w-full">
                                    <p class="mb-2.5 text-sm text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.email-templates.edit.variables-info')
                                    </p>

                                    <ul class="flex flex-col gap-1.5 text-sm">
                                        @foreach ($variables as $variable => $description)
                                            @php $token = '{{'.$variable.'}}'; @endphp

                                            <li>
                                                <code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-800 dark:bg-gray-800 dark:text-gray-200">{{ $token }}</code>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $description }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-slot>
                        </x-admin::accordion>
                    </div>
                @endif
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.settings.email_templates.edit.after', ['emailTemplate' => $emailTemplate]) !!}

</x-admin::layouts>
