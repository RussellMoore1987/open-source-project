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
        setTimeout(function() {loginContainer.classList.add('non-active-container');}, 700);
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

// log out
const logoutBtn = document.querySelector('.logout-btn');
logoutBtn.addEventListener("click", function(){
    // get elements
    const loginContainer = document.querySelector('.login-container');
    const loginBg = document.querySelector('.login-bg');
    const loginFormContainer = document.querySelector('.login-form-container');

    // move loginContainer back into position 
    loginContainer.classList.remove('non-active-container');
    // fade in loginContainer
    // loginContainer.classList.remove('fade-out');
    loginContainer.classList.add('fade-in');
    loginContainer.classList.remove('fade-out');
    // faded background image
    loginBg.classList.add('fade-in');

    // run login-enter animation
    loginFormContainer.classList.remove('login-leave');
    loginFormContainer.classList.remove('login-success');
    setTimeout(function() { 
        loginFormContainer.classList.add('login-form-container-hold');
        loginFormContainer.classList.remove('login-enter');
    }, 1600);
    loginFormContainer.classList.add('login-enter');
    setTimeout(function() { 
        loginFormContainer.classList.add('login-form-container-hold');
        loginFormContainer.classList.remove('login-enter');
        loginContainer.classList.remove('fade-in');
        // sets focus, needs timeout function so it dose not brake layout
        setTimeout(function(){ document.querySelector('.form-start').focus(); }, 1000);
    }, 1600);
});

// get scroll effect
const tableView = document.querySelector('.table-view');
tableView.addEventListener("scroll", function(){
    // console.log(document.querySelector('.table-view').scrollTop);
    // console.log(document.querySelector('.table-view').scrollLeft);
    const top = document.querySelector('.table-view').scrollTop;
    const left = document.querySelector('.table-view').scrollLeft;
    document.querySelector('.floating-header').scrollLeft = left;
    document.querySelector('.floating-options.moveable').scrollTop = top;
});
// Using the wheel event to trigger table-view to go up or down which will move the floating-options. Need to do it this way, using scroll caused some wired problems (really slow mouse scroll)
// https://gist.github.com/andjosh/6764939 
// https://www.sitepoint.com/html5-javascript-mouse-wheel/
const floatingOptions = document.querySelector('.floating-options.moveable');
floatingOptions.addEventListener("wheel", function(e){
    const tableViewTop = document.querySelector('.table-view').scrollTop
    if (e.wheelDelta > 0) {
        document.querySelector('.table-view').scrollTop = tableViewTop - 50;
    } else {
        document.querySelector('.table-view').scrollTop = tableViewTop + 50;
    }
    // check to see if over top > tableViewTop, This helps the table floating options not to go down further than the table does
    if (top > tableViewTop) {
        document.querySelector('.floating-options.moveable').scrollTop = tableViewTop;  
    }
    document.querySelector('.floating-options.moveable').scrollLeft = 0;
});

// search actions
const searchTables = document.querySelector(".tables-search");
searchTables.addEventListener("input", function(){
    // console.log(document.querySelector(".tables-search").value);
    // console.log(document.querySelectorAll(".table-card"));
    // get search value
    const searchValue = document.querySelector(".tables-search").value;
    // get cards
    const tableCards = document.querySelectorAll(".table-card");
    for (let i = 0; i < tableCards.length; i++) {
        // individual table card
        const element = tableCards[i];
        const tableName = element.dataset.name.toLowerCase();
        // check if str contains search value
        if (tableName.includes(searchValue.trim().toLowerCase())) {
            // make space
             element.classList.remove("table-card-hide");
        } else {
            // hide           
            element.classList.add("table-card-hide");
        }
    }
});

// edit view
const editTablesView = document.querySelector(".edit-table-btn"); 
editTablesView.addEventListener("click", function() {
    const devToolContainer = document.querySelector(".dev-tool-container");
    devToolContainer.classList.toggle("edit-tables-view");
});
