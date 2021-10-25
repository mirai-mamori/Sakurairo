<style>
.forgetmenot input:checked + label {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
#labelTip {
    background-color: <?php echo iro_opt('theme_skin'); ?>;
}
#label {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
#login .submit .button {
    background: <?php echo iro_opt('theme_skin'); ?>;
}

<?php if (iro_opt('captcha_select') != 'off'): ?>
	#login { 
	font:14px/1.4 "Helvetica Neue","HelveticaNeue",Helvetica,Arial,sans-serif;
	position:absolute;
	background: rgba(255, 255, 255, 0.40);
	border-radius: 12px;
	top:40%;
	left:50%;
	width:350px;
	padding:0px !important;
	margin:-235px 0px 0px -175px !important; 
    background-position: center 48%;
}
<?php endif; ?>

<?php if (iro_opt('login_blur', 'true')): ?>
body::before{
	-webkit-backdrop-filter: blur(2px);
	backdrop-filter: blur(2px);
	content:"";
	width:100vw;
	height:100vh;
	display: block;
}
<?php endif; ?>

</style>
