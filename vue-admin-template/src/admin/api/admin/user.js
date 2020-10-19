import request from '@admin/utils/request'

export function login(data) {
  return request({
    url: '/admin/authorizations',
    method: 'post',
    data: data
  })
}

export function getInfo() {
  return request({
    url: '/admin/me',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/vue-admin-template/user/logout',
    method: 'post'
  })
}

export function adminList(data) {
  return request({
    url: '/admin/admins',
    method: 'get',
    data
  })
}
