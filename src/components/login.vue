<template>
	<div class="center">
		<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="80px" class="demo-ruleForm">
		  <el-form-item label="账号" prop="identity">
		    <el-input v-model="ruleForm.identity"></el-input>
		  </el-form-item>
		  <el-form-item label="密码" prop="password">
		    <el-input v-model="ruleForm.password" type="password" auto-complete="off"></el-input>
		  </el-form-item>
		  <el-form-item>
		    <el-button type="success" @click="submit">登录</el-button>
		    <el-button type="info" @click="toRegister">注册</el-button>
		  </el-form-item>
		</el-form>
	</div>
</template>


<script>
	export default {
		data() {
			return {
				ruleForm: {
					identity: '',
					password: ''
				},
				rules: {
					identity: [
						{ required: true , message: '账号不能为空' }
					],
					password: [
						{ required: true , message: '密码不能为空' }
					]
				}
			}
		},
		methods: {
			submit: function() {
				this.$ajax.post('http://localhost/member.php' ,{
					"ruleForm": this.ruleForm,
					"do": 'login'
				}).then((res) => {
					//返回的是一个json对象，后台传回的数据在data中
					if(res.data.success === true) {
						sessionStorage.identity = res.data.identity;
						sessionStorage.memberID = res.data.memberID;
						this.$router.push({ path: '/home' });
					}else {
						alert('登录失败');
						this.resetForm('ruleForm');
					}
				})
			},
			toRegister: function() {
				this.$router.push({ path: '/register' })
			},
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
		}
	}
</script>


<style scoped>
	.center {
		margin: 0 auto;
		padding-top: 100px;
		width: 500px;
		height: 300px;
	}
</style>