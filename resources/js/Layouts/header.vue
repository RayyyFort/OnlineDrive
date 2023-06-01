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
                    color:this.randomcolor()
                }
            }
        },
    }
</script>

<template>
    <header :style="styleObject">
        <div class="d-flex justify-content-around">
            <p>logo</p>
            <div>
                <div class="flex flex-row">
                    <p>Login                       </p>
                    <p>Register</p>
                </div>
            </div>
        </div>
    </header>
</template>