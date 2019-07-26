// get needed elements
const loginFormContainer = document.querySelector('.login-form-container');
const loginSubmit = document.querySelector(".login-form input[type=submit]");
const loginShowPassword = document.querySelector(".login-show-password");

// if submit button is clicked run validation
loginSubmit.addEventListener("click", function(e){
    e.preventDefault()
    // console.log(e);
    login_check();
});

// toggle password show
loginShowPassword.addEventListener("click", function(){
    // get needed elements
    const loginPassword = document.querySelector('input[name=login-password]');
    // toggle
    if (this.classList.contains('fa-eye')) {
        this.classList.remove('fa-eye')
        this.classList.add('fa-eye-slash')
        loginPassword.type = 'text';
    } else {
        this.classList.remove('fa-eye-slash')
        this.classList.add('fa-eye')
        loginPassword.type = 'password';
    }
    
});

// animation trigger functions-------------------------------------------------------------
function login_pass() {
    // check to see if we need to remove error animation
    // ...code...
    // get needed elements
    const loginFormContainer = document.querySelector('.login-form-container');
    const loginContainer = document.querySelector('.login-container');
    // runs login leave animation
    loginFormContainer.classList.add('login-leave');
    loginFormContainer.classList.add('login-success');
    setTimeout(function() { 
        loginFormContainer.classList.remove('login-form-container-hold'); 
    }, 1600);
    // fade out background
    setTimeout(function() {document.querySelector('.login-bg').classList.add('fade-out');}, 500); 
    setTimeout(function() {
        loginContainer.classList.add('fade-out');
        setTimeout(function() {loginContainer.classList.add('non-active-container');}, 500);
    }, 1000); 
}

function login_failed() {
    // get needed elements
    const loginFormContainer = document.querySelector('.login-form-container');
    const loginForm = document.querySelector('.login-form');
    // runs login error animation
    loginFormContainer.classList.add('login-error');
    loginForm.classList.add('login-error');
    // take off error so it can run again 
    setTimeout(function() { 
        loginFormContainer.classList.remove('login-error'); 
        loginForm.classList.remove('login-error'); 
        document.querySelector('.form-start').focus();
    }, 1200);
}

function login_check() {
    // check if pass or fail test validation
    // get needed elements
    const loginUsername = document.querySelector('input[name=login-username]');
    const loginPassword = document.querySelector('input[name=login-password]');
    // check
    // console.log(loginUsername.value.trim());
    // console.log(loginPassword.value.trim());
    // loginUsername.value.trim() == "Ram" && loginPassword.value.trim() == "Gogo1234"
    if (loginUsername.value.trim() == "" && loginPassword.value.trim() == "") {
        login_pass()
    } else {
        login_failed();
    }
     
}
// animation trigger functions-------------------------------------------------------------

// run login-enter animation
loginFormContainer.classList.add('login-enter');
setTimeout(function() { 
    loginFormContainer.classList.add('login-form-container-hold');
    loginFormContainer.classList.remove('login-enter');
}, 1600);

// sets focus, needs timeout function so it dose not brake layout
setTimeout(function(){ document.querySelector('.form-start').focus(); }, 1000);

// get scroll effect
const tableView = document.querySelector('.table-view');
tableView.addEventListener("scroll", function(tableView){
    console.log(document.querySelector('.table-view').scrollTop);
    console.log(document.querySelector('.table-view').scrollLeft);
});