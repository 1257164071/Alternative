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
            <upload v-model="temp.avatar" :default-image.sync="temp.avatar" />
        </el-form-item>

        <el-form-item label="用户名" prop="name">
          <el-input v-model="temp.name" placeholder="请输入用户名"/>
        </el-form-item>
        <el-form-item label="登录帐号" prop="username">
          <el-input v-model="temp.username" placeholder="请输入登录帐号"></el-input>
        </el-form-item>
        <el-form-item label="角色" prop="roles">
          <el-select  v-model="temp.roles" clearable style="width: 100%" >
            <el-option v-for="item in rolesList" :key="item.key" :label="item.name" :value="item.role" />
          </el-select>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="temp.password" placeholder="请输入密码"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>


<script>
  import { adminList, adminCreate, adminUpdate, adminDestroy } from '@admin/api/admin/user'
  import { getRoles } from '@admin/api/permission/role'
  import upload from '@admin/components/Upload/UploadAvatar'
  export default {
    name: 'AdminList',
    components: { upload },
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
        },
        rolesList: null,
      }
    },
    created() {
      this.getList();
      this.getRoles();
    },
    methods: {
      getList(){
        adminList(this.listQuery).then(response => {
          this.listLoading = true
          this.list = response.data.data
          this.listLoading = false
        })
      },
      getRoles(){
        getRoles().then(response => {
          this.rolesList = response.data.data;
        })
      },
      AdminCreate() {
        this.dialogVisible = true;
        this.dialogStatus = 'create';
      },
      handleAdminUpdate(row) {
        this.dialogVisible = true;
        this.dialogStatus = 'update';
        this.temp = Object.assign({}, row)
      },
      createData(){
        adminCreate(this.temp).then(response => {
          this.getList();
          this.dialogVisible = false
          this.$notify({
            title: '成功',
            message: '新用户添加成功',
            type: 'success',
            duration: 2000
          })
        })
      },
      updateData(){
        adminUpdate(this.temp.id, this.temp).then(response => {
          this.getList();
          this.dialogVisible = false
          this.$notify({
            title: '成功',
            message: '修改成功',
            type: 'success',
            duration: 2000
          })
        })
      },
      handleAdminDestroy(row) {

        this.$confirm('此操作将永久删除该项目, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          adminDestroy(row.id).then(response => {
            this.getList();
            this.dialogVisible = false
            this.$notify({
              title: '成功',
              message: '删除成功',
              type: 'success',
              duration: 2000
            })
          })
        });

      },
    }
  }
</script>

<style>
</style>
