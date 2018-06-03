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

Vue.component('register-instead', {
    template : `
    <div class="register-instead">No account?<a href="/register"> Register here.</a></div>
    `
})



var app = new Vue({
  el: '#app',
});
