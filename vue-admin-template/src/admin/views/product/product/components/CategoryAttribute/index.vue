<template>
<div>
<el-row>
  <el-col :span="8">
    <div class="list_item">
      <span class="label">品牌: </span>
      <el-input class="input"></el-input>
      <span class="remove">x</span>
    </div>
  </el-col>
</el-row>
</div>
</template>

<script>
  export default {
    name: 'CategoryAttirbute',
    data() {
      return {
        data: [],
      }
    }
  }
</script>

<style scoped>
.list_item{
  position: relative;
  padding: 7px 10px;
  line-height: 16px;
  background-color: #f8f8f8
}
.list_item .input {
  width: 200px;
}
.list_item .label {
  margin-right: 7px;
}
.list_item .remove {
  top: 12px;
  right: 10px;
}

  /*  .remove*/
  /*  display: none*/
  /*  position: absolute*/
  /*  z-index: 2*/
  /*  width: 18px*/
  /*  height: 18px*/
  /*  font-size: 14px*/
  /*  line-height: 16px*/
  /*  color: #fff*/
  /*  text-align: center*/
  /*  cursor: pointer*/
  /*  background: rgba(0, 0, 0, .3)*/
  /*  border-radius: 50%*/

  /*.sku_group*/
  /*  margin-bottom: 10px*/

  /*  &:hover*/
  /*    .spec_title .remove*/
  /*      display: block*/
.remove {
  display: none;
  position: absolute;
  z-index: 2;
  width: 18px;
  height: 18px;
  font-size: 14px;
  line-height: 16px;
  color: #fff;
  text-align: center;
  cursor: pointer;
  background: rgba(0, 0, 0, .3);
  border-radius: 50%;
}
/*.remove:hover {*/
/*  display: block;*/
/*}*/
</style>
