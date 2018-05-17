<script>
  import { Bar } from 'vue-chartjs'

  export default {
    extends: Bar,
    props: {
      startDate: {
        type: String,
        required: true
      },
      endDate: {
        type: String,
        required: true
      }
    },
    mounted () {
      let Days = new Array();
      let Sales = new Array();
      let Profit = new Array();
      
      axios.get('multicom-pos/public/api/v1/sales/func/chart?searchVarStartDate='+ this.startDate + '&searchVarEndDate='+this.endDate).then((response) => {
        let data = response.data;
        if(data) {
          data.forEach(element => {
            Days.push(element.day);
            Sales.push(element.total_sold);
            Profit.push(element.profit);
          });
          this.renderChart({
            labels: Days,
            datasets: [{
              label: 'Sales',
              backgroundColor: '#FC2525',
              data: Sales
            },{
              label: 'Profit',
              backgroundColor: '#0000CD',
              data: Profit
          }]}, 
          {responsive: true, maintainAspectRatio: false})
        }
        else {
          console.log('No data');
        }
      })           
    }
  }
</script>