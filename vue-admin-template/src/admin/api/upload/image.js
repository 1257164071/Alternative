import request from '@admin/utils/request'

export function UploadImage(data) {
  return request({
    url: '/admin/images',
    method: 'post',
    data
  })
}
