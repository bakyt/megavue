import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './components/Home.vue'
import Users from './components/users/List.vue'
import UsersEdit from './components/users/Edit.vue'
import UsersCreate from './components/users/Create.vue'
import Roles from './components/roles/List.vue'
import RolesCreate from './components/roles/Create.vue'
import RolesEdit from './components/roles/Edit.vue'

import Error404 from './components/errors/404.vue'
import Error403 from './components/errors/403.vue'
import Error500 from './components/errors/500.vue'

import AdminUsers from './components/admin/UsersList.vue'
import AdminUsersEdit from './components/admin/UsersEdit.vue'
import AdminRoles from './components/admin/RolesList.vue'
import AdminRolesEdit from './components/admin/RolesEdit.vue'
import AdminRolesCreate from './components/admin/RolesCreate.vue'


Vue.use(VueRouter);
const router = new VueRouter({
    mode:'history',
    routes: [
        {
            path: '/',
            name:'home',
            component: Home
        },
        // users list
        {
            path: '/users',
            name:'users.index',
            component: Users,
            meta: {
                breadCrumbs: [
                    {
                        to: '/users',            // hyperlink
                        text: 'Пользователи', // crumb text
                        icon: 'fa fa-users'
                    },
                    {
                        text: 'Обзор', // crumb text
                        icon: 'fa fa-eye'
                    }
                ]
            }
        },
        // user create
        {
            path: '/users/create',
            name:'users.create',
            component: UsersCreate,
            meta: {
                breadCrumbs: [
                    {
                        to: '/users',            // hyperlink
                        text: 'Пользователи', // crumb text
                        icon: 'fa fa-users'
                    },
                    {
                        text: 'Добавить', // crumb text
                        icon: 'fa fa-user-plus'
                    }
                ]
            }
        },
        // user edit
        {
            path: '/users/:id/edit',
            name:'users.edit',
            component: UsersEdit,
            meta: {
                breadCrumbs: [
                    {
                        to: '/users',            // hyperlink
                        text: 'Пользователи', // crumb text
                        icon: 'fa fa-users'
                    },
                    {
                        text: 'Редактировать', // crumb text
                        icon: 'fa fa-edit'
                    }
                ]
            }
        },
        // roles list
        {
            path: '/roles',
            name:'site-roles.index',
            component: Roles,
            meta: {
                breadCrumbs: [
                    {
                        to: '/roles',            // hyperlink
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    },
                    {
                        text: 'Обзор', // crumb text
                        icon: 'fa fa-eye'
                    }
                ]
            }
        },
        // roles create
        {
            path: '/roles/create',
            name:'site-roles.create',
            component: RolesCreate,
            meta: {
                breadCrumbs: [
                    {
                        to: '/roles',            // hyperlink
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    },
                    {
                        text: 'Добавить', // crumb text
                        icon: 'fa fa-plus'
                    }
                ]
            }
        },
        // role edit
        {
            path: '/roles/:id/edit',
            name:'site-roles.edit',
            component: RolesEdit,
            meta: {
                breadCrumbs: [
                    {
                        to: '/roles',            // hyperlink
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    },
                    {
                        text: 'Редактировать', // crumb text
                        icon: 'fa fa-edit'
                    }
                ]
            }
        },
        // Here starts error pages
        // error 403
        {
            path: '/403',
            name:'403',
            component: Error403,
            meta: {
                breadCrumbs: [
                    {
                        text: 'У вас нет доступа к данной странице', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        // error 404
        {
            path: '/404',
            name:'404',
            component: Error404,
            meta: {
                breadCrumbs: [
                    {
                        text: 'Страница не найдена', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        // error 500
        {
            path: '/500',
            name:'500',
            component: Error500,
            meta: {
                breadCrumbs: [
                    {
                        text: 'Ошибка 500', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        // settings
        // settings users
        {
            path: '/tools/users',
            name:'tool-users.index',
            component: AdminUsers,
            meta: {
                breadCrumbs: [
                    {
                        to: '/tools',            // hyperlink
                        text: 'Инструменты', // crumb text
                        icon: 'fa fa-wrench'
                    },
                    {
                        text: 'Пользователи', // crumb text
                        icon: 'fa fa-users'
                    }
                ]
            }
        },
        // settings edit users
        {
            path: '/tools/users/:id/edit',
            name:'tool-users.edit',
            component: AdminUsersEdit,
            meta: {
                breadCrumbs: [
                    {
                        to: '/tools',            // hyperlink
                        text: 'Инструменты', // crumb text
                        icon: 'fa fa-wrench'
                    },
                    {
                        to:'/tools/users',
                        text: 'Пользователи', // crumb text
                        icon: 'fa fa-users'
                    },
                    {
                        text: 'Редактировать', // crumb text
                        icon: 'fa fa-edit'
                    }
                ]
            }
        },
        // settings edit users
        {
            path: '/tools/roles',
            name:'roles.index',
            component: AdminRoles,
            meta: {
                breadCrumbs: [
                    {
                        to: '/tools',            // hyperlink
                        text: 'Инструменты', // crumb text
                        icon: 'fa fa-wrench'
                    },
                    {
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    }
                ]
            }
        },
        // settings edit roles
        {
            path: '/tools/roles/:id/edit',
            name:'roles.edit',
            component: AdminRolesEdit,
            meta: {
                breadCrumbs: [
                    {
                        to: '/tools',            // hyperlink
                        text: 'Инструменты', // crumb text
                        icon: 'fa fa-wrench'
                    },
                    {
                        to:'/tools/roles',
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    },
                    {
                        text: 'Редактировать', // crumb text
                        icon: 'fa fa-edit'
                    }
                ]
            }
        },
        // settings create roles
        {
            path: '/tools/roles/create',
            name:'roles.create',
            component: AdminRolesCreate,
            meta: {
                breadCrumbs: [
                    {
                        to: '/tools',            // hyperlink
                        text: 'Инструменты', // crumb text
                        icon: 'fa fa-wrench'
                    },
                    {
                        to:'/tools/roles',
                        text: 'Роли', // crumb text
                        icon: 'fa fa-key'
                    },
                    {
                        text: 'Добавить', // crumb text
                        icon: 'fa fa-plus'
                    }
                ]
            }
        },
        {
            path: "*",
            component: Error404,
            meta: {
                breadCrumbs: [
                    {
                        text: 'Страница не найдена', // crumb text
                        icon: 'fa fa-error'
                    }
                ]
            }
        }
    ]
});

export default router
