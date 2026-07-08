<!DOCTYPE html>

<html class="scroll-smooth" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Access Edu | Master English for the Global Stage</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&amp;family=Montserrat:wght@400;500;600;700&amp;family=Quicksand:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .card-lift {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .card-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 31, 63, 0.12);
        }
        .inner-track {
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline": "#897362",
                        "on-background": "#1b1c1c",
                        "surface-container-high": "#eae8e7",
                        "surface-container": "#efeded",
                        "on-tertiary-fixed-variant": "#454748",
                        "surface-container-highest": "#e4e2e2",
                        "secondary": "#476083",
                        "surface-variant": "#e4e2e2",
                        "primary-fixed-dim": "#ffb77d",
                        "secondary-container": "#bdd6ff",
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "on-secondary-fixed-variant": "#2f486a",
                        "outline-variant": "#ddc1ae",
                        "surface-bright": "#fbf9f8",
                        "on-tertiary-container": "#3c3f40",
                        "on-secondary-container": "#445d80",
                        "tertiary-fixed": "#e1e3e4",
                        "on-tertiary-fixed": "#191c1d",
                        "on-secondary-fixed": "#001c3a",
                        "background": "#fbf9f8",
                        "on-primary-fixed-variant": "#6e3900",
                        "tertiary-fixed-dim": "#c5c7c8",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed": "#d4e3ff",
                        "surface-container-low": "#f5f3f3",
                        "inverse-surface": "#303030",
                        "primary-container": "#ff8c00",
                        "primary": "#904d00",
                        "tertiary-container": "#a8aaab",
                        "secondary-fixed-dim": "#afc8f0",
                        "on-surface": "#1b1c1c",
                        "primary-fixed": "#ffdcc3",
                        "surface-dim": "#dbd9d9",
                        "on-primary-container": "#623200",
                        "inverse-primary": "#ffb77d",
                        "on-surface-variant": "#564334",
                        "error": "#ba1a1a",
                        "surface-tint": "#904d00",
                        "on-secondary": "#ffffff",
                        "inverse-on-surface": "#f2f0f0",
                        "on-error": "#ffffff",
                        "surface": "#fbf9f8",
                        "on-primary-fixed": "#2f1500",
                        "tertiary": "#5c5f60"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "margin-desktop": "48px",
                        "base": "8px",
                        "xs": "4px",
                        "md": "24px",
                        "xl": "64px",
                        "sm": "12px",
                        "lg": "40px"
                    },
                    "fontFamily": {
                        "headline-md": ["Playfair Display"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "label-md": ["Montserrat"],
                        "body-md": ["Quicksand"],
                        "headline-lg": ["Playfair Display"],
                        "body-lg": ["Quicksand"],
                        "label-lg": ["Montserrat"],
                        "display-lg": ["Playfair Display"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "800"}],
                        "label-md": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "500"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "800"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "500"}],
                        "label-lg": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "900"}]
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
<!-- Top Navigation Bar -->
<header class="bg-surface shadow-sm sticky top-0 z-50">
<nav class="flex justify-between items-center w-full px-margin-desktop py-4 max-w-[1280px] mx-auto">
<div class="flex items-center gap-base">
<span class="text-headline-md font-headline-md font-black text-primary">Access Edu</span>
</div>
<div class="hidden md:flex items-center gap-lg">
<a class="text-primary border-b-2 border-primary font-bold pb-1 font-label-lg text-label-lg" href="#">Home</a>
<a class="text-secondary font-medium hover:text-primary transition-colors font-label-lg text-label-lg" href="#">Courses</a>
<a class="text-secondary font-medium hover:text-primary transition-colors font-label-lg text-label-lg" href="#">About</a>
<a class="text-secondary font-medium hover:text-primary transition-colors font-label-lg text-label-lg" href="#">Contact</a>
</div>
<div class="flex items-center gap-md">
<div class="relative hidden lg:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm w-64 focus:ring-2 focus:ring-primary transition-all font-body-md" placeholder="Search courses..." type="text"/>
</div>
<button class="hidden sm:block px-6 py-2 border-2 border-primary-container text-primary-container rounded-full font-label-lg text-label-lg hover:bg-primary-container hover:text-white transition-all active:scale-95 cursor-pointer">Login</button>
<button class="material-symbols-outlined text-secondary hover:text-primary cursor-pointer active:scale-95 transition-transform" data-icon="notifications">notifications</button>
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-container">
<img alt="User profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBe7YWbDARJFpDqxB8VkmmnPIFntY-6k5MK_xbGJ7Qq4RGCBwpQokRLq6Hr0uuLfOCnkaTHjJcxbb4ILt8dKO-1oQrnjtTBDBLXOJCBPqmjEawjPy4z5Q-KgKCe7LRI5ywo0-DWFFcgaADwNUDet1YJGfQPHaqvcRyoC9LMCJH89Zru2sdKKTLd9UeaxxJ0DcTLQxjRsJnlUHOsrdFSIQ6_VodgI69ayasoGXQZOZ5C8JMDrTntGXYUtU933b-8iq7wpRNdM3e7FJkh"/>
</div>
</div>
</nav>
</header>
<main>
<!-- Updated Hero Section -->
<section class="bg-surface-bright py-xl px-margin-desktop overflow-hidden">
<div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-xl items-center">
<div class="max-w-2xl">
<span class="inline-block px-4 py-1 bg-primary-container text-on-primary-container font-label-lg text-label-lg rounded-full mb-6">EMPOWERING GLOBAL COMMUNICATORS</span>
<h1 class="font-display-lg text-display-lg text-on-background mb-6 leading-tight italic">Master English for the <span class="text-primary-container">Global Stage</span></h1>
<p class="font-body-lg text-body-lg text-secondary mb-8 max-w-xl">
                        Unlock international opportunities with structured, professional English courses designed for ambitious learners. From business mastery to academic excellence.
                    </p>
<div class="flex flex-wrap gap-md">
<button class="bg-primary-container text-white px-8 py-4 rounded-xl font-label-lg text-label-lg shadow-[0_4px_0_#904d00] hover:shadow-none hover:translate-y-[2px] transition-all cursor-pointer active:scale-95">
                            Get Started Free
                        </button>
<button class="bg-transparent border-2 border-primary-container text-primary-container px-8 py-4 rounded-xl font-label-lg text-label-lg hover:bg-primary-container/5 transition-all cursor-pointer active:scale-95">
                            Browse Courses
                        </button>
</div>
</div>
<!-- Group of 3 images -->
<div class="grid grid-cols-3 gap-sm h-[400px] md:h-[500px]">
<div class="rounded-2xl overflow-hidden h-full shadow-lg">
<img alt="Student learning" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARqrLWO5C-divR3fEyJ_8yhic1W3IwEER2oYH7M2S1a1RDbKVXBykOmMGd6E_fRc_NMqP7wsnNz4pTUxfcDo3hCEakfv_cB-diLiLB2jPfOYD0vizXa1abiHSZ41FuttWXmAwui9-C2Rag7sRpr8pWsygBSz2TAq6ysZWRX76e9YNEFFcqXR0V2bBnoVV21V5ExY4hZ1IgFM7gtL9lreRAYDYAPnykZRsMSf-KzmZLsdthIDx-uyashJFVN1aHi5leUx7p973yDZUx"/>
</div>
<div class="rounded-2xl overflow-hidden h-full shadow-lg translate-y-12">
<img alt="Diverse students collaborating" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXY4wD2dWY_qWRB7FnjdapMHkD-Fu39HX8CrxKVxYox57Xo30m7sq4EV2f_X9GYNkjCzWsv_juw8YRTdp_qEcpGtMnCZ2Bs4NB9Qsga51tHc98iddDIRtVI5B1kE8Yl1KSixy95g4iJCAIIJkIOrpLm47LR2gfOhWWQ4VlVh3PdQWUwgxyaknZbJiSIEdSkOaALjf-ksUJMev9DfqsFHCIbvqrDU5h4-K_bRnD7aWeeUGT_4-isqheRPLuJ_FoHNUUcLN_XzpiOSJH"/>
</div>
<div class="rounded-2xl overflow-hidden h-full shadow-lg">
<img alt="Professional tutor" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzYyO_ig-wVn872s6dI5mEj0G8pSzTRAg963NXKGJEAfxCUIwkbcTCX4aZAnGM8Yp6IPJlOJm-TxHsg6iVJAkcXVRwoJrbY0I3s-4x1jSc1HQetjUp02EDrPKZmiWSTjU7ZPZXZcLaupIpAxaR2NJoUGDGJyK5vjNvbh3pmi8avSynN1KI0mff2i-FJUmdFgiAv1vAqYotxU_pYMbFYdsHxBVfbdcHIef0VW6YpBEGAWNw89UeiHdTEeOczueAw_mjv1zr-2sAuJ1j"/>
</div>
</div>
</div>
</section>
<!-- Explore Categories -->
<section class="py-xl px-margin-desktop max-w-[1280px] mx-auto">
<div class="flex justify-between items-end mb-lg">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-background mb-2">Explore Categories</h2>
<p class="text-secondary font-body-md text-body-md">Find the right path for your learning goals</p>
</div>
<a class="text-primary font-bold hover:underline flex items-center gap-1 font-label-lg text-label-lg" href="#">
                    See all categories <span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
<!-- Category 1 -->
<div class="card-lift bg-white p-6 rounded-xl border border-surface-container shadow-sm flex flex-col items-center text-center cursor-pointer">
<div class="w-16 h-16 bg-secondary-container rounded-full flex items-center justify-center mb-4 text-on-secondary-container">
<span class="material-symbols-outlined text-4xl">business_center</span>
</div>
<h3 class="font-headline-md text-headline-md mb-2">Business English</h3>
<p class="text-secondary text-sm font-body-md">Professional communication, meetings, and presentations.</p>
</div>
<!-- Category 2 -->
<div class="card-lift bg-white p-6 rounded-xl border border-surface-container shadow-sm flex flex-col items-center text-center cursor-pointer">
<div class="w-16 h-16 bg-primary-fixed rounded-full flex items-center justify-center mb-4 text-on-primary-fixed-variant">
<span class="material-symbols-outlined text-4xl">school</span>
</div>
<h3 class="font-headline-md text-headline-md mb-2">Academic Prep</h3>
<p class="text-secondary text-sm font-body-md">IELTS, TOEFL, and university-level writing skills.</p>
</div>
<!-- Category 3 -->
<div class="card-lift bg-white p-6 rounded-xl border border-surface-container shadow-sm flex flex-col items-center text-center cursor-pointer">
<div class="w-16 h-16 bg-tertiary-fixed rounded-full flex items-center justify-center mb-4 text-tertiary">
<span class="material-symbols-outlined text-4xl">record_voice_over</span>
</div>
<h3 class="font-headline-md text-headline-md mb-2">Fluency Hub</h3>
<p class="text-secondary text-sm font-body-md">Daily conversation, slang, and cultural nuances.</p>
</div>
<!-- Category 4 -->
<div class="card-lift bg-white p-6 rounded-xl border border-surface-container shadow-sm flex flex-col items-center text-center cursor-pointer">
<div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mb-4 text-on-surface-variant">
<span class="material-symbols-outlined text-4xl">edit_note</span>
</div>
<h3 class="font-headline-md text-headline-md mb-2">Creative Writing</h3>
<p class="text-secondary text-sm font-body-md">Expression, storytelling, and narrative structures.</p>
</div>
</div>
</section>
<!-- Featured Courses -->
<section class="bg-surface-container-low py-xl px-margin-desktop overflow-hidden">
<div class="max-w-[1280px] mx-auto">
<div class="flex flex-col md:flex-row justify-between items-end mb-xl gap-4">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-background">Popular Courses</h2>
</div>
<button class="bg-secondary text-white px-6 py-3 rounded-full font-label-lg flex items-center gap-2 hover:bg-primary transition-all active:scale-95">
                        Load More Courses
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<!-- Course Card 1 -->
<div class="bg-white p-4 rounded-2xl border border-surface-container shadow-sm flex flex-col h-full group">
<div class="relative h-48 rounded-xl overflow-hidden mb-4">
<img alt="Business English" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXY4wD2dWY_qWRB7FnjdapMHkD-Fu39HX8CrxKVxYox57Xo30m7sq4EV2f_X9GYNkjCzWsv_juw8YRTdp_qEcpGtMnCZ2Bs4NB9Qsga51tHc98iddDIRtVI5B1kE8Yl1KSixy95g4iJCAIIJkIOrpLm47LR2gfOhWWQ4VlVh3PdQWUwgxyaknZbJiSIEdSkOaALjf-ksUJMev9DfqsFHCIbvqrDU5h4-K_bRnD7aWeeUGT_4-isqheRPLuJ_FoHNUUcLN_XzpiOSJH"/>
<div class="absolute bottom-4 left-4">
<span class="bg-on-background/80 text-white text-[10px] font-bold px-3 py-1 rounded-lg uppercase tracking-widest font-label-lg">Business</span>
</div>
</div>
<div class="flex justify-between items-center mb-2">
<div class="flex items-center gap-1 text-primary-container">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-secondary text-xs font-bold ml-1 font-label-lg">4.8k</span>
</div>
<span class="text-primary font-bold text-sm font-label-lg">$50.00</span>
</div>
<h4 class="font-headline-md text-headline-md mb-4 group-hover:text-primary transition-colors line-clamp-2">Strategic Business Communication &amp; Analysis</h4>
<div class="flex items-center justify-between py-3 border-y border-surface-container mb-4 text-[10px] font-bold text-secondary font-label-lg">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">menu_book</span>
                                Lesson 10
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">schedule</span>
                                19h 30m
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">person</span>
                                Students 20+
                            </div>
</div>
<div class="flex items-center justify-between mt-auto">
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full overflow-hidden">
<img alt="Instructor" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBe7YWbDARJFpDqxB8VkmmnPIFntY-6k5MK_xbGJ7Qq4RGCBwpQokRLq6Hr0uuLfOCnkaTHjJcxbb4ILt8dKO-1oQrnjtTBDBLXOJCBPqmjEawjPy4z5Q-KgKCe7LRI5ywo0-DWFFcgaADwNUDet1YJGfQPHaqvcRyoC9LMCJH89Zru2sdKKTLd9UeaxxJ0DcTLQxjRsJnlUHOsrdFSIQ6_VodgI69ayasoGXQZOZ5C8JMDrTntGXYUtU933b-8iq7wpRNdM3e7FJkh"/>
</div>
<span class="text-xs font-bold text-on-background font-label-lg">Samantha</span>
</div>
<button class="bg-secondary text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 hover:bg-primary transition-all font-label-lg">
                                Enroll
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
</div>
<!-- Course Card 2 -->
<div class="bg-white p-4 rounded-2xl border border-surface-container shadow-sm flex flex-col h-full group">
<div class="relative h-48 rounded-xl overflow-hidden mb-4">
<img alt="Exams" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBgeMCVvbq9Kv1yf1dS0vgSvkmCD_FV7XpKq27MIWKR8xEEECmny4Jfz4ZLAjG4QTUf5nEa-_IkNBRBd3oNmnSceU5RWdCDZPp-FKD0AUnLI9IOW9HMvHhnn18ZHSHTPBukemst5fKzjWzDHDNtbzP6lVt2_rrJ-XHXH7uCchJijrVLtOdtPqN552l8lWV2jbv6ZFIsFTT0DIcPVz2InQNzzA4HkBfVJEuFZzRsOwfEJ3BuNo2JyurHI-DhSsUAA4u2HgyD2AG-KxC-"/>
<div class="absolute bottom-4 left-4">
<span class="bg-on-background/80 text-white text-[10px] font-bold px-3 py-1 rounded-lg uppercase tracking-widest font-label-lg">Exams</span>
</div>
</div>
<div class="flex justify-between items-center mb-2">
<div class="flex items-center gap-1 text-primary-container">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-secondary text-xs font-bold ml-1 font-label-lg">4.5k</span>
</div>
<span class="text-primary font-bold text-sm font-label-lg">$50.00</span>
</div>
<h4 class="font-headline-md text-headline-md mb-4 group-hover:text-primary transition-colors line-clamp-2">IELTS Mastery: 8.0+ Band Preparation</h4>
<div class="flex items-center justify-between py-3 border-y border-surface-container mb-4 text-[10px] font-bold text-secondary font-label-lg">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">menu_book</span>
                                Lesson 10
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">schedule</span>
                                19h 30m
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">person</span>
                                Students 20+
                            </div>
</div>
<div class="flex items-center justify-between mt-auto">
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full overflow-hidden">
<img alt="Instructor" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBe7YWbDARJFpDqxB8VkmmnPIFntY-6k5MK_xbGJ7Qq4RGCBwpQokRLq6Hr0uuLfOCnkaTHjJcxbb4ILt8dKO-1oQrnjtTBDBLXOJCBPqmjEawjPy4z5Q-KgKCe7LRI5ywo0-DWFFcgaADwNUDet1YJGfQPHaqvcRyoC9LMCJH89Zru2sdKKTLd9UeaxxJ0DcTLQxjRsJnlUHOsrdFSIQ6_VodgI69ayasoGXQZOZ5C8JMDrTntGXYUtU933b-8iq7wpRNdM3e7FJkh"/>
</div>
<span class="text-xs font-bold text-on-background font-label-lg">Charles</span>
</div>
<button class="bg-secondary text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 hover:bg-primary transition-all font-label-lg">
                                Enroll
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
</div>
<!-- Course Card 3 -->
<div class="bg-white p-4 rounded-2xl border border-surface-container shadow-sm flex flex-col h-full group">
<div class="relative h-48 rounded-xl overflow-hidden mb-4">
<img alt="Daily Life" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzYyO_ig-wVn872s6dI5mEj0G8pSzTRAg963NXKGJEAfxCUIwkbcTCX4aZAnGM8Yp6IPJlOJm-TxHsg6iVJAkcXVRwoJrbY0I3s-4x1jSc1HQetjUp02EDrPKZmiWSTjU7ZPZXZcLaupIpAxaR2NJoUGDGJyK5vjNvbh3pmi8avSynN1KI0mff2i-FJUmdFgiAv1vAqYotxU_pYMbFYdsHxBVfbdcHIef0VW6YpBEGAWNw89UeiHdTEeOczueAw_mjv1zr-2sAuJ1j"/>
<div class="absolute bottom-4 left-4">
<span class="bg-on-background/80 text-white text-[10px] font-bold px-3 py-1 rounded-lg uppercase tracking-widest font-label-lg">Daily Life</span>
</div>
</div>
<div class="flex justify-between items-center mb-2">
<div class="flex items-center gap-1 text-primary-container">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-secondary text-xs font-bold ml-1 font-label-lg">4.5k</span>
</div>
<span class="text-primary font-bold text-sm font-label-lg">$50.00</span>
</div>
<h4 class="font-headline-md text-headline-md mb-4 group-hover:text-primary transition-colors line-clamp-2">Natural English Fluency for Daily Life</h4>
<div class="flex items-center justify-between py-3 border-y border-surface-container mb-4 text-[10px] font-bold text-secondary font-label-lg">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">menu_book</span>
                                Lesson 10
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">schedule</span>
                                19h 30m
                            </div>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm">person</span>
                                Students 20+
                            </div>
</div>
<div class="flex items-center justify-between mt-auto">
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full overflow-hidden">
<img alt="Instructor" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBe7YWbDARJFpDqxB8VkmmnPIFntY-6k5MK_xbGJ7Qq4RGCBwpQokRLq6Hr0uuLfOCnkaTHjJcxbb4ILt8dKO-1oQrnjtTBDBLXOJCBPqmjEawjPy4z5Q-KgKCe7LRI5ywo0-DWFFcgaADwNUDet1YJGfQPHaqvcRyoC9LMCJH89Zru2sdKKTLd9UeaxxJ0DcTLQxjRsJnlUHOsrdFSIQ6_VodgI69ayasoGXQZOZ5C8JMDrTntGXYUtU933b-8iq7wpRNdM3e7FJkh"/>
</div>
<span class="text-xs font-bold text-on-background font-label-lg">Morgan</span>
</div>
<button class="bg-secondary text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 hover:bg-primary transition-all font-label-lg">
                                Enroll
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- About Section -->
<section class="py-xl px-margin-desktop max-w-[1280px] mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg items-center">
<div class="space-y-6">
<h2 class="font-headline-lg text-headline-lg text-on-background">About Access Edu</h2>
<p class="text-secondary font-body-lg text-body-lg">
                        We empower global communicators through immersive English language learning. Our mission is to bridge cultures and unlock international opportunities for learners worldwide.
                    </p>
<div class="grid grid-cols-2 gap-md pt-4">
<div class="flex flex-col gap-2">
<div class="w-12 h-12 bg-primary-container/20 rounded-lg flex items-center justify-center text-primary">
<span class="material-symbols-outlined">groups</span>
</div>
<div>
<div class="font-bold text-headline-md text-primary">50k+</div>
<div class="text-secondary text-sm font-medium font-body-md">Active Learners</div>
</div>
</div>
<div class="flex flex-col gap-2">
<div class="w-12 h-12 bg-primary-container/20 rounded-lg flex items-center justify-center text-primary">
<span class="material-symbols-outlined">sentiment_very_satisfied</span>
</div>
<div>
<div class="font-bold text-headline-md text-primary">98%</div>
<div class="text-secondary text-sm font-medium font-body-md">Satisfaction Rate</div>
</div>
</div>
</div>
</div>
<div class="relative hidden md:block">
<div class="aspect-square rounded-[32px] overflow-hidden">
<img alt="Access Edu Community" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARqrLWO5C-divR3fEyJ_8yhic1W3IwEER2oYH7M2S1a1RDbKVXBykOmMGd6E_fRc_NMqP7wsnNz4pTUxfcDo3hCEakfv_cB-diLiLB2jPfOYD0vizXa1abiHSZ41FuttWXmAwui9-C2Rag7sRpr8pWsygBSz2TAq6ysZWRX76e9YNEFFcqXR0V2bBnoVV21V5ExY4hZ1IgFM7gtL9lreRAYDYAPnykZRsMSf-KzmZLsdthIDx-uyashJFVN1aHi5leUx7p973yDZUx"/>
</div>
<div class="absolute -bottom-4 -right-4 w-32 h-32 bg-primary-container rounded-2xl -z-10"></div>
</div>
</div>
</section>
<!-- Contact Section -->
<section class="py-xl px-margin-desktop max-w-[1280px] mx-auto">
<div class="text-center mb-lg">
<h2 class="font-headline-lg text-headline-lg text-on-background mb-2">Contact Us</h2>
<p class="text-secondary font-body-md text-body-md">Have questions? We're here to help you on your learning journey.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="bg-surface-container-low p-8 rounded-xl">
<form class="space-y-4">
<div>
<label class="block text-label-lg font-label-lg text-on-background mb-2">Name</label>
<input class="w-full px-4 py-3 rounded-lg border-none bg-white focus:ring-2 focus:ring-primary font-body-md" placeholder="Your Name" type="text"/>
</div>
<div>
<label class="block text-label-lg font-label-lg text-on-background mb-2">Email</label>
<input class="w-full px-4 py-3 rounded-lg border-none bg-white focus:ring-2 focus:ring-primary font-body-md" placeholder="your@email.com" type="email"/>
</div>
<div>
<label class="block text-label-lg font-label-lg text-on-background mb-2">Message</label>
<textarea class="w-full px-4 py-3 rounded-lg border-none bg-white focus:ring-2 focus:ring-primary font-body-md" placeholder="How can we help?" rows="4"></textarea>
</div>
<button class="w-full bg-primary-container text-white px-8 py-4 rounded-xl font-label-lg text-label-lg shadow-[0_4px_0_#904d00] hover:shadow-none hover:translate-y-[2px] transition-all cursor-pointer active:scale-95" type="submit">
                            Send Message
                        </button>
</form>
</div>
<div class="flex flex-col justify-center space-y-8">
<div class="flex items-start gap-4">
<div class="w-12 h-12 bg-primary-container/20 rounded-lg flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined">mail</span>
</div>
<div>
<h4 class="font-bold text-on-background font-label-lg">Email</h4>
<p class="text-secondary font-body-md">support@accessedu.com</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-12 h-12 bg-primary-container/20 rounded-lg flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined">call</span>
</div>
<div>
<h4 class="font-bold text-on-background font-label-lg">Phone</h4>
<p class="text-secondary font-body-md">+1 (555) 000-0000</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-12 h-12 bg-primary-container/20 rounded-lg flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined">location_on</span>
</div>
<div>
<h4 class="font-bold text-on-background font-label-lg">Address</h4>
<p class="text-secondary font-body-md">123 Learning Way, EdTech City</p>
</div>
</div>
</div>
</div>
</section>
<!-- Newsletter / CTA -->
<section class="mb-xl px-margin-desktop max-w-[1280px] mx-auto">
<div class="bg-secondary rounded-[32px] p-12 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-lg">
<!-- Background decoration -->
<div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
<div class="absolute -left-10 -top-10 w-40 h-40 bg-primary-container/20 rounded-full blur-2xl"></div>
<div class="relative z-10 max-w-xl text-center md:text-left">
<h2 class="font-headline-lg text-headline-lg text-white mb-4 italic">Start your journey today.</h2>
<p class="text-surface-variant text-body-lg">Join 50,000+ learners mastering English. New courses added every month.</p>
</div>
<div class="relative z-10 w-full md:w-auto">
<form class="flex flex-col sm:flex-row gap-4">
<input class="px-6 py-4 rounded-xl border-none w-full sm:w-80 bg-white/10 text-white placeholder:text-white/60 focus:ring-2 focus:ring-primary-container font-body-md" placeholder="Enter your email" type="email"/>
<button class="bg-primary-container text-white px-8 py-4 rounded-xl font-label-lg whitespace-nowrap hover:bg-primary transition-all shadow-[0_4px_0_#904d00] active:translate-y-[4px] active:shadow-none" type="submit">
                            Get Free Lesson
                        </button>
</form>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-on-background py-xl px-margin-desktop">
<div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-gutter">
<div class="space-y-6">
<span class="text-headline-md font-headline-md font-black text-on-primary">Access Edu</span>
<p class="text-surface-variant font-body-md pr-4">Redefining English education for the digital nomad and global professional. Reliable, modern, and effective.</p>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors" href="#">
<svg class="w-5 h-5 text-white" fill="currentColor" viewbox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg>
</a>
<a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors" href="#">
<svg class="w-5 h-5 text-white" fill="currentColor" viewbox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.584.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
</a>
</div>
</div>
<div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-gutter">
<div class="space-y-4">
<h5 class="text-white font-label-lg uppercase tracking-widest text-[10px]">Resources</h5>
<ul class="space-y-2">
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Course Catalog</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Study Material</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Blog</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Student Stories</a></li>
</ul>
</div>
<div class="space-y-4">
<h5 class="text-white font-label-lg uppercase tracking-widest text-[10px]">Company</h5>
<ul class="space-y-2">
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">About Us</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Careers</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Partners</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Newsletter</a></li>
</ul>
</div>
<div class="space-y-4">
<h5 class="text-white font-label-lg uppercase tracking-widest text-[10px]">Legal</h5>
<ul class="space-y-2">
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Privacy Policy</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Terms of Service</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Cookie Policy</a></li>
<li><a class="text-surface-variant hover:text-white transition-all font-body-md text-sm" href="#">Support</a></li>
</ul>
</div>
</div>
</div>
<div class="max-w-[1280px] mx-auto mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-surface-variant font-label-lg text-label-lg">© 2024 Access Edu. All rights reserved.</p>
<div class="flex gap-4 items-center">
<span class="text-surface-variant text-xs font-label-lg">Language:</span>
<select class="bg-transparent text-white border-none text-xs focus:ring-0 cursor-pointer font-label-lg">
<option class="bg-on-background">English (US)</option>
<option class="bg-on-background">Español</option>
<option class="bg-on-background">Français</option>
<option class="bg-on-background">日本語</option>
</select>
</div>
</div>
</footer>
<script>
        // Micro-interaction for hover effects on navigation items
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('mouseenter', () => {
                if (!link.classList.contains('text-primary')) {
                    link.style.transform = 'translateY(-1px)';
                }
            });
            link.addEventListener('mouseleave', () => {
                link.style.transform = 'translateY(0)';
            });
        });

        // Simple scroll reveal effect for cards
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-lift, .group').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>
</body></html>