<?
if ($_POST) {
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }
}
if ($_GET) {
    foreach ($_GET as $key => $value) {
        if (empty($$key)) {
            $$key = $value;
        }
    }
}
include "../include/config.php";
$DB = new DB();
$sql = "SELECT * from INFORMATION_SCHEMA.columns where table_name = '$table_name'";
$exe = $DB->query($sql);
$DB_schema = array();

while ($fetch = $exe->fetchObject()) {
    $TABLE_NAME = $fetch->TABLE_NAME;
    $COLUMN_NAME = $fetch->COLUMN_NAME;
    $IS_NULLABLE = $fetch->IS_NULLABLE;
    $DATA_TYPE = $fetch->DATA_TYPE;
    $CHARACTER_MAXIMUM_LENGTH = $fetch->CHARACTER_MAXIMUM_LENGTH;
    $tmp_obj = new stdclass();
    $tmp_obj->TABLE_NAME = $TABLE_NAME;
    $tmp_obj->COLUMN_NAME = $COLUMN_NAME;
    $tmp_obj->IS_NULLABLE = $IS_NULLABLE;
    $tmp_obj->DATA_TYPE = $DATA_TYPE;
    $tmp_obj->CHARACTER_MAXIMUM_LENGTH = $CHARACTER_MAXIMUM_LENGTH;
    array_push($DB_schema, $tmp_obj);
}
$file_name = $table_name . "_schema.json";
file_put_contents($file_name, json_encode($DB_schema));
?>
<a href="<?= $file_name ?>" target="_blank">
    下載ＪＳＯＮ
</a>
<br>
<table class="table table-bordered table-striped">
    <tr>
        <th>TABLE_NAME</th>
        <th>COLUMN_NAME</th>
        <th>IS_NULLABLE</th>
        <th>DATA_TYPE</th>
        <th>CHARACTER_MAXIMUM_LENGTH</th>
    </tr>
    <?
    if ($DB_schema) {
        foreach ($DB_schema as $key => $value) {
            $TABLE_NAME = $value->TABLE_NAME;
            $COLUMN_NAME = $value->COLUMN_NAME;
            $IS_NULLABLE = $value->IS_NULLABLE;
            $DATA_TYPE = $value->DATA_TYPE;
            $CHARACTER_MAXIMUM_LENGTH = $value->CHARACTER_MAXIMUM_LENGTH;
            ?>
            <tr>
                <td><?=$TABLE_NAME?></th>
                <td><?=$COLUMN_NAME?></th>
                <td><?=$IS_NULLABLE?></th>
                <td><?=$DATA_TYPE?></th>
                <td><?=$CHARACTER_MAXIMUM_LENGTH?></td>
            </tr>
        <?
        }
    }
    ?>
</table>