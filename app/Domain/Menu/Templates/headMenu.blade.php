@php use Leantime\Domain\Auth\Models\Roles; @endphp
@dispatchEvent('beforeHeadMenu')

<ul class="headmenu">

    @dispatchEvent('afterHeadMenuOpen')

    @include('timesheets::partials.stopwatch', [
                   'progressSteps' => $onTheClock
               ])
    <li>
        <a
            href="{{ BASE_URL }}/dashboard/home"
            @if ($activePath == 'dashboard.home')
                class="active"
            @endif
            data-tippy-content="{{ __('popover.home') }}"
        >{!! __('menu.home') !!}</a>
    </li>

    <li>
        <a
            href='{{ BASE_URL }}/projects/showMy'
            @if ($activePath == 'projects.showMy')
                class="active"
            @endif
            data-tippy-content="{{ __('popover.my_projects') }}"
        >{!! __("menu.my_projects") !!}</a>
    </li>

    <li>
        <a
            href='{{ BASE_URL }}/timesheets/showMy'
            @if ($activePath == 'timesheets.showMy')
                class="active"
            @endif
            data-tippy-content="{{ __('popover.my_timesheets') }}"
        >{!! __("menu.my_timesheets") !!}</a>
    </li>

    <li>
        <a
            href='{{ BASE_URL }}/calendar/showMyCalendar'
            @if ($activePath == 'calendar.showMyCalendar')
                class="active"
            @endif
            data-tippy-content="{{ __('popover.my_calendar') }}"
        >{!! __("menu.my_calendar") !!}</a>
    </li>

    </li>
    <li class="notificationDropdown">

        <a
            href='javascript:void(0);'
            class="dropdown-toggle profileHandler notificationHandler"
            data-toggle='dropdown'
            data-tippy-content='{{ __('popover.notifications') }}'
        >
            <span class="fa-solid fa-bell"></span>
            @if($newNotificationCount>0)
                <span class='notificationCounter'>{{ $newNotificationCount }}</span>
            @endif
        </a>

        <div class='dropdown-menu' id='notificationsDropdown'>

            <div class='dropdownTabs'>
                <a
                    href='javascript:void(0);'
                    class='notifcationTabs active'
                    id="notificationsListLink"
                    onclick="toggleNotificationTabs('notifications')"
                >Notification ({{ $totalNewNotifications }})</a>
                <a
                    href='javascript:void(0);'
                    class='notifcationTabs'
                    id="mentionsListLink"
                    onclick="toggleNotificationTabs('mentions')"
                >Mentions ({{ $totalNewMentions }})</a>
            </div>

            <div class="scroll-wrapper">

                <ul id='notificationsList' class='notifcationViewLists'>
                    @if ($totalNotificationCount === 0)
                        <p style='padding: 10px'>{{ __('text.no_notifications') }}</p>
                    @endif

                    @foreach ($notifications as $notif)
                        @if ($notif['type'] == 'mention')
                            @continue
                        @endif

                        <li
                            @if ($notif['read'] == 0)
                                class='new'
                            @endif
                            data-url="{{ $notif['url'] }}"
                            data-id="{{ $notif['id'] }}"
                        >
                            <a href="{{ $notif['url'] }}">
                                <span class="notificationProfileImage">
                                    <img src="{{ BASE_URL }}/api/users?profileImage={{ $notif['authorId'] }}"/>
                                </span>
                                <span class="notificationDate">
                                    @formatDate($notif['datetime'])
                                    @formatTime($notif['datetime'])
                                </span>
                                <span class="notificationTitle">{!! $tpl->convertRelativePaths($notif['message']) !!}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <ul id='mentionsList' style='display:none;' class='notificationViewLists'>
                    @if ($totalMentionCount === 0)
                        <p style="padding: 10px">{{ __('text.no_notifications') }}</p>
                    @endif

                    @foreach ($notifications as $notif)
                        @if ($notif['type'] != 'mention')
                            @continue
                        @endif

                        <li
                            @if ($notif['read'] == 0)
                                class='new'
                            @endif
                            data-url="{{ $notif['url'] }}"
                            data-id="{{ $notif['id'] }}"
                        >
                            <a href="{{ $notif['url'] }}">
                                <span class="notificationProfileImage">
                                    <img src="{{ BASE_URL }}/api/users?profileImage={{ $notif['authorId'] }}"/>
                                </span>
                                <span class="notificationDate">
                                    @formatDate($notif['datetime'])
                                    @formatTime($notif['datetime'])
                                </span>
                                <span class="notificationTitle">{!! $tpl->convertRelativePaths($notif['message']) !!}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

    </li>

    @if ($login::userIsAtLeast("manager"))

        <li class="appsDropdown">

            <a
                href="javascript:void(0);"
                class="dropdown-toggle profileHandler"
                data-toggle="dropdown"
                data-tippy-content="{{ __('popover.company') }}"
            >
                <img src="{{ BASE_URL }}/dist/images/svg/apps-grid-icon.svg"
                     style="width:13px; vertical-align: middle;"/>
            </a>

            <ul class="dropdown-menu">
                <li class="nav-header">{{ __('header.management') }}</li>
                <li><a href="{{ BASE_URL }}/timesheets/showAll">{!! __('menu.all_timesheets') !!}</a></li>

                <li
                    @if (str_starts_with($activePath, 'projects'))
                        class="active"
                    @endif
                >
                    <a href="{{ BASE_URL }}/projects/showAll">{!! __('menu.all_projects') !!}</a>
                </li>

                @if ($login::userIsAtLeast(Roles::$admin))
                    <li
                        @if (str_starts_with($activePath, 'clients'))
                            class="active"
                        @endif
                    >
                        <a href="{{ BASE_URL }}/clients/showAll">{!! __('menu.all_clients') !!}</a>
                    </li>

                    <li
                        @if (str_starts_with($activePath, 'users'))
                            class="active"
                        @endif
                    >
                        <a href="{{ BASE_URL }}/users/showAll">{!! __('menu.all_users') !!}</a>
                    </li>

                    @if ($login::userIsAtLeast(Roles::$owner))
                        <li class="nav-header border">{!! __('label.administration') !!}</li>

                        <li
                            @if (str_starts_with($activePath, 'plugins'))
                                class="active"
                            @endif
                        >
                            <a href="{{ BASE_URL }}/plugins/show/">{!! __('menu.plugins') !!}</a>
                        </li>

                        <li
                            @if (str_starts_with($activePath, 'setting'))
                                class="active"
                            @endif
                        >
                            <a href="{{ BASE_URL }}/setting/editCompanySettings/">{!! __('menu.company_settings') !!}</a>
                        </li>

                        @dispatchEvent('companyMenuEnd', ["module" => explode('.', $activePath)[0]])

                    @endif
                @endif
            </ul>

        </li>

    @endif

    <li>
        <div class="userloggedinfo">

            @include("auth::partials.loginInfo")

        </div>

        @dispatchEvent('afterUser')

    </li>

    @dispatchEvent('beforeHeadMenuClose')

</ul>

@dispatchEvent('afterHeadMenu')

@once
    @push('scripts')
        <script>
            function toggleNotificationTabs(active) {
                jQuery(".notifcationTabs").removeClass("active");
                jQuery('#' + active + 'ListLink').addClass("active");
                jQuery('.notifcationViewLists').hide();
                jQuery('#' + active + 'List').show();
            }

            jQuery(document).ready(function () {
                jQuery('.notificationHandler').on('click', function () {
                    jQuery.ajax(
                        {
                            type: 'PATCH',
                            url: leantime.appUrl + '/api/notifications',
                            data: {
                                id: 'all',
                                action: 'read'
                            }
                        }
                    ).done(function () {
                        jQuery(".notifcationViewLists li.new").removeClass("new");
                        jQuery(".notificationCounter").fadeOut();
                    })
                });

                jQuery('.notificationDropdown .dropdown-menu').on('click', function (e) {
                    e.stopPropagation();
                });

                jQuery('notificationsDropdown li').click(function () {
                    const url = jQuery(this).data('url');
                    const id = jQuery(this).data('id');

                    window.location.href = url;
                })
            });
        </script>
    @endpush
@endonce
