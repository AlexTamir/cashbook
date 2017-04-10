<template>
  <div>
    <div>
      <el-menu theme="dark" :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item index="1">
          <router-link to="/addcase">记账</router-link>
        </el-menu-item>
        <el-menu-item index="2">
          <router-link to="/personalCount">个人账单</router-link>
        </el-menu-item>
        <el-menu-item index="3">
          <router-link to="/account">统计</router-link>
        </el-menu-item>   
        <el-menu-item index="4" @click="checkIdentity">
          <router-link to="/clearAccount">清账</router-link>
        </el-menu-item>
      </el-menu>
    </div>

    <div>
      <router-view></router-view>      
    </div>
  </div> 
</template>

<script>
  export default {
    data() {
      return {
        activeIndex: '1'
      }
    },
    methods: {
      handleSelect(key, keyPath) {
        console.log(key, keyPath);
      },
      checkIdentity:function() {
        //判断是管理员才可以进去，不然进不去
        if(sessionStorage.identity !== 'admin') {
          alert('您没有权限');
          this.$router.push({ path: '/home' });
        }
      }
    }
  }
</script>

<style scoped>
	
</style>