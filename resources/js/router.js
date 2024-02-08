const routes = [
    {
      path: '/auth',
      component: AuthLayout,
      redirect: '/auth/login',
      name: 'Auth',
      children: [
        {
          path: 'login',
          component: AuthLoginPage,
        },
        {
          path: 'register',
          component: AuthRegisterPage,
        },
        {
          path: 'recover',
          component: AuthRecoverPage,
        }
      ],
    },
    {
      path: '/',
      component: LandingLayout,
      redirect: '/',
      name: 'Public',
      children: [
        {
          path: '/',
          component: CreatePage,
        }
      ],
    },
    {
      path: '/user',
      component: UserLayout,
      redirect: '/user/home',
      name: 'User',
      children: [
        {
          path: 'home',
          component: UserHomePage,
        },
        {
          path: 'dashboard',
          component: UserDashboardPage,
        },
        {
          path: 'account',
          component: UserAccountPage,
        },
        {
          path: 'urls',
          component: UserUrlsList,
          meta: { middleware: 'auth', gate: 'manage_food_categories' },
        },
        {
          path: 'urls/new',
          component: UserUrlsNew,
          meta: { middleware: 'auth', gate: 'manage_food_categories' },
        },
        {
          path: 'urls/:id/edit',
          component: UserUrlsEdit,
          meta: { middleware: 'auth', gate: 'manage_food_categories' },
        },
        {
          path: 'subsets',
          component: UserSubsetsList,
          meta: { middleware: 'auth', gate: 'manage_food_items' },
        },
        {
          path: 'subsets/new',
          component: UserSubsetsNew,
          meta: { middleware: 'auth', gate: 'manage_food_items' },
        },
        {
          path: 'subsets/:uuid/edit',
          component: UserSubsetsEdit,
          meta: { middleware: 'auth', gate: 'manage_food_items' },
        },
        //reports
        {
          path: 'overall-report',
          component: UserOverallReport,
          meta: { middleware: 'auth', gate: 'overall_report' },
        },
        //advanced
        {
          path: 'imports-exports',
          component: UserImportExport,
          meta: { middleware: 'auth', gate: 'import_exports' },
        },
        {
          path: 'backup',
          component: UserBackupAndRestore,
          meta: { middleware: 'auth', gate: 'database_backup' },
        },
        {
          path: 'settings',
          component: UserSettings,
          meta: { middleware: 'auth', gate: null },
        },
        {
          path: 'languages',
          component: UserLanguageList,
          meta: { middleware: 'auth', gate: 'manage_languages' },
        },
        {
          path: '/:catchAll(.*)',
          component: DashboardNotFound,
          meta: { middleware: 'auth', gate: null },
        },
      ],
    },
  
    { path: '/:catchAll(.*)', component: PageNotFound },
];

export default routes