<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$table_data = $result['table_data'];
$primary_key = $result['primary_key'];
$primary_key_hidden = $result['primary_key_hidden'];
$data_type = $result['data_type'];
//print_r(json_encode($table_data));exit;
?>

<div class="h1 container-fluid ">
    PHP CRUD
</div>

<?php if($result['error']){ ?>
<div class="container m-t-5p">
        <div class="row">
            <div class="text-center well">
                <i class="glyphicon glyphicon-exclamation-sign text-danger"></i>
                <b> <?php echo $result['message']; ?></b>
            </div>
        </div>
</div>
<?php } else {?>
<div ng-controller="tableData" 
     ng-init="primary_key = <?php echo htmlspecialchars(json_encode($primary_key))?>; 
         primary_key_hidden = <?php echo htmlspecialchars(json_encode($primary_key_hidden));?>;
         data_type = <?php echo htmlspecialchars(json_encode($data_type));?>;
         selected_row = false;">
 
    <div class="container-fluid">
    <div class='row'> 
        <div class=" form-inline col-lg-3">
            <div class="input-group">
                <div class="btn btn-success" ng-click="openAddRecordModal()"><i class="glyphicon glyphicon-plus"></i> Add New Record</div>
            </div>
        </div>
        <div class=" form-inline col-lg-3">
            <div class="input-group">
                <div class="input-group-addon">Table Name</div>
                <input type="text" class="form-control" id="table_name" value="<?php echo htmlspecialchars($result['table_name']); ?>" disabled>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="text-right input-group">
                <input class="form-control width-250" name="search_all_col" type="search" placeholder="search all columns" ng-model="searchBy.$">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="text-right input-group pull-right m-t-0">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Download
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="#" ng-click="exportAsCsv(results)">CSV</a></li>
                      <li><a href="#">PDF (NA)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="table-responsive" ng-init="results = <?php echo htmlspecialchars(json_encode($table_data)); ?>; max_in_page = 10">
           
        <table class="container table table-hover" id="php_crud_table_view" >
            <thead class="breadcrumb">
                <?php //foreach ($table_data as $table_value){?>
                <!--tr>
                    <?php foreach ($table_value as $col_name => $col_value){?>
                        <?php if(($col_name == $primary_key && $primary_key_hidden == 'true')){}else{?>
                    
                            <td>
                                <div class="input-group">
                                    <input type="search" placeholder="Search by <?php echo $col_name; ?>" 
                                           class="form-control" 
                                           ng-model="searchBy.<?php echo $col_name; ?>" >
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </span>
                                </div>
                            </td>
                            
                        <?php } ?>
                    <?php } ?>
                    <td colspan="2"></td>
                </tr-->
                <?php //break;}?>
                <tr ng-repeat="row in results | limitTo : 1" class='bg-primary text-white'>
                    <th class="text-center">
                        <input type="checkbox" ng-click="selectAllRows()" class="selectAll">
                    </th>
                    <th ng-repeat="(key, value) in row" ng-hide="(primary_key_hidden == 'true' && primary_key == key)">
                        <span ng-if="primary_key == key" style="color:burlywood" title='Primay Key'>
                          {{key}}
                        </span>
                        <span ng-if="primary_key != key">
                          {{key}}
                        </span>
                    </th>
                    <th class="w-100px">
                        <!-- Control -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="row in filterData = (results | filter : searchBy : strict) | limitTo:max_in_page:max_in_page*(page-1)" >
                    <td class="text-center">
                        <input type="checkbox" class="all-rows" data-select="{{row}}">
                    </td>
                    <td ng-repeat="(key,col) in row track by $index" ng-hide="(key == primary_key) && (primary_key_hidden == 'true')">
                        {{col}}
                    </td>
                    <td>
                        <button class="btn btn-primary glyphicon glyphicon-edit" ng-click="edit(row, row[primary_key], data_type)" data-toggle="modal" data-target="#editModal">
                        </button>
                        <button class="btn btn-danger glyphicon glyphicon-trash" ng-click="setSelected(row)" data-toggle="modal" data-target="#delModal">
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Modal for Edit -->
    <div class="modal fade" id="editModal" role="dialog" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update Data</h4>
          </div>
          <div class="modal-body" id="edit_modal_body" ng-bind-html-unsafe="edit_modal">
          {{ edit_modal }}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal for Delete -->
    <div class="modal fade" id="delModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Warning!</h4>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this Row?</p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="delete()">Yes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal for Add New Row -->
    <div class="modal fade" id="addModal" role="dialog" ng-init="insert_record_data = <?php echo htmlspecialchars(json_encode($result['insert_record_template']))?>">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Data</h4>
          </div>
          <div class="modal-body" id="add_modal_body" ng-bind-html-unsafe="add_modal">
                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 text-sm-center">
                <uib-pagination  class="pagination" total-items="filterData.length" 
                ng-model="page"
                ng-change="pageChanged()" 
                previous-text="&lsaquo;" 
                next-text="&rsaquo;" 
                items-per-page = "max_in_page">
                    
                </uib-pagination>
            </div>
            <div class="col-sm-3 text-sm-center">
                <div class="dropup pull-right">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Rows per page
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu dropup">
                        <li><a href="#" ng-click="max_in_page = 1">1</a></li>
                        <li><a href="#" ng-click="max_in_page = 2">2</a></li>
                        <li><a href="#" ng-click="max_in_page = 5">5</a></li>
                        <li><a href="#" ng-click="max_in_page = 10">10</a></li>
                        <li><a href="#" ng-click="max_in_page = 20">20</a></li>
                        <li><a href="#" ng-click="max_in_page = 30">30</a></li>
                        <li><a href="#" ng-click="max_in_page = 40">40</a></li>
                        <li><a href="#" ng-click="max_in_page = 40">50</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<!--     Modal for Displaying Message 
    <div class="modal fade" id="msgModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Message</h4>
          </div>
          <div class="modal-body">
            <p id ="message" ></p>
          </div>
        </div>
      </div>
    </div>-->
</div>
<div class="chart-wrapper" id="chart-line1">
    
</div>
<?php } ?>
<script>
    var BASE_URL = "<?php echo base_url('index.php'); ?>" ;

</script>
