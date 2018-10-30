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
    
# NEW

h1. *[UI/UX] Compile & Using*

*I. [UI/UX] Resources & compile and using*
> *- Resources's UI/UX*
> > !https://78.media.tumblr.com/88795626e6530da701f8435e413f8aac/tumblr_p5iht1b4jh1uviehdo1_500.png!
> > 
> > +Note+: add css or js, then put the right place
> > 
> *- Compile*: After getting the new code from the testing branch, run the following commands.
> > + 1. npm install
> > + 2. npm run dev
> > +Note+: (2) command runs whenever a css or js code changes (or adds)
> > 
> *- Using*:
> + The Jquery Library (v3.2), Jquery-ui, Bootstrap (v3.3.7), font-awesome (v4.7) are already available, and people just call and use it (included both css and js).
> + If you need to embed css into your page, get it in public/css/lib (if it's a library). 
> + If you need to embed js into your page, get it in public/js/lib (if it's a library) or in public/js/pages (if it's a page). 

*II. [UI/UX] Components usage*
> *- Datepicker*:
> > Embed file datetime.js at the end of the .blade.php file 
> > *<script src="{{ mix('js/pages/helpers/datetime.js') }}"></script>*
> >and use one of these functions, depends on each screen's requirement:
> > > - For datepicker, call function: *Datetime.initForDatepicker();* and add class *datepicker* to input field
> > > - For datepicker limit, call function: *Datetime.initForDatepickerLimit();* and add class *datepicker_limit* to input field
> > > - For datetimepicker limit, call function: *Datetime.initForDateTimepicker();* and add class *datetimepicker* to input field and embed 
> > > *<script src="{{ mix('js/lib/jquery-ui-timepicker-addon.js') }}"></script>* at the end of .blade.php file
> > > - For timepicker limit, call function: *Datetime.initForTimepicker();*, add class *timepicker* to input field and embed 
> > > *<script src="{{ mix('js/lib/jquery-ui-timepicker-addon.js') }}"></script>* at the end of .blade.php file
> *- Modal*:
> > Link docs: https://getbootstrap.com/docs/4.0/components/modal/
> >
> > To use common modal style, please add class *modal-global* as below:
> > > <div class="modal *modal-global*" id="modal-ID" tabindex="-1" role="dialog">
> *- Validate*:
> > + Embed 3 js files at the end of the .blade.php file and call the function FormUtil.validate ('formId') | formId is id of form to need validation.
> > > *<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>*
> > > *<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>*
> > > *<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>*
> > > *<script src="{{ mix('js/utilities/form.validate.js') }}"></script>*
> > >
> > > *<script>*
> > > *FormUtil.validate('#formId');*
> > > *</script>*
> + Using:
> > *Note*: Everyone must use the structure and style form, as well as the form elements under Bootstrap 4:
> > > http://getbootstrap.com/docs/4.0/components/forms/
> > >
> > > By default, the label contains error will be put right after *input, select, textarea* tags. If you need to *place the error message somewhere else*, please follow these steps:
> > > > 1. Create a div to store all label error, *this div must have and id*. For example: <div *id="error-contain"*>error goes in here</div>
> > > > 2. Add attribute *data-error-container="#error-contain"* in the tag you need.
> > 1. All of the input, select, and textarea tags need to be validated will need to put data attrubite *data-rule-{rule}* inside the tag. If a tag has multiple validate conditions, then we will need to put multiple *data-rule-{rule}* inside it.
>>>*For example:* We have an input tag which *cannot be blank*, input *number only*, and range from *5 to 10*, then the tag will be written as below:
>>> <input type="text" *data-rule-required="true" data-rule-number="true" data-rule-minlength="5" data-rule-maxlength="10"*>
> > 2. Validate required, add attr: *data-rule-required="true"*
> > 3. Validate max lenght, add attr: *data-rule-maxlength="maxLenght"* | _maxLenght_ is max length
> > 4. Validate min lenght, add attr: *data-rule-minlength="minLenght"* | _minLenght_ is min lenght
> > 5. Validate type number, add attr: *data-rule-number="true"*
> > 6. Validate type email, add attr: *data-rule-email=”true”*
> > 7. Validate *multiple email*, add *class*: *multiple-email-validation* to the field
> > 8. Validate date time picker field, 
> > > - For date picker, input field will be *automatically validate* by format *YYYY/MM/DD*.
> > > - For time picker, input field will be *automatically validate* by format *HH:MM*.
> > > - For date time picker, input field will be *automatically validate* by format *YYYY/MM/DD HH:SS*.
> > > - To validate time *From less than or equal to To*, please add this data attribute to From field: *data-rule-lessThanTime="#ToTimeID"*
> > 9. For other validation rules, please refer to these example below:
> >
> > > (Tested, core)
> > >
> > >*-   data-rule-required="true"
> > >-   data-rule-email="true"*
> > >
> > >(Untested, core, but should work)
> > > 
> > > *-   data-rule-url="true"
> > >-   data-rule-date="true"
> > >-   data-rule-dateISO="true"
> > >-   data-rule-number="true"
> > >-   data-rule-digits="true"
> > >-   data-rule-creditcard="true"
> > >-   data-rule-min="5"
> > >-   data-rule-max="10"
> > >-   data-rule-equalto="#password"
> > >-   data-rule-remote="custom-validatation-endpoint.aspx"*
> > >(Or using custom pattern)
> > >*-   data-rule-pattern=""*
> > 10. For the case of checkbox group, which required at least one checkbox to be checked, please put all check boxes inside a div, and apply rule *data-group-required="true"* for that div. For example:
> > ><div data-group-required="true">
> > > ><input type="checkbox">
> > > ><input type="checkbox">
> > > ><input type="checkbox">
> > > ><input type="checkbox">
> > > ><input type="checkbox">
> > ></div>
> *- Pagination*:
> > + Add the code below to the table:
> > > <div class="dataTables_paginate">
> > > > <a class="paginate_button previous disabled" href="#" rel="prev" aria-controls="tbBillSearch" id="tbBillSearch_previous">< 前ページ</a>
> > > > <span class="pl-3 pr-3"></span>
> > > > <a class="paginate_button next active" href="#" rel="next" aria-controls="tbBillSearch" id="tbBillSearch_next">次ページ ></a>
> > > </div>
> > + Note: The PHP team needs additional test conditions to work exactly.
> > > For example:
> > > > ![](https://68.media.tumblr.com/2305fba2444d16b4f8e4381b4fb58fd6/tumblr_p66im1S51S1uviehdo1_1280.png)

*- Multiselect*:
>> Reference: http://www.erichynds.com/blog/jquery-ui-multiselect-widget
> > + Embed 2 js files at the end of the .blade.php file *above the page's javascript*.
> > > *<script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>*
> > > *<script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>* (just add if select has filter function)
> > > *<script src="{{ mix('js/pages/auto_commission_corp_index.js') }}"></script>*
> > >
> > > In page's javascript: Get id of select
> > > *Case multiselect:*
> > > $('#site_id').multiselect({
> > > > > >            multiple: true,
> > > > > >           checkAllText: '全選択',
> > > > > >           uncheckAllText: '選択解除',
> > > > > >           noneSelectedText: '--なし--'
> > > > > >       });
> > >
> > > *Case multiselect and filter:*
> > > $('#site_id').multiselect({
> > > > > >           multiple: true,
> > > > > >           checkAllText: '全選択',
> > > > > >           uncheckAllText: '選択解除',
> > > > > >           noneSelectedText: '--なし--'
> > > > > >       }).multiselectfilter({
> > > > > >           label: ''
> > > > > >        });
