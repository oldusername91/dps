Vue.component('blog-post', {
    props : ['post'],
    template : `
    <div>
      <h3>{{ post.title }}</h3>
      <button v-on:click="$emit('grow')">Grow</button>
      <button v-on:click="$emit('shrink')">Shrink</button>
      <div v-html="post.content"></div>
    </div>`
})


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
            <a class="nav-link" href="#">Link</a>
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



Vue.component('login-form', {
    template : `
    <div class="login-form">
    <form method="post" action="/login"  class="form-horizontal">
        <h2 class="text-center">Sign in</h2>
            <fieldset>
                <form-group type="text" name="email" label="Email"></form-group>
                <form-group type="password" name="password" label="Password"></form-group>
                <submit-btn></submit-btn>
                <register-instead></register-instead>
            </fieldset>
        </form>
    </div>
    `
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

Vue.component('register-instead', {
    template : `
    <div class="register-instead">No account?<a href="/register"> Register here.</a></div>
    `
})



var app = new Vue({
  el: '#app',
});
