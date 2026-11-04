<?php

declare(strict_types=1);

return [
    'menu' => [
        [
            'url' => '/about',
            'title' => 'About',
            'subItems' => [
                ['url' => "/association/history", 'title' => 'History', 'description' => 'Curious about the history of our association, read it here', 'icon' => 'far fa-clock'],
                ['url' => "/association/honorary-members", 'title' => 'Honorary members', 'description' => 'Francken knows two honorary members: Francken and De Hosson', 'icon' => 'fas fa-award'],
                ['url' => "/association/boards", 'title' => 'Boards', 'description' => 'Current and previous board members', 'icon' => 'fas fa-user-tie'],
                ['url' => "/association/sustainability", 'title' => 'Sustainability', 'description' => 'How we make our association more sustainable', 'icon' => 'fas fa-solar-panel'],
                ['url' => "/association/mental-health", 'title' => 'Mental health', 'description' => 'Where to find help, and our confidential contact persons', 'icon' => 'fas fa-heartbeat'],
                ['url' => "/contact", 'title' => 'Contact', 'description' => 'How to reach the board', 'icon' => 'fas fa-envelope'],
            ],
            'icon' => 'info-circle',
        ],
        [
            'url' => '/study',
            'title' => 'Study',
            'subItems' => [
                ['url' => "/study/books", 'title' => 'Books', 'description' => 'Buy or sell second hand books', 'icon' => 'fas fa-book'],
                ['url' => "/study/research-groups", 'title' => 'Research Groups', 'description' => 'Find a research group for your bachelor or master thesis', 'icon' => 'fas fa-flask'],
                ['url' => "https://www.rug.nl/about-ug/organization/administrative-organization/participation-decision-making/uraad/composition?lang=en", 'title' => 'University Council', 'description' => 'Read about the parties that represent students within the university', 'icon' => 'fas fa-user-tie'],
                ['url' => "https://student.portal.rug.nl/infonet/studenten/regelingen-klacht-inspraak/medezeggenschap/faculteitsraden/faculty-council-fwn", 'title' => 'Faculty Council', 'description' => 'See who is representing students from the FSE', 'icon' => 'fas fa-person-booth'],
                ['url' => "/study/internationals", 'title' => 'Internationals', 'description' => 'Useful information about studying and living in Groningen', 'icon' => 'fas fa-globe-europe'],
            ],
            'icon' => 'graduation-cap',
        ],
        [
            'url' => '/association',
            'title' => 'Association',
            'subItems' => [
                ['url' => "/association/activities", 'title' => 'Activities', 'description' => 'Upcoming career, study and social activities', 'icon' => 'fas fa-calendar-week'],
                ['url' => "/association/news", 'title' => 'News', 'description' => 'The latest news from the association', 'icon' => 'fas fa-newspaper'],
                ['url' => "/association/committees", 'title' => 'Committees', 'description' => 'Want to join a committee?', 'icon' => 'fas fa-users'],
                ['url' => "/association/francken-vrij", 'title' => 'Francken Vrij', 'description' => 'Our popular science magazine', 'icon' => 'fas fa-book-open'],
            ],
            //    'icon' => 'beer',
            'icon' => 'coffee',
        ],
        [
            'url' => '/association/photos',
            'title' => 'Photos',
            'subItems' => [],
            'icon' => 'camera',
        ],
        [
            'url' => '/career',
            'title' => 'Career',
            'subItems' => [
                // Job prospects
                ['url' => "/career/job-openings", 'title' => 'Job openings', 'icon' => 'fas fa-search-dollar', 'description' => 'Curious about your job opportunities, check these job openings!'],
                ['url' => "/career/companies", 'title' => 'Company profiles', 'icon' => 'fas fa-building', 'description' => ''],
            ],
            'icon' => 'suitcase',
        ],
    ],

    'admin-menu' => [
        [
            "name" => "Association",
            "url" => "association",
            "items" => [
                [
                    "name" => "Registration requests",
                    "url" => "registration-requests",
                    "icon" => "fas fa-user-plus",
                    "description" => "New members who signed up",
                    "works" => true,
                    "can" => "dashboard:registrations-read",
                ],
                [
                    "name" => "Boards",
                    "url" => "boards",
                    "icon" => "fas fa-user-tie",
                    "description" => "Boards, their members and photos",
                    "works" => true,
                    "can" => "dashboard:board-members-read",
                ],
                [
                    "name" => "Members",
                    "url" => "members",
                    "icon" => "fas fa-users",
                    "description" => "All members and their details",
                    "works" => true,
                    "can" => "dashboard:members-read",
                ],
                [
                    "name" => "Committees",
                    "url" => "committees",
                    // The pages of committees are pages of a board
                    "also" => ["admin/association/boards/*/committees"],
                    "icon" => "fas fa-users-cog",
                    "description" => "Committees and their members",
                    "works" => true,
                    "can" => "dashboard:committees-read",
                ],
                [
                    "name" => "Activities",
                    "url" => "activities",
                    "icon" => "fas fa-calendar-alt",
                    "description" => "Activities and their sign ups",
                    "works" => true,
                    "can" => "dashboard:activities-read",
                ],
                [
                    "name" => "News",
                    "url" => "news",
                    "icon" => "fas fa-newspaper",
                    "description" => "News articles",
                    "works" => true,
                    "can" => "dashboard:news-read",
                ],
                [
                    "name" => "Photos",
                    "url" => "photo-albums",
                    "icon" => "fas fa-images",
                    "description" => "Photo albums",
                    "works" => true,
                    "can" => "dashboard:photos-read",
                ],
                [
                    "name" => "Symposium",
                    "url" => "symposia",
                    "icon" => "fas fa-microphone",
                    "description" => "Symposia and their participants",
                    "works" => true,
                    "can" => "dashboard:symposia-read",
                ],
                [
                    "name" => "Alumni activity",
                    "url" => "alumni-activity",
                    "icon" => "fas fa-user-graduate",
                    "description" => "Who comes to the alumni activity",
                    "works" => true,
                    "can" => "dashboard:alumni-activity",
                ],
                [
                    "name" => "Custom pages",
                    "url" => "pages",
                    "icon" => "fas fa-file-alt",
                    "description" => "Pages with their own text",
                    "works" => true,
                    "can" => "dashboard:pages-read",
                ],
            ]
        ],
        [
            "name" => "Study",
            "url" => "study",
            "items" => [
                [
                    "name" => "Research Groups",
                    "url" => "research-groups",
                    "icon" => "fas fa-flask",
                    "description" => "Research groups of the study",
                    "works" => false,
                    "can" => "dashboard:research-groups-read",
                ],
                [
                    "name" => "Books",
                    "url" => "books",
                    "icon" => "fas fa-book",
                    "description" => "Second-hand books for sale",
                    "works" => true,
                    "can" => "dashboard:books-read",
                ],
            ]
        ], [
            "name" => "Francken Vrij",
            "url" => "association/francken-vrij",
            "items" => [
                [
                    "name" => "Editions",
                    "url" => "editions",
                    "icon" => "fas fa-book-open",
                    "description" => "Editions of the Francken Vrij",
                    "works" => true,
                    "can" => "dashboard:francken-vrij-read",
                ],
                [
                    "name" => "Subscriptions",
                    "url" => "subscriptions",
                    "icon" => "fas fa-envelope-open-text",
                    "description" => "Who receives the Francken Vrij",
                    "works" => true,
                    "can" => "dashboard:francken-vrij-subscriptions-read",
                ],
                ],
            ], [
            "name" => "Treasurer",
            "url" => "treasurer",
            "items" => [
                [
                    "name" => "Deductions",
                    "url" => "deductions",
                    "icon" => "fas fa-file-invoice-dollar",
                    "description" => "Direct debits from members",
                    "works" => true,
                    "can" => "board-treasurer",
                ],
                [
                    "name" => "Products",
                    "url" => "products",
                    "icon" => "fas fa-beer",
                    "description" => "Products of the Francken room",
                    "works" => true,
                    "can" => "board-treasurer",
                ],
                [
                    "name" => "Transactions",
                    "url" => "transactions",
                    "icon" => "fas fa-exchange-alt",
                    "description" => "What members bought",
                    "works" => true,
                    "can" => "board-treasurer",
                ],
            ]
        ], [
            "name" => "Extern",
            "url" => "extern",
            "items" => [
                [
                    "name" => "Partners",
                    "url" => "partners",
                    "icon" => "fas fa-handshake",
                    "description" => "Companies, their profiles and job openings",
                    "works" => true,
                    "can" => "dashboard:companies-read",
                ],
                [
                    "name" => "Factsheet",
                    "url" => "fact-sheet",
                    "icon" => "fas fa-chart-pie",
                    "description" => "Numbers about the association for companies",
                    "works" => true,
                    "can" => "dashboard:fact-sheet-read",
                ]
            ]
        ], [
            "name" => "Compucie",
            "url" => "compucie",
            "items" => [
                [
                    "name" => "Accounts",
                    "url" => "accounts",
                    "icon" => "fas fa-user-lock",
                    "description" => "Login accounts of members",
                    "works" => true,
                    "can" => "dashboard:accounts-read",
                ],
                [
                    "name" => "Settings",
                    "url" => "settings",
                    "icon" => "fas fa-cog",
                    "description" => "Settings of the website",
                    "works" => true,
                    "can" => "dashboard:settings-read",
                ],
                [
                    "name" => "Roles",
                    "url" => "roles",
                    "icon" => "fas fa-user-shield",
                    "description" => "Roles and their permissions",
                    "works" => true,
                    "can" => "dashboard:permissions-read",
                ],
                [
                    "name" => "Media",
                    "url" => "media",
                    "icon" => "fas fa-photo-video",
                    "description" => "Uploaded files",
                    "works" => true,
                    "can" => "dashboard:media-read",
                ],
                [
                    "name" => "Telescope",
                    "url" => "telescope",
                    "icon" => "fas fa-binoculars",
                    "description" => "Requests, errors and queries, for debugging",
                    "works" => true,
                    "can" => "dashboard:super-admin-read",
                ],
                [
                    "name" => "Horizon",
                    "url" => "horizon",
                    "icon" => "fas fa-tasks",
                    "description" => "Jobs that run in the background",
                    "works" => true,
                    "can" => "dashboard:super-admin-read",
                ],
            ]
        ],
    ]
];
