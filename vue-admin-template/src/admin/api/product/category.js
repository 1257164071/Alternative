import request from '@admin/utils/request'

export function getCategory() {
  return request({
    url: '/admin/categories',
    methods: 'get',
  })
}
