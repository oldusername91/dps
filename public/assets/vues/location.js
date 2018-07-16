//Vue.component('search-main', {
//    props    : ['location'],
//    template : `
//    <div><label style="margin-right:10px;">Enter a postcode to search:</label>
//    <input 
//    v-bind:value="location"
//    type="text"></input>
//    <button @click=>Search</button>
//    </div>
//    `
//})

var app = new Vue({
  el: '#app',
  data : {
      location : '',
      stations : false,
      prices  : false
  },
  methods : {
      searchLocation : function () {
	axios
	.get('http://dps/location/' + this.location)
	.then(response => (this.stations = response.data.stations))
	.then(response => (this.stations = response.data.prices))
      }

  }
});
