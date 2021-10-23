var csrfTokenPage = $('meta[name="csrf-token"]').attr("content");
function fn_Check_Page_Exists_or_Not_in_withleftbar_woleftbar(PageId) {
	existingIds=$("#hdn_not_to_show_pages_list_Ids").val();
	existsFlag=0;
	
	if($.trim(existingIds)!='') {
	  page_list_IdsArr=existingIds.split(',');
	  for(i=0;i<page_list_IdsArr.length;i++) {
		  if($.trim(page_list_IdsArr[i])!='') {
			if(page_list_IdsArr[i]==PageId) {
				  existsFlag=1;
			}
		  }
	  }
	}
	
	
	existingIds_2=$("#hdn_pages_list_Ids").val();
	if($.trim(existingIds_2)!='') {
	  page_list_Ids_2_Arr=existingIds_2.split('#');
	  for(i=0;i<page_list_Ids_2_Arr.length;i++) {
		  if($.trim(page_list_Ids_2_Arr[i])!='') {
			if(page_list_Ids_2_Arr[i]==PageId) {
				  existsFlag=2;
			}
		  }
	  }
	}
	return existsFlag;
}
function fn_Check_Heading_Exists_or_Not(headingId) {
	existingIds=$("#hdn_heading_list_Ids").val();
	existsFlag=false;
	heading_list_IdsArr=existingIds.split(',');
	for(i=0;i<heading_list_IdsArr.length;i++) {
		if($.trim(heading_list_IdsArr[i])!='') {
		  if(heading_list_IdsArr[i]==headingId) {
			  	existsFlag=true;
		  }
		}
	}
	return existsFlag;
}
function fn_GET_All_Heading_IDs() {
	ss=0;
	allHeadingIds=0;
	$(".headingClass").each(function() {
		allHeadingIds+=($(this).data('heading-id'))+",";
		ss++;
	});
	return allHeadingIds;
}
function fn_GET_First_Heading_ID() {
	ss=0;
	firstHeadingId=0;
	$(".headingClass").each(function() {
		if(ss>0) { mm=0; }else {
		 firstHeadingId=($(this).data('heading-id'));
		}
		ss++;
	});
	return firstHeadingId;
}
function AddEditRole() {

	pgsLst	= $("#hdn_pages_list_Ids").val();
	headingLst=$("#hdn_heading_list_Ids").val();
	
	role_name = $("#role_name").val();
	role_description = $("#role_description").val();
	

	$("#RoleNameErrMsg").html("");
	$("#RoleDescErrMsg").html("");
	$("#PageErrMsg").html("");
	$("#HeadingErrMsg").html("");
	$("#DefaultPageErrMsg").html("");
	if($.trim(role_name)=='') {
		$("#RoleNameErrMsg").html("<b><font color='red'>Enter Role Name</font>");
		return false;
	}
	
	if($.trim(role_description)=='') {
		$("#RoleDescErrMsg").html("<b><font color='red'>Enter Role Description</font>");
		return false;
	}
	

	if($.trim(pgsLst)=='') {
		$("#PageErrMsg").html("<b><font color='red'>Select Page</font>");
		return false;
	}
	
	defatulPageVal=$("#sel_role_default_page").val();

	if($.trim(defatulPageVal)=='') {
		$("#DefaultPageErrMsg").html("<b><font color='red'>Select Default Page</font>");
		return false;
	}

	headingSortIds='';

	$(".headingClass").each(function() {

		 headingSortIds+=($(this).data('heading-id'))+",";
	});


    headingId_PageIds='';
	headingIds=headingSortIds;

	if($.trim(headingIds)=='') {
		$("#HeadingErrMsg").html("<b><font color='red'>Please Select Leftbar Heading</font>");
		return false;
	}

	headingPageExists=false;
	headingIdsArr=headingIds.split(',');
	for(i=0;i<headingIdsArr.length;i++) {
		if($.trim(headingIdsArr[i])!='') {	
		headingId_PageIds+=headingIdsArr[i]+"#";
		$("#UL_Heading_"+headingIdsArr[i]+" li").each(function() {
			headingId_PageIds+=($(this).attr('data-liId'))+",";
			headingPageExists=true;
		});
		headingId_PageIds+="|";
	  }
	}

	$("#PageErrMsg").html("");
	PostUrl = pstURL_Add_Edit_Role;
	$("#loading_action_Row").show();
	$("#post_action_Row").html("");
	oldpgsLst = $("#hdn_old_pages_list_Ids").val();
	$("#btnAction_role").hide();
	
	not_shown_pageIds=$("#hdn_not_to_show_pages_list_Ids").val();
	not_shown_old_pageIds=$("#hdn_old_not_to_show_pages_list_Ids").val();

	$.post(
			PostUrl, {
					pagesList:pgsLst,
					role_name:role_name,
					role_description:role_description,
					pstOldpgsLst:oldpgsLst,
					pstEditRoleId:$("#hdn_roleId").val(),
					pstHeadingIds:headingIds,
					pstHeadingPageIds:headingId_PageIds,
					pstDefaultPageId:defatulPageVal,
					pstNotShownPageIds:not_shown_pageIds,
					pstNotShownOldPageIds:not_shown_old_pageIds,
            		_csrf : csrfTokenPage
				},
				function(ResponseData)
				{
                    $("#loading_action_Row").hide();
					if($.trim(ResponseData)=="1") {

                        $("#page_names_div").html("");
                        $("#sel_user").val("");
                        $("#hdn_old_pages_list_Ids").val('');
                        $("#hdn_pages_list_Ids").val('');
                        $("#hdn_page_names").val('');

						$("#post_action_Row").html("<b><font color='green'>Role Added/Updated Successfully.</font></b>");
						window.location.href =rdURL_role;
					}
					else if($.trim(ResponseData)=="roleNameEmpty") {
                        $("#role_name").val('');
                        $("#RoleNameErrMsg").html("<b><font color='red'>Enter Role Name</font>");
                        $("#btnAction_role").show();
                    }
                    else if($.trim(ResponseData)=="roleDescEmpty") {
                        $("#role_description").val('');
                        $("#RoleDescErrMsg").html("<b><font color='red'>Enter Role Description</font>");
                        $("#btnAction_role").show();
                    }
					else {
						$("#btnAction_role").show();
						$("#post_action_Row").html("<b><font color='red'>Something went wrong.</font></b>");
					}
				}
	);	
}
function getPageDetails(e,slectdVal) {
	//alert(slectdVal);
	$("#PageErrMsg").empty();
	var selectedIcdCode 	= 	$("#inputPage").val();
	sICdArr					=	slectdVal.split('@#|');
	
	
	var selectedPCP 		=  	sICdArr[0].split(' (');
	dx_code 				= 	selectedPCP[0];
	var npi 				= 	selectedPCP[1].slice(0,-1)
	var pcpNamesHtml 		= '';
	//alert(dx_code);
	//alert(npi);
	//getting providers from the hidden field
	var provider_npis 		= 	$("#hdn_page_names").val();
	var provider_npis_type 	= 	$("#hdn_pages_list_Ids").val();

	$("#inputPage").val(''); 
	returnFlag_test=fn_Check_Page_Exists_or_Not_in_withleftbar_woleftbar(npi);
	if(returnFlag_test!=0) {
		$("#PageErrMsg").html('Page:&nbsp;<strong>"'+dx_code+'"</strong> already selected.Please select another Page.').css({'color':'red'}); 
		return false;
	}

	firstHeading_Id=fn_GET_First_Heading_ID();
	$('#UL_Heading_'+firstHeading_Id).append("<li id='heading_page_li_"+npi+"' class='LiheadingSort lsort1' data-liId='"+npi+"'>"+dx_code+"</li>");
	if(provider_npis != '') {
		//append new icd to the already exsisits icds in the hidden field
		var added_icds 			= 	provider_npis+"#"+dx_code;
		var added_npis 			= 	provider_npis_type+"#"+npi;
		//assigning to value to hidden field
		$("#hdn_page_names").val(added_icds);
		$("#hdn_pages_list_Ids").val(added_npis);
		
		provider_npis_arr 		= 	added_icds.split('#');
		provider_npis_type_arr 	= 	added_npis.split('#');
		provider_npis_count 	= 	provider_npis_arr.length;
		var i 					= 	0;
		var ids 				= 	1;
		for (var i = 0; i < provider_npis_count; i++) { 
		pcpNamesHtml += "<span class='badge bg-primary rounded-pill' id='pcp_div_"+ids+"' data-npi="+provider_npis_type_arr[i]+">"+provider_npis_arr[i]+"&nbsp;<a class=' text-white' href='javascript:void(0);' onclick = 'removePageName("+ids+")'>x</a></span>&nbsp;";
				ids++;		
		}
		$("#page_names_div").html(pcpNamesHtml);
	} else {
		var ids = 1;
		$("#hdn_page_names").val(dx_code);
		$("#hdn_pages_list_Ids").val(npi);
		pcpNamesHtml += "<span class='badge bg-primary rounded-pill' id='pcp_div_"+ids+"' data-npi="+npi+">"+dx_code+"&nbsp;<a class=' text-white' href='javascript:void(0);' onclick = 'removePageName("+ids+")'>x</a></span>&nbsp;";
		$("#page_names_div").html(pcpNamesHtml);
		
	}
	
	$('#sel_role_default_page').append($('<option>', {
           value: npi,
           text: dx_code
    }));
	$("#inputPage").val('');
}
function removePageName(ids) {

	var provider_npis 		= 	$("#hdn_page_names").val();
	var provider_list_arr 	= 	provider_npis.split('#');
	var provider_npis_type 	= 	$("#hdn_pages_list_Ids").val();
	provider_npis_type_arr 	= 	provider_npis_type.split('#');
	
	//Getting provider which was clicked to remove
	var removeText 			= 	$("#pcp_div_"+ids).text();
	var removeNpi 			= 	$("#pcp_div_"+ids).data("npi");	
	var removeIcdArr 		= 	removeText.split('<a');
	var removeIcdCode 		= 	removeIcdArr[0].replace('x','');
	removeIcdCode 			= 	removeIcdCode.trim();
	$("#heading_page_li_"+removeNpi).remove();
	$('#sel_role_default_page option[value="'+removeNpi+'"]').remove();

	var a 					= 	provider_list_arr.indexOf(removeIcdCode);
	
	//removing provider
    provider_list_arr.splice(a,1);
	provider_npis_type_arr.splice(a,1);	
	
	//Removing the html clicked provider
	$("#pcp_div_"+ids).remove();
	
	//getting new array length
	provider_npis_count = provider_list_arr.length;
	
	//converting array to string
	var new_provider_npis 		= 	provider_list_arr.join("#");
	var new_provider_npis_type 	= 	provider_npis_type_arr.join("#");
	
	//assigning new values to hidden input field
	$("#hdn_page_names").val(new_provider_npis);
	$("#hdn_pages_list_Ids").val(new_provider_npis_type);
	

	var pcpNamesHtml = '';
	//appending remaining icd codes
	var ids = 1;
	for (var i = 0; i < provider_npis_count; i++) { 
	 pcpNamesHtml += "<span class='badge bg-primary rounded-pill' id='pcp_div_"+ids+"' data-npi="+provider_npis_type_arr[i]+">"+provider_list_arr[i]+"&nbsp;<a class=' text-white' href='javascript:void(0);' onclick = 'removePageName("+ids+")'>x</a></span>&nbsp;";
			
			ids++;		
		}
	$("#page_names_div").html(pcpNamesHtml);
	
}
function removeHeading(headingId) {
	$("#HeadingErrMsg").html("");
	
	//Remove Pages in Heading
	existingPageIds=$("#hdn_pages_list_Ids").val();
	//alert(existingPageIds);
	if($.trim(existingPageIds)!='') {
	  existingPageIdsArr=existingPageIds.split('#');
	  $("#UL_Heading_"+headingId+" li").each(function() {
		  //alert($(this).attr('data-liId'));
		  existsPageId=$(this).attr('data-liId')
		  existsPageIdIndex=existingPageIdsArr.indexOf(existsPageId);
		  if(existsPageIdIndex!='-1') {
			 pgLiItem=$("#heading_page_li_"+existsPageId).html();
			 $('#UL_Heading_0').append("<li id='heading_page_li_"+existsPageId+"' class='LiheadingSort lsort1' data-liId='"+existsPageId+"'>"+pgLiItem+"</li>");
		  }
	  });
	}
	
	//Remove Heading
	$("#div_heading_"+headingId).remove();
	//$("#hdn_heading_list_Ids").val(headingId+",");
	$("#heading_close_div_"+headingId).remove();
	existingIds=$("#hdn_heading_list_Ids").val();
	newIds='';
	hdn_heading_list_IdsArr=existingIds.split(',');
	for(i=0;i<hdn_heading_list_IdsArr.length;i++) {
		if($.trim(hdn_heading_list_IdsArr[i])!='') {
		  if(hdn_heading_list_IdsArr[i]==headingId) {
			  ss=0;
		  }else {
			  newIds+=hdn_heading_list_IdsArr[i]+",";
		  }
		}
	}
	$("#hdn_heading_list_Ids").val(newIds);
}
function AddHeadingDetails(e,slectdVal) {
	//alert(slectdVal);
	
	$("#HeadingErrMsg").empty();
	var selectedItem 	= 	$("#inputHeading").val();
	var selectedHeading 		=  	slectdVal.split(' (');
	headingName 				= 	selectedHeading[0];
	var headingId 				= 	selectedHeading[1].slice(0,-1)
	returnFlag=fn_Check_Heading_Exists_or_Not(headingId);
	if(returnFlag==true) {
		$("#HeadingErrMsg").html('Heading:&nbsp;<strong>"'+headingName+'"</strong> already selected.Please select another Heading.').css({'color':'red'}); 
		return false;
	}
	htmlData='<div id="div_heading_'+headingId+'" class="well span2 tile headingClass ui-sortable-handle" data-heading-id="'+headingId+'"><strong>'+headingName+'</strong><br /><ul class="ULheadingSort ui-sortable" id="UL_Heading_'+headingId+'"></ul></div>';
	if($.trim($("#HeadingBobILI").html())!='') {
		$("#HeadingBobILI").html($("#HeadingBobILI").html()+htmlData);
	}else {
		$("#HeadingBobILI").html(htmlData);
	}
	//heading_names_div
	
	$("#inputHeading").val('');
	
	//Start Inner Page Sort
	$(".ULheadingSort").on('click', 'li', function (e) {
    if (e.ctrlKey || e.metaKey) {
        $(this).toggleClass("selected");
    } else {
        $(this).addClass("selected").siblings().removeClass('selected');
    }
}).sortable({
    connectWith: "ul",
    delay: 150, //Needed to prevent accidental drag when trying to select
    revert: 0,
    helper: function (e, item) {
        var helper = $('<li/>');
        if (!item.hasClass('selected')) {
            item.addClass('selected').siblings().removeClass('selected');
        }
        var elements = item.parent().children('.selected').clone();
        item.data('multidrag', elements).siblings('.selected').remove();
        return helper.append(elements);
    },
    stop: function (e, info) {
        info.item.after(info.item.data('multidrag')).remove();
    }

});
	//End Inner Page Sort 

	//Start Heading Sort Div 
	$(".grid").sortable({
        tolerance: 'pointer',
        revert: 'invalid',
        placeholder: 'span2 well placeholder tile',
        forceHelperSize: true
    });
	//End Heading Sort Div
	
	if($.trim($("#hdn_heading_list_Ids").val())!='') { 
		$("#hdn_heading_list_Ids").val($("#hdn_heading_list_Ids").val()+","+headingId+",");
	}else {
		$("#hdn_heading_list_Ids").val(headingId);
	}
	headingNamesHtml="";
	if($.trim($("#heading_names_div").html())!='') {
		headingNamesHtml+="<span class='badge bg-primary rounded-pill' id='heading_close_div_"+headingId+"'>"+headingName+"&nbsp;";
		headingNamesHtml+="<a class='text-white' href='javascript:void(0);'	onclick = 'removeHeading("+headingId+")'>x</a></span>&nbsp;";
		$("#heading_names_div").html($("#heading_names_div").html()+headingNamesHtml);
	}else {
		headingNamesHtml+="<span class='badge bg-primary rounded-pill' id='heading_close_div_"+headingId+"'>"+headingName+"&nbsp;";
		headingNamesHtml+="<a class='text-white' href='javascript:void(0);'	onclick = 'removeHeading("+headingId+")'>x</a></span>&nbsp;";
		$("#heading_names_div").html(headingNamesHtml);
	}
	
	
	$("#inputHeading").val('');
}
function getPageDetails_not_to_show_in_leftbar(e,slectdVal) {

	$("#PageErr_Not_to_Show_in_LeftBar_Msg").empty();
	var selectedItem 	= 	$("#inputPage_not_to_show").val();
	
	sICdArr					=	slectdVal.split('@#|');
	
	var selectedPgae 		=  	sICdArr[0].split(' (');
	pageName 				= 	selectedPgae[0];
	var pageId 				= 	selectedPgae[1].slice(0,-1);


	//hdn_not_to_show_pages_list_Ids
	returnFlag=fn_Check_Page_Exists_or_Not_in_withleftbar_woleftbar(pageId);
	if(returnFlag!=0) {
		$("#PageErr_Not_to_Show_in_LeftBar_Msg").html('Page:&nbsp;<strong>"'+pageName+'"</strong> already selected.Please select another Page.').css({'color':'red'}); 
		return false;
	}
	
	if($.trim($("#hdn_not_to_show_pages_list_Ids").val())!='') { 
		$("#hdn_not_to_show_pages_list_Ids").val($("#hdn_not_to_show_pages_list_Ids").val()+","+pageId+",");
	}else {
		$("#hdn_not_to_show_pages_list_Ids").val(pageId);
	}
	pgNamesHtml="";

	$("#inputPage_not_to_show").val("");
	if($.trim($("#page_names_not_to_show_in_LeftBar_div").html())!='') {
		pgNamesHtml+="<span class='badge bg-primary rounded-pill' id='pg_wo_leftbar_close_div_"+pageId+"'>"+pageName+"&nbsp;";
		pgNamesHtml+="<a class='text-white' href='javascript:void(0);'	onclick = 'removePg_wo_leftbar("+pageId+")'>x</a></span>&nbsp;";
		$("#page_names_not_to_show_in_LeftBar_div").html($("#page_names_not_to_show_in_LeftBar_div").html()+pgNamesHtml);
	}else {
		pgNamesHtml+="<span class='badge bg-primary rounded-pill' id='pg_wo_leftbar_close_div_"+pageId+"'>"+pageName+"&nbsp;";
		pgNamesHtml+="<a class='text-white' href='javascript:void(0);'	onclick = 'removePg_wo_leftbar("+pageId+")'>x</a></span>&nbsp;";
		$("#page_names_not_to_show_in_LeftBar_div").html(pgNamesHtml);
	}
	
	
	$("#inputPage_not_to_show").val('');
}
function removePg_wo_leftbar(pageId) {
	$("#PageErr_Not_to_Show_in_LeftBar_Msg").html("");
	
	//Remove Pages in Heading
	existingPageIds=$("#hdn_not_to_show_pages_list_Ids").val();
	
	$("#pg_wo_leftbar_close_div_"+pageId).remove();
	existingIds=$("#hdn_not_to_show_pages_list_Ids").val();
	newIds='';
	hdn_pg_list_IdsArr=existingIds.split(',');
	for(i=0;i<hdn_pg_list_IdsArr.length;i++) {
		if($.trim(hdn_pg_list_IdsArr[i])!='') {
		  if(hdn_pg_list_IdsArr[i]==pageId) {
			  ss=0;
		  }else {
			  newIds+=hdn_pg_list_IdsArr[i]+",";
		  }
		}
	}
	$("#hdn_not_to_show_pages_list_Ids").val(newIds);
}
