<div class="fixed top-14 z-[1000] h-full w-[270px] border-r border-[#dbe0e6] bg-[#f8f9fa] pt-4 transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] dark:border-gray-800 dark:bg-gray-900 max-lg:hidden">
    <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="grid w-full gap-1.5">
            <!-- Navigation Menu -->
            @foreach (menu()->getItems('admin') as $menuItem)
                <div
                    class="relative px-4 group/item {{ $menuItem->isActive() ? 'active' : 'inactive' }}"
                    onmouseenter="adjustSubMenuPosition(event)"
                >
                    <a
                        href="{{ $menuItem->haveChildren() ? 'javascript:void(0)' : $menuItem->getUrl() }}"
                        @if ($menuItem->haveChildren()) data-sidebar-toggle @endif
                        class="relative flex items-center gap-2.5 rounded-[5px] px-[15px] py-[10px] cursor-pointer peer {{ $menuItem->isActive() ? 'bg-primary/10 text-primary' : 'text-[#67748E] hover:bg-primary/10 hover:text-primary' }}"
                    >
                        <span class="{{ $menuItem->getIcon() }} shrink-0 text-lg {{ $menuItem->isActive() ? 'text-primary' : 'text-[#637381] group-hover/item:text-primary' }}"></span>

                        <p class="whitespace-nowrap text-[15px] font-medium leading-none group-[.sidebar-collapsed]/container:hidden {{ $menuItem->isActive() ? 'text-primary' : '' }}">
                            {{ $menuItem->getName() }}
                        </p>

                        @if ($menuItem->haveChildren())
                            <span
                                class="sidebar-submenu-arrow ltr:ml-auto rtl:mr-auto flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[rgba(27,41,80,0.04)] transition-transform duration-200 group-[.sidebar-collapsed]/container:hidden {{ $menuItem->isActive() ? 'rotate-90 !bg-[#FFEDDC]' : '' }}"
                            >
                                <span class="block h-[5px] w-[5px] rotate-[-45deg] border-b-2 border-r-2 border-[#5B6670]"></span>
                            </span>
                        @endif
                    </a>

                    @if ($menuItem->haveChildren())
                        <div class="sidebar-submenu {{ $menuItem->isActive() ? '' : 'hidden' }} grid min-w-[180px] gap-0.5 border-b border-[rgba(222,226,230,0.5)] pb-3.5 pt-1 ltr:pl-10 rtl:pr-10 z-[100] overflow-hidden group-[.sidebar-collapsed]/container:!hidden group-[.sidebar-collapsed]/container:fixed group-[.sidebar-collapsed]/container:ltr:!left-[70px] group-[.sidebar-collapsed]/container:rtl:!right-[70px] group-[.sidebar-collapsed]/container:border-0 group-[.sidebar-collapsed]/container:p-[0] group-[.sidebar-collapsed]/container:bg-white dark:group-[.sidebar-collapsed]/container:bg-gray-900 group-[.sidebar-collapsed]/container:border group-[.sidebar-collapsed]/container:ltr:rounded-r-lg group-[.sidebar-collapsed]/container:rtl:rounded-l-lg group-[.sidebar-collapsed]/container:border-gray-300 group-[.sidebar-collapsed]/container:dark:border-gray-800 group-[.sidebar-collapsed]/container:rounded-none group-[.sidebar-collapsed]/container:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:group-hover/item:!grid">
                            @foreach ($menuItem->getChildren() as $subMenuItem)
                                <a
                                    href="{{ $subMenuItem->getUrl() }}"
                                    class="group/sub flex items-center gap-2.5 whitespace-nowrap rounded-[5px] py-2 text-sm font-medium {{ $subMenuItem->isActive() ? 'text-primary' : 'text-[#67748E] hover:text-primary' }} group-[.sidebar-collapsed]/container:px-5"
                                >
                                    <span class="h-2 w-2 shrink-0 rounded-full border-2 border-white {{ $subMenuItem->isActive() ? 'bg-[#FE9F43]' : 'bg-[rgba(50,71,92,0.38)] group-hover/sub:bg-[#FE9F43]' }}"></span>

                                    {{ $subMenuItem->getName() }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
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

            const scope = toggle.closest('nav') ?? document;

            scope.querySelectorAll('.sidebar-submenu').forEach((otherSubmenu) => {
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