<template>
    <div>                    
        <table class="w3-table w3-striped w3-text-black">
            <tbody>
                <tr>
                    <td>Login</td><td>{{user.login}}</td>
                </tr>           
                <tr>
                    <td>E-mail</td><td>{{user.email}}</td>
                </tr>
                <tr>
                    <td>{{labels[10].text}}</td><td>{{user.name}}</td>
                </tr>
                <tr>
                    <td>{{labels[11].text}}</td><td>{{user.surname}}</td>
                </tr>
                <tr>
                    <td>{{labels[12].text}}</td><td v-if="user.gender==='male'">{{labels[13].text}}</td>
                    <td v-else-if="user.gender==='female'">{{labels[14].text}}</td>
                    <td v-else>{{labels[25].text}}</td>
                </tr>
                <tr>
                    <td>{{labels[9].text}}</td><td>{{user.regist}}</td>
                </tr>
            </tbody>
        </table>    
        <footer class="w3-container w3-blue">        
                    <p>{{labels[26].text}}: {{user.poc}}<br>
                    {{labels[27].text}}: {{user.poca}}</p>           
        </footer>
     </div>
</template>

<script>
export default {
    name: 'User',
    props: ['uid', 'labels', 'user'],
    data(){
        return{             
        }
    }
}
</script>

<style scoped>
    
</style>
