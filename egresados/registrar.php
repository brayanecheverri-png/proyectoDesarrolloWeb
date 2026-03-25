<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Labor Observatory - Register Graduate</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="style.css">
    <script src="config.js"></script>
</head>
<body class="bg-background text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">

<nav class="fixed top-0 z-50 flex justify-between items-center w-full px-12 h-16 bg-slate-50 dark:bg-slate-900 font-public-sans text-sm tracking-tight">
    <div class="flex items-center gap-8">
        <span class="text-xl font-bold tracking-tighter text-green-800 dark:text-green-500">Labor Observatory</span>
        <div class="hidden md:flex gap-6">
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 transition-colors" href="#">Home</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 transition-colors" href="#">Job Offers</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 transition-colors" href="#">Companies</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 transition-colors" href="#">Graduates</a>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all rounded-full"><span class="material-symbols-outlined">account_circle</span></button>
        <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all rounded-full"><span class="material-symbols-outlined">logout</span></button>
    </div>
</nav>

<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 h-screen w-64 border-r border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 font-public-sans text-sm font-medium">
    <div class="px-6 mb-8">
        <h2 class="text-on-surface font-bold text-lg">Management</h2>
        <p class="text-slate-500 text-xs">Observatory Portal</p>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="#"><span class="material-symbols-outlined">post_add</span><span>Register Offer</span></a>
        <a class="flex items-center gap-3 px-6 py-3 text-green-700 bg-white dark:bg-slate-900 rounded-l-lg shadow-sm font-bold" href="#"><span class="material-symbols-outlined">school</span><span>Register Graduate</span></a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="#"><span class="material-symbols-outlined">domain</span><span>Register Company</span></a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="#"><span class="material-symbols-outlined">description</span><span>View Applications</span></a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="#"><span class="material-symbols-outlined">analytics</span><span>Reports</span></a>
    </nav>
</aside>

<main class="ml-64 mt-16 min-h-screen bg-surface p-12">
    <div class="max-w-4xl mx-auto">
        <header class="mb-12">
            <span class="text-label-md uppercase tracking-[0.05rem] text-primary font-semibold mb-2 block">Registration Portal</span>
            <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-4">Register Graduate</h1>
            <p class="text-on-surface-variant text-lg max-w-2xl leading-relaxed">Expand the observatory database by adding new graduate profiles.</p>
        </header>

        <div class="bg-surface-container-lowest rounded-xl p-10 shadow-none ring-1 ring-outline-variant/20">
            <form action="registro.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <div class="col-span-full mb-2">
                    <h3 class="text-on-primary-fixed-variant font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">person</span> Personal Information
                    </h3>
                    <div class="h-px bg-surface-container mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Name</label>
                    <input name="nombre" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="Enter first name" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Last Name</label>
                    <input name="apellido" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="Enter last name" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">ID Number</label>
                    <input name="id_number" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="National Identity Number" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Phone</label>
                    <input name="phone" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="tel" placeholder="+1 (555) 000-0000"/>
                </div>

                <div class="col-span-full space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
                    <input name="email" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="email" placeholder="graduate@example.com" required/>
                </div>

                <div class="col-span-full mt-6 mb-2">
                    <h3 class="text-on-primary-fixed-variant font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">location_on</span> Location Details
                    </h3>
                    <div class="h-px bg-surface-container mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Country</label>
                    <select name="country" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4 appearance-none">
                        <option value="">Select Country</option>
                        <option value="US">United States</option>
                        <option value="UK">United Kingdom</option>
                        <option value="CA">Canada</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Department</label>
                    <input name="department" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="State/Province"/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">City</label>
                    <input name="city" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="Current city"/>
                </div>

                <div class="col-span-full mt-6 mb-2">
                    <h3 class="text-on-primary-fixed-variant font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">lock</span> Account Credentials
                    </h3>
                    <div class="h-px bg-surface-container mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Username</label>
                    <input name="username" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="text" placeholder="Choose a username" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Password</label>
                    <input name="password" class="w-full bg-surface-container-lowest border-none outline outline-1 outline-outline-variant/30 focus:outline-primary-fixed-dim rounded-md py-3 px-4" type="password" placeholder="••••••••" required/>
                </div>

                <div class="col-span-full mt-10 flex flex-col items-center gap-4">
                    <button type="submit" class="w-full md:w-1/2 py-4 px-8 bg-primary text-on-primary font-bold rounded-md hover:bg-primary-container transition-all flex justify-center items-center gap-2 shadow-lg shadow-primary/10">
                        Register Graduate Profile <span class="material-symbols-outlined">send</span>
                    </button>
                    <p class="text-xs text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span> All fields are mandatory.
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-2 bg-secondary-container/30 p-8 rounded-xl flex items-center gap-6">
                <div class="shrink-0 w-16 h-16 bg-white rounded-full flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined text-3xl">verified_user</span>
                </div>
                <div>
                    <h4 class="font-bold text-on-secondary-container">Secure Verification</h4>
                    <p class="text-sm text-on-secondary-fixed-variant leading-relaxed">Profiles are verified against university records.</p>
                </div>
            </div>
            <div class="bg-surface-container-high p-8 rounded-xl flex flex-col justify-center">
                <h4 class="font-bold text-on-surface text-2xl tracking-tighter">98.4%</h4>
                <p class="text-xs font-bold uppercase text-on-surface-variant mt-1">Data Accuracy</p>
                <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                    <div class="h-full bg-primary w-[98%]"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="w-full px-12 flex justify-between items-center bg-slate-50 dark:bg-slate-900 py-8 border-t border-slate-200 mt-auto">
    <div class="flex items-center gap-4">
        <span class="text-sm font-black text-slate-800 dark:text-slate-200">Labor Observatory</span>
        <span class="text-slate-400 text-xs">© 2024. All rights reserved.</span>
    </div>
    <div class="flex gap-8 text-xs uppercase tracking-widest">
        <a class="text-slate-400 hover:text-green-700" href="#">Privacy Policy</a>
        <a class="text-slate-400 hover:text-green-700" href="#">Terms</a>
    </div>
</footer>

</body>
</html>