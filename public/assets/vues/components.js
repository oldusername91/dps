Vue.component('header-main', {
    template : `
    <nav class="navbar navbar-expand-md navbar-light">
      <a class="navbar-brand" href="#"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#"><span class="fa fa-home"></span><span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/login">Log in</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Dropdown
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>`
})

Vue.component('form-group', {
    props : ['label', 'name', 'type'],
    template : `
    <div class="form-group">
        <div class="row"><label>{{ label }}</label></div>
        <div class="row"><input :type="type" :name="name"/></div>
    </div>
    `
})

Vue.component('submit-btn', {
    props : ['label', 'name'],
    template : `
    <button class="btn">Submit</button>
    `
})


Vue.component('petrol-station-card', {
    props : ['station'],
    template : `
    <div style="display:flex;">
	<div class="ps_name">
	  <h4>{{station.name}}</h4>
	</div>
	<div class="ps_prices"></div>
    </div>
    `
})
