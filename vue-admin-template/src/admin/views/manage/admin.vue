<template>

  <div class="app-container">
    <div class="filter-container">
      <el-button type="primary" @click="AdminCreate">新建管理员</el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{scope.$index + 1}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="用户头像">
        <template slot-scope="{ row }">
          <el-avatar shape="square" :size="23" fit="fill" :src="row.avatar"></el-avatar>
        </template>
      </el-table-column>
      <el-table-column align="center" label="用户名" prop="name"></el-table-column>
      <el-table-column align="center" label="登录帐号" prop="username"></el-table-column>
      <el-table-column align="center" label="绑定角色" prop="name"></el-table-column>
      <el-table-column>
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleAdminUpdate(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" @click="handleAdminDestroy(scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogVisible">
      <el-form ref="adminFrom" :model="temp" label-width="80px" label-position="left">
        <el-form-item label="头像">
          <el-upload
            class="avatar-uploader"
            action="https://jsonplaceholder.typicode.com/posts/"
            :show-file-list="false"
            :on-success="handleAvatarSuccess"
            :before-upload="beforeAvatarUpload"
          ></el-upload>
        </el-form-item>

        <el-form-item label="用户名" prop="name">
          <el-input v-model="temp.name"/>
        </el-form-item>
        <el-form-item label="登录帐号" prop="username">
          <el-input v-model="temp.username"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>


<script>
  import { adminList } from '@admin/api/admin/user'

  export default {
    name: 'AdminList',
    data() {
      return {
        tableKey: 0,
        list: null,
        total: 0,
        listLoading: true,
        dialogStatus: '',
        textMap: {
          update: '修改',
          create: '创建'
        },
        dialogVisible: false,
        temp:{
          name: '',
          username: '',
          avatar: '',
        },
        listQuery: {
          page: 1,
          limit: 20,
        }
      }
    },
    created() {
      this.getList();
    },
    methods: {
      getList(){
        adminList(this.listQuery).then(response => {
          this.listLoading = true
          this.list = response.data.data
          this.listLoading = false
        })
      },
      AdminCreate() {
        this.dialogVisible = true;
        this.dialogStatus = 'create';
      },
      handleAdminUpdate() {
        this.dialogVisible = true;
        this.dialogStatus = 'update'
      },
      handleAdminDestroy() {

      },
      handleAvatarSuccess(res, file) {
        this.temp.avatar = res;
      },
      beforeAvatarUpload(file) {
        const isJPG = file.type === 'image/jpeg';
        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 格式!');
        }
        const isLt2M = file.size / 1024 / 1024 < 5;
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 5MB!');
        }
        return isJPG && isLt2M;
      }
    }
  }
</script>

<style scoped>

</style>
