/*
 * @Author: Vinod Selvin
 * @Desc: Constants for developement and production
 */

var HOSTNAME = window.location.hostname;
var PROTOCOL = window.location.protocol;

var app = angular.module("phpCrud", ["ui.bootstrap","ngRoute"]);

//var app = angular.module('phpCrud', ['ngAria', 'ngMaterial', 'miniapp', 'ngAnimate', 'ui.bootstrap', 'ngSanitize']);
app.controller("tableData", function ($scope, $http, $location, $compile) {

    $scope.page = 1;

    $scope.edit = function (row, primary_key, data_type) {
            
        delete row.$$hashKey;

        var data = {'table_name': angular.element('#table_name').val(),
            'row': row,
            'primary_key': primary_key,
            'data_type': data_type};

        $http({
            url: BASE_URL + "/crud_controller/edit",
            method: "POST",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function successCallback(response)
        {
			var this_scope = angular.element("#editModal").scope();
			
            $scope.setSelected(row);
				
			data_input = JSON.stringify(response.data);

    		json2Html('edit_row').setJson(data_input);

    		json2Html('edit_row').getHtml(function (html) {

                var compiledHtml = $compile(html)($scope);

    			document.getElementById("edit_modal_body").innerHTML = "";

                angular.element(document.getElementById('edit_modal_body')).append(compiledHtml);
    		});	
           
        }
        , function errorCallback(response)
        {

        });
        
    };
    
    /*
    * @Author: Pratik Pathak
    * @Desc: Added delete feature for each row
    */
    
    $scope.delete = function () {
        
        var row = $scope.selected_row;
        var pk = $scope.primary_key;
        delete row.$$hashKey;
        
        var data = {
            'table_name': angular.element('#table_name').val(),
            'row': row,
            'primary_key': $scope.primary_key
         };

        $http({
            url: BASE_URL + "/crud_controller/delete",
            method: "POST",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function successCallback(response)
        {
            alert(response.data.message);
            location.reload();
        }
        , function errorCallback(response)
        {

        });
        
    };
    
    /*
    * @Author: Pratik Pathak
    * @Desc: saves selected row
    */
   
    $scope.setSelected = function (row) {
            
            $scope.selected_row = row;
    };

    $scope.pageChanged = function () {
//	  var startPos = ($scope.page - 1) * 10;
//	  console.log(filteredArray.length);
    };
    
    /*
     * @Author: Vinod Selvin
     * @param {type} table_id
     * @returns {Boolean|Window|sa}
     * @Desc: We may need it in future
     */
    $scope.downloadAsExcel = function(table_id){
        
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";

        var j=0;

        var tab = document.getElementById(table_id); // id of table

        for(j = 0 ; j < tab.rows.length ; j++) 
        {     console.log(tab_text+tab.rows[j].innerHTML);return false;
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }  
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

        return (sa);
    }
    
    /*
     * @Author: Vinod Selvin
     * @param {Array} Results
     * @returns {file}
     * @Desc: Export Array to CSV
     */
    $scope.exportAsCsv = function (Results) {
        
        var table_name = document.getElementById("table_name").value;
        
        var file_name = prompt("Please choose the name for the file, to be downloaded!", table_name);
        
        if (file_name != null) {
            
            var csv = "";
            var csv_head = "";
            
            Results.forEach(function (row, index) {
                
                csv_head = "";
                
                for (var col in row) {
                    
                    if(col != '$$hashKey'){
                        csv_head += col + ',';
                        csv      += row[col] + ',';
                    }
                }
                
                csv_head += "\r\n";
                csv += "\r\n";
            });
            
            csv = csv_head + csv;
            
            csv = "data:application/csv," + encodeURIComponent(csv);

            var x = document.createElement("A");

            x.setAttribute("href", csv);

            x.setAttribute("download", file_name + ".csv");

            document.body.appendChild(x);

            x.click();
        }
    }
	
   /*
    * @Author: Manoj Selvin
    * @Desc: Added Update feature for each row
    */
    
    $scope.update = function () {
        
        var row = $scope.selected_row;
        var pk = $scope.primary_key;
		delete row.$$hashKey;
        
        var form_data = angular.element("#edit-form").serializeArray();

        var processed_form_data = {};

        for(var x in form_data){
            processed_form_data[form_data[x]['name']] = form_data[x]['value'];
        }

        var data = {
            'table_name': angular.element('#table_name').val(),
            'row': processed_form_data,
            'actual_row': row,
            'primary_key': $scope.primary_key
         };

        $http({
            url: BASE_URL + "/crud_controller/update",
            method: "POST",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function successCallback(response)
        {
            alert(response.data.message);
            location.reload();
        }
        , function errorCallback(response)
        {

        });
        
    };

    
    /*
    * @Author: Pratik Pathak
    * @Desc: selects all rows
    */
   
    $scope.selectAllRows = function () {

        var selectAll = angular.element('.selectAll');
        var allRows = angular.element('.all-rows');

        if (selectAll.is(':checked'))
        {
            allRows.attr('checked', false);
            allRows.trigger('click');
        }
        else
        {
            allRows.attr('checked', false);
        }

    };

    /*
     * @Author: Vinod Selvin
     * @Desc: Open Add Record Modal and generate Template HTML
     */
    $scope.openAddRecordModal = function(){

        data_input = JSON.stringify($scope.insert_record_data);

        json2Html('add_row').setJson(data_input);

        json2Html('add_row').getHtml(function (html) {

            var compiledHtml = $compile(html)($scope);

            document.getElementById("add_modal_body").innerHTML = "";

            angular.element(document.getElementById('add_modal_body')).append(compiledHtml);
        });

        angular.element("#addModal").modal();
    };
    
    /*
    * @Author: Vinod Selvin
    * @Desc: Add New Record
    */
    
    $scope.addRow = function () {
        
        var pk = $scope.primary_key;
        
        var form_data = angular.element("#add-form").serializeArray();

        var processed_form_data = {};

        var flag = false;

        for(var x in form_data){

            if(form_data[x]['value'].toString().trim()){
                flag = true;
            }

            processed_form_data[form_data[x]['name']] = form_data[x]['value'];
        }

        if(!flag){
            alert("New Record Cannot be inserted, with empty data");
            return false;
        }

        var data = {
            'table_name': angular.element('#table_name').val(),
            'row': processed_form_data,
            'primary_key': $scope.primary_key
         };

        $http({
            url: BASE_URL + "/crud_controller/insert",
            method: "POST",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function successCallback(response)
        {
            alert(response.data.message);
            location.reload();
        }
        , function errorCallback(response)
        {

        });
        
    };

});

// $(document).on("click", "#form-btn-update", function(e){
//     e.preventDefault();

//     alert("aaya");
// });
