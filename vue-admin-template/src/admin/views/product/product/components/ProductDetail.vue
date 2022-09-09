<template>
  <div class="app-container">
    <div class="createPost-container">
      <el-form ref="postForm">
        <sticky :z-index="10">
          <div  class="sub-navbar">
            <div class="sub-navbar-item">
              <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="基础信息" name="card-0"></el-tab-pane>
                <el-tab-pane label="商品类型" name="card-1"></el-tab-pane>
                <el-tab-pane label="商品属性" name="card-2"></el-tab-pane>
                <el-tab-pane label="SKU设置" name="card-3"></el-tab-pane>
              </el-tabs>
            </div>
            <div class="sub-navbar-item" style="float: right">
              <el-button v-loading="loading" style="" size="medium" type="success" @click="">
                提交商品
              </el-button>
            </div>
          </div>
        </sticky>

        <div>
          <div class="box-card" id="card-0" style="margin-top: 30px">
            <el-card>
              <div slot="header">
                <span>基础信息</span>
              </div>
                <el-row>
                    <el-form-item style="margin-bottom: 40px;" prop="title" >
                      <el-col :span="2" class="form-item">
                        <span>商品标题:</span>
                      </el-col>
                      <el-col :span="22">
                        <el-input v-model="postForm.title" placeholder="请输入商品标题,最大不超过100个字符" type="text" maxlength="100" show-word-limit></el-input>
                      </el-col>
                    </el-form-item>
                    <el-form-item style="margin-bottom: 40px;" prop="title">
                      <el-col :span="2" class="form-item">
                        <span>商品类型:</span>
                      </el-col>
                      <el-col :span="22">
                        <category-select v-model="postForm.select"></category-select>
                      </el-col>
                    </el-form-item>
                </el-row>
            </el-card>
          </div>
          <div class="box-card" id="card-1">
            <el-card>
              <div slot="header">
                <span>商品类型</span>
              </div>
                <el-row>
                  <el-form-item style="margin-bottom: 40px;" prop="title">
                    <el-col :span="2" class="form-item">
                      <span>属性类目:</span>
                    </el-col>
                    <el-col :span="22">
                      <category-select v-model="postForm.select"></category-select>
                    </el-col>
                  </el-form-item>
                </el-row>
            </el-card>
          </div>
          <div class="box-card" id="card-2">
            <el-card>
              <div slot="header">
                <span>商品属性</span>
              </div>
              <div>
                <category-attirbute></category-attirbute>
              </div>
            </el-card>
          </div>
          <div class="box-card" id="card-3">
            <el-card>
              <div slot="header">
                <span>SKU设置</span>
              </div>
              <div>
                <sku-select></sku-select>
              </div>
            </el-card>
          </div>

        </div>
      </el-form>
    </div>
  </div>
</template>

<script>

  import Sticky from '@admin/components/Sticky'
  import CategorySelect from './CategorySelect'
  import SkuSelect from './SkuSelect'
  import CategoryAttirbute from './CategoryAttribute'
  export default {
    name: 'ProductDetail',
    components: { Sticky, CategorySelect, SkuSelect, CategoryAttirbute },
    data() {
      return {
        loading: false,
        activeName: '',
        postForm: {},
      }
    },
    methods: {
      handleClick() {
        window.location.hash= this.$route.path+'#' + this.activeName
      }
    }
  }
</script>

<style scoped>
  .createPost-container {
    position: relative;
  }


  .sub-navbar {
    height: 50px;
    line-height: 50px;
    position: relative;
    width: 100%;
    /*text-align: right;*/
    padding-right: 20px;
    transition: 600ms ease position;
    border-color: #e6e7eb;
    background: #d0d0d0;
    /*background: linear-gradient(90deg, rgba(32, 182, 249, 1) 0%, rgba(32, 182, 249, 1) 0%, rgba(33, 120, 241, 1) 100%, rgba(33, 120, 241, 1) 100%);*/
  }
  .subtitle {
    font-size: 20px;
    color: #fff;
  }

  .sub-navbar-draft {
    background: #d0d0d0;
  }

  .sub-navbar-deleted {
    background: #d0d0d0;
  }
  .sub-navbar-item {
    display: inline-block;
    margin-left: 10px;
  }
  .box-card {
    width: 100%;
    margin-bottom: 30px;
  }
  :target {
    padding-top: 60px;
    margin-top: -60px;
  }

  .form-item span{
    float: right;
    margin-right: 12px;
  }


</style>
