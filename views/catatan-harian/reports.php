<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CatatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Catatan Harian';
$this->params['breadcrumbs'][] = $this->title;
?>

<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body ">

                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                    <ul class="nav" role="tablist">
                        <li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab" aria-expanded="true">Today's Activity</a></li>
                        <li class=""><a href="#tab-bottom-left2" role="tab" data-toggle="tab" aria-expanded="false">This week</a></li>
                        <li class=""><a href="#tab-bottom-left3" role="tab" data-toggle="tab" aria-expanded="false">This month</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab-bottom-left1">
                        <ul class="list-unstyled activity-timeline" id="today_list">
                            
                        </ul>
                        <!-- <div class="margin-top-30 text-center"><a href="#" class="btn btn-default">See all activity</a></div> -->
                    </div>
                    <div class="tab-pane fade" id="tab-bottom-left2">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tabel_minggu">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Unsur</th>
                                        <th>Total Poin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-bottom-left3">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tabel_bulan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Unsur</th>
                                        <th>Total Poin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php


$this->registerJs(' 

function getToday(){
    var obj = new Object;
    obj.params = "today"
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['catatan-harian/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
          
            $("#today_list").empty()
            var row = ""
            $.each(res, function(i, obj){
                row += "<li value=\'"+obj.id+"\'><i class=\'fa fa-comment activity-icon\'></i><p>"+obj.nama+" <a href=\'#\'>["+obj.induk+"]</a></p></li>"
            })

            $("#today_list").append(row)
        }

    });
}

function getWeekly(params, selector){
    var obj = new Object;
    obj.params = params
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['catatan-harian/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
       
            selector.empty()
            var row = ""
            $.each(res, function(i, obj){
                row += "<tr>";
                row += "<td>"+eval(i+1)+"</td>";
                row += "<td>"+obj.unsur+"</td>";
                row += "<td>"+obj.poin+"</td>";
                row += "<td>-</td>";
                row += "</tr>";
            })

            selector.append(row)
        }

    });
}
getToday()
getWeekly("week", $("#tabel_minggu > tbody"))
getWeekly("month",$("#tabel_bulan > tbody"))
', \yii\web\View::POS_READY);

?>