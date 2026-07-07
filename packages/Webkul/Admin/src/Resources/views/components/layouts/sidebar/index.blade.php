<div class="fixed top-14 z-[1000] h-full w-[270px] border-r border-[#dbe0e6] bg-[#f8f9fa] pt-4 transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] dark:border-gray-800 dark:bg-gray-900 max-lg:hidden">
    <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="grid w-full gap-1">
            <!-- Navigation Menu -->
            @foreach (menu()->getItems('admin') as $topMenuItem)
                @if ($topMenuItem->haveChildren())
                    <!-- Section: real top-level item name used as-is, no invented category -->
                    <p class="mb-1 mt-3 px-4 text-xs font-semibold uppercase tracking-wide text-[#1B2950] first:mt-0 dark:text-gray-400 group-[.sidebar-collapsed]/container:hidden">
                        {{ $topMenuItem->getName() }}
                    </p>

                    <div class="grid gap-0.5" data-sidebar-section>
                        @foreach ($topMenuItem->getChildren() as $menuItem)
                            <x-admin::layouts.sidebar.menu-item :menu-item="$menuItem" />
                        @endforeach
                    </div>
                @else
                    <!-- Standalone page with no children (e.g. Dashboard, CMS, Configuration) -->
                    <x-admin::layouts.sidebar.menu-item :menu-item="$topMenuItem" />
                @endif
            @endforeach

            <!-- Help & Resources (Always Visible) -->
            @php
                $isHelpActive = request()->routeIs('admin.help.index');
            @endphp

            <!-- Divider -->
            <div class="mx-4 my-1 border-t border-[#dbe0e6] dark:border-gray-800"></div>

            <div class="px-4 group/item">
                <a
                    href="{{ route('admin.help.index') }}"
                    class="flex items-center gap-2.5 rounded-[5px] px-[15px] py-[10px] cursor-pointer {{ $isHelpActive ? 'bg-primary/10 text-primary' : 'text-[#67748E] hover:bg-primary/10 hover:text-primary' }}"
                >
                    <svg
                        class="h-[18px] w-[18px] shrink-0 {{ $isHelpActive ? 'text-primary' : 'text-[#637381] group-hover/item:text-primary' }}"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M9.4 9.3a2.6 2.6 0 0 1 5.05.85c0 1.7-2.45 2.05-2.45 3.6"></path>
                        <path d="M12 17.2h.01"></path>
                    </svg>

                    <p class="whitespace-nowrap text-[15px] font-medium leading-none group-[.sidebar-collapsed]/container:hidden {{ $isHelpActive ? 'text-primary' : '' }}">
                        @lang('admin::app.components.layouts.sidebar.help')
                    </p>
                </a>
            </div>
        </nav>
    </div>

    <!-- Collapse menu -->
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-sidebar-collapse-template"
    >
        <div
            class="fixed bottom-0 w-full max-w-[270px] cursor-pointer border-t border-gray-200 bg-white px-4 transition-all duration-300 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex items-center gap-2.5 p-1.5">
                <span
                    class="icon-collapse text-2xl transition-all"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: {{ request()->cookie('sidebar_collapsed') ?? 0 }},
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }
                    
                    return 0;
                },
            },
        });
    </script>

    <script>
        const adjustSubMenuPosition = (event) => {
            let menuContainer = event.currentTarget;

            let subMenuContainer = menuContainer.lastElementChild;

            if (subMenuContainer) {
                const menuTopOffset = menuContainer.getBoundingClientRect().top;

                const subMenuHeight = subMenuContainer.offsetHeight;

                const availableHeight = window.innerHeight - menuTopOffset;

                let subMenuTopOffset = menuTopOffset;

                if (subMenuHeight > availableHeight) {
                    subMenuTopOffset = menuTopOffset - (subMenuHeight - availableHeight);
                }

                subMenuContainer.style.top = `${subMenuTopOffset}px`;
            }
        };
    </script>

    <script>
        /**
         * Accordion style submenu toggle for the expanded sidebar, mirroring the
         * Dreams POS sidebar behaviour: clicking a parent item with children
         * expands its own submenu inline and collapses any other open submenu
         * at the same level. In collapsed (icons-only) mode, children continue
         * to reveal via the existing hover flyout instead, so clicks are ignored.
         *
         * Delegated on `document` (rather than bound per toggle element) because
         * Vue re-creates this whole subtree when it mounts on `#app` on window
         * `load`, which happens after `DOMContentLoaded` and would silently drop
         * any listeners attached directly to the server-rendered elements.
         */
        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('[data-sidebar-toggle]');

            if (! toggle) {
                return;
            }

            e.preventDefault();

            if (toggle.closest('.sidebar-collapsed')) {
                return;
            }

            const submenu = toggle.parentElement.querySelector(':scope > .sidebar-submenu');

            if (! submenu) {
                return;
            }

            const isOpen = ! submenu.classList.contains('hidden');

            // Only close open submenus within the same section, matching
            // Dreams POS's parents('ul:first') scoping, rather than
            // collapsing unrelated sections elsewhere in the sidebar.
            const scope = toggle.closest('[data-sidebar-section], nav') ?? document;

            scope.querySelectorAll(':scope > div > .sidebar-submenu').forEach((otherSubmenu) => {
                if (otherSubmenu !== submenu) {
                    otherSubmenu.classList.add('hidden');

                    otherSubmenu.parentElement
                        .querySelector(':scope > a .sidebar-submenu-arrow')
                        ?.classList.remove('rotate-90', '!bg-[#FFEDDC]');
                }
            });

            submenu.classList.toggle('hidden', isOpen);

            toggle.querySelector('.sidebar-submenu-arrow')?.classList.toggle('rotate-90', ! isOpen);
            toggle.querySelector('.sidebar-submenu-arrow')?.classList.toggle('!bg-[#FFEDDC]', ! isOpen);
        });
    </script>
@endpushOnce