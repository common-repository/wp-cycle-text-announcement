function wpcytxt_setting_submit()
{
	if(document.wpcytxt_setting_form.wpcytxt_sname.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_sname);
		document.wpcytxt_setting_form.wpcytxt_sname.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_slink.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_slink);
		document.wpcytxt_setting_form.wpcytxt_slink.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_sspeed.value=="" || isNaN(document.wpcytxt_setting_form.wpcytxt_sspeed.value))
	{
		alert(wp_cycle_adminscripts.wpcytxt_sspeed);
		document.wpcytxt_setting_form.wpcytxt_sspeed.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_stimeout.value=="" || isNaN(document.wpcytxt_setting_form.wpcytxt_stimeout.value))
	{
		alert(wp_cycle_adminscripts.wpcytxt_stimeout);
		document.wpcytxt_setting_form.wpcytxt_stimeout.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_sdirection.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_sdirection);
		document.wpcytxt_setting_form.wpcytxt_sdirection.focus();
		return false;
	}
}

function wpcytxt_content_submit()
{
	if(document.wpcytxt_content_form.wpcytxt_ctitle.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_ctitle);
		document.wpcytxt_content_form.wpcytxt_ctitle.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_clink.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_clink);
		document.wpcytxt_content_form.wpcytxt_clink.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_csetting.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_csetting);
		document.wpcytxt_content_form.wpcytxt_csetting.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_cstartdate.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_cstartdate);
		document.wpcytxt_content_form.wpcytxt_cstartdate.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_cenddate.value=="")
	{
		alert(wp_cycle_adminscripts.wpcytxt_cenddate);
		document.wpcytxt_content_form.wpcytxt_cenddate.focus();
		return false;
	}
}

function wpcytxt_content_delete(id)
{
	if(confirm(wp_cycle_adminscripts.wpcytxt_cdelete))
	{
		document.frm_wpcytxt_display.action="options-general.php?page=wp-cycle-text-announcement&ac=del&did="+id;
		document.frm_wpcytxt_display.submit();
	}
}	

function wpcytxt_content_redirect()
{
	window.location = "options-general.php?page=wp-cycle-text-announcement";
}

function wpcytxt_setting_redirect()
{
	window.location = "options-general.php?page=wp-cycle-text-announcement&ac=showcycle";
}

function wpcytxt_help()
{
	window.open("http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/");
}