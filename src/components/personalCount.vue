<template>
	<div class="block" style="margin-top:20px">
    <span class="demonstration">选择查询的时间</span>
    <el-date-picker
      v-model="value"
      type="datetimerange"
      :picker-options="pickerOptions"
      placeholder="选择时间范围"
      align="right">
    </el-date-picker>
    <el-button type="info" @click="queryspell">查询</el-button>
    <el-button type="info" @click="queryall">查询所有</el-button>


		<div style="margin-top:20px">
			<el-table
		    :data="tableData"
		    border
		    style="width: 100%">
		    <el-table-column
		      prop="date"
		      label="日期"
		      width="200">
          <el-icon name="time"></el-icon>
		    </el-table-column>
		    <el-table-column
		      prop="name"
		      label="姓名"
		      width="150">
		    </el-table-column>
		    <el-table-column
		      prop="type"
		      label="类型"
		      width="150">
		    </el-table-column>
		    <el-table-column
		      prop="money"
		      label="金额"
		      width="150">
		    </el-table-column>
		    <el-table-column
		      prop="content"
		      label="内容">
		    </el-table-column>
		  </el-table>
		</div>
  </div>
</template>


<script>
  import '../assets/js/settime.js';
	export default {
    data() {
      return {
        pickerOptions: {
          shortcuts: [{
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          }]
        },
        value: '',//输入框的时间,是一个数组

        tableData: [],

        theBeginTime: '',
        theEndTime: ''
      }
    },
    methods: {
      queryspell: function() {
        this.$ajax.post('http://localhost/account.php' ,{
          "queryTimeScope": {
            "theBeginTime": this.value[0].Format("yyyy-MM-dd hh:mm:ss"),
            "theEndTime": this.value[1].Format("yyyy-MM-dd hh:mm:ss")
          },
          "do": 'queryPersonalAcc',
          "memberID": sessionStorage.memberID
        }).then((res) => {
          //查询返回的数据
          if(res.data.success === true) {
            this.tableData = res.data.resultData;
          }
        })
      },
      queryall: function() {
        this.$ajax.post('http://localhost/account.php' ,{
          "queryTimeScope": {
            "theBeginTime": '',
            "theEndTime": ''
          },
          "do": 'queryPersonalAcc',
          "memberID": sessionStorage.memberID
        }).then((res) => {
          //查询返回的数据
          if(res.data.success === true) {
            this.tableData = res.data.resultData;
          }
        })
      }
    }
  }
</script>


<style scoped>
	
</style>