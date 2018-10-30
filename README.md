### I. Structure folder
   |-app
   |---Console
   |-----Commands      <----- shell command directory
   |---Exceptions
   |---Helpers         <----- helper function such as AmazonSnsUtility, CommonUtility
   |---Http
   |-----Controllers   <----- controller
   |-------Auth
   |-------Demand
   |-------User
   |-----Middleware    <----- Local.php for handle multilingual, CheckRole for authorized
   |-----Requests      <----- FormRequest for validation
   |---Models          <----- Database models
   |---Providers       <----- DBQueryServiceProvider, HashServiceProvider, ResponseMacroServiceProvider   
   |---Services        <----- Cake255Hash, DBQuery
   |---Validators      <----- create globalrule for validation
   |-bootstrap
   |---cache
   |-config            <----- cake.php for using hash passwords
   |-database
   |---factories
   |---migrations      <----- m_users table migrate for remmember_token
   |---seeds
   |-public
   |---css
   |---js
   |-resources
   |---assets
   |-----js
   |-------components
   |-----sass
   |---lang
   |-----en
   |-----jp
   |---views           <------ views
   |-----auth
   |-------passwords
   |-----demand
   |-----layouts
   |-----partials
   |-----user
   |-routes            <----- routes for web, api, ... add group and roles
   |-storage           <----- sql/files
   |---app
   |-----public
   |---framework
   |-----cache
   |-----sessions
   |-----testing
   |-----views
   |---logs
   |-tests
   |---Feature
   |---Unit
   
### II. How to install
https://laravel.com/docs/5.5/installation
- php 7.0 or above
- database PostgreSQL 9.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- git clone https://st-orange.backlog.jp/git/ORANGE/rits-laravel-5.5.git
- change database connection
- composer install
- php artisan serve --env=testing
   
### III. Porting
1. Create routes. Note that check roles and group for route
2. Create controller in Http/Controllers. All controller should be locate in subfolder such as Demand/DemandController.php, User/UserController.php
3. Create models in Http/Models. E.g. MUser.php, MItem.php
4. If validation is needed, add App/Http/Requests class. E.g. RegisterDemandFromRequest.php, RegisterUserFormRequest.php
5. All cake components is moved to common models
6. Reformat (indent should be 1tab=4spaces) code before commit to git


### IV. IDE (PHPStorm) config
- install laravel code style https://github.com/michaeldyrynda/phpstorm-laravel-code-style
- enable phpcs and phpmd

### VI. [UI/UX] Resources & compile and using
- Resources's UI/UX

        resources
            |
            |-sass-|
            |	  |- pages (which contains the pages's css files)
            |	  |
            |	  |- lib (which contains the library's css files)
            |	
            |-js-|
                |-pages (which contains the pages's js files)
                |
                |- lib (which contains the library's js files)

    #Note: add css or js, then put the right place

- Compile: After getting the new code from the testing branch, run the following commands.
    + 1. npm install
    + 2. npm run dev
    #Note: (2) command runs whenever a css or js code changes (or adds)

- Using:
    + The Jquery Library (v3.2), Jquery-ui, Bootstrap (v3.3.7), font-awesome (v4.7) are already available, and people just call and use it (included both css and js).
    + If you need to embed css into your page, get it in public/css/lib (if it's a library). 
    + If you need to embed js into your page, get it in public/js/lib (if it's a library) or in public/js/pages (if it's a page). 

### VII. [UI/UX] Components usage
- Datepicker:
    $('.class').datepicker({}); // class: class name
    $('#id').datepicker({}); // id: id name
    Link docs: https://jqueryui.com/datepicker/