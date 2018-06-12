<template>
    <div>  
        <div class="w3-panel w3-blue w3-round" style="margin-top:0">
            <h1 class="w3-center">{{labels[0].text}}</h1>
        </div>

        <div class="w3-row">
            <div class="w3-col l3" style="visibility:hidden">left</div>
            <div class="w3-col l6 w3-center">
                <form @submit.prevent="doLogin" class="w3-container">
                    <label class="w3-">{{labels[2].text}}</label>
                    <input class="w3-input" type="text" v-model="login">
                        <br>
                    <label>{{labels[1].text}}</label>
                    <input class="w3-input" type="password" v-model="pass">

                    <div class="w3-display-container w3-panel">                           
                        <button class="w3-button w3-hover-gray w3-amber w3-round w3-display-topmiddle" type="submit">{{labels[3].text}}</button> 
                    </div>
                </form>
            </div>
        </div>
                
  </div>
</template>

<script>
export default {
        name: "Login",
        data(){
            return {
                login: '',
                pass: '',
                lang: localStorage.getItem('lang'),
                labels: []
            };          
        },
        mounted(){
            this.reload();
        },        
        methods: {
            reload(){                
                this.$http.get('api/ui/'+localStorage.getItem('lang')+'/Login')
                    .then((response) =>{
                        this.labels = response.data;
                    }); 
            },
            doLogin() {         
                this.$http.post('api/login', {
                    login: this.login,
                    pass: this.pass
                }).then((response) => {                     
                    this.$http.defaults.headers.common['Authorization'] = response.data.token;     
                    localStorage.setItem('token', response.data.token);                    
                    this.$router.push({name: 'inLayout'});
                }, () => {
                    alert(this.labels[4].text);
                });
            }
        }
    }
</script>

<style scoped>

</style>