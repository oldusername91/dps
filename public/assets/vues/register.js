Vue.component('register-form', {
    template : `
    <div class="register-form">
    <form method="post" action="/register"  class="form-horizontal">
        <h2 class="text-center">Sign up</h2>
            <fieldset>
                <form-group type="text" name="email" label="Email"></form-group>
                <form-group type="password" name="password" label="Password"></form-group>
                <submit-btn></submit-btn>
                <login-instead></login-instead>
            </fieldset>
        </form>
    </div>
    `
})

Vue.component('login-instead', {
    template : `
    <div class="login-instead">Already registered?<a href="/login"> Log in here.</a></div>
    `
})


var app = new Vue({
  el: '#app',
});
