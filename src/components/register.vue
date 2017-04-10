<template>
	<div class="center">
		<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
			<el-form-item label="昵称" prop="username">
		    <el-input v-model="ruleForm.username"></el-input>
		  </el-form-item>
		  <el-form-item label="账号" prop="identity">
		    <el-input v-model="ruleForm.identity"></el-input>
		  </el-form-item>
		  <el-form-item label="密码" prop="password">
		    <el-input type="password" v-model="ruleForm.password" auto-complete="off"></el-input>
		  </el-form-item>
		  <el-form-item>
		    <el-button type="success" @click="register">注册</el-button>
		    <el-button type="danger" @click="resetForm('ruleForm')">重置</el-button>
		  </el-form-item>
		</el-form>
	</div>
</template>


<script>
	export default {
		data() {
			var checkUsername = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('称呼不能为空'));
        }
        return callback();

      };
			var checkIdentity = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('用户名不能为空'));
        }
        else {
          this.$ajax.post('http://localhost/member.php', {
						"identity": value,
						"do": 'checkIdentity'
					}).then((res) => {
              if (res.status == 200) {
                if (res.data.msg == "重复") {
                  return callback(new Error('该用户名已经被使用过了'));
                }
              }
            })
            .catch((err) => {
              return callback(new Error(err));
            })
        }
      };
			var checkPassword = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('密码不能为空'));
        }
        return callback();
      };
			return {
				ruleForm: {
					username: '',
					identity: '',
					password: ''
				},
				rules: {
					username: [
						{ validator: checkUsername, trigger: 'blur' }
					],
					identity: [
						{ validator: checkIdentity, trigger: 'blur' }
					],
					password: [
						{ validator: checkPassword, trigger: 'blur' }
					]
				}
			}
		},
		methods: {
			register: function() {
				this.$ajax.post('http://localhost/member.php' ,{
					"ruleForm": this.ruleForm,
					"do": 'register'
				}).then((res) => {
					//返回的是一个json对象，后台传回的数据在data中
					if(res.data.success === true) {
						alert(res.data.msg);
						this.$router.push({ path: '/' });
					}else {
						alert(res.data.msg);
					}
				})
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