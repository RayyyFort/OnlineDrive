<script>
    export default {
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
            <div class="d-flex flex-row">
                <a :href="route('About')">About</a>
                <a :href="route('login')">Login</a>
                <a :href="route('register')">Register</a>
            </div>
        </div>
    </header>
</template>