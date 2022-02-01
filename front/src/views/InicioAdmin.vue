<template>
    <v-app v-if="autenticado">
        <v-system-bar class="deep-purple darken-4" height="60">
            <div class="div_2">
                <b><h1>Σ</h1></b>
            </div>

            <h2 style="color: white; margin-left: 2%">SIGMA</h2>

            <v-btn
              style="margin-left: 3%; margin-top: -3px"
              @click="historial"
              color="yellow accent-2"
              text
            >
            VER HISTORIAL
            </v-btn>

            <v-btn
              style="margin-left: 0%; margin-top: -3px"
              @click="estadistica"
              color="yellow accent-2"
              text
            >
            ESTADÍSTICA
            </v-btn>

            <v-spacer></v-spacer>
            <h2 style="color: white">¡Bienvenid@ {{this.user}}!</h2> 
        </v-system-bar>

        <v-snackbar
        :timeout="-1"
        v-model="mensaje_error"
        color="deep-orange darken-4"
        height="70"
        >
        <strong>Favor completar todos los campos requeridos *</strong>
        </v-snackbar>

        <v-data-table
            :headers="headers"
            :items="desserts"
            class="elevation-1"
            v-if="autenticado"
            :search="search"
        >
            <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>Administrador del sistema ⚙️🛠️</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-text-field
                    class="filtro"
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Escriba aquí para realizar un filtro"
                    single-line
                    hide-details
                ></v-text-field>
                <v-dialog
                v-model="dialog"
                max-width="500px"
                >
                <v-card max-width="580px">
                    <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                    </v-card-title>
                    <v-divider></v-divider>

                    <v-card-text>
                    <v-container>
                        <v-text-field 
                        v-model="editedItem.ASUNTO" 
                        label="ASUNTO *" 
                        outlined
                        counter
                        maxlength="200"
                        >
                        </v-text-field>

                        <div class="input-group-prepend" style="width: 30%">
                        <label style="width: 100%" class="input-group-text" for="inputGroupSelect01">ANALISTA *</label>
                        </div>

                        <select name="tipo" class="form-select form-control" style="width: 100%" v-model="editedItem.ASIGNADO"> 
                        <option disabled="disabled">Elige una opción</option>
                        <option v-for="item in list_users" :value="item" v-bind:key="item">
                            {{item}}
                        </option>
                        </select>

                        <div class="input-group-prepend" style="width: 30%; margin-top: 7%">
                        <label class="input-group-text" for="inputGroupSelect01">WORKLINE *</label>
                        </div>

                        <select name="tipo" class="form-select form-control" style="width: 100%" v-model="editedItem.WORKLINE"> 
                        <option disabled="disabled">Elige una opción</option>
                        <option selected value="ANALISIS">ANALISÍS</option>
                        <option value="AUTOMATIZACION">AUTOMATIZACION</option>
                        <option value="CAPACITY">CAPACITY</option>
                        <option value="HUDDLE">HUDDLE</option>
                        <option value="MODELO">MODELO</option>
                        <option value="REPORTE">REPORTE</option>
                        <option value="RUDI">RUDI</option>
                        </select>
                        
                        <div class="input-group-prepend" style="width: 30%; margin-top: 7%">
                        <label class="input-group-text" for="inputGroupSelect01">NIVEL DIFICULTAD *</label>
                        </div>

                        <select v-model="editedItem.DIFICULTAD" name="dificultad" id="dificultad" class="form-select form-control" style="width: 100%"> 
                        <option disabled="disabled">Elige una opción</option>
                        <option select value="BAJA">BAJA</option>
                        <option value="MEDIA">MEDIA</option>
                        <option value="ALTA">ALTA</option>
                        </select>

                        <div class="input-group-prepend" style="width: 30%; margin-top: 7%">
                            <label class="input-group-text" for="inputGroupSelect01">DEADLINE</label>
                        </div>
                        <input class="entrada" id="deadline" name="estimado" type="date" :min="today" v-model="editedItem.DEADLINE">
                    </v-container>
                    </v-card-text>

                    <v-divider></v-divider>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="close"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="save"
                    >
                        Guardar
                    </v-btn>
                    </v-card-actions>
                </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="750px">
                <v-card class="blue-grey darken-3">
                    <v-card-title class="justify-center" style="color: white;">¿Desea realmente eliminar "{{editedItem.ASUNTO}}"?</v-card-title>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" text @click="closeDelete">Cancelar</v-btn>
                    <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                    <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
                </v-dialog>

                <v-dialog v-model="dialogComment" max-width="500px">
                <v-card>
                    <v-card-title>
                    <span class="text-h5">Comentario</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-container>
                    <v-textarea
                        v-model="editedItem.COMMENT"
                        color="teal"
                        counter
                        maxlength="1000"
                        outlined
                        filled
                        hint="¡Por favor brinde solo detalles relevantes!"
                        label="Detalles de la solicitud"
                        solo
                    >
                    </v-textarea>
                    <v-divider></v-divider>
                    <div style="margin-left: 60%; margin-top: 9px">
                        <v-btn color="info" text @click="closeComment">Cancelar</v-btn>
                        <v-btn color="error" text @click="save">OK</v-btn>
                    </div>
                    </v-container>
                </v-card>
                </v-dialog>
                
                <v-dialog v-model="dialogStatus" max-width="500px">
                <v-card>
                    <v-card-title>
                    <span class="text-h5">Modificar estado</span>
                    </v-card-title>
                    <v-divider></v-divider>

                    <v-card-text>
                    <v-row style="margin-top: 5%; margin-left: 2%">
                        <div class="lab">
                        <label class="label_yo">ELEGIR NUEVO ESTADO</label>
                        </div>

                        <select v-model="editedItem.ESTADO" id="dffv" class="sel"> 
                        <option disabled="disabled">Elige una opción</option>
                        <option select value="FINALIZADO">FINALIZADO</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="CANCELADO">CANCELADO</option>
                        <option value="EN CURSO">EN CURSO</option>
                        </select>
                    </v-row>
                    </v-card-text>

                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" text @click="closeStatus">Cancelar</v-btn>
                    <v-btn color="error" text @click="save">OK</v-btn>
                    </v-card-actions>
                </v-card>
                </v-dialog>
            </v-toolbar>
            </template>
            <template v-slot:[`item.actions`]="{ item }" >
            <div class="text-center">
                <v-btn
                color="warning"
                dark
                class="mb-2"
                small
                @click="editItem(item)"
                >
                Editar
                </v-btn>
                <v-btn
                color="info"
                dark
                class="mb-2"
                small
                @click="editStatus(item)"
                >
                Estado
                </v-btn>
                <v-btn
                color="success"
                dark
                class="mb-2"
                small
                @click="editComment(item)"
                >
                Comentario
                </v-btn>
                <v-btn
                color="error"
                dark
                class="mb-2"
                small
                @click="deleteItem(item)"
                >
                Eliminar
                </v-btn>
            </div>
            </template>
            <template v-slot:no-data>
                <strong>Ups 😨!  No hay ningún proyecto en curso, y eso es muyyy extraño 😓</strong>
            </template>
        </v-data-table>
        <v-btn
            class="flotante"
            fab 
            fixed 
            bottom
            dark
            color="green darken-3 white--text"
            large
            right
            @click="editItem(defaultItem)"
        >
            <v-icon dark>
                mdi-plus
            </v-icon>
        </v-btn>
    </v-app>
</template>

<script>
    import axios from "axios"
    export default {
        name: 'inicioadmin',
    data: () => ({
      today: new Date().toISOString().slice(0, 10),
      autenticado: false,
      user: null,
      user2: null,
      mensaje_error: false,
      dialog: false,
      dialogDelete: false,
      dialogStatus: false,
      dialogComment: false,
      resp_upd: null,
      valor: null,
      search: '',
      headers: [
        { text: 'FECHA',  value: 'FECHA', class:'deep-purple lighten-1 white--text'},
        { text: 'ASIGNADO', value: 'ASIGNADO', class:'deep-purple lighten-1 white--text'},
        { text: 'ASUNTO',  value: 'ASUNTO', class:'deep-purple lighten-1 white--text'},
        { text: 'WORKLINE', value: 'WORKLINE', class:'deep-purple lighten-1 white--text'},
        { text: 'ESTADO', value: 'ESTADO', class:'deep-purple lighten-1 white--text'},
        { text: 'DIFICULTAD', value: 'DIFICULTAD', class:'deep-purple lighten-1 white--text'},
        { text: 'DEADLINE', value: 'DEADLINE', class:'deep-purple lighten-1 white--text'},
        { text: 'CONTROLES', width: '398px', align:'center', value: 'actions', sortable: false, class:'deep-purple lighten-1 white--text'},
      ],
      list_users: [],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        ID: null,
        FECHA: '',
        ASIGNADO: '',
        ASUNTO: '',
        WORKLINE: '',
        ESTADO: '',
        DIFICULTAD: '',
        DEADLINE: '',
        COMMENT: '',
        USUARIO_CREACION: ''
      },
      defaultItem: {
        ID: null,
        FECHA: '',
        ASIGNADO: '',
        ASUNTO: '',
        WORKLINE: '',
        ESTADO: '',
        DIFICULTAD: '',
        DEADLINE: '',
        COMMENT: ''
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Registrar Solicitud' : 'Modificar Solicitud #' + this.editedItem.ID
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },

      dialogStatus (val) {
        val || this.closeStatus()
      },

      dialogComment (val) {
        val || this.closeComment()
      },
    },

    created () {
      /*var dire = this.$route.path
      if(dire == '/history'){
        this.historial();
      }else{
        this.axios_funcion();
      }*/

      /*switch(this.$route.path){
        case '/history':
          this.historial();
          break;
        case '/admin':
          this.admin();
          break;
        default:
          this.axios_funcion();
      }*/
        this.get_user();
        this.get_list_users();
        this.axios_funcion();

        /*this.desserts = [{
            ID: null,
            FECHA: '14/02/1998 15:00',
            ASIGNADO: 'RICHARD',
            ASUNTO: 'Gloria Rojas - Pedido de Volumetria',
            WORKLINE: 'AUTOMATIZACION',
            ESTADO: 'PENDIENTE',
            DIFICULTAD: 'MEDIA',
            DEADLINE: '12/01/2022',
            COMMENT: 'S',
            USUARIO_CREACION: 'RICHARD'
        }]*/


    },

    methods: {
        get_list_users(){
            let url = this.$my_host + '/sigma-back/public/index.php/api/lista_usuarios';
            axios.get(url)
            .then(response=> {
                this.list_users = response.data;
            })
        },

        get_user(){
            let url = this.$my_host + '/sigma-back/public/index.php/api/usuario_conectado';
            axios.get(url)
            .then(response=> {
                if(response.data[1].trim().length > 0){
                    if(response.data[3]){
                      this.autenticado = true;
                      this.user = response.data[1];
                    }else{
                      this.autenticado = false;
                      window.location.replace("/sigma/");
                      //window.location.href = "/";
                      //this.$router.replace("/");
                      //this.$router.push('/');
                    }
                }else{
                    this.autenticado = false;
                    this.$router.replace("/error");
                }
            })
            
        },

      estadistica(){
        this.autenticado = false;
        this.$router.replace("/metrica").catch(()=>{});
      },

      historial(){
        this.autenticado = false;
        this.$router.replace("/history_admin").catch(()=>{});
      },

      admin(){
        this.autenticado = false;
        this.$router.replace("/admin").catch(()=>{});
      },

      axios_funcion(){
        let url2 = this.$my_host + '/sigma-back/public/index.php/api/listar_actual';
        axios.get(url2, {
            params: {
              usuario: this.user
            }
        })
        .then(response=> {
            this.desserts = response.data;
        })
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      editStatus(item){
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogStatus = true;
      },

      editComment(item){
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogComment = true;
      },

      deleteItemConfirm () {
        let url = this.$my_host + '/sigma-back/public/index.php/api/eliminar';
        axios.get(url, {
          params: {
            ID: this.editedItem.ID
          }
        })
        .then(response=> {
          if(response.data == 1){
            this.desserts.splice(this.editedIndex, 1)
          }
        })
        this.closeDelete()
      },

      close () {
        this.dialog = false;
        this.mensaje_error = false;
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeStatus(){
        this.dialogStatus = false;
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeComment(){
        this.dialogComment = false;
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })

      },

      save () {
        if(this.editedItem.ASUNTO.trim() == '' || this.editedItem.WORKLINE.trim() == '' || this.editedItem.DIFICULTAD.trim() == '' || this.editedItem.ASIGNADO.trim() == ''){
          this.mensaje_error = true;

        }else{
          if (this.editedIndex > -1) {
            let url = this.$my_host + '/sigma-back/public/index.php/api/actualizar';
            axios.get(url, {
              params: {
                ID: this.editedItem.ID,
                ASUNTO: this.editedItem.ASUNTO,
                WORKLINE: this.editedItem.WORKLINE,
                ESTADO: this.editedItem.ESTADO,
                DIFICULTAD: this.editedItem.DIFICULTAD,
                DEADLINE: this.editedItem.DEADLINE,
                COMMENT: this.editedItem.COMMENT
              }
            })
            //.then(response=> {
              //if(response.data == 1){
                Object.assign(this.desserts[this.editedIndex], this.editedItem);
                //alert(response.data);
            //  })
            if(this.editedItem.ESTADO == 'FINALIZADO' || this.editedItem.ESTADO == 'CANCELADO'){
              this.desserts.splice(this.editedIndex, 1)

            }else{
              Object.assign(this.desserts[this.editedIndex], this.editedItem);
            }
          } else {
              let url = this.$my_host + '/sigma-back/public/index.php/api/crear';
              axios.get(url, {
                params: {
                  ASUNTO: this.editedItem.ASUNTO,
                  WORKLINE: this.editedItem.WORKLINE,
                  DIFICULTAD: this.editedItem.DIFICULTAD,
                  DEADLINE: this.editedItem.DEADLINE,
                  USUARIO_CREACION: this.user,
                  ASIGNADO: this.editedItem.ASIGNADO
                }
              })
              this.desserts.push(this.editedItem)
              window.location.reload()
          }

          this.close();
          this.closeStatus();
          this.closeComment();
          this.mensaje_error = false;
        }

        //alert(this.editedItem.ID);
        //aqui guardar en la base de datos con el ID en cuestion
        //Desabilitar boton si no esta escrito
      },
    },
  }
</script>

<style scoped>
  .sel{
    width: 50%;
    height: 40px;
    border: 2px solid rgba(0, 0, 0, 0.616);
    text-align: center;
  }

  h1{
    font-size:35px; 
    margin-top: -27%; 
    color: yellow; 
    font-family: Times New Roman, Times, serif;
  }

  .div_2{
      border: 3.5px;
      border-radius: 8px;
      border-style: solid;
      border-color: black;
      width: 40px;
      height: 40px;
      text-align: center;
      background-color: blue;
      margin-left: 1%;
  }

  .label_yo{
    margin-left: 5%;
    margin-top: 7px;
    position: absolute;
  }

  .lab{
    background: #E6ECEF;
    border-radius: 5px 0px 0px 5px;
    height: 40px;
    width: 45%;
    border: 2px solid rgb(175, 175, 175);
  }

  .flotante{
    z-index: 100;
  }

  .text-center{
    z-index: 1;
  }

  .v-btn {
    margin-right: 1%;
    margin-top: 1%;
    z-index: 0;
  }
</style>