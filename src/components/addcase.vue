<template>
  <div style="margin-top:20px">
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
      <el-form-item label="内容" prop="content">
        <el-input v-model="ruleForm.content" placeholder="请输入详细内容"></el-input>
      </el-form-item>
      <el-form-item label="记账人" prop="name">
        <el-input v-model="ruleForm.name" placeholder="请输入记账人姓名"></el-input>
      </el-form-item>
      <el-form-item label="类型" prop="type">
        <el-select v-model="ruleForm.type" placeholder="请选择类型" style="width:100%">
          <el-option label="收入" value="income"></el-option>
          <el-option label="支出" value="expend"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="时间" required prop="date">
        <el-date-picker type="datetime" placeholder="选择日期时间" v-model="ruleForm.date" style="width: 100%;"></el-date-picker>
      </el-form-item>
      <el-form-item label="金额" prop="money">
        <el-input type="money" v-model.number="ruleForm.money" auto-complete="off" placeholder="请输入金额"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="success" @click="submitForm">立即创建</el-button>
        <el-button type="danger" @click="resetForm('ruleForm')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>


<script>
  import '../assets/js/settime.js'; 
	export default {
		data() {
			return {
        ruleForm: {
          content: '',
          name: '',
          type: '',
          date: '',
          money: ''
        },
        rules: {
          content: [
            { required: true, message: '内容不能为空' },
          ],
          name: [
            { required: true, message: '姓名不能为空' },
          ],
          type: [
            { required: true, message: '类型不能为空' },
          ],
          date: [
            { required: true, message: '时间不能为空' }
          ],
          money: [
            { required: true, message: '金额不能为空' },
            { type: 'number' , message: '金额必须为数字值' }
          ]
        }
			}
		},
		methods: {
      submitForm: function() {
        this.$ajax.post('http://localhost/account.php' ,{
          "ruleForm": {
            "content": this.ruleForm.content,
            "name": this.ruleForm.name,
            "type": this.ruleForm.type,
            "date": this.ruleForm.date.Format("yyyy-MM-dd hh:mm:ss"),
            "money": this.ruleForm.money,
          },
          "do": 'addaccount',
          "memberID": sessionStorage.memberID
        }).then((res) => {
          //返回的是一个json对象，后台传回的数据在data中
					if(res.data.success === true) {
            alert(res.data.msg);
            this.resetForm('ruleForm');
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
	
</style>
