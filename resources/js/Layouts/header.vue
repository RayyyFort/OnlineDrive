<script>
    export default {
        props: {
            logged: String,
        },
        methods:{
            randomcolor: function (){
                return "#" + Math.floor(Math.random() * 16777215).toString(16)
            }
        },
        computed:{
            styleObject: function () {
                return{
                    'background-color':this.randomcolor(),
                    color:this.randomcolor(),
                    'min-height':'50px'
                }
            }
        },
    }
</script>

<template>
    <header :style="styleObject" class="d-flex flex-row justify-content-around align-items-center">
        <p>logo</p>
        <div>
            <div v-if="logged=='1'" class="d-flex flex-row">
                <a :href="route('Files')">Files</a>
                <a :href="route('About')">About</a>
            </div>
            <div v-else class="d-flex flex-row">
                <a :href="route('About')">About</a>
                <a :href="route('login')">Login</a>
                <a :href="route('login')">Login</a>
                <a :href="route('register')">Register</a>
            </div>
        </div>
    </header>
</template>