<template v-if="autenticado">
    <div>
      <vue-c3 :handler="handler"></vue-c3>
      <div style="margin-top: 30px; align-content: center; background: #EEEEEE; height: 90px">
        <h3 style="margin-bottom: 10px;  margin-left: 15px;">Opciones de vista:</h3>
        <v-btn
        class="bot"
        color="pink"
        @click="to_pie()"
        >
        Pie Chart
        </v-btn>
        <v-btn
        class="bot"
        color="indigo"
        @click="to_donut()"
        >
        Donut Chart
        </v-btn>
        <v-btn
        class="bot"
        color="purple"
        @click="to_area()"
        >
        Area Chart
        </v-btn>
        <v-btn
        class="bot"
        @click="to_normal()"
        color="teal"
        >
        Bar Chart
        </v-btn>
      </div>
    </div>
</template>

<script>
import axios from "axios"
import Vue from "vue";
import VueC3 from "vue-c3";
import "c3/c3.min.css";

export default {
  name: "metrica",
  components: {
    VueC3,
  },
  data: () => ({
    handler: new Vue(),
    autenticado: false
  }),

computed: {
    options() {
      return {
        data: {
            columns: [
                ['ANALISÍS', 2, 4, 7, 3, 6, 2, 3, 6, 2, 1, 3, 8],
                ['AUTOMATIZACIONES', 3, 6, 2, 1, 3, 8, 8, 9, 2, 4, 7, 3],
                ['CAPACITY', 5, 3, 1, 4, 3, 4, 1, 3, 8, 7, 8, 9],
                ['HUDDLE', 1, 3, 8, 7, 8, 9, 1, 3, 8, 7, 8, 9],
                ['MODELOS', 4, 3, 4, 8, 3, 4, 5, 3, 1, 4, 3, 4],
                ['REPORTES', 7, 8, 9, 2, 4, 7, 3, 6, 2, 1, 3, 8],
                ['RUDI', 8, 3, 4, 7, 1, 6, 2, 4, 7, 3, 6, 2]
            ],
            type: 'bar',
            labels: true
        },
        bar: {
            width: {
                ratio: 0.7
            }
        },
        grid: {
            x: {
                show: true
            },
            y: {
                show: true
            }
        },
        axis: {
            x: {
                type: 'category',
                categories: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE']
            }
        }
      };
    },
  },

  created () {
    let url = this.$my_host + '/sigma-back/public/index.php/api/usuario_conectado';
    axios.get(url)
    .then(response=> {
        if(response.data[1].trim().length > 0){
            if(response.data[3]){
              this.autenticado = true;
              this.user = response.data[1];
              this.handler.$emit("init", this.options);
              }else{
                this.autenticado = false;
                //window.location.href = "/";
                //this.$router.replace("/"); 
                window.location.replace("/sigma/");
              }
          }else{
              this.autenticado = false;
              this.$router.replace("/error");
          }
    })
  },

  mounted() {
    //this.handler.$emit("init", this.options);
  },

  methods: {
    cambiar(){
      this.handler.$emit("dispatch", (chart) => {
        //chart.groups([['REPORTE', 'RUDI']])
        /*const options = {
          columns: [
            ["弊社", 0, 20, 10, 40, 15],
            ["御社", 30, 200, 100, 400, 150, 250],
          ],
        };*/
        //chart.load(options);
        chart.groups([['REPORTES', 'RUDI']]);
        //chart.transform('line', 'REPORTES');
        //chart.transform('line');
        //chart.transform('spline');
      });
    },

    to_normal(){
      this.handler.$emit("init", this.options);
    },

    to_pie(){
      this.handler.$emit("dispatch", (chart) => {
        chart.transform('pie');
      });
    },

    to_donut(){
      this.handler.$emit("dispatch", (chart) => {
        chart.transform('donut');
      });
    },

    to_area(){
      this.handler.$emit("dispatch", (chart) => {
        chart.transform('area');
      });
    }
  }
}

</script>

<style scoped>
  .bot{
    margin-left: 15px;
    color: white;
  }
</style>