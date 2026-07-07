@props(['menuItem'])

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
