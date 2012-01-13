// $Id: listsurvey.js 9692 2011-01-15 21:31:10Z c_schmitz $
$(document).ready(function(){

$("#addbutton").click(function(){
id=2;
html = "<tr name='joincondition_"+id+"' id='joincondition_"+id+"'><td><select name='join_"+id+"' id='join_"+id+"'><option value='and'>AND</option><option value='or'>OR</option></td><td></td></tr><tr><td><select name='field_"+id+"' id='field_"+id+"'>\n\
<option value='firstname'>"+colNames[2]+"</option>\n\
<option value='lastname'>"+colNames[3]+"</option>\n\
<option value='email'>"+colNames[4]+"</option>\n\
<option value='emailstatus'>"+colNames[5]+"</option>\n\
<option value='token'>"+colNames[6]+"</option>\n\
<option value='sent'>"+colNames[7]+"</option>\n\
<option value='remindersent'>"+colNames[8]+"</option>\n\
<option value='remindercount'>"+colNames[9]+"</option>\n\
<option value='completed'>"+colNames[10]+"</option>\n\
<option value='usesleft'>"+colNames[11]+"</option>\n\
<option value='validfrom'>"+colNames[12]+"</option>\n\
<option value='validuntil'>"+colNames[13]+"</option>\n\
</select>\n\</td>\n\<td>\n\
<select name='condition_"+id+"' id='condition_"+id+"'>\n\
<option value='equal'>"+searchtypes[0]+"</option>\n\
<option value='contains'>"+searchtypes[1]+"</option>\n\
<option value='notequal'>"+searchtypes[2]+"</option>\n\
<option value='notcontains'>"+searchtypes[3]+"</option>\n\
<option value='greaterthan'>"+searchtypes[4]+"</option>\n\
<option value='lessthan'>"+searchtypes[5]+"</option>\n\
</select></td>\n\<td><input type='text' id='conditiontext_"+id+"' style='margin-left:10px;' /></td>\n\
<td><img src="+minusbutton+" onClick= $(this).parent().parent().remove();$('#joincondition_"+id+"').remove() id='removebutton'"+id+">\n\
<img src="+addbutton+" id='addbutton'  onclick='addcondition();' style='margin-bottom:4px'></td></tr><tr></tr>";
$('#searchtable tr:last').after(html);
});
var searchconditions = {};
var field;
$('#searchbutton').click(function(){
        
});
var lastSel,lastSel2;
function returnColModel() {
    if($.cookie("detailedsurveycolumns")) {
        hidden=$.cookie("detailedsurveycolumns").split('|');
        for (i=0;i<hidden.length;i++)
            if(hidden[i]!="false") colModels[i]['hidden']=true;
    }
    return colModels;
}
jQuery("#displaysurveys").jqGrid({
    align:"center",
    url: jsonUrl,
    editurl: editUrl,
    datatype: "json",
    mtype: "post",
    colNames : colNames,
    colModel: returnColModel(),
    toppager: true,
    height: "100%",
    width: "100%",
    rowNum: 25,
    editable:true,
    scrollOffset:0,
    autowidth: true,
    sortable : true,
    sortname: 'sid',
    sortorder: 'asc',
    viewrecords : true,
    rowList: [25,50,100,250,500,1000,5000,10000],
    multiselect: true,
    loadonce : true,
    pager: "#pager",
    caption: "Surveys",
    });
jQuery("#displaysurveys").jqGrid('navGrid','#pager',{ add:false,del:true,edit:false,refresh: false,search: true},{},{},{ msg:delmsg, width : 700 });
jQuery("#displaysurveys").jqGrid('filterToolbar', {searchOnEnter : false});
jQuery("#displaysurveys").jqGrid('navButtonAdd','#pager',{
    buttonicon:"ui-icon-refresh",
    caption:"",
    title: "Refresh",
    onClickButton : function (){
        $("#displaysurveys").setGridParam({datatype:'json', page:1}).trigger('reloadGrid');
    }
});
jQuery("#displaysurveys").jqGrid('navButtonAdd','#pager',{
    buttonicon:"ui-icon-calculator",
    caption:"",
    title: "Select Columns",
    onClickButton : function (){
        jQuery("#displaysurveys").jqGrid('columnChooser', {
            done : function (perm) {
                if (perm) {
                    this.jqGrid("remapColumns", perm, true);
                    var hidden = [];
                    $.each($("#displaysurveys").getGridParam("colModel"), function(key, val) {hidden.push( val['hidden'] );});
                    hidden.splice(0,1);
                    $.cookie("detailedsurveycolumns", hidden.join("|") );
                }
            }
        });
    }
});
$('.ui-jqgrid .ui-jqgrid-htable th div').css('white-space', 'normal');
$('.ui-jqgrid .ui-jqgrid-htable th div').css('height', 'auto');
});