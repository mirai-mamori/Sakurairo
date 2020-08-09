<style type="text/css">
.forgetmenot input:checked + label {
    background: <?php echo akina_option('theme_skin'); ?>;
}
#labelTip {
    background-color: <?php echo akina_option('theme_skin'); ?>;
}
#label {
    color: <?php echo akina_option('theme_skin'); ?>;
}
#login .submit .button {
    background: <?php echo akina_option('theme_skin'); ?>;
}

#login { 
	font:14px/1.4 "Helvetica Neue","HelveticaNeue",Helvetica,Arial,sans-serif;
	position:absolute;
	background: rgba(255, 255, 255, 0.40);
	border-radius: 12px;
	top:50%;
	left:50%;
	width:350px;
    /*height: 500px;*/
	padding:0px !important;
	margin:-235px 0px 0px -175px !important; 
    background-position: center 48%;
}

<?php if (akina_option('login_pf', '1')): ?>
#login {
    font: 14px/1.4 "Helvetica Neue","HelveticaNeue",Helvetica,Arial,sans-serif;
    position: absolute;
    background: rgba(255, 255, 255, 0.40);
    border-radius: 12px;
    top: 50%;
    left: 50%;
    width: 350px;
    /* height: 500px; */
    padding: 0px !important;
    margin: -265px 0px 0px -175px !important;
    background-position: center 48%;
}
<?php endif; ?>

<?php if (akina_option('login_blur', '0')): ?>
#bg{
	-webkit-filter: blur(2px); /* Chrome, Opera */
	-moz-filter: blur(2px);
	-ms-filter: blur(2px);   
	filter: blur(2px);
}
<?php endif; ?>

</style>
