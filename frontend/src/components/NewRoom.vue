<template>
    <div>
        <h2 class="w3-blue w3-round">{{labels[4].text}}</h2>
        <form >
            <div class="w3-row">
                <div class="w3-col m9 l10">
                <input v-model="title" class="w3-left w3-round w3-large" style="width: 100%">
                </div>
                <div class="w3-col m3 l2">                
                <select v-model="roomlang" class="w3-round w3-large w3-right">
                    <option value=""></option>
                    <option value="en">EN</option>
                    <option value="cz">CZ</option>
                </select>  
                <label class="w3-large w3-right">{{labels[5].text}}:&nbsp;</label>
                </div>
            </div> 
            <div class="w3-row w3-margin">
            <button v-on:click.prevent="createRoom" type="submit" class="w3-button w3-hover-gray w3-round w3-amber">
                {{labels[4].text}}
            </button>
            </div>
        </form>
  </div>
</template>

<script>
export default {        
    props: ['labels'],
    data(){
        return {
            title: '',
            roomlang: '',
        }           
    },
    methods: {
        createRoom() {  
            this.$http.post('api/auth/rooms', {
                    title: this.title,
                    roomlang: this.roomlang
                }).then((response) => {
                    console.log(response.data); 
                    this.title = '';
                    this.roomlang = '';                    
                    this.$emit('new');
                });
            }
    }
}
</script>

<style scoped>

</style>
