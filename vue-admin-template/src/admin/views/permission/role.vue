<template>
  <div class="app-container">
    <el-button type="primary" @click="handleAddRole">新建角色</el-button>

    <el-table :data="rolesList" style="width: 100%;margin-top:30px;" border>
      <el-table-column align="center" label="ID" min-width="50">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="角色名称" min-width="220">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Guard" min-width="100" >
        <template slot-scope="scope">
          {{ scope.row.guard }}
        </template>
      </el-table-column>
      <el-table-column align="header-center" label="描述" min-width="400">
        <template slot-scope="scope">
          {{ scope.row.remark }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" min-width="200">
        <template slot-scope="scope">
          <el-button type="primary" size="small" @click="handleEdit(scope)">编辑</el-button>
          <el-button type="danger" size="small" @click="handleDelete(scope)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑角色':'新建角色'">
      <el-form :model="role" label-width="80px" label-position="left">
        <el-form-item label="名称">
          <el-input v-model="role.name" placeholder="角色名称" />
        </el-form-item>
        <el-form-item label="Guard">
          <el-select v-model="role.guard" style="width: 100%">
            <el-option v-for="item in guardList" :value="item.value" :label="item.label">{{ item.label }}</el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="角色描述">
          <el-input
            v-model="role.remark"
            :autosize="{ minRows: 2, maxRows: 4}"
            type="textarea"
            placeholder="角色描述"
          />
        </el-form-item>
        <el-form-item label="菜单">
          <el-input
            placeholder="输入关键字进行过滤"
            v-model="filterText">
          </el-input>
          <el-divider></el-divider>
          <el-tree
            ref="tree"
            :check-strictly="checkStrictly"
            :data="routesData"
            :props="defaultProps"
            :filter-node-method="filterNode"
            show-checkbox
            node-key="id"
            class="permission-tree"
          />
        </el-form-item>
      </el-form>
      <div style="text-align:right;">
        <el-button type="danger" @click="dialogVisible=false">取消</el-button>
        <el-button type="primary" @click="confirmRole">确认</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import path from 'path'
import { deepClone } from '@admin/utils'
import { getRoutes, getRoles, addRole, deleteRole, updateRole } from '@admin/api/permission/role'

const defaultRole = {
  name: '',
  remark: '',
  guard: 'Admin',
}

export default {
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },
  data() {
    return {
      role: Object.assign({}, defaultRole),
      routes: [],
      rolesList: [],
      dialogVisible: false,
      dialogType: 'new',
      checkStrictly: false,
      defaultProps: {
        children: 'children',
        label: 'name'
      },
      guardList: [
        {
          label: '总后台',
          value: 'admin',
        },
        {
          label: '站长',
          value: 'site_manage'
        },
        {
          label: '供应商',
          value: 'provider',
        }
      ],
      filterText: '',
    }
  },
  computed: {
    routesData() {
      return this.routes
    }
  },
  created() {
    // Mock: get all routes and roles list from server
    this.getRoutes()
    this.getRoles()
  },
  methods: {
    async getRoutes() {
      const res = await getRoutes()
      this.serviceRoutes = res.data
      this.routes = res.data.data;
      // this.routes = this.generateRoutes(res.data.data)
      // this.routes = [{"path":"/dashboard","title":"Dashboard", "children":''},{"path":"/documentation/index","title":"Documentation", "children":''},{"path":"/guide/index","title":"Guide"},{"path":"/permission","title":"Permission","children":[{"path":"/permission/page","title":"Page Permission"},{"path":"/permission/directive","title":"Directive Permission"},{"path":"/permission/role","title":"Role Permission"}]},{"path":"/icon/index","title":"Icons"},{"path":"/components","title":"Components","children":[{"path":"/components/tinymce","title":"Tinymce"},{"path":"/components/markdown","title":"Markdown"},{"path":"/components/json-editor","title":"Json Editor"},{"path":"/components/split-pane","title":"SplitPane"},{"path":"/components/avatar-upload","title":"Avatar Upload"},{"path":"/components/dropzone","title":"Dropzone"},{"path":"/components/sticky","title":"Sticky"},{"path":"/components/count-to","title":"Count To"},{"path":"/components/mixin","title":"componentMixin"},{"path":"/components/back-to-top","title":"Back To Top"},{"path":"/components/drag-dialog","title":"Drag Dialog"},{"path":"/components/drag-select","title":"Drag Select"},{"path":"/components/dnd-list","title":"Dnd List"},{"path":"/components/drag-kanban","title":"Drag Kanban"}]},{"path":"/charts","title":"Charts","children":[{"path":"/charts/keyboard","title":"Keyboard Chart"},{"path":"/charts/line","title":"Line Chart"},{"path":"/charts/mixchart","title":"Mix Chart"}]},{"path":"/nested","title":"Nested","children":[{"path":"/nested/menu1","title":"Menu1","children":[{"path":"/nested/menu1/menu1-1","title":"Menu1-1"},{"path":"/nested/menu1/menu1-2","title":"Menu1-2","children":[{"path":"/nested/menu1/menu1-2/menu1-2-1","title":"Menu1-2-1"},{"path":"/nested/menu1/menu1-2/menu1-2-2","title":"Menu1-2-2"}]},{"path":"/nested/menu1/menu1-3","title":"Menu1-3"}]},{"path":"/nested/menu2","title":"Menu2"}]},{"path":"/example","title":"Example","children":[{"path":"/example/create","title":"Create Article"},{"path":"/example/list","title":"Article List"}]},{"path":"/tab/index","title":"Tab"},{"path":"/error","title":"Error Pages","children":[{"path":"/error/401","title":"Page 401"},{"path":"/error/404","title":"Page 404"}]},{"path":"/error-log/log","title":"Error Log"},{"path":"/excel","title":"Excel","children":[{"path":"/excel/export-excel","title":"Export Excel"},{"path":"/excel/export-selected-excel","title":"Select Excel"},{"path":"/excel/export-merge-header","title":"Merge Header"},{"path":"/excel/upload-excel","title":"Upload Excel"}]},{"path":"/zip","title":"Zip","children":[{"path":"/zip/download","title":"Export Zip"}]},{"path":"/pdf/index","title":"PDF"},{"path":"/theme/index","title":"Theme"},{"path":"/clipboard/index","title":"Clipboard Demo"},{"path":"/i18n/index","title":"I18n"},{"path":"/external-link/https:/github.com/PanJiaChen/vue-element-admin","title":"External Link"}];

    },
    async getRoles() {
      const res = await getRoles()
      this.rolesList = res.data.data
    },
    filterNode(value, data) {
      if (!value) return true;
      return data.name.indexOf(value) !== -1;
    },

    // Reshape the routes structure so that it looks the same as the sidebar
    generateRoutes(routes, basePath = '/') {
      const res = []

      for (let route of routes) {
        // skip some route
        if (route.hidden) { continue }

        const onlyOneShowingChild = this.onlyOneShowingChild(route.children, route)

        if (route.children && onlyOneShowingChild && !route.alwaysShow) {
          route = onlyOneShowingChild
        }

        const data = {
          path: path.resolve(basePath, route.path),
          title: route.meta && route.meta.title
        }

        // recursive child routes
        if (route.children) {
          data.children = this.generateRoutes(route.children, data.path)
        }
        res.push(data)
      }
      return res
    },
    generateArr(routes) {
      let data = []
      routes.forEach(route => {
        data.push(route)
        if (route.children) {
          const temp = this.generateArr(route.children)
          if (temp.length > 0) {
            data = [...data, ...temp]
          }
        }
      })
      return data
    },
    handleAddRole() {
      this.role = Object.assign({}, defaultRole)
      if (this.$refs.tree) {
        this.$refs.tree.setCheckedNodes([])
      }
      this.dialogType = 'new'
      this.dialogVisible = true
    },
    handleEdit(scope) {
      this.dialogType = 'edit'
      this.dialogVisible = true
      this.checkStrictly = true
      this.role = deepClone(scope.row)
      this.$nextTick(() => {
        // const routes = this.generateRoutes(this.role.routes)
        this.$refs.tree.setCheckedKeys(this.role.auth_group_ids);
        // set checked state of a node not affects its father and child nodes
        this.checkStrictly = false
      })
    },
    handleDelete({ $index, row }) {
      this.$confirm('确认删除该角色?', '警告', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(async() => {
          await deleteRole(row.id)
          this.rolesList.splice($index, 1)
          this.$message({
            type: 'success',
            message: '删除成功!'
          })
        })
        .catch(err => { console.error(err) })
    },
    generateTree(routes, basePath = '/', checkedKeys) {
      const res = []

      for (const route of routes) {
        const routePath = path.resolve(basePath, route.path)

        // recursive child routes
        if (route.children) {
          route.children = this.generateTree(route.children, routePath, checkedKeys)
        }

        if (checkedKeys.includes(routePath) || (route.children && route.children.length >= 1)) {
          res.push(route)
        }
      }
      return res
    },
    async confirmRole() {
      const isEdit = this.dialogType === 'edit'
      //
      // const checkedKeys = this.$refs.tree.getCheckedKeys()
      // this.role.routes = this.generateTree(deepClone(this.serviceRoutes), '/', checkedKeys)
      //
      // this.role.auth_group_ids = this.mergeArray(this.$refs.tree.getHalfCheckedKeys(),this.$refs.tree.getHalfCheckedKeys())
      let a = this.$refs.tree.getHalfCheckedKeys()
      let b = this.$refs.tree.getCheckedKeys()
      this.role.bind_auth_group_ids = b.concat(a);

      if (isEdit) {
        await updateRole(this.role.id, this.role)
        for (let index = 0; index < this.rolesList.length; index++) {
          if (this.rolesList[index].key === this.role.key) {
            this.rolesList.splice(index, 1, Object.assign({}, this.role))
            break
          }
        }
      } else {
        const { data } = await addRole(this.role)
        this.role.id = data.data.id
        this.rolesList.push(this.role)
      }

      const { remark, name, } = this.role
      this.dialogVisible = false
      this.$notify({
        title: '成功',
        dangerouslyUseHTMLString: true,
        message: `
            <div>角色名: ${name}</div>
            <div>描述: ${remark}</div>
          `,
        type: 'success'
      })

      this.getRoles();
    },
    // reference: src/view/layout/components/Sidebar/SidebarItem.vue
    onlyOneShowingChild(children = [], parent) {
      let onlyOneChild = null
      const showingChildren = children.filter(item => !item.hidden)

      // When there is only one child route, the child route is displayed by default
      if (showingChildren.length === 1) {
        onlyOneChild = showingChildren[0]
        onlyOneChild.path = path.resolve(parent.path, onlyOneChild.path)
        return onlyOneChild
      }

      // Show parent if there are no child route to display
      if (showingChildren.length === 0) {
        onlyOneChild = { ... parent, path: '', noShowingChildren: true }
        return onlyOneChild
      }

      return false
    },
  }
}
</script>

<style lang="scss" scoped>
.app-container {
  .roles-table {
    margin-top: 30px;
  }
  .permission-tree {
    margin-bottom: 30px;
  }
}
</style>
