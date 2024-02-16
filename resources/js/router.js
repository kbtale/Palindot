import AuthLoginPage from './views/auth/login.vue'
import AuthRegisterPage from './views/auth/register.vue'
import AuthRecoverPage from './views/auth/recover.vue'

import FirstUrlPage from './views/landing/first-url.vue'

import UserDashboardPage from './views/user/dashboard.vue'
import UserAccountPage from './views/user/account.vue'
import UserUrlsList from './views/user/urls-list.vue'
import UserUrlsNew from './views/user/urls-new.vue'
import UserUrlsEdit from './views/user/urls-edit.vue'
import UserSubsetsList from './views/user/subsets-list.vue'
import UserSubsetsNew from './views/user/subsets-new.vue'
import UserSubsetsEdit from './views/user/subsets-edit.vue'
import UserOverallReport from './views/user/overall-report.vue'
import UserImportExport from './views/user/import-and-export.vue'
import UserBackupAndRestore from './views/user/backups.vue'
import UserSettings from './views/user/settings.vue'
import UserLanguages from './views/user/languages.vue'

import PageNotFound from './views/not-found.vue'

import AuthLayout from './layouts/AuthLayout.vue'
import LandingLayout from './layouts/LandingLayout.vue'
import UserLayout from './layouts/UserLayout.vue'

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
      name: 'Landing',
      children: [
        {
          path: '/',
          component: FirstUrlPage,
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
        },
        {
          path: 'urls/new',
          component: UserUrlsNew,
        },
        {
          path: 'urls/:id/edit',
          component: UserUrlsEdit,
        },
        {
          path: 'subsets',
          component: UserSubsetsList,
        },
        {
          path: 'subsets/new',
          component: UserSubsetsNew,
        },
        {
          path: 'subsets/:id/edit',
          component: UserSubsetsEdit,
        },
        //reports
        {
          path: 'overall-report',
          component: UserOverallReport,
        },
        //advanced
        {
          path: 'import-and-export',
          component: UserImportExport,
        },
        {
          path: 'backup',
          component: UserBackupAndRestore,
        },
        {
          path: 'settings',
          component: UserSettings,
        },
        {
          path: 'languages',
          component: UserLanguages,
        },
        {
          path: '/:catchAll(.*)',
          component: PageNotFound,
        },
      ],
    },
  
    { path: '/:catchAll(.*)', component: PageNotFound },
];

export default routes;