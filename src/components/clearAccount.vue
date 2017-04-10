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
    <el-button type="info" @click="save" class="saveButton">保存</el-button>


		<div style="margin-top:20px">
			<el-table
		    :data="tableData"
		    border
		    style="width: 100%">
		    <el-table-column
		      label="日期"
		      width="200">
          <template scope="scope">
            <el-icon name="time"></el-icon>
            <span style="margin-left: 10px">{{ scope.row.date }}</span>
          </template>
		    </el-table-column>
		    <el-table-column
		      label="姓名"
		      width="120">
          <template scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
		    </el-table-column>
		    <el-table-column
		      label="类型"
		      width="100">
          <template scope="scope">
            <span>{{ scope.row.type }}</span>
          </template>
		    </el-table-column>
		    <el-table-column
		      label="金额"
		      width="100">
          <template scope="scope">
            <span>{{ scope.row.money }}</span>
          </template>
		    </el-table-column>
		    <el-table-column
		      label="内容"
          width="300">
          <template scope="scope">
            <span>{{ scope.row.content }}</span>
          </template>
		    </el-table-column>
        <el-table-column
          label="是否已清账"
          width="120">
          <template scope="scope">
            <span>{{ scope.row.isCheck }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="清账方式"
          width="200">
          <template scope="scope">
            <span>{{ scope.row.checkWay }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="清账人"
          width="120">
          <template scope="scope">
            <span>{{ scope.row.check_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template scope="scope">
            <el-button size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          </template>
        </el-table-column>
		  </el-table>
		</div>

    <div>
      <el-dialog title="清账" v-model="dialogFormVisible">
        <el-form :model="row">
          <el-form-item label="是否清账" :label-width="formLabelWidth">
            <el-input v-model="row.isCheck"></el-input>
          </el-form-item>
          <el-form-item label="清账方式" :label-width="formLabelWidth">
            <el-input v-model="row.checkWay"></el-input>
          </el-form-item>
          <el-form-item label="清账人" :label-width="formLabelWidth">
            <el-input v-model="row.check_name"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>


<script>
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
        value: '',

        tableData: [],

        dialogFormVisible: false,
        formLabelWidth: '80px',
        //index: '',
        row: ''
      }
    },
    methods: {
      queryspell: function() {
        this.$ajax.post('http://localhost/clearAccount.php' ,{
          "queryTimeScope": {
            "theBeginTime": this.value[0].Format("yyyy-MM-dd hh:mm:ss"),
            "theEndTime": this.value[1].Format("yyyy-MM-dd hh:mm:ss")
          },
          "do": 'queryAllAccount',
        }).then((res) => {
          //查询返回的数据
          if(res.data.success === true) {
            this.tableData = res.data.resultData;
          }
        })
      },
      queryall: function() {
        this.$ajax.post('http://localhost/clearAccount.php' ,{
          "queryTimeScope": {
            "theBeginTime": '',
            "theEndTime": ''
          },
          "do": 'queryAllAccount',
        }).then((res) => {
          //查询返回的数据
          if(res.data.success === true) {
            this.tableData = res.data.resultData;
          }
        })
      },
      //index是行索引,row是这一行的数据
      handleEdit: function(index, row) {
        //console.log(index);
        //console.log(row.isCheck);
        //console.log(row.check_name);
        this.dialogFormVisible = true;
        //this.index = index;
        this.row = row;
      },
      save: function() {
        this.$ajax.post('http://localhost/clearAccount.php' ,{
          "tableData": this.tableData,
          "do": 'updateData',
        }).then((res) => {
          //查询返回的数据
          if(res.data.success === true) {
            alert(res.data.msg);
          }
        })
      }
    }
  }
</script>


<style scoped>
	.saveButton {
    float: right;
    margin-right: 20px;
  }
</style>