import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@admin/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@admin/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@admin/views/404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: '主页',
      component: () => import('@admin/views/dashboard/index'),
      meta: { title: '主页', icon: 'dashboard' }
    }]
  },
  {
    path: '/permission',
    component: Layout,
    name: 'permission',
    redirect: '/permission/role',
    meta: { title: '权限管理', icon: 'form' },
    children: [
      {
        path: 'role',
        name: 'role.manager.index',
        component: () => import('@admin/views/permission/role'),
        meta: { title: '角色管理', icon: 'form' }
      },
      {
        path: 'admin',
        name: 'admin.manager',
        component: () => import('@admin/views/manage/admin'),
        meta: { title: '管理员管理', icon: 'form'}
      },
    ]
  },
  {
    path: '/product',
    component: Layout,
    name: 'permission',
    redirect: '/product/product/index',
    meta: { title: '商品管理', icon: 'form' },
    children: [
      {
        path: 'role',
        name: 'role.manager.index',
        component: () => import('@admin/views/product/category'),
        meta: { title: '主分类管理', icon: 'form' }
      },
      {
        path: 'product/create',
        name: 'product.create',
        component: () => import('@admin/views/product/product/create'),
        meta: { title: '发布商品', icon: 'form'}
      },
      {
        path: 'admin',
        name: 'admin.manager',
        component: () => import('@admin/views/manage/admin'),
        meta: { title: '商品列表', icon: 'form'}
      },
    ]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
