<template>
    <v-app style="background: gray"> 
        <div class="contenedor">
            <div class="hijo" style="text-align: center;">
                <p>Error: Acceso Denegado</p>
                Contactar con <a style="color: orange" href="mailto:richard.cabrera@itau.com.py">richard.cabrera@itau.com.py</a> para acceder
                <p></p>
                ID Equipo: {{resp}}
                <p></p>
            </div>
        </div>
    </v-app>
</template>

<script>
import axios from 'axios'
export default {
    name: 'error',
    data(){
        return{
            resp: null,
            autenticado: false,
        }
    },
    methods:{
        login_get(){
            var url = 'http://192.168.0.8/sigma-back/public/index.php/api/usuario_conectado'
            axios.get(url)
            .then(response=> {
                if(response.data[1].trim().length > 0){
                    if(response.data[3]){
                        this.$router.replace("/admin");
                    }else{
                        //this.$router.replace("/");
                        window.location.replace("/sigma/");
                    }
                }
            
                this.resp = response.data[2];
            })
        }
    },
    created(){
        this.login_get()
    }
}
</script>

<style>
    .super_div{
        background-color: gray;
    }

    .contenedor{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15%;
    }
    .hijo {
        background: #525252;
        width: 600px;
        border-style: solid;
        font-family: Arial, Helvetica, sans-serif;
        border-color: black;
        color: white;
        font-size: 20px;
    }

    p {
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
        font-size: 30px;
        color: white;
    }
</style>