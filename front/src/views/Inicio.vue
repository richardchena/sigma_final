<!--<template>
    <div>
        INICIANDO SESIÓN
    </div>
</template>-->

<script>
import axios from 'axios'
export default {
    name: 'inicio',
    data(){
        return{
            resp: null
        }
    },
    methods:{
        login_get(){
            var url = 'http://192.168.0.8/sigma-back/public/index.php/api/usuario_conectado'
            axios.get(url)
            .then(response=> {
                this.$emit('authentificated', true);
                if(response.data[0]){
                    this.$router.push('/df').catch(err => err);
                }else{
                    this.$router.push('/error').catch(err => err);
                }
            })

        }
    },
    created(){
        this.login_get()
    }
}
</script>