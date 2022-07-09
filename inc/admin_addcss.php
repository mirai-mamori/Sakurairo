<style>
.csf-header-left {
    margin-top: -29px;
}
/* 圆角 */
.csf-theme-light .csf-container {
    border: 0px solid #ccd0d4;
    box-shadow: 0 0 15px rgba(0,0,0,.04);
}
.csf-field .csf--transparent-wrap {
    top: 5px;
    border-radius: 10px;
}
.csf-field-tabbed .csf-tabbed-content{
    border-radius: 0 10px 10px 10px;
}
.csf-field-tabbed .csf-tabbed-nav a,
.csf-field-tabbed .csf-tabbed-nav a.csf-tabbed-active {
    border-radius: 10px 10px 0 0;
}
.csf-field-heading,
.csf-field-subheading,.csf-submessage-success,.csf-submessage-danger,
.wp-picker-container .iris-picker,
.csf-submessage-info {
    border-radius: 10px;
    margin: 12px;
}
.csf-field-image_select .csf--active figure,.csf-field-image_select img,.csf-field-image_select figure,
.csf-field-slider .ui-slider-handle,
.csf-image-preview,.csf-image-preview i
{
    border-radius: 10px;
}
.csf-form-warning,.csf-form-success,
.csf-field-code_editor .cm-s-default,
.csf-image-preview img{
    border-radius: 5px;
}
.csf-field+.csf-field {
    border-top: 0px solid #eee;
}
.csf-field-image_select figure:before {
    top: 5px;
    left: 5px;
    line-height: 17px;
    border-radius: 10px;
}
/* 顶栏 */
.csf-theme-light .csf-header-inner {
    border-bottom: 0px;
    /* background: linear-gradient(#fefefe, <?php echo iro_opt('admin_first_class_color'); ?> ); */
    border-radius: 10px 10px 0 0;
}
/* 底栏 */
.csf-theme-light .csf-footer {
    color: #555;
    border-top: 0px;
    /* background: linear-gradient(<?php echo iro_opt('admin_first_class_color'); ?> ,#f5f5f5); */
    border-radius: 0 0 10px 10px;
}

<?php if (iro_opt('admin_left_style') == "v1") : ?>
/* 侧边栏颜色 */
.csf-theme-light .csf-nav ul li a {
	font-weight: 500;
	/* color: <?php echo iro_opt('admin_text_color'); ?>; */
	/* background-color: <?php echo iro_opt('admin_first_class_color'); ?>; */
	border-radius: 10px;
}
.csf-theme-light .csf-nav-normal>ul li .csf-active{
    border-right-color: #f5f5f5;
}
.csf-theme-light .csf-nav ul ul li a {
    /* background-color: <?php echo iro_opt('admin_second_class_color'); ?>; */
    border-radius: 10px;
}

.csf-theme-light .csf-nav ul li a:hover {
	color: <?php echo iro_opt('admin_text_color'); ?>;
    background-color: <?php echo iro_opt('admin_second_class_color'); ?>;
    border-radius: 10px;
}

.csf-theme-light .csf-nav ul li .csf-active {
	color: <?php echo iro_opt('admin_text_color'); ?>;
    background-color: <?php echo iro_opt('admin_first_class_color'); ?>;
    border-radius: 10px;
}
/* 图标与字间距 */
.csf-nav .csf-tab-icon {
    margin-right: 4px;
}
.csf-theme-light .csf-nav-normal>ul li a {
	border-top: 3px solid #f5f5f5;
    border-bottom: 3px solid #f5f5f5;
	border-right: 4px solid #f5f5f5;
    border-left: 4px solid #f5f5f5;
}
.csf-theme-light .csf-nav-background {
    /* background-color: <?php echo iro_opt('admin_first_class_color'); ?>; */
    border-right: 0px solid <?php echo iro_opt('admin_first_class_color'); ?>;
    width: 227px;
}
<?php endif; ?>

<?php if (iro_opt('admin_left_style') == "v2") : ?>
/* 侧边栏颜色2 */
.csf-theme-light .csf-nav ul li a:hover {
	color: <?php echo iro_opt('admin_text_color'); ?>;
    border-left: <?php echo iro_opt('admin_first_class_color'); ?> 4px solid;
}

.csf-theme-light .csf-nav ul li .csf-active {
	color: <?php echo iro_opt('admin_text_color'); ?>;
    border-left: <?php echo iro_opt('admin_first_class_color'); ?> 4px solid;
}
.csf-theme-light .csf-nav-normal>ul li a {
	border: 0px solid #f5f5f5;
}
.csf-theme-light .csf-nav-background {
    border-right: 0px solid <?php echo iro_opt('admin_first_class_color'); ?>;
    width: 227px;
}
<?php endif; ?>

/* 侧边栏动画 */
.csf-nav ul li.csf-tab-expanded ul {
    max-height: 666px;
    transition: max-height .9s;
	display: block !important
}
.csf-nav ul ul {
    max-height: 0;
    overflow: hidden;
    transition: max-height .6s;
	display: block !important;
}

.csf-nav {
    position: -webkit-sticky;
    position: sticky;
    top: 125px;
}
</style>