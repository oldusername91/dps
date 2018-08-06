
Vue.component('tabs-xx', {
    data  : function(){
        return {
            tab0 : true,
            tab1 : false,
            tab2 : false,
        }
    },
    methods : {
        updateTab : function(event){
            this.tab0 = false;
            this.tab1 = false;
            this.tab2 = false;

            utab = event.target.getAttribute('id');

            this[utab] = true;
            console.log(event.target.getAttribute('id'));
        }
    },
    template : `
    <div>
        <div>
            <div id="tab0" @click="updateTab" class="tabs" :class="{ active : tab0}">First</div>
            <div id="tab1" @click="updateTab" class="tabs" :class="{ active : tab1}">Second</div>
            <div id="tab2" @click="updateTab" class="tabs" :class="{ active : tab2}">Third</div>
        </div>
        <div v-if="tab0">Yes</div>
        <div v-if="tab1">No</div>
        <div v-if="tab2">Woohoo</div>
        </div>
    </div>
    `
})


var app = new Vue({
  el: '#app'
});
