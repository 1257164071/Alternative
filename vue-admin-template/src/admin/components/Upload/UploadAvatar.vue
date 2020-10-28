<template>
  <div class="upload-container">
    <el-upload
      class="avatar-uploader"
      :multiple="false"
      :show-file-list="false"
      :http-request="Upload"
      action="https://jsonplaceholder.typicode.com/posts/"
    >
      <img v-if="tempUrl.length" :src="tempUrl" class="avatar" alt="avatar">
      <i v-else class="el-icon-plus avatar-uploader-icon" ></i>
    </el-upload>
  </div>

</template>

<script>
import { UploadImage } from '@admin/api/upload/image'
export default {
  name: 'UploadImage',
  props: {
    type: {
      type: String,
      default: 'admin'
    },
    defaultImage: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      tempUrl: ''
    }
  },
  watch: {
    defaultImage(val) {
      this.tempUrl = val
    }
  },
  created() {
    if (this.defaultImage.length !== 0) {
      this.emitInput(this.defaultImage)
    }
  },

  methods: {
    emitInput(val) {
      this.$emit('input', val)
      this.tempUrl = val
    },
    Upload(content) {
      const form = new FormData()
      form.append('image', content.file)
      UploadImage(form).then((response) => {
        content.onSuccess('上传成功')
        console.log(response.data.path)
        this.emitInput(response.data.path)
      }).catch(error => {
        content.onError('上传失败')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
    @import "~@admin/styles/mixin.scss";
    .upload-container {
        width: 100%;
        position: relative;
        display: inline-block;
        @include clearfix;
        .image-uploader {
            width: 60%;
            float: left;
            border: 1px dashed #d9d9d9;
            height: 200px;

            .el-icon-upload {
                line-height: 50px;
                margin: 40px 0 16px;
                font-size: 67px;
            }

        }
        .image-preview {
            width: 200px;
            height: 200px;
            position: relative;
            border: 1px dashed #d9d9d9;
            float: left;
            margin-left: 50px;
            .image-preview-wrapper {
                position: relative;
                width: 100%;
                height: 100%;
                img {
                    width: 100%;
                    height: 100%;
                }
            }
            .image-preview-action {
                position: absolute;
                width: 100%;
                height: 100%;
                left: 0;
                top: 0;
                cursor: default;
                text-align: center;
                color: #fff;
                opacity: 0;
                font-size: 20px;
                background-color: rgba(0, 0, 0, .5);
                transition: opacity .3s;
                cursor: pointer;
                text-align: center;
                line-height: 200px;
                .el-icon-delete {
                    font-size: 36px;
                }
            }
            &:hover {
                .image-preview-action {
                    opacity: 1;
                }
            }
        }
    }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 100%;
    height: 100%;
    display: block;
  }

</style>
