/****  Variables Initiation  ****/
var doc = document;
var docEl = document.documentElement;
var $sidebar = $('.sidebar');
var $mainContent = $('.main-content');
var $sidebarWidth = $(".sidebar").width();
var is_RTL = false; 
if($('body').hasClass('rtl'))  is_RTL = true;

/* ==========================================================*/
/* PLUGINS                                                   */
/* ========================================================= */

(function($) {
    $.fn.autogrow = function() {
        return this.each(function() {
            var textarea = this;
            $.fn.autogrow.resize(textarea);
            $(textarea).focus(function() {
                textarea.interval = setInterval(function() {
                    $.fn.autogrow.resize(textarea);
                }, 500);
            }).blur(function() {
                clearInterval(textarea.interval);
            });
        });
    };
    $.fn.autogrow.resize = function(textarea) {
        var lineHeight = parseInt($(textarea).css('line-height'), 10);
        var lines = textarea.value.split('\n');
        var columns = textarea.cols;
        var lineCount = 0;
        $.each(lines, function() {
            lineCount += Math.ceil(this.length / columns) || 1;
        });
        var height = lineHeight * (lineCount + 1);
        $(textarea).css('height', height);
    };
})(jQuery);

/**** Numeric Stepper ****/
function numericStepper(){
    if ($('.numeric-stepper').length && $.fn.TouchSpin) {
        $('.numeric-stepper').each(function () {
            $(this).TouchSpin({
                min: $(this).data('min') ? $(this).data('min') : 0,
                max: $(this).data('max') ? $(this).data('max') : 100,
                step: $(this).data('step') ? $(this).data('step') : 0.1,
                decimals: $(this).data('decimals') ? $(this).data('decimals') : 0,
                boostat: $(this).data('boostat') ? $(this).data('boostat') : 5,
                maxboostedstep: $(this).data('maxboostedstep') ? $(this).data('maxboostedstep') : 10,
                verticalbuttons: $(this).data('vertical') ? $(this).data('vertical') : false,
                buttondown_class: $(this).data('btn-before') ? 'btn btn-' + $(this).data('btn-before') : 'btn btn-default',
                buttonup_class: $(this).data('btn-after') ? 'btn btn-' + $(this).data('btn-after') : 'btn btn-default',
            });
        });
    }
}

 /**** Sortable Table ****/
function sortableTable(){
    if ($('.sortable_table').length && $.fn.sortable) {
        $(".sortable_table").sortable({
            itemPath: '> tbody',
            itemSelector: 'tbody tr',
            placeholder: '<tr class="placeholder"/>'
        });
    }
}

/****  Show Tooltip  ****/
function showTooltip(){
    if ($('[data-rel="tooltip"]').length && $.fn.tooltip) {
        $('[data-rel="tooltip"]').tooltip();
    }
}

 /****  Show Popover  ****/
function popover() {
    if ($('[rel="popover"]').length && $.fn.popover) {
        $('[rel="popover"]').popover({
            trigger: "hover"
        });
        $('[rel="popover_dark"]').popover({
            template: '<div class="popover popover-dark"><div class="arrow"></div><h3 class="popover-title popover-title"></h3><div class="popover-content popover-content"></div></div>',
            trigger: "hover"
        });
    }
}

function inputSelect(){
    if($.fn.select2){
        setTimeout(function () {
            $('select').each(function(){
                function format(state) {
                    var state_id = state.id;
                    if (!state_id)  return state.text; // optgroup
                    var res = state_id.split("-");
                    if(res[0] == 'image') {
                        if(res[2]) return "<img class='flag' src='assets/images/flags/" + res[1].toLowerCase() + "-" + res[2].toLowerCase() +".png' style='width:27px;padding-right:10px;margin-top: -3px;'/>" + state.text;
                        else return "<img class='flag' src='assets/images/flags/" + res[1].toLowerCase() + ".png' style='width:27px;padding-right:10px;margin-top: -3px;'/>" + state.text;
                    }
                    else {
                        return state.text; 
                    }
                }
                $(this).select2({
                    formatResult: format,
                    formatSelection: format,
                    placeholder: $(this).data('placeholder') ?  $(this).data('placeholder') : '',
                    allowClear: $(this).data('allowclear') ? $(this).data('allowclear') : true,
                    minimumInputLength: $(this).data('minimumInputLength') ? $(this).data('minimumInputLength') : -1,
                    minimumResultsForSearch: $(this).data('search') ? 1 : -1,
                    dropdownCssClass: $(this).data('style') ? 'form-white' : '',
                    maximumSelectionSize: 3 // Limit the number max of selection for multiple select
                });
            });

        }, 200);
    }
}

function inputTags(){
    $('.select-tags').each(function(){
        $(this).tagsinput({
            tagClass: 'label label-primary'
        });
    });
}

/****  Tables Responsive  ****/
function tableResponsive(){
    setTimeout(function () {
       $('.table').each(function () {
            window_width = $(window).width();
            table_width = $(this).width();
            content_width = $(this).parent().width();
            if(table_width > content_width) {
                $(this).parent().addClass('force-table-responsive');
            }
            else{
                $(this).parent().removeClass('force-table-responsive');
            }
        });
    }, 200);
}

function editableTable() {
    $('.dataTables_filter input').attr("placeholder", "Search table...");

    jQuery('#table-edit_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    jQuery('#table-edit_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    
    var oTableUsers = $('#table-editable-users').dataTable({
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "sDom": "<'row'<'col-md-6 filter-left'f>",
        // set the initial value
        "iDisplayLength": 10,
        "bPaginate": false,
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            },
            "sSearch": ""
        },
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
        }]
    });

    function restoreUsersRow(oTableUsers, nRow) {
        var aData = oTableUsers.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTableUsers.fnUpdate(aData[i], nRow, i, false);
        }
        oTableUsers.fnDraw();
    }

    function editUsersRow(oTableUsers, nRow) {
        var aData = oTableUsers.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '" required>';
        jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '" disabled>';
        jqTds[2].innerHTML = '<input type="text" class="form-control small" value="' + aData[2] + '" required>';
        jqTds[3].innerHTML = '<input type="text" class="form-control small" value="' + aData[3] + '" disabled>';
        jqTds[4].innerHTML = '<div class="text-right"><a class="edit btn btn-sm btn-success" href="">Save</a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>';
    }

    function saveUsersRow(oTableUsers, nRow) {
        var jqInputs = $('input', nRow);
        oTableUsers.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableUsers.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableUsers.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableUsers.fnUpdate(jqInputs[3].value, nRow, 3, false);
        oTableUsers.fnUpdate('<div class="text-right"><a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>', nRow, 4, false);
        oTableUsers.fnDraw();
    }

    function canceleditUsersRow(oTableUsers, nRow) {
        var jqInputs = $('input', nRow);
        oTableUsers.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableUsers.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableUsers.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableUsers.fnUpdate(jqInputs[3].value, nRow, 3, false);
        oTableUsers.fnUpdate('<a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a>', nRow, 4, false);
        oTableUsers.fnDraw();
    }
    var nEditingUsers = null;
    $('#table-editable-users a.delete').live('click', function(e) {
        e.preventDefault();

        var nRow = $(this).parents('tr')[0];
        var aData = oTableUsers.fnGetData(nRow);

        if (confirm("Are you sure to delete the user named '" + aData[0] + "' ?") == false) {
            return;
        }

        // Some AJAX to sync woth backend

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                oTableUsers.fnDeleteRow(nRow);
            }
        };

        xmlhttp.open("POST","../admin/assets/deleteUser.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("username="+aData[0]);
    });
    $('#table-editable-users a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
            var nRow = $(this).parents('tr')[0];
            oTableUsers.fnDeleteRow(nRow);
        } else {
            restoreUsersRow(oTableUsers, nEditingUsers);
            nEditingUsers = null;
        }
    });
    $('#table-editable-users a.edit').live('click', function(e) {
        e.preventDefault();
        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];
        if (nEditingUsers !== null && nEditingUsers != nRow) {
            restoreUsersRow(oTableUsers, nEditingUsers);
            editUsersRow(oTableUsers, nRow);
            nEditingUsers = nRow;
        } else if (nEditingUsers == nRow && this.innerHTML == "Save") {

            var oldData = oTableUsers.fnGetData(nRow);
            var usernameOld = oldData[0];
            var newData = oTableUsers.fnGetData(nEditingUsers);

            saveUsersRow(oTableUsers, nEditingUsers);

            // Some AJAX to sync woth backend

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    nEditingUsers = null;
                }
            };

            xmlhttp.open("POST","../admin/assets/updateUser.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("username_old="+usernameOld+"&username_new="+newData[0]+"&user_role="+newData[2]);
            
        } else {
            /* No row currently being edited */
            editUsersRow(oTableUsers, nRow);
            nEditingUsers = nRow;
        }
    });

    var oTableCommittees = $('#table-editable-committees').dataTable({
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "sDom": "<'row'<'col-md-6 filter-left'f>",
        // set the initial value
        "iDisplayLength": 10,
        "bPaginate": false,
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            },
            "sSearch": ""
        },
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
        }]
    });

    function restoreCommitteesRow(oTableCommittees, nRow) {
        var aData = oTableCommittees.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTableCommittees.fnUpdate(aData[i], nRow, i, false);
        }
        oTableCommittees.fnDraw();
    }

    function editCommitteesRow(oTableCommittees, nRow) {
        var aData = oTableCommittees.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control" value="' + aData[0] + '" required>';
        jqTds[1].innerHTML = '<input type="text" class="form-control" value="' + aData[1] + '" required>';
        jqTds[2].innerHTML = '<input type="text" class="form-control" value="' + aData[2] + '" disabled>';
        jqTds[3].innerHTML = '<div class="text-right"><a class="edit btn btn-sm btn-success" href="">Save</a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>';
    }

    function saveCommitteesRow(oTableCommittees, nRow) {
        var jqInputs = $('input', nRow);
        oTableCommittees.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableCommittees.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableCommittees.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableCommittees.fnUpdate('<div class="text-right"><a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>', nRow, 3, false);
        oTableCommittees.fnDraw();
    }

    function canceleditCommitteesRow(oTableCommittees, nRow) {
        var jqInputs = $('input', nRow);
        oTableCommittees.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableCommittees.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableCommittees.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableCommittees.fnUpdate('<a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a>', nRow, 3, false);
        oTableCommittees.fnDraw();
    }
    var nEditingCommittees = null;
    $('#table-editable-committees a.delete').live('click', function(e) {
        e.preventDefault();
        
        var nRow = $(this).parents('tr')[0];
        var aData = oTableCommittees.fnGetData(nRow);

        if (confirm("Are you sure to delete the committee named '" + aData[0] + "' ?") == false) {
            return;
        }

        // Some AJAX to sync with backend

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                oTableCommittees.fnDeleteRow(nRow);
            }
        };

        xmlhttp.open("POST","../admin/assets/deleteCommittee.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("committee_name="+aData[0]);

    });
    $('#table-editable-committees a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
            var nRow = $(this).parents('tr')[0];
            oTableCommittees.fnDeleteRow(nRow);
        } else {
            restoreCommitteesRow(oTableCommittees, nEditingCommittees);
            nEditingCommittees = null;
        }
    });
    $('#table-editable-committees a.edit').live('click', function(e) {
        e.preventDefault();
        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];
        if (nEditingCommittees !== null && nEditingCommittees != nRow) {
            restoreCommitteesRow(oTableCommittees, nEditingCommittees);
            editCommitteesRow(oTableCommittees, nRow);
            nEditingCommittees = nRow;
        } else if (nEditingCommittees == nRow && this.innerHTML == "Save") {
            
            var oldData = oTableCommittees.fnGetData(nRow);
            var committeeOld = oldData[0];
            var newData = oTableCommittees.fnGetData(nEditingCommittees);

            saveCommitteesRow(oTableCommittees, nEditingCommittees);

            // Some AJAX to sync woth backend

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    nEditingCommittees = null;
                }
            };

            xmlhttp.open("POST","../admin/assets/updateCommittee.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("committee_old="+committeeOld+"&committee_new="+newData[0]+"&committee_desc="+newData[1]);

        } else {
            /* No row currently being edited */
            editCommitteesRow(oTableCommittees, nRow);
            nEditingCommittees = nRow;
        }
    });

    var oTableAssociations = $('#table-editable-associations').dataTable({
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "sDom": "<'row'<'col-md-6 filter-left'f>",
        // set the initial value
        "iDisplayLength": 5,
        "bPaginate": false,
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            },
            "sSearch": ""
        },
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
        }]
    });

    function restoreAssociationsRow(oTableAssociations, nRow) {
        var aData = oTableAssociations.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTableAssociations.fnUpdate(aData[i], nRow, i, false);
        }
        oTableAssociations.fnDraw();
    }

    function editAssociationsRow(oTableAssociations, nRow) {
        var aData = oTableAssociations.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control" value="' + aData[0] + '" required>';
        jqTds[1].innerHTML = '<input type="text" class="form-control" value="' + aData[1] + '" required>';
        jqTds[2].innerHTML = '<input type="text" class="form-control" value="' + aData[2] + '" disabled>';
        jqTds[3].innerHTML = '<div class="text-right"><a class="edit btn btn-sm btn-success" href="">Save</a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>';
    }

    function saveAssociationsRow(oTableAssociations, nRow) {
        var jqInputs = $('input', nRow);
        oTableAssociations.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableAssociations.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableAssociations.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableAssociations.fnUpdate('<div class="text-right"><a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>', nRow, 3, false);
        oTableAssociations.fnDraw();
    }

    function canceleditAssociationsRow(oTableAssociations, nRow) {
        var jqInputs = $('input', nRow);
        oTableAssociations.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableAssociations.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableAssociations.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableAssociations.fnUpdate('<a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a>', nRow, 3, false);
        oTableAssociations.fnDraw();
    }
    var nEditingAssociations = null;
    $('#table-editable-associations a.delete').live('click', function(e) {
        e.preventDefault();

        var nRow = $(this).parents('tr')[0];
        var aData = oTableAssociations.fnGetData(nRow);

        if (confirm("Are you sure to delete the association named '" + aData[0] + "' ?") == false) {
            return;
        }

        // Some AJAX to sync woth backend

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                oTableAssociations.fnDeleteRow(nRow);
            }
        };

        xmlhttp.open("POST","../admin/assets/deleteAssociation.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("association_name="+aData[0]);
    });
    $('#table-editable-associations a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
            var nRow = $(this).parents('tr')[0];
            oTableAssociations.fnDeleteRow(nRow);
        } else {
            restoreAssociationsRow(oTableAssociations, nEditingAssociations);
            nEditingAssociations = null;
        }
    });
    $('#table-editable-associations a.edit').live('click', function(e) {
        e.preventDefault();
        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];
        if (nEditingAssociations !== null && nEditingAssociations != nRow) {
            restoreAssociationsRow(oTableAssociations, nEditingAssociations);
            editAssociationsRow(oTableAssociations, nRow);
            nEditingAssociations = nRow;
        } else if (nEditingAssociations == nRow && this.innerHTML == "Save") {
           
            var oldData = oTableAssociations.fnGetData(nRow);
            var associationOld = oldData[0];
            var newData = oTableAssociations.fnGetData(nEditingAssociations);

            saveAssociationsRow(oTableAssociations, nEditingAssociations);

            // Some AJAX to sync woth backend

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    nEditingAssociations = null;
                }
            };

            xmlhttp.open("POST","../admin/assets/updateAssociation.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("association_old="+associationOld+"&association_new="+newData[0]+"&association_website="+newData[1]);

        } else {
            /* No row currently being edited */
            editAssociationsRow(oTableAssociations, nRow);
            nEditingAssociations = nRow;
        }
    });

    var oTableOfficePeriods = $('#table-editable-periods').dataTable({
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "sDom": "<'row'<'col-md-6 filter-left'f>",
        // set the initial value
        "iDisplayLength": 10,
        "bPaginate": false,
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            },
            "sSearch": ""
        },
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
        }]
    });

    function restorePeriodsRow(oTableOfficePeriods, nRow) {
        var aData = oTableOfficePeriods.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTableOfficePeriods.fnUpdate(aData[i], nRow, i, false);
        }
        oTableOfficePeriods.fnDraw();
    }

    function editPeriodsRow(oTableOfficePeriods, nRow) {
        var aData = oTableOfficePeriods.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '" disabled>';
        jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '" required>';
        jqTds[2].innerHTML = '<input type="text" class="form-control small" value="' + aData[2] + '" required>';
        jqTds[3].innerHTML = '<div class="text-right"><a class="edit btn btn-sm btn-success" href="">Save</a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>';
    }

    function savePeriodsRow(oTableOfficePeriods, nRow) {
        var jqInputs = $('input', nRow);
        oTableOfficePeriods.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableOfficePeriods.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableOfficePeriods.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableOfficePeriods.fnUpdate('<div class="text-right"><a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>', nRow, 3, false);
        oTableOfficePeriods.fnDraw();
    }

    function canceleditPeriodsRow(oTableOfficePeriods, nRow) {
        var jqInputs = $('input', nRow);
        oTableOfficePeriods.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableOfficePeriods.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableOfficePeriods.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableOfficePeriods.fnUpdate('<a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a>', nRow, 3, false);
        oTableOfficePeriods.fnDraw();
    }
    var nEditingPeriods = null;
    $('#table-editable-periods a.delete').live('click', function(e) {
        e.preventDefault();

        var nRow = $(this).parents('tr')[0];
        var aData = oTableOfficePeriods.fnGetData(nRow);

        if (confirm("Are you sure to delete the period of office ?") == false) {
            return;
        }

        // Some AJAX to sync woth backend

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                oTableOfficePeriods.fnDeleteRow(nRow);
            }
        };

        xmlhttp.open("POST","../admin/assets/deletePeriod.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("period_id="+aData[0]);
    });
    $('#table-editable-periods a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
            var nRow = $(this).parents('tr')[0];
            oTableOfficePeriods.fnDeleteRow(nRow);
        } else {
            restorePeriodsRow(oTableOfficePeriods, nEditingPeriods);
            nEditingPeriods = null;
        }
    });
    $('#table-editable-periods a.edit').live('click', function(e) {
        e.preventDefault();
        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];
        if (nEditingPeriods !== null && nEditingPeriods != nRow) {
            restorePeriodsRow(oTableOfficePeriods, nEditingPeriods);
            editPeriodsRow(oTableOfficePeriods, nRow);
            nEditingPeriods = nRow;
        } else if (nEditingPeriods == nRow && this.innerHTML == "Save") {

            var oldData = oTableOfficePeriods.fnGetData(nRow);
            var periodId = oldData[0];
            var newData = oTableOfficePeriods.fnGetData(nEditingPeriods);

            savePeriodsRow(oTableOfficePeriods, nEditingPeriods);

            // Some AJAX to sync woth backend

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    nEditingPeriods = null;
                }
            };

            xmlhttp.open("POST","../admin/assets/updatePeriod.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("period_id="+periodId+"&period_date_starts="+newData[1]+"&period_date_ends="+newData[2]);
            
        } else {
            /* No row currently being edited */
            editPeriodsRow(oTableOfficePeriods, nRow);
            nEditingPeriods = nRow;
        }
    });

    var oTableMemberships = $('#table-editable-memberships').dataTable({
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "sDom": "<'row'<'col-md-6 filter-left'f>",
        // set the initial value
        "iDisplayLength": 10,
        "bPaginate": false,
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            },
            "sSearch": ""
        },
        "aoColumnDefs": [{
            'bSortable': true,
            'aTargets': [0]
        }]
    });

    function restoreMembershipsRow(oTableMemberships, nRow) {
        var aData = oTableMemberships.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTableMemberships.fnUpdate(aData[i], nRow, i, false);
        }
        oTableMemberships.fnDraw();
    }

    function editMembershipsRow(oTableMemberships, nRow) {
        var aData = oTableMemberships.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '" disabled>';
        jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '" required>';
        jqTds[2].innerHTML = '<input type="text" class="form-control small" value="' + aData[2] + '" required>';
        jqTds[3].innerHTML = '<div class="text-right"><a class="edit btn btn-sm btn-success" href="">Save</a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>';
    }

    function saveMembershipsRow(oTableMemberships, nRow) {
        var jqInputs = $('input', nRow);
        oTableMemberships.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableMemberships.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableMemberships.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableMemberships.fnUpdate('<div class="text-right"><a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a> <a class="delete btn btn-sm btn-danger" href=""><i class="icons-office-52"></i></a></div>', nRow, 3, false);
        oTableMemberships.fnDraw();
    }

    function canceleditMembershipsRow(oTableMemberships, nRow) {
        var jqInputs = $('input', nRow);
        oTableMemberships.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTableMemberships.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTableMemberships.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTableMemberships.fnUpdate('<a class="edit btn btn-sm btn-default" href=""><i class="icon-note"></i></a>', nRow, 3, false);
        oTableMemberships.fnDraw();
    }
    var nEditingMemberships = null;
    $('#table-editable-memberships a.delete').live('click', function(e) {
        e.preventDefault();

        var nRow = $(this).parents('tr')[0];
        var aData = oTableMemberships.fnGetData(nRow);

        if (confirm("Are you sure to delete the period of office ?") == false) {
            return;
        }

        // Some AJAX to sync woth backend

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                oTableMemberships.fnDeleteRow(nRow);
            }
        };

        xmlhttp.open("POST","../admin/assets/deleteMembership.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("membership_id="+aData[0]);
    });
    $('#table-editable-memberships a.cancel').live('click', function(e) {
        e.preventDefault();
        if ($(this).attr("data-mode") == "new") {
            var nRow = $(this).parents('tr')[0];
            oTableMemberships.fnDeleteRow(nRow);
        } else {
            restoreMembershipsRow(oTableMemberships, nEditingMemberships);
            nEditingMemberships = null;
        }
    });
    $('#table-editable-memberships a.edit').live('click', function(e) {
        e.preventDefault();
        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];
        if (nEditingMemberships !== null && nEditingMemberships != nRow) {
            restoreMembershipsRow(oTableMemberships, nEditingMemberships);
            editMembershipsRow(oTableMemberships, nRow);
            nEditingMemberships = nRow;
        } else if (nEditingMemberships == nRow && this.innerHTML == "Save") {

            var oldData = oTableMemberships.fnGetData(nRow);
            var periodId = oldData[0];
            var newData = oTableMemberships.fnGetData(nEditingMemberships);

            savePeriodsRow(oTableMemberships, nEditingMemberships);

            // Some AJAX to sync woth backend

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    nEditingMemberships = null;
                }
            };

            xmlhttp.open("POST","../admin/assets/updateMembership.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("membership_id="+periodId+"&membership_date_start="+newData[1]+"&membership_date_end="+newData[2]);
            
        } else {
            /* No row currently being edited */
            editPeriodsRow(oTableMemberships, nRow);
            nEditingMemberships = nRow;
        }
    });
};

/****  Tables Dynamic  ****/
function tableDynamic(){
    if ($('.table-dynamic').length && $.fn.dataTable) {
        $('.table-dynamic').each(function () {
            var opt = {};
            opt.bPaginate = false;
            if ($(this).hasClass('no-header')) {
                opt.bFilter = false;
                opt.bLengthChange = false;
            }
            if ($(this).hasClass('no-footer')) {
                opt.bInfo = false;
                opt.bPaginate = false;
            }
            if ($(this).hasClass('filter-head')) {
                $('.filter-head thead th').each( function () {
                    var title = $('.filter-head thead th').eq($(this).index()).text();
                    $(this).append( '<input type="text" onclick="stopPropagation(event);" class="form-control" placeholder="Filter '+title+'" />' );
                });
                var table = $('.filter-head').DataTable();
                $(".filter-head thead input").on( 'keyup change', function () {
                    table.column( $(this).parent().index()+':visible').search( this.value ).draw();
                });
            } 
            if ($(this).hasClass('filter-footer')) {
                $('.filter-footer tfoot th').each( function () {
                    var title = $('.filter-footer thead th').eq($(this).index()).text();
                    $(this).html( '<input type="text" class="form-control" placeholder="Filter '+title+'" />' );
                });
                var table = $('.filter-footer').DataTable();
                $(".filter-footer tfoot input").on( 'keyup change', function () {
                    table.column( $(this).parent().index()+':visible').search( this.value ).draw();
                });
            } 
            if ($(this).hasClass('filter-select')) {
                $(this).DataTable( {
                    initComplete: function () {
                        var api = this.api();
         
                        api.columns().indexes().flatten().each( function ( i ) {
                            var column = api.column( i );
                            var select = $('<select class="form-control" data-placeholder="Select to filter"><option value=""></option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $(this).val();
         
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
         
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        } );
                    }
                } );
            } 
            if (!$(this).hasClass('filter-head') && !$(this).hasClass('filter-footer') && !$(this).hasClass('filter-select'))  {
                var oTable = $(this).dataTable(opt);
                oTable.fnDraw();
            }
       
        });
    }
}

 /* Date picker */     
function bDatepicker(){
    $('.b-datepicker').each(function () {
        $(this).bootstrapDatepicker({
            startView: $(this).data('view') ? $(this).data('view') : 0, // 0: month view , 1: year view, 2: multiple year view
            language: $(this).data('lang') ? $(this).data('lang') : "en",
            forceParse: $(this).data('parse') ? $(this).data('parse') : false,
            daysOfWeekDisabled: $(this).data('day-disabled') ? $(this).data('day-disabled') : "", // Disable 1 or various day. For monday and thursday: 1,3
            calendarWeeks: $(this).data('calendar-week') ? $(this).data('calendar-week') : false, // Display week number 
            autoclose: $(this).data('autoclose') ? $(this).data('autoclose') : false,
            todayHighlight: $(this).data('today-highlight') ? $(this).data('today-highlight') : true, // Highlight today date
            toggleActive: $(this).data('toggle-active') ? $(this).data('toggle-active') : true, // Close other when open
            multidate: $(this).data('multidate') ? $(this).data('multidate') : false, // Allow to select various days
            orientation: $(this).data('orientation') ? $(this).data('orientation') : "top", // Allow to select various days,
            rtl: $('html').hasClass('rtl') ? true : false
        });
    });
}

function formValidation(){
    if($('.form-validation').length && $.fn.validate){
        /* We add an addition rule to show you. Example : 4 + 8. You can other rules if you want */
        $.validator.methods.operation = function(value, element, param) {
            return value == param;
        };
        $.validator.methods.customemail = function(value, element) {
            return /^([-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4})+$/.test(value);
        };
        $('.form-validation').each(function(){
            var formValidation = $(this).validate({
                success: "valid",
                // submitHandler: function() { alert("Form is valid! We submit it") },
                errorClass: "form-error",
                validClass: "form-success",
                errorElement: "div",
                ignore: [],
                rules: {
                    email: {
                        required:  {
                                depends:function(){
                                    $(this).val($.trim($(this).val()));
                                    return true;
                                }   
                            },
                        customemail: true
                    }
                },
                messages:{
                    name: {required: 'Enter a name'},
                    lastname: {required: 'Enter a last name'},
                    firstname: {required: 'Enter a first name'},

                    fname: {required: 'Enter a first name'},
                    lname: {required: 'Enter a last name'},

                    username: {required: 'Enter a username'},
                    email: {required: 'Enter an email address', customemail: 'Enter a valid email address'},
                    password: {required: 'Enter a password'},

                    selectMember: {required: 'Select a member'},
                    selectPeriod: {required: 'Select a period of office'},
                    selectCommittee: {required: 'Select a committee'},
                    selectAssociation: {required: 'Select an association'}

                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-control').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-control').removeClass(errorClass).addClass(validClass);
                },
                errorPlacement: function(error, element) {
                   if (element.hasClass("custom-file") || element.hasClass("checkbox-type") || element.hasClass("language")) {
                        element.closest('.option-group').after(error);
                   }
                   else if (element.is(":radio") || element.is(":checkbox"))  {
                        element.closest('.option-group').after(error);
                   }
                   else if (element.parent().hasClass('input-group'))  {
                        element.parent().after(error);
                   }
                   else{
                       error.insertAfter(element);
                   }
                },
                invalidHandler: function(event, validator) {
                    var errors = validator.numberOfInvalids();         
                }      
            });
            $(".form-validation .cancel").click(function() {
                formValidation.resetForm();
            });
        });
    }
}

function textareaAutosize(){
    $('textarea.autosize').each(function(){
        $(this).autosize();   
    });
}

/****  Initiation of Main Functions  ****/
$(document).ready(function () {
    sortableTable();
    showTooltip();
    popover();
    numericStepper();
    inputSelect();
    inputTags();
    tableResponsive();
    tableDynamic();
    editableTable();
    bDatepicker();
    formValidation();
    textareaAutosize();
    $('.autogrow').autogrow();
});

/****  On Resize Functions  ****/
$(window).bind('resize', function (e) {
    window.resizeEvt;
    $(window).resize(function () {
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function () {
            tableResponsive();
        }, 250);
    });
});