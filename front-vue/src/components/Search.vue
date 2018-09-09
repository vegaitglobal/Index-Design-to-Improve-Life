<template>
	<div>
		<div class="row mt-4 justify-content-center">
			<div class= "col-md-7 text-center">
					<h2>Index Search Engine</h2>
			</div>
		</div>
		<div class="row mt-3 justify-content-center">
			<div class= "col-md-12">
				<input-tag placeholder="Enter keywords" :tags.sync="keywords" :limit="limit"></input-tag>
			</div>  
		</div>
		<div class="row mt-3 justify-content-between">
			<div class= "col-xs-12 col-md-6">
				  <date-picker v-model="timeRange" range type="date"  format="YYYY-MM-DD" :not-after="new Date()" :shortcuts="shortcuts" lang="en"></date-picker>
			</div>
			<div class= "col-md-3  text-center" v-if="tableData.length!=0">
				<download-excel
					class   = "search_button d-flex align-items-center justify-content-center"
					:data   = "tableData"
					:fields = "excel_fields"
					name    = "results.xls">
					Download Excel
				</download-excel>
			</div>

			<div class= "col-md-2 ml-1 text-right">
				<button class="search_button" @click="getResults()"> Search </button>
			</div>

		</div>
		<div class="row mt-5 justify-content-center" v-if="noKeyword">
			<div class="col-lg-10 text-center">
				<p style="color:red; font-size:18px;">Please enter minimum 1 keyword!</p>
			</div>
		</div>
		<div class="row mt-3 justify-content-center">
			<div class="col-12 mt-5" v-if="loading">
				<Spinner line-fg-color="#000"></Spinner>
			</div>	
			<div class= "col-lg-12 mt-3" v-else>
				<div class="card">
					<div class="card-body">
						<v-client-table :data="tableData" :columns="columns" :options="options">
							<p slot="pubDate" slot-scope="props">{{moment(props.row.pubDate).format("DD-MM-YY @ HH:MM")}}</p>
							<a :href="props.row.link" slot="link" slot-scope="props">{{props.row.link}}</a>
						</v-client-table>
						
					</div>
				</div>
			</div>  
		</div>	
	 </div> 
</template>

<script>
	import axios from 'axios'
	import InputTag from 'vue-input-tag'
	import Spinner from 'vue-simple-spinner'
	import DatePicker from 'vue2-datepicker'
	import moment from 'moment'

export default {
  components: {
    InputTag,
		Spinner,
		DatePicker
  },
  data () {
    return {
	    	moment: moment,
			keywords: [],
			limit:50,
			noKeyword: false,
			loading: false,
			excel_fields: {
							'id': 'id',
							'title': 'title',
							'link': 'link',
							'pubDate': 'pubDate',
					},
			columns: ['title', 'link', 'pubDate'],
					headings: {
							title: 'Title',
							link: 'link',
							pubDate: 'Date'
						},
			tableData: [],
			options: {
				sortable: [ 'title', 'link', 'pubDate' ]
			},
			shortcuts: [
        {
          text: 'Today',
          onClick: () => {
            this.timeRange = [ new Date(), new Date() ]
          }
				},
				{
          text: 'Last 2 Days',
          onClick: () => {
            this.timeRange = [ (moment().add(-1, 'days')) , new Date() ]
          }
				},
				{
          text: 'Last 3 Days',
          onClick: () => {
             this.timeRange = [ (moment().add(-2, 'days')) , new Date() ]
          }
				},
				{
          text: 'Last 4 Days',
          onClick: () => {
             this.timeRange = [ (moment().add(-3, 'days')) , new Date() ]
          }
				},
				{
          text: 'Last 5 Days',
          onClick: () => {
             this.timeRange = [ (moment().add(-4, 'days')) , new Date() ]
          }
        }
			],
			timeRange: [ new Date(), new Date() ]
    }
  },
  methods: {
	  getResults: function() {
			if(this.keywords.length == 0) return this.noKeyword = true
			else this.noKeyword = false
		  var self = this
		  self.loading = true
		  axios.post('/api/api.php', {
			keywords: self.keywords,
			from : self.timeRange[0],
			to : self.timeRange[1],
		  })
		  .then(function (response) {
				self.tableData = response.data
				self.loading = false
		  })
		  .catch(function (error) {
				self.loading = false
				alert("Server error!");
		  });
	  }
  }
}
</script>

<style>


	.vue-input-tag-wrapper {
		-webkit-appearance: textfield;
		background-color: #fff;
		border: 0px;
		/* cursor: text; */
		display: flex;
		/* flex-wrap: wrap; */
		overflow: hidden;
		/* padding-left: 4px; */
		/* padding-top: 4px; */
		/* text-align: left; */
	}

	.vue-input-tag-wrapper .new-tag {
		background: transparent;
		border: none;
		border-bottom: 3px solid #000;
		color: #000;
		outline: none;
		padding-left: 10px;
		width: 100%;
	}

	.vue-input-tag-wrapper .input-tag {
		background-color: #000;
		border: 1px solid #000;
		color: #fff;
		display: inline-block;
		font-size: 13px;
		font-weight: 400;
		margin-bottom: 4px;
		margin-right: 4px;
		padding: 7px 17px
	}

	.vue-input-tag-wrapper .input-tag .remove{
		color: #fff;
		margin-left: 7px;
	}

	.mx-calendar-content .cell.actived{
		background-color:#000
	}

	.VueTables__search{
		display: none;
	}

	VueTables__limit{
		display: none;
	}

.table-striped tbody tr:nth-of-type(odd) {
	background-color: #fff;
}

.table-hover tbody tr:hover {
	background-color: #fff;
}

.table thead th,
.table td {
	  border: none;
    border-bottom: 1px solid #000;
}


.search_button {
background-color: transparent;
border: 1px solid #000;
color: #000;
cursor: pointer;
height: 48px;
padding: 0 34px;
transition: background-color .3s,
color .3s;
border-radius: 0px;
outline: none;
}

.search_button:hover {
background-color: #000;
color: #fff;
}


.card {
border: 1px solid #000;
border-radius: 0;
}

.mx-input{
	border: 1px solid #000;
	border-radius: 0px;
	height: 48px;
}

.mx-datepicker-range,
.mx-range-wrapper,
.mx-calendar,
.mx-calendar-content,
.mx-datepicker-popup {
	width: 100%;
}


@media only screen and (min-width: 1440px) {
	.mx-calendar  {
		width: 50%;
	}
	
	
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #000;
    border-color: #000;
}

.page-link,
.page-item.disabled .page-link {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #000;
    background-color: #fff;
		border: 1px solid #000;
		transition: background-color 0.3s, color 0.3;
}

.page-link:hover {
    color: #fff;
    text-decoration: none;
    background-color: #000;
    border-color: #000;
}

.form-control {
	borde-radius: none!important;
}


.mx-datepicker .mx-datepicker-range {
	width: 100%;
}
</style>
