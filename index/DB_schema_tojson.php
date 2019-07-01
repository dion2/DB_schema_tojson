<?
include "../include/config.php";
$DB = new DB();
$sql = "SELECT DISTINCT table_name from information_schema.tables where 1=1";
$exe = $DB->query($sql);
while ($fetch = $exe->fetchObject()) {
    $table_arr[] = $fetch->table_name;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>Document</title>
    <style>
        #data_show_area {
            width: 100%;
            height: 50vh;
            border: 1px #797979 solid;
            overflow: auto;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#table_name").change(function() {
                var table_name = $(this).val();
                var data_arr = {
                    table_name: table_name
                }
                
                show_data_detail("data_show_area", "DB_schema_json.php", data_arr)
            });
        });

        function show_data_detail(id, url, data_arr) {
            $("#" + id).load(url, data_arr);
        }
    </script>
</head>

<body>
    <div class="container">
        <form action="DB_schema_tojson.php" method="POST">
            <table class="table table-bordered">
                <tr>
                    <th colspan="2">
                        DB Schema to Json
                    </th>
                </tr>
                <tr>
                    <td>
                        Table Name
                    </td>
                    <td>
                        <select name="table_name" id="table_name" class="form-control">
                            <option value="">SELECT</option>
                            <?
                            if ($table_arr) {
                                foreach ($table_arr as $key => $value) {
                                    ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

            </table>
        </form>
        <div id="data_show_area">

        </div>
    </div>
</body>

</html>